<?php

namespace App\Console\Commands;

use App\Models\Mahasiswa;
use App\Services\AkademikCalculationService;
use Illuminate\Console\Command;

class UpdateMahasiswaIpkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'siakad:update-ipk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and update IPK and total SKS for all students.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting IPK and SKS update for all students...');

        $allMahasiswa = Mahasiswa::with('user')->get();
        $bar = $this->output->createProgressBar($allMahasiswa->count());
        $konversi = config('siakad.nilai_konversi');

        $bar->start();

        foreach ($allMahasiswa as $mahasiswa) {
            $nilaiList = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->with('kelas.mataKuliah')
                ->get();

            if ($nilaiList->isEmpty()) {
                $mahasiswa->ipk = 0;
                $mahasiswa->total_sks = 0;
            } else {
                $totalSks = 0;
                $totalBobot = 0;

                foreach ($nilaiList as $nilai) {
                    if (!$nilai->kelas || !$nilai->kelas->mataKuliah) {
                        continue;
                    }
                    $sks = $nilai->kelas->mataKuliah->sks;
                    $bobot = 0;
                    foreach ($konversi as $k) {
                        if ($k['huruf'] === $nilai->nilai_huruf) {
                            $bobot = $k['bobot'];
                            break;
                        }
                    }
                    
                    $totalSks += $sks;
                    $totalBobot += ($sks * $bobot);
                }

                $ipk = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

                $mahasiswa->ipk = $ipk;
                $mahasiswa->total_sks = $totalSks;
            }
            
            $mahasiswa->save();
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nUpdate completed successfully for all students.");

        return 0;
    }
}
