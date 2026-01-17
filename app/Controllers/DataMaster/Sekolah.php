<?php

namespace App\Controllers\DataMaster;

use App\Controllers\BaseController;
use App\Models\SekolahModel;

class Sekolah extends BaseController
{
    protected $sekolahModel;

    public function __construct()
    {
        $this->sekolahModel = new SekolahModel();
    }

    public function index()
    {
        // 1. Ambil ID Guru yang sedang login
        $id_guru = session()->get('id');

        // 2. Cari data sekolah milik guru ini (Bukan find(1) lagi)
        // Kita pakai first() karena asumsinya 1 guru cuma punya 1 data sekolah
        $dataSekolah = $this->sekolahModel->where('user_id', $id_guru)->first();

        $data = [
            'title' => 'Identitas Sekolah',
            's'     => $dataSekolah // Data ini bisa berisi array data, atau NULL (jika pengguna baru)
        ];

        return view('datamaster/sekolah/index', $data);
    }

    public function update()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        // 2. Ambil semua inputan dari form
        $input = $this->request->getPost();

        // 3. Wajib: Tempelkan 'user_id' agar data ini sah milik guru tersebut
        $input['user_id'] = $id_guru;

        // 4. Logika Cek: Apakah guru ini update data lama atau baru pertama kali input?
        $dataLama = $this->sekolahModel->where('user_id', $id_guru)->first();

        if ($dataLama) {
            // Jika data lama ditemukan, kita ambil ID-nya agar sistem melakukan UPDATE
            $input['id'] = $dataLama['id'];
        } 
        // Jika dataLama kosong (NULL), maka $input['id'] tidak diset.
        // CodeIgniter otomatis akan menganggap ini sebagai INSERT (Data Baru).

        // 5. Simpan (Bisa Insert atau Update tergantung ada ID atau tidak)
        $this->sekolahModel->save($input);
        
        session()->setFlashdata('success', 'Data Sekolah berhasil disimpan!');
        return redirect()->to('/datamaster/sekolah');
    }
}