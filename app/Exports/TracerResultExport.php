<?php

namespace App\Exports;

use App\Models\TracerStudy;
use App\Models\TracerResponse;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TracerResultExport implements FromQuery, WithHeadings, WithMapping
{
    protected $tracer;
    protected $questions;

    public function __construct(TracerStudy $tracer)
    {
        $this->tracer = $tracer;
        $this->questions = $tracer->questions()->orderBy('urutan')->get();
    }

    public function query()
    {
        return TracerResponse::query()
            ->where('tracer_study_id', $this->tracer->id)
            ->with(['user.mahasiswa.prodi', 'answers']);
    }

    public function headings(): array
    {
        $headers = [
            'Nama Alumni',
            'NIM',
            'Program Studi',
            'Angkatan',
            'Tanggal Mengisi',
        ];

        foreach ($this->questions as $question) {
            $headers[] = $question->pertanyaan;
        }

        return $headers;
    }

    public function map($response): array
    {
        $alumni = $response->user->mahasiswa;

        // Create a map of question_id => answer for easy lookup
        $answerMap = $response->answers->pluck('jawaban', 'question_id');

        $row = [
            $alumni->nama,
            $alumni->nim,
            $alumni->prodi->nama,
            $alumni->angkatan,
            $response->submitted_at->format('Y-m-d H:i'),
        ];

        foreach ($this->questions as $question) {
            $answer = $answerMap->get($question->id);
            if ($question->tipe === 'checkbox') {
                $row[] = implode(', ', json_decode($answer, true) ?? []);
            } else {
                $row[] = $answer;
            }
        }

        return $row;
    }
}
