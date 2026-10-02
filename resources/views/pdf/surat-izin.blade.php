<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Izin {{ $nomor_surat }}</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box;}
        body{font-family:'Times New Roman',serif;font-size:12pt;color:#000;padding:1.5cm 2cm;}
        .header{text-align:center;border-bottom:3px double #000;padding-bottom:10px;margin-bottom:15px;}
        .header .sekolah{font-size:16pt;font-weight:bold;}
        .header .alamat{font-size:10pt;}
        .judul{text-align:center;margin:20px 0 5px;font-size:14pt;font-weight:bold;text-decoration:underline;text-transform:uppercase;}
        .nomor{text-align:center;font-size:11pt;margin-bottom:25px;color:#333;}
        .intro{margin-bottom:15px;text-align:justify;line-height:1.8;}
        .data-table{width:100%;margin:15px 0;border-collapse:collapse;}
        .data-table td{padding:4px 8px;font-size:11pt;vertical-align:top;}
        .data-table td:first-child{width:40%;}
        .data-table td:nth-child(2){width:5%;text-align:center;}
        .penutup{margin:20px 0;text-align:justify;line-height:1.8;}
        .ttd-section{display:flex;justify-content:space-between;margin-top:30px;}
        .ttd-box{text-align:center;width:45%;}
        .ttd-nama{font-weight:bold;margin-top:70px;text-decoration:underline;}
        .ttd-jabatan{font-size:10pt;}
        .kode-box{border:2px dashed #333;padding:10px 15px;margin-top:25px;text-align:center;background:#f9f9f9;}
        .kode-verif{font-family:'Courier New',monospace;font-size:18pt;font-weight:bold;letter-spacing:8px;}
        .footer-note{font-size:9pt;color:#666;margin-top:15px;font-style:italic;}
    </style>
</head>
<body>
<div class="header">
    <div class="sekolah">{{ $nama_sekolah }}</div>
    <div class="alamat">{{ $alamat_sekolah }}</div>
</div>
<div class="judul">Surat Keterangan Izin Siswa</div>
<div class="nomor">Nomor: {{ $nomor_surat }}</div>
<p class="intro">Yang bertanda tangan di bawah ini, Guru/Petugas <strong>{{ $nama_sekolah }}</strong>, menerangkan bahwa siswa yang tersebut di bawah ini:</p>
<table class="data-table">
    <tr><td>Nama Siswa</td><td>:</td><td><strong>{{ $pengajuan->siswa->nama }}</strong></td></tr>
    <tr><td>Kelas / Jurusan</td><td>:</td><td>{{ $pengajuan->siswa->kelas }} / {{ $pengajuan->siswa->jurusan }}</td></tr>
    <tr><td>NIS</td><td>:</td><td>{{ $pengajuan->siswa->nomor_identitas ?? '-' }}</td></tr>
    <tr><td>Alasan Izin</td><td>:</td><td>{{ $pengajuan->alasan_izin }}</td></tr>
    <tr><td>Waktu Izin</td><td>:</td><td>{{ substr($pengajuan->waktu_mulai,0,5) }} s/d {{ substr($pengajuan->waktu_selesai,0,5) }} WIB ({{ $pengajuan->durasi_menit }} menit)</td></tr>
    <tr><td>Tanggal</td><td>:</td><td>{{ $pengajuan->tanggal_izin->translatedFormat('d F Y') }}</td></tr>
    <tr><td>Verifikasi Wajah</td><td>:</td><td>{{ $pengajuan->verifikasiWajah ? ucfirst($pengajuan->verifikasiWajah->hasil_verifikasi) : '-' }}</td></tr>
</table>
<p class="penutup">Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
<div class="ttd-section">
    <div class="ttd-box"><div>Mengetahui,</div><div class="ttd-jabatan">Kepala Sekolah</div><div class="ttd-nama">________________________</div></div>
    <div class="ttd-box"><div>{{ $tanggal_cetak }},</div><div class="ttd-jabatan">Guru/Petugas</div><div class="ttd-nama">{{ $nama_guru }}</div></div>
</div>
<div class="kode-box">
    <div style="font-size:9pt;margin-bottom:5px;">KODE VERIFIKASI KEASLIAN SURAT</div>
    <div class="kode-verif">{{ $kode_verifikasi }}</div>
    <div style="font-size:9pt;color:#666;margin-top:3px;">Verifikasi di: {{ url('/verifikasi/' . $kode_verifikasi) }}</div>
</div>
<div class="footer-note">* Surat ini digenerate otomatis oleh Sistem Surat Izin Digital {{ $nama_sekolah }} pada {{ now()->format('d F Y, H:i') }} WIB.</div>
</body>
</html>