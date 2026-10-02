<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Dispensasi {{ $nomor_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            padding: 1.5cm 2cm;
        }
        .header {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header-text { flex: 1; text-align: center; }
        .header-text .sekolah { font-size: 16pt; font-weight: bold; }
        .header-text .alamat { font-size: 10pt; }
        .judul {
            text-align: center;
            margin: 20px 0 5px;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 25px;
            color: #333;
        }
        .intro { margin-bottom: 15px; text-align: justify; line-height: 1.8; }
        .data-table { width: 100%; margin: 15px 0; border-collapse: collapse; }
        .data-table td {
            padding: 4px 8px;
            font-size: 11pt;
            vertical-align: top;
        }
        .data-table td:first-child { width: 40%; }
        .data-table td:nth-child(2) { width: 5%; text-align: center; }
        .penutup { margin: 20px 0; text-align: justify; line-height: 1.8; }
        .ttd-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .ttd-box { text-align: center; width: 45%; }
        .ttd-nama { font-weight: bold; margin-top: 70px; text-decoration: underline; }
        .ttd-jabatan { font-size: 10pt; }
        .kode-box {
            border: 2px dashed #333;
            padding: 10px 15px;
            margin-top: 25px;
            text-align: center;
            background: #f9f9f9;
        }
        .kode-verif {
            font-family: 'Courier New', monospace;
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 8px;
            color: #333;
        }
        .kode-url { font-size: 9pt; color: #666; margin-top: 3px; }
        .footer-note { font-size: 9pt; color: #666; margin-top: 15px; font-style: italic; }
    </style>
</head>
<body>

{{-- Header Surat --}}
<div class="header">
    <div class="header-text">
        <div class="sekolah">{{ $nama_sekolah }}</div>
        <div class="alamat">{{ $alamat_sekolah }}</div>
        <div class="alamat">Telp: {{ env('SCHOOL_PHONE') }} | Email: {{ env('SCHOOL_EMAIL') }}</div>
    </div>
</div>

{{-- Judul --}}
<div class="judul">Surat Keterangan Dispensasi</div>
<div class="nomor-surat">Nomor: {{ $nomor_surat }}</div>

{{-- Isi Surat --}}
<p class="intro">
    Yang bertanda tangan di bawah ini, Guru Piket <strong>{{ $nama_sekolah }}</strong>,
    menerangkan bahwa siswa yang tersebut di bawah ini:
</p>

<table class="data-table">
    <tr>
        <td>Nama Siswa</td>
        <td>:</td>
        <td><strong>{{ $dispensasi->nama_siswa }}</strong></td>
    </tr>
    <tr>
        <td>Kelas / Jurusan</td>
        <td>:</td>
        <td>{{ $dispensasi->kelas }} / {{ $dispensasi->jurusan }}</td>
    </tr>
    <tr>
        <td>Wali Kelas</td>
        <td>:</td>
        <td>{{ $dispensasi->wali_kelas }}</td>
    </tr>
    <tr>
        <td>Guru Pengajar</td>
        <td>:</td>
        <td>{{ $dispensasi->guru_mengajar }}</td>
    </tr>
    <tr>
        <td>Keperluan / Alasan</td>
        <td>:</td>
        <td>{{ $dispensasi->alasan }}</td>
    </tr>
    <tr>
        <td>Waktu Dispensasi</td>
        <td>:</td>
        <td>{{ \Carbon\Carbon::parse($waktu_mulai)->format('H:i') }} s/d {{ \Carbon\Carbon::parse($waktu_selesai)->format('H:i') }} WIB ({{ $dispensasi->durasi_menit }} menit)</td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>:</td>
        <td>{{ \Carbon\Carbon::parse($waktu_mulai)->format('d F Y') }}</td>
    </tr>
</table>

<p class="penutup">
    Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
    Surat ini berlaku selama waktu yang telah ditentukan di atas.
</p>

{{-- Tanda Tangan --}}
<div class="ttd-section">
    <div class="ttd-box">
        <div>Mengetahui,</div>
        <div class="ttd-jabatan">Kepala Sekolah</div>
        <div class="ttd-nama">{{ $kepala_sekolah }}</div>
        <div class="ttd-jabatan">NIP. -</div>
    </div>
    <div class="ttd-box">
        <div>{{ $tanggal_cetak }},</div>
        <div class="ttd-jabatan">Guru Piket</div>
        <div class="ttd-nama">{{ $nama_guru_piket }}</div>
        <div class="ttd-jabatan">NIP. {{ optional($dispensasi->guruPiket)->nip ?? '-' }}</div>
    </div>
</div>

{{-- Kode Verifikasi --}}
<div class="kode-box">
    <div style="font-size:9pt;margin-bottom:5px;">🔐 KODE VERIFIKASI KEASLIAN SURAT</div>
    <div class="kode-verif">{{ $kode_verifikasi }}</div>
    <div class="kode-url">Verifikasi di: {{ url('/verifikasi/' . $kode_verifikasi) }}</div>
</div>

<div class="footer-note">
    * Surat ini digenerate secara otomatis oleh Sistem Dispensasi {{ $nama_sekolah }} pada {{ now()->format('d F Y, H:i') }} WIB.
    Verifikasi keaslian surat dapat dilakukan dengan mengunjungi URL di atas atau menghubungi guru piket.
</div>

</body>
</html>
