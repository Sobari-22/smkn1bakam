<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendaftar SPMB</title>
    <style>
        @page { margin: 28px 28px 46px 28px; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 7.5pt; color: #0f172a; }
        h1 { font-size: 15pt; margin: 0 0 2px 0; color: #0c4a6e; }
        .sub { font-size: 9pt; color: #475569; margin: 0 0 10px 0; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
        .summary td { border: 1px solid #cbd5e1; padding: 6px 10px; text-align: center; background: #f8fafc; }
        .summary .n { font-size: 13pt; font-weight: bold; color: #0369a1; display: block; }
        .summary .l { font-size: 7.5pt; color: #64748b; }
        table.data { width: 100%; border-collapse: collapse; table-layout: fixed; }
        table.data thead { display: table-header-group; }
        table.data th { background: #0369a1; color: #ffffff; text-align: left; padding: 5px 4px; font-size: 7pt; border: 1px solid #0369a1; }
        table.data td { padding: 4px; border: 1px solid #cbd5e1; vertical-align: top; word-wrap: break-word; }
        .nw { white-space: nowrap; }
        table.data tr { page-break-inside: avoid; }
        table.data tbody tr:nth-child(even) td { background: #f8fafc; }
        .center { text-align: center; }
        .empty { text-align: center; padding: 22px; color: #64748b; }
        #footer { position: fixed; bottom: -30px; left: 0; right: 0; font-size: 7.5pt; color: #64748b; border-top: 1px solid #cbd5e1; padding-top: 4px; }
        #footer .pn:before { content: counter(page); }
        #footer .r { float: right; }
    </style>
</head>
<body>
    <div id="footer">
        Dicetak {{ $printedAt }} · Dokumen ini memuat data pribadi (NIK, No. KK); simpan dan bagikan dengan hati-hati
        <span class="r">Halaman <span class="pn"></span></span>
    </div>

    <h1>Data Pendaftar SPMB {{ now()->year }}/{{ now()->year + 1 }}</h1>
    <p class="sub">
        {{ $schoolName }} · Sistem Penerimaan Murid Baru
        @if ($filterLabel) · Filter status: <strong>{{ $filterLabel }}</strong> @endif
    </p>

    @php
        $count = fn ($st) => $registrants->where('status', $st)->count();
    @endphp
    <table class="summary">
        <tr>
            <td><span class="n">{{ $registrants->count() }}</span><span class="l">TOTAL PENDAFTAR</span></td>
            <td><span class="n">{{ $count('verified') }}</span><span class="l">DITERIMA</span></td>
            <td><span class="n">{{ $count('pending') }}</span><span class="l">DALAM VERIFIKASI</span></td>
            <td><span class="n">{{ $count('rejected') }}</span><span class="l">DITOLAK</span></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:7%">No. Reg</th>
                <th style="width:12%">Nama Lengkap</th>
                <th style="width:3%" class="center">L/P</th>
                <th style="width:11%">NIK</th>
                <th style="width:7.5%">NISN</th>
                <th style="width:11%">No. KK</th>
                <th style="width:11.5%">Domisili</th>
                <th style="width:8%">Program</th>
                <th style="width:7%">Konsentrasi</th>
                <th style="width:6%">Bantuan</th>
                <th style="width:6%">Status</th>
                <th style="width:7%">Tgl Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registrants as $i => $r)
                @php
                    $aid = collect([$r->has_kip ? 'KIP' : null, $r->has_kis ? 'KIS' : null, $r->has_pkh ? 'PKH' : null])->filter()->implode(', ');
                @endphp
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $r->registration_number }}</td>
                    <td>{{ $r->full_name }}</td>
                    <td class="center">{{ $r->gender }}</td>
                    <td class="nw">{{ $r->nik }}</td>
                    <td class="nw">{{ $r->nisn }}</td>
                    <td class="nw">{{ $r->no_kk }}</td>
                    <td>{{ $r->address }}</td>
                    <td>{{ $r->program->name ?? '-' }}</td>
                    <td>{{ $r->concentration->name ?? '-' }}</td>
                    <td>{{ $aid !== '' ? $aid : 'Tidak ada' }}</td>
                    <td>{{ $statusLabels[$r->status] ?? $r->status }}</td>
                    <td class="nw">{{ $r->created_at->timezone('Asia/Jakarta')->format('d-m-Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="13" class="empty">Belum ada data pendaftar.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
