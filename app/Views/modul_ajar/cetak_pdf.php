<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Modul Ajar PDF</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; margin-bottom: 20px; text-transform: uppercase; }
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        td { vertical-align: top; padding: 2px; }
        .bordered { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .bordered th, .bordered td { border: 1px solid black; padding: 5px; }
        ul { margin-top: 0; padding-left: 20px; }
        .ttd-table { width: 100%; margin-top: 40px; page-break-inside: avoid; }
        .ttd-table td { text-align: center; }
    </style>
</head>
<body>

    <div class="title">PERENCANAAN PEMBELAJARAN MENDALAM (PPM)</div>

    <table>
        <tr>
            <td width="25%"><b>Satuan Pendidikan</b></td><td width="2%">:</td>
            <td><?= esc($sekolah['nama_sekolah'] ?? '-') ?></td>
        </tr>
        <tr><td><b>Mata Pelajaran</b></td><td>:</td><td><?= esc($modul['mapel']) ?></td></tr>
        <tr><td><b>Fase / Kelas</b></td><td>:</td><td><?= esc($modul['fase']) ?> / <?= esc($modul['kelas']) ?></td></tr>
        <tr><td><b>Materi Pokok</b></td><td>:</td><td><?= esc($modul['materi']) ?></td></tr>
        <tr><td><b>Alokasi Waktu</b></td><td>:</td><td><?= esc($modul['alokasi_waktu']) ?></td></tr>
        <tr><td><b>Model Belajar</b></td><td>:</td><td><?= esc($modul['model_belajar']) ?></td></tr>
    </table>

    <div class="section-title">A. Identifikasi Murid</div>
    <div><?= !empty($modul['identifikasi_murid']) ? $modul['identifikasi_murid'] : '-' ?></div>

    <div class="section-title">B. Dimensi Profil Lulusan</div>
    <?php 
        $profil = json_decode($modul['profil_pancasila'] ?? '[]', true);
        if(!empty($profil) && is_array($profil)): 
    ?>
    <ul>
        <?php foreach($profil as $p): ?>
            <?php if(is_array($p)): ?>
                <li><b><?= esc($p['dimensi'] ?? '') ?>:</b> <?= esc($p['deskripsi'] ?? '') ?></li>
            <?php else: ?>
                <li><?= esc($p) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>

    <div class="section-title">C. Desain Pembelajaran</div>
    <b>1. Tujuan Pembelajaran</b>
    <div><?= $modul['tujuan_pembelajaran'] ?></div>
    
    <br>
    <b>2. Kerangka Pembelajaran</b>
    <?php 
        $kerangka = json_decode($modul['kerangka_pembelajaran'] ?? '[]', true);
        if(!empty($kerangka) && is_array($kerangka)):
    ?>
    <table class="bordered">
        <tr><th>Aspek</th><th>Deskripsi</th></tr>
        <tr><td>Praktik Pedagogik</td><td><?= $kerangka['praktik_pedagogik'] ?? '-' ?></td></tr>
        <tr><td>Kemitraan</td><td><?= $kerangka['kemitraan'] ?? '-' ?></td></tr>
        <tr><td>Lingkungan</td><td><?= $kerangka['lingkungan'] ?? '-' ?></td></tr>
        <tr><td>Digital</td><td><?= $kerangka['digital'] ?? '-' ?></td></tr>
    </table>
    <?php endif; ?>

    <div class="section-title">D. Langkah Pembelajaran</div>
    <div><?= $modul['kegiatan_inti'] ?></div>

    <div class="section-title">E. Media Pembelajaran</div>
    <div><?= str_ireplace('HTML', '', $modul['media_pembelajaran']) ?></div>

    <div class="section-title">F. Asesmen</div>
    <?php 
        $asesmenJson = json_decode($modul['asesmen_deskripsi'] ?? '', true);
        if(json_last_error() === JSON_ERROR_NONE && is_array($asesmenJson)): 
    ?>
        <ul>
            <li><b>Pengetahuan:</b> <?= $asesmenJson['pengetahuan'] ?? '-' ?></li>
            <li><b>Keterampilan:</b> <?= $asesmenJson['keterampilan'] ?? '-' ?></li>
            <li><b>Sikap:</b> <?= $asesmenJson['sikap'] ?? '-' ?></li>
        </ul>
    <?php else: ?>
        <?= $modul['asesmen_formatif'] ?>
    <?php endif; ?>

    <?php
        // Logika Fallback Tanda Tangan
        $namaKepsek = $sekolah['kepala_sekolah'] ?? $sekolah['nama_kepsek'] ?? '..........................';
        $nipKepsek  = $sekolah['nip_kepala_sekolah'] ?? $sekolah['nip_ks'] ?? $sekolah['nip_kepsek'] ?? '..........................';
        $namaGuru   = $dataKelas['nama_guru'] ?? $dataKelas['wali_kelas'] ?? session()->get('nama') ?? '..........................';
        $nipGuru    = $dataKelas['nip_guru'] ?? $dataKelas['nip_wali'] ?? '..........................';
        $kota       = $sekolah['kabupaten_kota'] ?? 'Bondowoso';
    ?>

    <table class="ttd-table">
        <tr>
            <td width="50%">
                Mengetahui,<br>Kepala Sekolah<br><br><br><br>
                <b><u><?= esc($namaKepsek) ?></u></b><br>
                NIP. <?= esc($nipKepsek) ?>
            </td>
            <td width="50%">
                <?= esc($kota) ?>, <?= date('d F Y') ?><br>
                Guru Kelas <?= esc($modul['kelas']) ?><br><br><br><br>
                <b><u><?= esc($namaGuru) ?></u></b><br>
                NIP. <?= esc($nipGuru) ?>
            </td>
        </tr>
    </table>

</body>
</html>