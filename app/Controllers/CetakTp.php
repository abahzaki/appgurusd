<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MapelModel;
use App\Models\ReferensiCpModel;
use App\Models\ReferensiTpModel;
use App\Models\SekolahModel;
use App\Models\KelasModel;
use App\Models\TpModel;

class CetakTp extends BaseController
{
    protected $mapelModel;
    protected $cpModel;
    protected $tpModel;
    protected $sekolahModel;
    protected $kelasModel;
    protected $tpUserModel;

    public function __construct()
    {
        $this->mapelModel   = new MapelModel();
        $this->cpModel      = new ReferensiCpModel();
        $this->tpModel      = new ReferensiTpModel();
        $this->sekolahModel = new SekolahModel();
        $this->kelasModel   = new KelasModel();
        $this->tpUserModel  = new TpModel();
    }

    // 1. HALAMAN DEPAN (BANK TP)
    public function index()
    {
        $userId = session()->get('id');

        // AMBIL DATA KELAS GURU (PENTING UNTUK PERBAIKAN FASE)
        $kelasGuru = $this->kelasModel->where('user_id', $userId)->first();

        // Ambil riwayat
        $riwayat = $this->tpUserModel
                        ->select('tujuan_pembelajaran.mapel_id, tujuan_pembelajaran.fase, mapel.nama_mapel, COUNT(tujuan_pembelajaran.id) as jumlah_tp, MAX(tujuan_pembelajaran.created_at) as tgl_update')
                        ->join('mapel', 'mapel.id = tujuan_pembelajaran.mapel_id')
                        ->where('tujuan_pembelajaran.user_id', $userId)
                        ->groupBy('tujuan_pembelajaran.mapel_id, tujuan_pembelajaran.fase')
                        ->orderBy('tgl_update', 'DESC')
                        ->findAll();

        $data = [
            'title'      => 'Bank Tujuan Pembelajaran (TP)',
            'riwayat'    => $riwayat,
            'mapel'      => $this->mapelModel->findAll(),
            'kelas_guru' => $kelasGuru // Dikirim ke view sebagai penyelamat jika fase kosong
        ];
        
        return view('cetak_tp/index', $data);
    }

    // 2. FORM PENYUSUNAN
    public function susun()
    {
        $mapel_id = $this->request->getPost('mapel_id');
        $fase     = $this->request->getPost('fase');
        $userId   = session()->get('id');

        // [AUTO-FIX] Jika Fase Kosong (dari tombol edit yg error), AMBIL DARI KELAS GURU
        if (empty($fase)) {
            $kelasGuru = $this->kelasModel->where('user_id', $userId)->first();
            $fase = $kelasGuru['fase'] ?? 'A'; // Default A jika parah banget
        }

        if (!$mapel_id) {
            return redirect()->to('cetak_tp')->with('error', 'Data Mapel hilang.');
        }

        // 1. Ambil Referensi Sistem (Wajib pakai Fase yang benar)
        $dataCP = $this->cpModel->getCpByMapelFase($mapel_id, $fase);
        $dataTP = $this->tpModel->where('mapel_id', $mapel_id)
                                ->where('fase', $fase)
                                ->findAll();

        // 2. AMBIL DATA TERSIMPAN (SAYA LONGGARKAN FILTERNYA)
        // Kita HAPUS where('fase', $fase) disini supaya data lama yang fasenya NULL tetap ke-load
        $savedTP = $this->tpUserModel->where('user_id', $userId)
                                     ->where('mapel_id', $mapel_id)
                                     ->findColumn('deskripsi_tp') ?? [];

        $dtMapel = $this->mapelModel->find($mapel_id);

        $data = [
            'title'    => 'Susun Tujuan Pembelajaran',
            'mapel'    => $dtMapel,
            'fase'     => $fase,
            'data_cp'  => $dataCP,
            'data_tp'  => $dataTP,
            'saved_tp' => $savedTP
        ];

        return view('cetak_tp/susun', $data);
    }

