<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\MapelModel;
use App\Models\TpModel;
use App\Models\NilaiTpModel;
use App\Models\NilaiUjianModel;

class Nilai extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $tpModel;
    protected $nilaiTpModel;
    protected $nilaiUjianModel; // Tambahkan deklarasi properti

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->mapelModel = new MapelModel();
        $this->tpModel    = new TpModel();
        $this->nilaiTpModel = new NilaiTpModel();
        $this->nilaiUjianModel = new NilaiUjianModel();
    }

    // Halaman 1: Pilih Kelas & Mapel Dulu
    public function index()
    {
        $id_guru = session()->get('id');

        $data = [
            'title' => 'Input Asesmen Sumatif',
            // Hanya tampilkan Kelas & Mapel milik Guru yang Login
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll(),
            'mapel' => $this->mapelModel->where('user_id', $id_guru)->findAll()
        ];
        return view('nilai/index', $data);
    }

    // Halaman 2: Input Angka (Grid)
    public function form()
    {
        $id_guru = session()->get('id');
        $kelas_id = $this->request->getGet('kelas_id');
        $mapel_id = $this->request->getGet('mapel_id');

        if (!$kelas_id || !$mapel_id) {
            return redirect()->to('/nilai');
        }

        // 1. Ambil Siswa (Filter user_id agar tidak nyasar ke siswa guru lain)
        $siswa = $this->siswaModel->where(['kelas_id' => $kelas_id, 'user_id' => $id_guru])->findAll();

        // 2. Ambil TP milik guru ini
        $list_tp = $this->tpModel->where([
            'kelas_id' => $kelas_id, 
            'mapel_id' => $mapel_id,
            'user_id'  => $id_guru // Filter User
        ])->findAll();

        if (empty($list_tp)) {
            session()->setFlashdata('error', 'Belum ada TP untuk mapel/kelas ini. Silakan input TP dulu di Data Master.');
            return redirect()->to('/nilai');
        }

        // 3. Ambil Nilai Lama (Punya Guru Ini)
        $existing_nilai = $this->nilaiTpModel->where([
            'kelas_id' => $kelas_id, 
            'mapel_id' => $mapel_id,
            'user_id'  => $id_guru
        ])->findAll();
        
        $nilai_map = [];
        foreach ($existing_nilai as $n) {
            $nilai_map[$n['siswa_id']][$n['tp_id']] = $n['nilai'];
        }

        $data = [
            'title'     => 'Form Input Nilai',
            'siswa'     => $siswa,
            'list_tp'   => $list_tp,
            'nilai_map' => $nilai_map,
            'kelas_id'  => $kelas_id,
            'mapel_id'  => $mapel_id
        ];

        return view('nilai/form_sumatif', $data);
    }

    // Proses Simpan
    public function simpan()
    {
        $id_guru = session()->get('id');
        $kelas_id = $this->request->getPost('kelas_id');
        $mapel_id = $this->request->getPost('mapel_id');
        $input_nilai = $this->request->getPost('nilai'); // Array [siswa_id][tp_id] => nilai

        if ($input_nilai) {
            foreach ($input_nilai as $siswa_id => $tp_data) {
                foreach ($tp_data as $tp_id => $nilai_angka) {
                    
                    // Cek data lama (Spesifik User)
                    $cek = $this->nilaiTpModel->where([
                        'user_id'  => $id_guru,
                        'siswa_id' => $siswa_id,
                        'tp_id'    => $tp_id
                    ])->first();

                    if ($cek) {
                        // Update
                        $this->nilaiTpModel->update($cek['id'], ['nilai' => $nilai_angka]);
                    } else {
                        // Insert Baru
                        if ($nilai_angka !== "") { 
                            $this->nilaiTpModel->save([
                                'user_id'  => $id_guru, // WAJIB
                                'siswa_id' => $siswa_id,
                                'kelas_id' => $kelas_id,
                                'mapel_id' => $mapel_id,
                                'tp_id'    => $tp_id,
                                'nilai'    => $nilai_angka
                            ]);
                        }
                    }
                }
            }
        }

        session()->setFlashdata('success', 'Nilai berhasil disimpan!');
        return redirect()->to("/nilai/form?kelas_id=$kelas_id&mapel_id=$mapel_id");
    }
    
    // --- FITUR BARU: INPUT STS & SAS ---

    // Halaman Form Input Ujian
    public function form_ujian()
    {
        $id_guru = session()->get('id');
        $kelas_id = $this->request->getGet('kelas_id');
        $mapel_id = $this->request->getGet('mapel_id');

        if (!$kelas_id || !$mapel_id) {
            return redirect()->to('/nilai');
        }

        // 1. Ambil Siswa (Milik Guru Ini)
        $siswa = $this->siswaModel->where(['kelas_id' => $kelas_id, 'user_id' => $id_guru])->findAll();

        // 2. Ambil Nilai Ujian Lama (Milik Guru Ini)
        $existing = $this->nilaiUjianModel->where([
            'kelas_id' => $kelas_id, 
            'mapel_id' => $mapel_id,
            'user_id'  => $id_guru
        ])->findAll();
        
        $nilai_map = [];
        foreach ($existing as $n) {
            $nilai_map[$n['siswa_id']] = [
                'sts' => $n['nilai_sts'],
                'sas' => $n['nilai_sas']
            ];
        }

        $data = [
            'title'     => 'Input Nilai STS & SAS',
            'siswa'     => $siswa,
            'nilai_map' => $nilai_map,
            'kelas_id'  => $kelas_id,
            'mapel_id'  => $mapel_id
        ];

        return view('nilai/form_ujian', $data);
    }

    // Proses Simpan Ujian
    public function simpan_ujian()
    {
        $id_guru = session()->get('id');
        $kelas_id = $this->request->getPost('kelas_id');
        $mapel_id = $this->request->getPost('mapel_id');
        
        $sts = $this->request->getPost('sts'); 
        $sas = $this->request->getPost('sas'); 
        
        if (empty($sts)) {
            session()->setFlashdata('error', 'Tidak ada data siswa.');
            return redirect()->to("/nilai/form_ujian?kelas_id=$kelas_id&mapel_id=$mapel_id");
        }

        foreach ($sts as $siswa_id => $val_sts) {
            $val_sas = $sas[$siswa_id];

            // Cek data lama (Spesifik User)
            $cek = $this->nilaiUjianModel->where([
                'user_id'  => $id_guru,
                'siswa_id' => $siswa_id,
                'mapel_id' => $mapel_id
            ])->first();

            if ($cek) {
                // Update
                $this->nilaiUjianModel->update($cek['id'], [
                    'nilai_sts' => $val_sts,
                    'nilai_sas' => $val_sas
                ]);
            } else {
                // Insert Baru
                $this->nilaiUjianModel->save([
                    'user_id'   => $id_guru, // WAJIB
                    'siswa_id'  => $siswa_id,
                    'kelas_id'  => $kelas_id,
                    'mapel_id'  => $mapel_id,
                    'nilai_sts' => $val_sts,
                    'nilai_sas' => $val_sas
                ]);
            }
        }

        session()->setFlashdata('success', 'Nilai Ujian (STS/SAS) berhasil disimpan!');
        return redirect()->to("/nilai/form_ujian?kelas_id=$kelas_id&mapel_id=$mapel_id");
    }
}