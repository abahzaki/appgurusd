<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 11pt; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .table-kisi { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        .table-kisi th, .table-kisi td { border: 1px solid black; padding: 4px; vertical-align: top; }
        .table-kisi th { background-color: #f2f2f2; text-align: center; font-weight: bold; font-size: 10pt; }
        .table-kisi td { font-size: 10pt; }
        .table-no-border { width: 100%; border: none; margin-bottom: 10px; }
        .table-no-border td { padding: 2px; border: none; vertical-align: top; }
        .page-break { mso-special-character: line-break; page-break-before: always; }
    </style>
</head>
<body>

    <div class="text-center text-bold">
        <p style="margin: 0;">KISI-KISI SOAL <?= $jenis ?></p>
        <p style="margin: 0;">KECAMATAN <?= strtoupper($kecamatan) ?></p>
        <p style="margin: 0;">TAHUN PELAJARAN <?= $tahun ?></p>
        <p style="margin: 0;">KURIKULUM MERDEKA</p>
    </div>
    <br>

    <table class="table-no-border">
        <tr><td width="20%">Mata Pelajaran</td><td width="2%">:</td><td><?= $mapel ?></td></tr>
        <tr><td>Kelas / Semester</td><td>:</td><td><?= $kelas ?> / <?= $semester ?></td></tr>
        <tr><td>Penyusun</td><td>:</td><td><?= $guru ?></td></tr>
    </table>

    <p class="text-bold">A. KISI-KISI PENULISAN SOAL</p>
    
    <table class="table-kisi">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Capaian Pembelajaran (CP)</th>
                <th width="20%">Tujuan Pembelajaran (TP)</th>
                <th width="15%">Materi</th>
                <th width="25%">Indikator Soal</th>
                <th width="8%">Bentuk</th>
                <th width="7%">No. Soal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($content['kisi_kisi'])): ?>
                <?php foreach ($content['kisi_kisi'] as $index => $k): ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= $k['cp'] ?? '-' ?></td> 
                    <td>
                        <?= $k['tp_deskripsi'] ?>
                    </td>
                    <td><?= $k['materi'] ?></td>
                    <td><?= $k['indikator_soal'] ?></td>
                    <td class="text-center"><?= $k['bentuk_soal'] ?></td>
                    <td class="text-center"><?= $k['nomor_soal'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">Data Kisi-kisi Kosong</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <br class="page-break">

    <div class="text-center text-bold">
        <p style="margin: 0;">NASKAH SOAL <?= $jenis ?></p>
        <p style="margin: 0;"><?= strtoupper($mapel) ?> - KELAS <?= strtoupper($kelas) ?></p>
    </div>
    <br>
    <p class="text-bold">B. SOAL PILIHAN GANDA & ISIAN</p>
    <table class="table-no-border">
    <?php if (!empty($content['soal'])): ?>
        <?php foreach ($content['soal'] as $s): ?>
            <tr>
                <td width="5%" valign="top"><?= $s['nomor'] ?>.</td>
                <td width="95%">
                    <?= $s['pertanyaan'] ?>
                    <?php if ($s['bentuk'] == 'PG' && !empty($s['opsi'])): ?>
                        <table class="table-no-border" style="margin-top: 5px; margin-left: 10px;">
                            <?php foreach ($s['opsi'] as $key => $val): ?>
                                <tr><td width="20px"><b><?= $key ?>.</b></td><td><?= $val ?></td></tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else: ?>
                        <p style="margin-top: 5px;">Jawab: __________________________________________________</p>
                    <?php endif; ?>
                    <br>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </table>

    <br class="page-break">
    <p class="text-bold text-center">KUNCI JAWABAN</p>
    <table class="table-kisi" style="width: 50%; margin: 0 auto;">
        <thead><tr><th>No Soal</th><th>Kunci Jawaban</th></tr></thead>
        <tbody>
            <?php if (!empty($content['soal'])): ?>
                <?php foreach ($content['soal'] as $s): ?>
                <tr><td class="text-center"><?= $s['nomor'] ?></td><td class="text-center text-bold"><?= $s['kunci'] ?></td></tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>