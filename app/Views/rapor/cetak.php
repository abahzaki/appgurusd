<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor - <?= $siswa['nama_lengkap'] ?? 'Siswa' ?></title>
    <style>
        /* RESET & DASAR */
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 11pt; 
            color: #000; 
            margin: 0; 
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        /* CONTAINER UTAMA */
        /* Kita pakai padding di body saja saat print agar header browser hilang */
        .container { 
            width: 210mm; 
            margin: 0 auto; 
            padding: 10mm;
            background: white;
        }
        
        /* HEADER */
        .judul { text-align: center; font-weight: bold; font-size: 14pt; margin-bottom: 2px; }
        .sub-judul { text-align: center; font-size: 11pt; font-weight: bold; margin-bottom: 20px; }

        /* TABEL IDENTITAS */
        .tbl-identitas { width: 100%; margin-bottom: 15px; font-size: 10pt; }
        .tbl-identitas td { padding: 2px; vertical-align: top; }

        /* TABEL NILAI */
        .tbl-nilai { width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 10pt; }
        .tbl-nilai th, .tbl-nilai td { border: 1px solid #000; padding: 4px 6px; vertical-align: middle; }
        .tbl-nilai th { background-color: #f2f2f2; text-align: center; font-weight: bold; height: 30px; }
        .nilai-angka { text-align: center; font-weight: bold; font-size: 11pt; }
        .deskripsi { text-align: justify; font-size: 9.5pt; line-height: 1.2; padding: 5px; }
        
        .sub-header-mapel { background-color: #fafafa; font-weight: bold; font-size: 10pt; }

        /* GROUPING KHUSUS (CATATAN + TTD) */
        /* Ini kuncinya: Blok ini tidak boleh terpotong. Kalau gak muat, pindah semua ke hal 2 */
        .signature-wrapper {
            width: 100%;
            margin-top: 10px;
            page-break-inside: avoid; 
            break-inside: avoid; 
        }

        /* TANDA TANGAN */
        .signature-box { 
            width: 100%; 
            display: flex; 
            justify-content: space-between; 
            text-align: center;
            margin-top: 20px;
        }
        
        .ttd { width: 35%; }
        .ttd-kepsek { width: 100%; text-align: center; margin-top: 20px; }
        
        /* SPACER TANDA TANGAN (Biar lebih lebar) */
        .spacer-ttd {
            height: 100px; /* Sesuaikan tinggi ini jika kurang lebar */
        }

        /* TOMBOL PRINT */
        .no-print { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        .btn-print { background: #007bff; color: white; padding: 10px 20px; border-radius: 5px; cursor: pointer; border: none; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .btn-back { background: #6c757d; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-left: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }

        /* PENGATURAN CETAK KHUSUS */
        @media print {
            .no-print { display: none; }
            
            /* HILANGKAN HEADER/FOOTER BROWSER */
            @page { 
                size: A4; 
                margin: 0; /* Penting! Margin 0 mematikan teks browser */
            }
            
            /* Ganti Margin Halaman lewat Body */
            body { 
                margin: 20mm 15mm 20mm 15mm; /* Atas Kanan Bawah Kiri */
            }

            .container { 
                width: 100%; 
                padding: 0; 
                margin: 0; 
                border: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ CETAK RAPOR</button>
        <a href="<?= base_url('rapor') ?>" class="btn-back">Kembali</a>
    </div>

    <div class="container">
        <div class="judul">LAPORAN HASIL BELAJAR (RAPOR)</div>
        <div class="sub-judul">KURIKULUM MERDEKA</div>
        
        <table class="tbl-identitas">
            <tr>
                <td width="20%">Nama Peserta Didik</td><td width="2%">:</td><td width="40%"><b><?= strtoupper($siswa['nama_lengkap']) ?></b></td>
                <td width="15%">Kelas</td><td width="2%">:</td><td><?= $kelas['nama_kelas'] ?></td>
            </tr>
            <tr>
                <td>NIS / NISN</td><td>:</td><td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?></td>
                <td>Fase</td><td>:</td><td><?= $kelas['fase'] ?></td>
            </tr>
            <tr>
                <td>Sekolah</td><td>:</td><td><?= strtoupper($sekolah['nama_sekolah'] ?? '-') ?></td>
                <td>Semester</td><td>:</td><td><?= ($setting['semester'] == '1') ? '1 (Ganjil)' : '2 (Genap)' ?></td>
            </tr>
            <tr>
                <td>Alamat</td><td>:</td><td><?= $sekolah['alamat_sekolah'] ?? '-' ?></td>
                <td>Tahun Ajaran</td><td>:</td><td><?= $setting['tahun_ajaran'] ?? '-' ?></td>
            </tr>
        </table>

        <table class="tbl-nilai">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="28%">Muatan Pelajaran</th>
                    <th width="9%">Nilai</th>
                    <th width="58%">Capaian Kompetensi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $nomor_tampil = 1;
                    function cek_kategori($nama_mapel) {
                        $nama = strtolower($nama_mapel);
                        if (strpos($nama, 'muatan lokal') !== false || strpos($nama, 'bahasa daerah') !== false || strpos($nama, 'mulok') !== false || strpos($nama, 'madura') !== false || strpos($nama, 'jawa') !== false || strpos($nama, 'sunda') !== false || strpos($nama, 'baca tulis') !== false || strpos($nama, 'btq') !== false || strpos($nama, 'qur\'an') !== false ) { return 'mulok'; }
                        if (strpos($nama, 'seni') !== false || strpos($nama, 'pilihan') !== false) { return 'seni'; }
                        return 'wajib';
                    }
                    
                    // 1. KELOMPOK A (WAJIB)
                    foreach($nilai as $id_mapel => $data_nilai) {
                        if (cek_kategori($data_nilai['nama_mapel']) == 'wajib') {
                            echo '<tr>';
                            echo '<td class="nilai-angka">' . $nomor_tampil++ . '</td>';
                            echo '<td>' . $data_nilai['nama_mapel'] . '</td>';
                            echo '<td class="nilai-angka">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['nilai_akhir'] : '-') . '</td>';
                            echo '<td class="deskripsi">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['deskripsi'] : '-') . '</td>';
                            echo '</tr>';
                        }
                    }
                ?>

                <tr>
                    <td class="nilai-angka"><?= $nomor_tampil++ ?></td>
                    <td colspan="3" class="sub-header-mapel">Seni dan Pilihan</td>
                </tr>
                <?php 
                    $abjad = 'a';
                    foreach($nilai as $id_mapel => $data_nilai) {
                        if (cek_kategori($data_nilai['nama_mapel']) == 'seni') {
                            echo '<tr>';
                            echo '<td></td>'; 
                            echo '<td>' . $abjad++ . '. ' . $data_nilai['nama_mapel'] . '</td>';
                            echo '<td class="nilai-angka">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['nilai_akhir'] : '-') . '</td>';
                            echo '<td class="deskripsi">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['deskripsi'] : '-') . '</td>';
                            echo '</tr>';
                        }
                    }
                ?>

                <tr>
                    <td colspan="4" class="sub-header-mapel">Muatan Lokal</td>
                </tr>
                <?php 
                    $no_mulok = 1;
                    $ada_mulok = false;
                    foreach($nilai as $id_mapel => $data_nilai) {
                        if (cek_kategori($data_nilai['nama_mapel']) == 'mulok') {
                            $ada_mulok = true;
                            echo '<tr>';
                            echo '<td class="nilai-angka">' . $no_mulok++ . '</td>';
                            echo '<td>' . $data_nilai['nama_mapel'] . '</td>';
                            echo '<td class="nilai-angka">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['nilai_akhir'] : '-') . '</td>';
                            echo '<td class="deskripsi">' . ($data_nilai['nilai_akhir'] > 0 ? $data_nilai['deskripsi'] : '-') . '</td>';
                            echo '</tr>';
                        }
                    }
                    if(!$ada_mulok) {
                        echo '<tr><td colspan="4" class="text-center">-</td></tr>';
                    }
                ?>
            </tbody>
        </table>

        <div style="display: flex; gap: 10px; margin-bottom: 10px;">
            <div style="flex: 1;">
                <table class="tbl-nilai" style="margin: 0; width: 100%;">
                    <thead><tr><th colspan="2">Ekstrakurikuler</th></tr></thead>
                    <tbody>
                        <?php if(!empty($ekskul)): foreach($ekskul as $e): ?>
                        <tr><td><?= $e['nama_ekskul'] ?></td><td class="nilai-angka"><?= $e['predikat'] ?></td></tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="2" style="text-align:center">-</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="flex: 1;">
                <table class="tbl-nilai" style="margin: 0; width: 100%;">
                    <thead><tr><th colspan="2">Ketidakhadiran</th></tr></thead>
                    <tbody>
                        <tr><td>Sakit</td><td class="nilai-angka"><?= $absensi['sakit'] ?? 0 ?> hari</td></tr>
                        <tr><td>Izin</td><td class="nilai-angka"><?= $absensi['izin'] ?? 0 ?> hari</td></tr>
                        <tr><td>Alpha</td><td class="nilai-angka"><?= $absensi['alpha'] ?? 0 ?> hari</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="signature-wrapper">

            <table class="tbl-nilai" style="margin-bottom: 20px;">
                <tr><th style="text-align: left; background: #fafafa;">Catatan Wali Kelas</th></tr>
                <tr>
                    <td style="height: 50px; font-style: italic; padding: 10px;">
                        "<?= $absensi['catatan'] ?? '-' ?>"
                    </td>
                </tr>
            </table>

            <?php if(isset($setting['semester']) && $setting['semester'] == '2'): ?>
            <div style="border: 1px solid #000; padding: 10px; margin-bottom: 20px; text-align: center;">
                Berdasarkan pencapaian kompetensi, peserta didik ditetapkan:<br>
                <b>
                <?php 
                    $status = $absensi['status_naik'] ?? '';
                    if($status == 'Naik Kelas') echo 'NAIK KE KELAS ' . (intval($kelas['nama_kelas']) + 1); 
                    elseif($status == 'Lulus') echo 'LULUS';
                    elseif($status == 'Tinggal Kelas') echo 'TINGGAL DI KELAS ' . $kelas['nama_kelas'];
                    else echo '-';
                ?>
                </b>
            </div>
            <?php endif; ?>

            <?php 
                $bulan_indo = [1=>'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $tgl_db = !empty($setting['tanggal_rapor']) ? $setting['tanggal_rapor'] : date('Y-m-d');
                $pecah = explode('-', $tgl_db);
                $tgl_indo = $pecah[2] . ' ' . $bulan_indo[(int)$pecah[1]] . ' ' . $pecah[0];
                $kota = !empty($setting['kota_terbit']) ? $setting['kota_terbit'] : ($sekolah['kabupaten_kota'] ?? '..........');
            ?>

            <div class="signature-box">
                <div class="ttd">
                    Mengetahui,<br>Orang Tua/Wali,<br>
                    <div class="spacer-ttd"></div>
                    (.......................................)
                </div>
                <div class="ttd">
                    <?= ucfirst(strtolower($kota)) ?>, <?= $tgl_indo ?><br>
                    Wali Kelas,<br>
                    <div class="spacer-ttd"></div>
                    <b><u><?= $kelas['wali_kelas'] ?? '......................' ?></u></b><br>
                    NIP. <?= $kelas['nip_wali'] ?? '-' ?>
                </div>
            </div>

            <div class="ttd-kepsek">
                Mengetahui,<br>Kepala Sekolah<br>
                <div class="spacer-ttd"></div>
                <b><u><?= $sekolah['nama_kepsek'] ?? '......................' ?></u></b><br>
                NIP. <?= $sekolah['nip_kepsek'] ?? '-' ?>
            </div>
            
        </div>
        </div>
</body>
</html>