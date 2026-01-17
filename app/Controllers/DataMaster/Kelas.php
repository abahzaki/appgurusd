<?php

namespace App\Controllers\DataMaster;

use App\Controllers\BaseController;
use App\Models\KelasModel;

class Kelas extends BaseController
{
    protected $kelasModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        $data = [
            'title' => 'Data Kelas & Wali Kelas',
            // 2. Filter: Hanya ambil kelas milik guru yang login
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll()
        ];
        return view('datamaster/kelas/index', $data);
    }

    public function store()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        $this->kelasModel->save([
            // 2. STEMPEL: Ini data milik siapa?
            'user_id'    => $id_guru, 
            
            // Data Inputan Form
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'fase'       => $this->request->getPost('fase'),
            'wali_kelas' => $this->request->getPost('wali_kelas'),
            'nip_wali'   => $this->request->getPost('nip_wali'),
        ]);
        
        session()->setFlashdata('success', 'Data Kelas berhasil ditambahkan.');
        return redirect()->to('/datamaster/kelas');
    }

    public function update($id)
    {
        // Ambil ID Guru untuk keamanan
        $id_guru = session()->get('id');

        // Tips Aman: Pastikan yang diupdate adalah milik user ini
        $cekPunyaSaya = $this->kelasModel->where(['id' => $id, 'user_id' => $id_guru])->first();

        if (!$cekPunyaSaya) {
            return redirect()->to('/datamaster/kelas')->with('error', 'Data tidak ditemukan atau bukan milik Anda.');
        }

        // Lakukan Update
        $this->kelasModel->update($id, [
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'fase'       => $this->request->getPost('fase'),
            'wali_kelas' => $this->request->getPost('wali_kelas'),
            'nip_wali'   => $this->request->getPost('nip_wali'),
        ]);

        session()->setFlashdata('success', 'Data Kelas berhasil diperbarui.');
        return redirect()->to('/datamaster/kelas');
    }

    public function delete($id)
    {
        $id_guru = session()->get('id');

        // PERBAIKAN DI SINI: Menambahkan kurung tutup dan titik koma
        $this->kelasModel->where('user_id', $id_guru)->delete($id);

        session()->setFlashdata('success', 'Data Kelas dihapus.');
        return redirect()->to('/datamaster/kelas');
    }
}