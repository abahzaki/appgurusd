<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <title>Modul Ajar Word</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; line-height: 1.15; }
        h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; margin-bottom: 20px; text-transform: uppercase; }
        .section-title { font-weight: bold; margin-top: 15px; margin-bottom: 5px; text-transform: uppercase;}
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        td { vertical-align: top; padding: 3px; }
        
        ul, ol { margin-top: 0; margin-bottom: 0; padding-left: 20px; }
        .signature-table { margin-top: 40px; width: 100%; page-break-inside: avoid; }
        .signature-table td { text-align: center; }
        
        .sub-point { margin-left: 20px; margin-bottom: 5px; }
    </style>
</head>
<body>

    <p class="title">PERENCANAAN PEMBELAJARAN MENDALAM (PPM)</p>

    <table>
        <tr>
            <td width="25%"><b>Satuan Pendidikan</b></td>
            <td width="2%">:</td>
            <td><?= esc($sekolah['nama_sekolah'] ?? 'UPTD SPF SDN ...') ?></td>
        </tr>
        <tr><td><b>Mata Pelajaran</b></td><td>:</td><td><?= esc($modul['mapel']) ?></td></tr>
        <tr><td><b>Fase / Kelas</b></td><td>:</td><td><?= esc($modul['fase']) ?> / <?= esc($modul['kelas']) ?></td></tr>
        <tr><td><b>Pembelajaran Ke-</b></td><td>:</td><td>1 (Satu)</td></tr>
        <tr><td><b>Alokasi Waktu</b></td><td>:</td><td><?= esc($modul['alokasi_waktu']) ?></td></tr>
        <tr><td><b>Materi Pokok</b></td><td>:</td><td><?= esc($modul['materi']) ?></td></tr>
    </table>

    <div class="section-title">A. IDENTIFIKASI MURID</div>
    <div style="text-align: justify;">
        <?php 
            // Fallback Identifikasi Murid
            if (!empty($modul['identifikasi_murid']) && strlen($modul['identifikasi_murid']) > 5) {
                echo $modul['identifikasi_murid'];
            } else {
                echo "Murid di kelas ini memiliki latar belakang kemampuan akademik yang beragam. Sebagian siswa memiliki gaya belajar visual yang menyukai gambar, sementara yang lain kinestetik yang lebih suka praktik langsung.";
            }
        ?>
    </div>

    <div class="section-title">B. DIMENSI PROFIL LULUSAN</div>
    <?php 
        $profil = json_decode($modul['profil_pancasila'] ?? '[]', true);
        if(!empty($profil) && is_array($profil)): 
    ?>
    <ol>
        <?php foreach($profil as $p): ?>
            <?php if(is_array($p)): ?>
                <li><b><?= esc($p['dimensi'] ?? '') ?>:</b> <?= esc($p['deskripsi'] ?? '') ?></li>
            <?php else: ?>
                <li><?= esc($p) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
    <?php else: ?>
        <p>1. Mandiri<br>2. Bernalar Kritis</p> 
    <?php endif; ?>

    <div class="section-title">C. DESAIN PEMBELAJARAN</div>
    
    <div style="margin-bottom: 10px;">
        <b>1. TUJUAN PEMBELAJARAN</b>
        <div style="margin-left: 15px;">
            <?= $modul['tujuan_pembelajaran'] ?>
        </div>
    </div>

    <div>
        <b>2. KERANGKA PEMBELAJARAN</b>
        <?php 
            $kerangka = json_decode($modul['kerangka_pembelajaran'] ?? '[]', true);
        ?>
        
        <div class="sub-point">
            a. Praktik Pedagogik
            <div style="margin-left: 15px;">
                <?= !empty($kerangka['praktik_pedagogik']) ? $kerangka['praktik_pedagogik'] : "Model " . esc($modul['model_belajar']) . " dengan pendekatan diskusi interaktif." ?>
            </div>
        </div>
        
        <div class="sub-point">
            b. Kemitraan Pembelajaran
            <div style="margin-left: 15px;">
                <?= !empty($kerangka['kemitraan']) ? $kerangka['kemitraan'] : '-' ?>
            </div>
        </div>

        <div class="sub-point">
            c. Lingkungan Pembelajaran
            <div style="margin-left: 15px;">
                <?= !empty($kerangka['lingkungan']) ? $kerangka['lingkungan'] : 'Ruang kelas yang disusun berkelompok.' ?>
            </div>
        </div>

        <div class="sub-point">
            d. Pemanfaatan Digital
            <div style="margin-left: 15px;">
                <?= !empty($kerangka['digital']) ? $kerangka['digital'] : 'Menggunakan proyektor dan slide presentasi.' ?>
            </div>
        </div>
    </div>

    <div class="section-title">D. LANGKAH - LANGKAH PEMBELAJARAN</div>
    <div>
        <?= $modul['kegiatan_inti'] ?>
    </div>

    <div class="section-title">E. MEDIA PEMBELAJARAN</div>
    <div>
        <?php 
            // Bersihkan kata 'HTML'
            $mediaClean = str_ireplace('HTML', '', $modul['media_pembelajaran']);
            echo $mediaClean;
        ?>
    </div>

    <div class="section-title">F. ASESMEN</div>
    <?php 
        $asesmenJson = json_decode($modul['asesmen_deskripsi'] ?? '', true);
        if(json_last_error() === JSON_ERROR_NONE && is_array($asesmenJson)): 
    ?>
        <ol>
            <li><b>Pengetahuan:</b> <?= $asesmenJson['pengetahuan'] ?? 'Tes Tulis' ?></li>
            <li><b>Keterampilan:</b> <?= $asesmenJson['keterampilan'] ?? 'Unjuk Kerja' ?></li>
            <li><b>Sikap:</b> <?= $asesmenJson['sikap'] ?? 'Observasi' ?></li>
        </ol>
    <?php else: ?>
        <ol>
            <li><b>Pengetahuan:</b> Tes Tulis (Isian Singkat)</li>
            <li><b>Keterampilan:</b> Penilaian Kinerja Kelompok</li>
            <li><b>Sikap:</b> Jurnal Observasi Profil Pancasila</li>
        </ol>
    <?php endif; ?>

    <br><br>
    
    <?php
        // Cek Nama Kepala Sekolah (Prioritas: kepala_sekolah -> nama_kepsek -> titik-titik)
        $namaKepsek = !empty($sekolah['kepala_sekolah']) ? $sekolah['kepala_sekolah'] : ($sekolah['nama_kepsek'] ?? '..........................');
        $nipKepsek  = !empty($sekolah['nip_kepala_sekolah']) ? $sekolah['nip_kepala_sekolah'] : ($sekolah['nip_ks'] ?? $sekolah['nip_kepsek'] ?? '..........................');
        
        // Cek Nama Guru
        $namaGuru   = !empty($dataKelas['nama_guru']) ? $dataKelas['nama_guru'] : ($dataKelas['wali_kelas'] ?? session()->get('nama') ?? '..........................');
        $nipGuru    = !empty($dataKelas['nip_guru']) ? $dataKelas['nip_guru'] : ($dataKelas['nip_wali'] ?? '..........................');
        
        $kota       = $sekolah['kabupaten_kota'] ?? 'Bondowoso';
    ?>

    <table class="signature-table">
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
    
    <?php if(!empty($modul['lampiran_lkpd']) || !empty($modul['asesmen_sumatif'])): ?>
        <br clear="all" style="page-break-before:always" />
        <p class="title">LAMPIRAN</p>
        
        <?php if(!empty($modul['lampiran_lkpd'])): ?>
            <div class="section-title">1. ASESMEN FORMATIF (LKPD & RUBRIK)</div>
            <?= $modul['lampiran_lkpd'] ?>
        <?php endif; ?>

        <?php if(!empty($modul['asesmen_sumatif'])): ?>
            <div class="section-title">2. ASESMEN SUMATIF (SOAL)</div>
            <?= $modul['asesmen_sumatif'] ?>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>