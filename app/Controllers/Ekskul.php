<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\NilaiEkskulModel;

class Ekskul extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $ekskulModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->ekskulModel = new NilaiEkskulModel();
    }

    public function index()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Input Ekstrakurikuler',
            // 1. Filter: Hanya ambil kelas milik guru ini
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll(),
            'kelas_id' => $kelas_id,
            'siswa' => [],
            'data_ekskul' => [] 
        ];

        if ($kelas_id) {
            // 2. Filter: Ambil siswa di kelas ini (Pastikan siswa milik guru ini)
            $data['siswa'] = $this->siswaModel->where([
                'kelas_id' => $kelas_id, 
                'user_id'  => $id_guru
            ])->findAll();
            
            // 3. Filter: Ambil data ekskul yang sudah ada (Milik guru ini)
            $ekskulRaw = $this->ekskulModel->where([
                'kelas_id' => $kelas_id,
                'user_id'  => $id_guru
            ])->findAll();
            
            // Grouping data
            foreach($ekskulRaw as $row) {
                $data['data_ekskul'][$row['siswa_id']][] = $row;
            }
        }

        return view('ekskul/index', $data);
    }

    public function simpan()
    {
        $id_guru = session()->get('id');

        // Fitur ini menyimpan 1 data ekskul untuk 1 siswa
        $this->ekskulModel->save([
            'user_id'     => $id_guru, // WAJIB: STEMPEL KEPEMILIKAN
            'siswa_id'    => $this->request->getPost('siswa_id'),
            'kelas_id'    => $this->request->getPost('kelas_id'),
            'nama_ekskul' => $this->request->getPost('nama_ekskul'),
            'predikat'    => $this->request->getPost('predikat'),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/ekskul?kelas_id=' . $this->request->getPost('kelas_id'))
                         ->with('success', 'Data ekskul berhasil ditambahkan');
    }

    public function hapus($id)
    {
        $id_guru = session()->get('id');

        // Cari data dulu (Cek kepemilikan)
        $dt = $this->ekskulModel->where(['id' => $id, 'user_id' => $id_guru])->first();

        if($dt) {
            $this->ekskulModel->delete($id);
            
            return redirect()->to('/ekskul?kelas_id=' . $dt['kelas_id'])
                             ->with('success', 'Data dihapus');
        }
        
        // Jika data tidak ditemukan atau bukan milik guru tersebut
        return redirect()->to('/ekskul');
    }
}