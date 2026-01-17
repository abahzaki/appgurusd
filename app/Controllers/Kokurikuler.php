<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\DimensiModel;
use App\Models\KokurikulerModel;
use App\Models\NilaiKokurikulerModel;

class Kokurikuler extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $dimensiModel;
    protected $kokuModel;
    protected $nilaiKokuModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->dimensiModel = new DimensiModel();
        $this->kokuModel = new KokurikulerModel();
        $this->nilaiKokuModel = new NilaiKokurikulerModel();
    }

    public function index()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        $kelas_id = $this->request->getGet('kelas_id');
        
        $data = [
            'title' => 'Input Nilai Kokurikuler (P5)',
            // 1. Hanya tampilkan kelas milik guru ini
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll(),
            'kelas_id' => $kelas_id,
            'list_projek' => [],
            'ref_dimensi' => $this->dimensiModel->findAll() // Dimensi sifatnya UMUM (Punya Pemerintah), jadi findAll() saja
        ];

        if ($kelas_id) {
            // 2. Hanya tampilkan projek milik guru ini di kelas tersebut
            $data['list_projek'] = $this->kokuModel->where([
                'kelas_id' => $kelas_id,
                'user_id'  => $id_guru
            ])->findAll();
        }

        return view('kokurikuler/index', $data);
    }

    // Buat Projek Baru
    public function tambah_projek()
    {
        $id_guru = session()->get('id');

        $this->kokuModel->save([
            'user_id'     => $id_guru, // WAJIB: STEMPEL KEPEMILIKAN
            'kelas_id'    => $this->request->getPost('kelas_id'),
            'nama_projek' => $this->request->getPost('nama_projek'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
        ]);
        return redirect()->to('/kokurikuler?kelas_id=' . $this->request->getPost('kelas_id'))->with('success', 'Projek baru berhasil dibuat');
    }

    // Halaman Input Nilai (Detail Projek)
    public function nilai($projek_id)
    {
        $id_guru = session()->get('id');

        // 1. Cek: Apakah Projek ini milik user yang login?
        $projek = $this->kokuModel->where(['id' => $projek_id, 'user_id' => $id_guru])->first();
        if(!$projek) return redirect()->to('/kokurikuler')->with('error', 'Akses ditolak.');

        // 2. Ambil siswa (Pastikan siswa milik guru ini, extra safety)
        $siswa = $this->siswaModel->where([
            'kelas_id' => $projek['kelas_id'],
            'user_id'  => $id_guru
        ])->findAll();
        
        $dimensi = $this->dimensiModel->findAll(); 
        
        // 3. Ambil Nilai Existing (Milik guru ini)
        $nilaiRaw = $this->nilaiKokuModel->where([
            'kokurikuler_id' => $projek_id,
            'user_id'        => $id_guru
        ])->findAll();

        $nilaiMap = [];
        foreach($nilaiRaw as $n) {
            $nilaiMap[$n['siswa_id']][$n['dimensi_id']] = $n['skor'];
        }

        $data = [
            'title'     => 'Input Skor: ' . $projek['nama_projek'],
            'projek'    => $projek,
            'siswa'     => $siswa,
            'dimensi'   => $dimensi,
            'nilai_map' => $nilaiMap
        ];

        return view('kokurikuler/nilai', $data);
    }

    // Simpan Nilai
    public function simpan_nilai()
    {
        $id_guru = session()->get('id');
        $projek_id = $this->request->getPost('kokurikuler_id');
        $input = $this->request->getPost('skor'); // Array [siswa_id][dimensi_id] = skor

        // Verifikasi kepemilikan projek sebelum simpan
        $cekProjek = $this->kokuModel->where(['id' => $projek_id, 'user_id' => $id_guru])->first();
        if(!$cekProjek) return redirect()->to('/kokurikuler');

        if ($input) {
            foreach ($input as $siswa_id => $dimensi_data) {
                foreach ($dimensi_data as $dimensi_id => $skor) {
                    
                    // Cek nilai lama (Spesifik User)
                    $cek = $this->nilaiKokuModel->where([
                        'user_id'        => $id_guru,
                        'siswa_id'       => $siswa_id,
                        'kokurikuler_id' => $projek_id,
                        'dimensi_id'     => $dimensi_id
                    ])->first();

                    if ($skor > 0) { 
                        $dataSave = [
                            'user_id'        => $id_guru, // WAJIB
                            'siswa_id'       => $siswa_id,
                            'kokurikuler_id' => $projek_id,
                            'dimensi_id'     => $dimensi_id,
                            'skor'           => $skor
                        ];

                        if ($cek) {
                            $this->nilaiKokuModel->update($cek['id'], $dataSave);
                        } else {
                            $this->nilaiKokuModel->save($dataSave);
                        }
                    }
                }
            }
        }

        return redirect()->to('/kokurikuler/nilai/' . $projek_id)->with('success', 'Nilai Kokurikuler berhasil disimpan!');
    }
    
    public function hapus_projek($id) {
        $id_guru = session()->get('id');

        // Hapus projek HANYA jika milik user ini
        $p = $this->kokuModel->where(['id' => $id, 'user_id' => $id_guru])->first();

        if($p) {
            $this->kokuModel->delete($id);
            // Hapus juga nilai-nilai terkait yang punya user_id sama
            $this->nilaiKokuModel->where(['kokurikuler_id' => $id, 'user_id' => $id_guru])->delete();
            
            return redirect()->to('/kokurikuler?kelas_id='.$p['kelas_id']);
        }
        return redirect()->to('/kokurikuler');
    }
}