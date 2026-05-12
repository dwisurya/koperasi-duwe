<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kontrak Pinjaman - {{ $pinjaman->anggota->nama }}</title>
    <style>
        @page { margin: 2cm; size: A4; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.6; color: #000; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { font-size: 16pt; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; text-decoration: underline; }
        .header h2 { font-size: 14pt; font-weight: bold; margin-top: 0; }
        .header p { margin: 2px 0; font-size: 11pt; }
        hr { border: 1px solid #000; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        td { padding: 6px 8px; vertical-align: top; }
        .label { width: 35%; font-weight: bold; }
        .value { width: 65%; }
        .signature { margin-top: 50px; }
        .signature table td { text-align: center; vertical-align: bottom; padding-top: 40px; }
        .signature .name { font-weight: bold; margin-top: 30px; }
        .footer { margin-top: 30px; font-size: 10pt; text-align: center; font-style: italic; }
        .terms { margin-top: 20px; }
        .terms ol { padding-left: 20px; }
        .terms li { margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h2>SURAT PERJANJIAN PINJAMAN</h2>
        <p>Nomor: PIN/{{ $pinjaman->created_at->format('Y/m') }}/{{ str_pad($pinjaman->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <hr>

    <p style="text-align: justify;">Pada hari ini <strong>{{ ($pinjaman->approved_at ?? $pinjaman->tanggal_pengajuan)->format('d/m/Y') }}</strong>, yang bertanda tangan di bawah ini:</p>

    <table>
        <tr>
            <td colspan="2"><strong>Pihak Pertama (Kreditur)</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Lembaga</td>
            <td class="value">: {{ config('app.name') }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="value">: {{ config('app.url') }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td colspan="2"><strong>Pihak Kedua (Debitur)</strong></td>
        </tr>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td class="value">: {{ $pinjaman->anggota->nama }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="value">: {{ $pinjaman->anggota->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td class="value">: -</td>
        </tr>
        <tr>
            <td class="label">No. HP</td>
            <td class="value">: {{ $pinjaman->anggota->no_hp ?? '-' }}</td>
        </tr>
    </table>

    <p style="text-align: justify;">Kedua belah pihak sepakat untuk mengadakan perjanjian pinjaman dengan ketentuan sebagai berikut:</p>

    <div class="terms">
        <ol>
            <li>Pihak Pertama memberikan pinjaman kepada Pihak Kedua sebesar <strong>Rp {{ number_format($pinjaman->nominal, 2, ',', '.') }}</strong>.</li>
            <li>Pinjaman dikenakan bunga sebesar <strong>{{ $pinjaman->bunga_persen }}%</strong> per tahun {{ $pinjaman->bungaPinjaman ? '(' . $pinjaman->bungaPinjaman->nama . ')' : '' }}.</li>
            <li>Jangka waktu pinjaman adalah <strong>{{ $pinjaman->tenor }} bulan</strong>, terhitung sejak tanggal {{ $pinjaman->tanggal_pengajuan->format('d/m/Y') }}.</li>
            <li>Jatuh tempo pinjaman pada tanggal <strong>{{ $pinjaman->jatuh_tempo->format('d/m/Y') }}</strong>.</li>
            <li>Pembayaran angsuran dilakukan setiap bulan sebesar <strong>Rp {{ number_format(($pinjaman->nominal + ($pinjaman->nominal * $pinjaman->bunga_persen / 100 * $pinjaman->tenor / 12)) / $pinjaman->tenor, 2, ',', '.') }}</strong>.</li>
            <li>Pembayaran dilakukan paling lambat tanggal {{ $pinjaman->tanggal_pengajuan->format('d') }} setiap bulan melalui kasir {{ config('app.name') }}.</li>
            <li>Apabila Pihak Kedua terlambat melakukan pembayaran angsuran, akan dikenakan denda sesuai ketentuan yang berlaku di {{ config('app.name') }}.</li>
            <li>Pihak Kedua berhak melunasi pinjaman sebelum jatuh tempo tanpa dikenakan penalti.</li>
            <li>Apabila Pihak Keduan tidak memenuhi kewajibannya, Pihak Pertama berhak melakukan tindakan hukum sesuai peraturan perundang-undangan yang berlaku.</li>
        </ol>
    </div>

    <p style="text-align: justify;">Demikian surat perjanjian ini dibuat dengan sebenarnya dan dalam keadaan sadar tanpa paksaan dari pihak manapun.</p>

    <div class="signature">
        <table>
            <tr>
                <td>
                    <p>Pihak Pertama (Kreditur),</p>
                    <br><br><br>
                    <p class="name">{{ config('app.name') }}</p>
                </td>
                <td>
                    <p>Pihak Kedua (Debitur),</p>
                    <br><br><br>
                    <p class="name">{{ $pinjaman->anggota->nama }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada {{ now()->format('d/m/Y H:i') }} | Dokumen ini sah dan berlaku</p>
    </div>
</body>
</html>
