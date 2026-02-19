<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <meta charset="utf-8">
    <title>Program Semester</title>
    <style>
        /* DEFINISI HALAMAN KHUSUS WORD (LANDSCAPE) */
        @page Section1 {
            size: 33cm 21.59cm; /* Ukuran F4 Landscape (Lebih lebar dari A4) */
            mso-page-orientation: landscape;
            margin: 1cm 1cm 1cm 1cm; /* Margin tipis */
        }
        
        div.Section1 { 
            page: Section1; 
        }

        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 9pt; 
        } 

        /* JUDUL */
        .title { text-align: center; font-weight: bold; font-size: 12pt; margin-bottom: 10px; text-transform: uppercase; }
        
        /* IDENTITAS */
        .identitas-table { width: 100%; border: none; margin-bottom: 10px; font-weight: bold; font-size: 9pt; }
        .identitas-table td { padding: 2px; vertical-align: top; }

        /* TABEL UTAMA */
        table.promes { width: 100%; border-collapse: collapse; margin-bottom: 10px; border: 2px solid #000; }
        table.promes th { 
            border: 1px solid #000; 
            padding: 3px; 
            text-align: center; 
            font-weight: bold; 
            background-color: #e0e0e0; 
            vertical-align: middle;
            font-size: 8pt;
        }
        table.promes td { 
            border: 1px solid #000; 
            padding: 3px; 
            vertical-align: middle;
            font-size: 8.5pt;
        }

        /* LEBAR KOLOM */
        .col-no { width: 3%; text-align: center; }
        .col-tp { text-align: justify; } /* Auto Width */
        .col-jp { width: 5%; text-align: center; white-space: nowrap; }
        
        /* Kolom Minggu Kecil-Kecil */
        .col-minggu { width: 15px; text-align: center; font-size: 7pt; } 
        .col-ket { width: 5%; }

        /* TANDA TANGAN */
        .ttd-table { width: 100%; border: none; margin-top: 15px; font-size: 10pt; page-break-inside: avoid; }
        .ttd-table td { text-align: center; vertical-align: top; }
        
    </style>
</head>
<body>

<div class="Section1">

    <?php 
    // FUNGSI RENDER TABEL (Reusable)
    function renderPromesTable($semester, $data_tp, $total_jp, $mapel, $kelas) {
        if($semester == 1) {
            $bulans = ['JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOVEMBER', 'DESEMBER'];
        } else {
            $bulans = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI'];
        }
    ?>
        <div class="title">PROGRAM SEMESTER <?= $semester == 1 ? 'GANJIL' : 'GENAP' ?></div>

        <table class="identitas-table">
            <tr>
                <td width="15%">MATA PELAJARAN</td><td width="40%">: <?= strtoupper($mapel['nama_mapel']) ?></td>
                <td width="10%">KELAS / FASE</td><td width="35%">: <?= strtoupper($kelas['nama_kelas']) ?> / <?= $kelas['fase'] ?></td>
            </tr>
            <tr>
                <td>TAHUN PELAJARAN</td><td>: <?= date('Y') ?> / <?= date('Y')+1 ?></td>
                <td>SEMESTER</td><td>: <?= $semester == 1 ? 'I (SATU)' : 'II (DUA)' ?></td>
            </tr>
        </table>

        <table class="promes">
            <thead>
                <tr>
                    <th rowspan="2" class="col-no">NO</th>
                    <th rowspan="2" class="col-tp">TUJUAN PEMBELAJARAN (TP)</th>
                    <th rowspan="2" class="col-jp">ALOKASI<br>WAKTU</th>
                    <th colspan="30">BULAN / MINGGU KE-</th>
                    <th rowspan="2" class="col-ket">KET</th>
                </tr>
                <tr>
                    <?php foreach($bulans as $bln): ?>
                        <th colspan="5"><?= substr($bln, 0, 3) ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th colspan="3"></th> <?php for($i=0; $i<6; $i++): ?>
                        <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
                    <?php endfor; ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1; 
                if(!empty($data_tp)):
                    foreach($data_tp as $tp): 
                ?>
                <tr>
                    <td align="center"><?= $no++ ?></td>
                    <td class="col-tp"><?= $tp['text'] ?></td>
                    <td align="center"><?= $tp['jp'] ?> JP</td>
                    
                    <?php for($k=0; $k<30; $k++): ?><td></td><?php endfor; ?>
                    
                    <td></td>
                </tr>
                <?php endforeach; endif; ?>

                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td align="center"><?= $no++ ?></td>
                    <td>SUMATIF TENGAH SEMESTER (STS)</td>
                    <td align="center">2 JP</td>
                    <?php for($k=0; $k<30; $k++): ?><td></td><?php endfor; ?>
                    <td></td>
                </tr>
                <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td align="center"><?= $no++ ?></td>
                    <td>SUMATIF AKHIR SEMESTER (SAS)</td>
                    <td align="center">2 JP</td>
                    <?php for($k=0; $k<30; $k++): ?><td></td><?php endfor; ?>
                    <td></td>
                </tr>
                
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="2" align="center">TOTAL JAM PELAJARAN</td>
                    <td align="center"><?= $total_jp ?> JP</td>
                    <td colspan="30" style="background-color: #ccc;"></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    <?php } ?>

    <?php if(!empty($sem1)): ?>
        <?= renderPromesTable(1, $sem1, $total_jp_1, $mapel, $kelas); ?>
        
        <table class="ttd-table">
            <tr>
                <td width="10%"></td> <td width="30%">
                    Mengetahui,<br>Kepala Sekolah
                    <br><br><br><br><br>
                    <b><u><?= $kepsek_nama ?></u></b><br>NIP. <?= $kepsek_nip ?>
                </td>
                <td width="20%"></td> <td width="30%">
                    <?= $kabupaten ?>, Juli <?= date('Y') ?><br>Guru Kelas <?= $kelas['nama_kelas'] ?>
                    <br><br><br><br><br>
                    <b><u><?= $guru_nama ?></u></b><br>NIP. <?= $guru_nip ?>
                </td>
                <td width="10%"></td> </tr>
        </table>
    <?php else: ?>
        <p align="center">-- Data Semester 1 Kosong --</p>
    <?php endif; ?>


    <?php if(!empty($sem1) && !empty($sem2)): ?>
        <br clear=all style='mso-special-character:line-break;page-break-before:always'>
    <?php endif; ?>


    <?php if(!empty($sem2)): ?>
        <?= renderPromesTable(2, $sem2, $total_jp_2, $mapel, $kelas); ?>
        
        <table class="ttd-table">
            <tr>
                <td width="10%"></td>
                <td width="30%">
                    Mengetahui,<br>Kepala Sekolah
                    <br><br><br><br><br>
                    <b><u><?= $kepsek_nama ?></u></b><br>NIP. <?= $kepsek_nip ?>
                </td>
                <td width="20%"></td>
                <td width="30%">
                    <?= $kabupaten ?>, Januari <?= date('Y')+1 ?><br>Guru Kelas <?= $kelas['nama_kelas'] ?>
                    <br><br><br><br><br>
                    <b><u><?= $guru_nama ?></u></b><br>NIP. <?= $guru_nip ?>
                </td>
                <td width="10%"></td>
            </tr>
        </table>
    <?php endif; ?>

</div>
</body>
</html>