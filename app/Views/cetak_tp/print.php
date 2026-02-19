<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 40px; }
        .header { text-align: center; margin-bottom: 20px; font-weight: bold; text-transform: uppercase; }
        .sub-header { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; vertical-align: top; }
        th { background-color: #f2f2f2; text-align: center; }
        .ttd-area { width: 100%; margin-top: 50px; display: table; }
        .ttd-box { display: table-cell; width: 50%; text-align: center; vertical-align: top; }
        .garis-bawah { border-bottom: 1px solid black; display: inline-block; min-width: 150px; }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        TUJUAN PEMBELAJARAN (TP)<br>
        TAHUN PELAJARAN <?= date('Y') ?> / <?= date('Y')+1 ?>
    </div>

    <div class="sub-header">
        <table style="border: none; width: auto;">
            <tr>
                <td style="border: none; padding: 2px 20px 2px 0;">Satuan Pendidikan</td>
                <td style="border: none; padding: 2px;">: <?= $sekolah['nama_sekolah'] ?? 'SD .........' ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 20px 2px 0;">Mata Pelajaran</td>
                <td style="border: none; padding: 2px;">: <?= $mapel['nama_mapel'] ?></td>
            </tr>
            <tr>
                <td style="border: none; padding: 2px 20px 2px 0;">Fase / Kelas</td>
                <td style="border: none; padding: 2px;">: <?= $fase ?> / (<?= ($fase == 'A') ? 'I - II' : (($fase == 'B') ? 'III - IV' : 'V - VI') ?>)</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">ELEMEN</th>
                <th style="width: 35%;">CAPAIAN PEMBELAJARAN (CP)</th>
                <th style="width: 45%;">TUJUAN PEMBELAJARAN (TP)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result as $row): ?>
            <tr>
                <td style="font-weight: bold;"><?= $row['elemen'] ?></td>
                <td style="text-align: justify;"><?= $row['deskripsi_cp'] ?></td>
                <td>
                    <?php if(!empty($row['tps'])): ?>
                        <ul style="margin: 0; padding-left: 20px;">
                            <?php foreach($row['tps'] as $tp): ?>
                                <li style="margin-bottom: 5px; text-align: justify;"><?= $tp['deskripsi_tp'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="ttd-area">
        <div class="ttd-box">
            Mengetahui,<br>
            Kepala Sekolah<br><br><br><br><br>
            <span class="garis-bawah"><b><?= $kepsek_nama ?></b></span><br>
            NIP. <?= $kepsek_nip ?>
        </div>
        <div class="ttd-box">
            <?= $sekolah['kabupaten'] ?? 'Kota' ?>, <?= date('d F Y') ?><br>
            Guru Kelas<br><br><br><br><br>
            <span class="garis-bawah"><b><?= $guru_nama ?></b></span><br>
            NIP. <?= $guru_nip ?>
        </div>
    </div>

</body>
</html>