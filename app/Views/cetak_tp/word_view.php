<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 5px; vertical-align: top; }
        th { background-color: #E0E0E0; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        ul { margin: 0; padding-left: 20px; }
        li { margin-bottom: 5px; }
    </style>
</head>
<body>

    <h3 class="text-center">TUJUAN PEMBELAJARAN (TP)<br>TAHUN PELAJARAN <?= date('Y') ?> / <?= date('Y')+1 ?></h3>
    <br>

    <table style="border: none;">
        <tr>
            <td style="border: none; width: 150px;">Satuan Pendidikan</td>
            <td style="border: none;">: <?= $nama_sekolah ?></td>
        </tr>
        <tr>
            <td style="border: none;">Mata Pelajaran</td>
            <td style="border: none;">: <?= $mapel['nama_mapel'] ?></td>
        </tr>
        <tr>
            <td style="border: none;">Fase / Kelas</td>
            <td style="border: none;">: <?= $fase ?> / (<?= ($fase == 'A') ? 'I - II' : (($fase == 'B') ? 'III - IV' : 'V - VI') ?>)</td>
        </tr>
    </table>

    <br>

    <table>
        <thead>
            <tr>
                <th width="20%">ELEMEN</th>
                <th width="35%">CAPAIAN PEMBELAJARAN (CP)</th>
                <th width="45%">TUJUAN PEMBELAJARAN (TP)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($result as $row): ?>
            <tr>
                <td class="text-bold"><?= $row['elemen'] ?></td>
                <td><?= $row['deskripsi_cp'] ?></td>
                <td>
                    <?php if(!empty($row['tps'])): ?>
                        <ul>
                            <?php foreach($row['tps'] as $tp_item): ?>
                                <li><?= $tp_item ?></li>
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

    <br><br>

    <table style="border: none;">
        <tr>
            <td style="border: none; text-align: center; width: 40%;">
                Mengetahui,<br>
                Kepala Sekolah
                <br><br><br><br>
                <b><u><?= $kepsek_nama ?></u></b><br>
                NIP. <?= $kepsek_nip ?>
            </td>
            <td style="border: none; width: 20%;"></td>
            <td style="border: none; text-align: center; width: 40%;">
                <?= $kabupaten ?>, <?= $tanggal ?><br>
                Guru Kelas
                <br><br><br><br>
                <b><u><?= $guru_nama ?></u></b><br>
                NIP. <?= $guru_nip ?>
            </td>
        </tr>
    </table>

</body>
</html>