<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\CatatanWalasModel;

class Catatan extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $catatanModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->catatanModel = new CatatanWalasModel();
    }

    public function index()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        $kelas_id = $this->request->getGet('kelas_id');
        
        // 1. Ambil data kelas untuk dropdown (Hanya milik guru ini)
        $kelas = $this->kelasModel->where('user_id', $id_guru)->findAll();
        
        $siswa = [];
        $catatan_map = [];

        if ($kelas_id) {
            // 2. Ambil Siswa di kelas tersebut (Milik guru ini)
            $siswa = $this->siswaModel->where([
                'kelas_id' => $kelas_id,
                'user_id'  => $id_guru
            ])->findAll();
            
            // 3. Ambil data catatan yang sudah ada (Milik guru ini)
            $existing = $this->catatanModel->where([
                'kelas_id' => $kelas_id,
                'user_id'  => $id_guru
            ])->findAll();
            
            // Mapping biar mudah dipanggil di view
            foreach($existing as $row) {
                $catatan_map[$row['siswa_id']] = $row;
            }
        }

        $data = [
            'title'       => 'Input Catatan Walas & Absensi',
            'kelas'       => $kelas,
            'kelas_id'    => $kelas_id,
            'siswa'       => $siswa,
            'catatan_map' => $catatan_map
        ];

        return view('catatan/index', $data);
    }

    public function simpan()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        
        $kelas_id = $this->request->getPost('kelas_id');
        $input = $this->request->getPost('data'); // Array [siswa_id] => [sakit, izin, ...]

        if(empty($input)) {
            return redirect()->to('/catatan?kelas_id='.$kelas_id);
        }

        foreach ($input as $siswa_id => $val) {
            
            // Cek data lama (Spesifik User)
            $cek = $this->catatanModel->where([
                'user_id'  => $id_guru,
                'siswa_id' => $siswa_id, 
                'kelas_id' => $kelas_id
            ])->first();

            $dataSimpan = [
                'user_id'     => $id_guru, // WAJIB: STEMPEL KEPEMILIKAN
                'siswa_id'    => $siswa_id,
                'kelas_id'    => $kelas_id,
                'sakit'       => $val['sakit'],
                'izin'        => $val['izin'],
                'alpha'       => $val['alpha'],
                'catatan'     => $val['catatan'],
                'status_naik' => $val['status_naik']
            ];

            if ($cek) {
                $this->catatanModel->update($cek['id'], $dataSimpan);
            } else {
                $this->catatanModel->save($dataSimpan);
            }
        }

        return redirect()->to('/catatan?kelas_id=' . $kelas_id)->with('success', 'Catatan & Absensi berhasil disimpan!');
    }
}