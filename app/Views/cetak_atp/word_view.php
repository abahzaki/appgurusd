<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cetak ATP</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; margin-bottom: 20px; }
        .identitas-table { width: 100%; border: none; margin-bottom: 20px; font-weight: bold; }
        .identitas-table td { padding: 4px; vertical-align: top; }
        
        /* TABEL BIRU TOSCA */
        table.main { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.main th { 
            border: 1px solid #000; 
            background-color: #4db6ac; 
            color: #000; padding: 10px; text-align: center; font-weight: bold; 
        }
        table.main td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        
        ol { margin: 0; padding-left: 25px; }
        li { margin-bottom: 5px; text-align: justify; }
        
        .ttd-table { width: 100%; border: none; margin-top: 30px; }
        .ttd-table td { text-align: center; vertical-align: top; }
    </style>
</head>
<body>

    <div class="title">ALUR TUJUAN PEMBELAJARAN (ATP)</div>

    <table class="identitas-table">
        <tr><td width="180">MATA PELAJARAN</td><td>: <?= strtoupper($mapel['nama_mapel']) ?></td></tr>
        <tr><td>FASE / KELAS</td><td>: FASE <?= $kelas['fase'] ?> (KELAS <?= strtoupper($kelas['nama_kelas']) ?>)</td></tr>
        <tr><td>TAHUN PELAJARAN</td><td>: <?= date('Y') ?> / <?= date('Y')+1 ?></td></tr>
    </table>

    <table class="main">
        <thead>
            <tr>
                <th width="15%">KELAS</th>
                <th width="20%">SEMESTER</th>
                <th width="65%">ALUR TUJUAN PEMBELAJARAN (ATP)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center text-bold" style="vertical-align: middle;">KELAS <?= $kelas['nama_kelas'] ?></td>
                <td class="text-center text-bold" style="vertical-align: middle;">
                    SEMESTER 1<br>
                    (Total: <?= $total_jp_1 ?> JP)
                </td>
                <td>
                    <?php if(!empty($sem1)): ?>
                        <ol>
                            <?php foreach($sem1 as $item): ?>
                                <li><?= $item['text'] ?></li> 
                                <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p class="text-center">-</p>
                    <?php endif; ?>
                </td>
            </tr>

            <tr>
                <td class="text-center text-bold" style="vertical-align: middle;">KELAS <?= $kelas['nama_kelas'] ?></td>
                <td class="text-center text-bold" style="vertical-align: middle;">
                    SEMESTER 2<br>
                    (Total: <?= $total_jp_2 ?> JP)
                </td>
                <td>
                    <?php if(!empty($sem2)): ?>
                        <ol start="<?= count($sem1) + 1 ?>">
                            <?php foreach($sem2 as $item): ?>
                                <li><?= $item['text'] ?></li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else: ?>
                        <p class="text-center">-</p>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="ttd-table">
        <tr>
            <td width="50%">
                Mengetahui,<br>Kepala Sekolah<br><br><br><br><br>
                <b><u><?= $kepsek_nama ?></u></b><br>NIP. <?= $kepsek_nip ?>
            </td>
            <td width="50%">
                <?= $kabupaten ?>, <?= $tanggal ?><br>Guru Kelas <?= $kelas['nama_kelas'] ?><br><br><br><br><br>
                <b><u><?= $guru_nama ?></u></b><br>NIP. <?= $guru_nip ?>
            </td>
        </tr>
    </table>
</body>
</html>