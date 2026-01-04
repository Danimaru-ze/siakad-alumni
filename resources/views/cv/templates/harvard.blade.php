<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV {{ $user->name }}</title>
    <style>
        @page {
            margin: 0.75in 0.75in 0.75in 0.75in;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11pt;
            line-height: 1.15;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .name {
            font-size: 12pt;
            text-transform: uppercase;
            font-weight: bold;
            margin: 0 0 3px 0;
        }
        .contact-info {
            font-size: 10pt;
            margin: 0;
        }
        .section-title {
            text-transform: uppercase;
            font-weight: bold;
            font-size: 12pt;
            border-bottom: 1px solid #000;
            margin-top: 10px;
            margin-bottom: 4px;
            padding-bottom: 2px;
            page-break-after: avoid;
        }
        .item {
            margin-bottom: 6px;
            page-break-inside: avoid;
        }
        .item-header {
            display: table;
            width: 100%;
            margin-bottom: 1px;
        }
        .left-header {
            display: table-cell;
            text-align: left;
            font-weight: bold;
            width: 75%;
        }
        .right-header {
            display: table-cell;
            text-align: right;
            width: 25%;
        }
        .item-subheader {
            display: table;
            width: 100%;
            margin-bottom: 2px;
        }
        .role {
            display: table-cell;
            text-align: left;
            font-style: italic;
            width: 75%;
        }
        .date {
            display: table-cell;
            text-align: right;
            font-style: italic;
            width: 25%;
        }
        ul {
            margin: 2px 0 0 0;
            padding-left: 20px;
        }
        li {
            margin-bottom: 1px;
            text-align: left;
        }
        .summary {
            text-align: left;
            margin-bottom: 8px;
            margin-top: 0;
        }
        a {
            color: #000;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="name">{{ $user->name }}</div>
        <div class="contact-info">
            @php
                $contactParts = [];
                if($cvProfile && $cvProfile->address) $contactParts[] = $cvProfile->address;
                if($cvProfile && $cvProfile->phone_number) $contactParts[] = $cvProfile->phone_number;
                if($user->email) $contactParts[] = $user->email;
                if($cvProfile && $cvProfile->linkedin_url) $contactParts[] = $cvProfile->linkedin_url;
                if($cvProfile && $cvProfile->portfolio_url) $contactParts[] = $cvProfile->portfolio_url;
            @endphp
            {{ implode(' | ', $contactParts) }}
        </div>
    </div>

    @if($cvProfile && $cvProfile->professional_summary)
    <div class="summary">
        {{ $cvProfile->professional_summary }}
    </div>
    @endif

    @if($mahasiswa || $educations->count() > 0)
    <div class="section-title">EDUCATION</div>
    
    {{-- Academic Data from Siakad --}}
    @if($mahasiswa)
    <div class="item">
        <div class="item-header">
            <div class="left-header">{{ config('app.name', 'University Name') }}</div>
            <div class="right-header">Indonesia</div>
        </div>
        <div class="item-subheader">
            <div class="role">Bachelor of {{ $mahasiswa->prodi->nama_prodi ?? 'Science' }}</div>
            <div class="date">
                {{ $mahasiswa->tahun_masuk ?? '' }} &ndash; 
                {{ $mahasiswa->status === 'lulus' ? 'Graduated' : 'Present' }}
            </div>
        </div>
        <ul>
            @if($mahasiswa->ipk)
            <li>Cumulative GPA: {{ number_format($mahasiswa->ipk, 2) }}/4.00</li>
            @endif
            @if($mahasiswa->judul_skripsi)
            <li>Thesis: "{{ $mahasiswa->judul_skripsi }}"</li>
            @endif
        </ul>
    </div>
    @endif

    {{-- Additional Education --}}
    @foreach($educations as $edu)
    <div class="item">
        <div class="item-header">
            <div class="left-header">{{ $edu->institution }}</div>
            <div class="right-header">{{ $edu->location }}</div>
        </div>
        <div class="item-subheader">
            <div class="role">{{ $edu->degree }} {{ $edu->field_of_study ? 'in ' . $edu->field_of_study : '' }}</div>
            <div class="date">
                {{ $edu->start_date->format('Y') }} &ndash; 
                {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('Y') : '') }}
            </div>
        </div>
        @if($edu->description)
        <ul>
            @foreach(explode("\n", $edu->description) as $line)
                @if(trim($line)) <li>{{ trim($line) }}</li> @endif
            @endforeach
        </ul>
        @endif
    </div>
    @endforeach
    @endif

    @if($experiences->count() > 0)
    <div class="section-title">EXPERIENCE</div>
    @foreach($experiences as $exp)
    <div class="item">
        <div class="item-header">
            <div class="left-header">{{ $exp->company }}</div>
            <div class="right-header">{{ $exp->location }}</div>
        </div>
        <div class="item-subheader">
            <div class="role">{{ $exp->role }}</div>
            <div class="date">
                {{ $exp->start_date->format('M Y') }} &ndash; 
                {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
            </div>
        </div>
        @if($exp->description)
        <ul>
            @foreach(explode("\n", trim($exp->description)) as $line)
                @if(trim($line))
                    @if(str_starts_with(trim($line), '•') || str_starts_with(trim($line), '-'))
                        <li>{{ ltrim(trim($line), '•-') }}</li>
                    @else
                        <li>{{ trim($line) }}</li>
                    @endif
                @endif
            @endforeach
        </ul>
        @endif
    </div>
    @endforeach
    @endif

    @if($skills->count() > 0)
    <div class="section-title">SKILLS</div>
    <div class="item">
        <ul>
            @foreach($skills->groupBy('category') as $category => $categorySkills)
            <li>
                <strong>{{ $category }}:</strong> 
                {{ $categorySkills->pluck('name')->implode(', ') }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($certifications->count() > 0)
    <div class="section-title">CERTIFICATIONS</div>
    <div class="item">
        <ul>
            @foreach($certifications as $cert)
            <li>
                <strong>{{ $cert->name }}</strong> &ndash; {{ $cert->issuer }} 
                ({{ $cert->issue_date->format('Y') }})
            </li>
            @endforeach
        </ul>
    </div>
    @endif

</body>
</html>
