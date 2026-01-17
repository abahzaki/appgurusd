<?php

namespace App\Controllers\DataMaster;

use App\Controllers\BaseController;
use App\Models\MapelModel;

class Mapel extends BaseController
{
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MapelModel();
    }

    public function index()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        $data = [
            'title' => 'Mata Pelajaran',
            // 2. Filter: Ambil mapel milik user ini, lalu urutkan
            'mapel' => $this->mapelModel->where('user_id', $id_guru)->orderBy('no_urut', 'ASC')->findAll()
        ];
        return view('datamaster/mapel/index', $data);
    }

    public function store()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        $this->mapelModel->save([
            // 2. STEMPEL: Tandai ini milik guru tersebut
            'user_id'    => $id_guru,

            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'kelompok'   => $this->request->getPost('kelompok'),
            'no_urut'    => $this->request->getPost('no_urut'),
        ]);
        
        session()->setFlashdata('success', 'Mapel berhasil ditambahkan.');
        return redirect()->to('/datamaster/mapel');
    }

    public function update($id)
    {
        $id_guru = session()->get('id');

        // Tips Aman: Cek kepemilikan sebelum update
        $cekPunyaSaya = $this->mapelModel->where(['id' => $id, 'user_id' => $id_guru])->first();

        if (!$cekPunyaSaya) {
            return redirect()->to('/datamaster/mapel')->with('error', 'Akses ditolak.');
        }

        $this->mapelModel->update($id, [
            'nama_mapel' => $this->request->getPost('nama_mapel'),
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'kelompok'   => $this->request->getPost('kelompok'),
            'no_urut'    => $this->request->getPost('no_urut'),
        ]);

        session()->setFlashdata('success', 'Mapel berhasil diperbarui.');
        return redirect()->to('/datamaster/mapel');
    }

    public function delete($id)
    {
        $id_guru = session()->get('id');

        // Hapus Aman: Pastikan user_id cocok
        $this->mapelModel->where('user_id', $id_guru)->delete($id);

        session()->setFlashdata('success', 'Mapel dihapus.');
        return redirect()->to('/datamaster/mapel');
    }
}