    // 3. SIMPAN (SAMA SEPERTI SEBELUMNYA, TAPI DIJAMIN RAPI)
    public function simpan_dan_export()
    {
        $mapel_id = $this->request->getPost('mapel_id');
        $fase     = $this->request->getPost('fase');
        $userId   = session()->get('id');

        $kelasGuru = $this->kelasModel->where('user_id', $userId)->first();
        $kelasId   = $kelasGuru ? $kelasGuru['id'] : 0;

        $pilihan_tp = $this->request->getPost('pilihan_tp') ?? [];
        $tp_mandiri = $this->request->getPost('tp_mandiri') ?? [];

        $dataToSave  = [];
        $dataForWord = [];

        $dataCP = $this->cpModel->getCpByMapelFase($mapel_id, $fase);

        foreach ($dataCP as $cp) {
            $id_cp  = $cp['id'];
            $elemen = $cp['elemen'];
            $list_tp_final = [];

            // A. Checkbox
            if (isset($pilihan_tp[$id_cp])) {
                foreach ($pilihan_tp[$id_cp] as $teks_tp) {
                    $list_tp_final[] = $teks_tp;
                    $dataToSave[] = [
                        'user_id' => $userId, 'mapel_id' => $mapel_id, 'kelas_id' => $kelasId,
                        'fase' => $fase, 'elemen' => $elemen, 
                        'kode_tp' => '', 'deskripsi_tp' => $teks_tp,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                }
            }

            // B. Mandiri
            if (!empty($tp_mandiri[$id_cp])) {
                $lines = explode("\n", $tp_mandiri[$id_cp]);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (!empty($line)) {
                        $list_tp_final[] = $line;
                        $dataToSave[] = [
                            'user_id' => $userId, 'mapel_id' => $mapel_id, 'kelas_id' => $kelasId,
                            'fase' => $fase, 'elemen' => $elemen,
                            'kode_tp' => 'MANDIRI', 'deskripsi_tp' => $line,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                    }
                }
            }

            $dataForWord[] = ['elemen' => $elemen, 'deskripsi_cp' => $cp['deskripsi_cp'], 'tps' => $list_tp_final];
        }

        // Hapus data lama (Apapun fasenya, pokoknya mapel ini, user ini)
        $this->tpUserModel->where('user_id', $userId)->where('mapel_id', $mapel_id)->delete();
        
        if (!empty($dataToSave)) {
            $this->tpUserModel->insertBatch($dataToSave);
        }

        // Export Logic
        $sekolah = $this->sekolahModel->where('user_id', $userId)->first();
        $dtMapel = $this->mapelModel->find($mapel_id);

        $data = [
            'mapel' => $dtMapel, 'fase' => $fase, 'result' => $dataForWord,
            'nama_sekolah' => $sekolah['nama_sekolah'] ?? 'Sekolah',
            'kabupaten' => $sekolah['kabupaten_kota'] ?? 'Kota',
            'kepsek_nama' => $sekolah['nama_kepsek'] ?? '......................',
            'kepsek_nip' => $sekolah['nip_kepsek'] ?? '......................',
            'guru_nama' => $kelasGuru['wali_kelas'] ?? session()->get('nama'),
            'guru_nip' => $kelasGuru['nip_wali'] ?? '......................',
            'tanggal' => date('d F Y')
        ];

        $filename = 'TP_' . str_replace(' ', '_', $dtMapel['nama_mapel']) . '.doc';
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=" . $filename);
        
        return view('cetak_tp/word_view', $data);
    }

    public function delete($mapel_id)
    {
        $userId = session()->get('id');

        // Hapus semua TP milik user ini pada mapel ini
        $this->tpUserModel->where('user_id', $userId)
                          ->where('mapel_id', $mapel_id)
                          ->delete();

        return redirect()->to('cetak_tp')->with('success', 'Data TP berhasil direset. Silakan susun baru.');
    }
}