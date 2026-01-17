<?php

namespace App\Controllers\DataMaster;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
    }

    public function index()
    {
        // 1. Ambil ID Guru
        $id_guru = session()->get('id');

        // 2. Tampilkan data siswa milik user ini saja
        $siswa = $this->siswaModel->select('siswa.*, kelas.nama_kelas, kelas.fase')
                                  ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
                                  ->where('siswa.user_id', $id_guru) // FILTER WAJIB
                                  ->findAll();

        $data = [
            'title' => 'Data Siswa',
            'siswa' => $siswa
        ];
        return view('datamaster/siswa/index', $data);
    }

    // --- FITUR 1: IMPORT EXCEL LENGKAP ---
    public function import()
    {
        $id_guru = session()->get('id'); // Ambil ID Guru
        $file = $this->request->getFile('file_excel');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            
            $spreadsheet = IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();
            $dataExcel = $sheet->toArray();

            $jumlahSukses = 0;

            foreach ($dataExcel as $key => $row) {
                if ($key == 0) continue; // Skip Header
                if (empty($row[2])) continue; // Skip jika Nama (Kolom C) kosong

                // LOGIKA PINTAR DETEKSI KELAS
                $namaKelasDiExcel = isset($row[22]) ? trim($row[22]) : '';
                $idKelasFix = 0; 

                if(!empty($namaKelasDiExcel)) {
                    // Cari di database: Apakah ada kelas dengan nama ini MILIK GURU INI?
                    $cekKelas = $this->kelasModel->like('nama_kelas', $namaKelasDiExcel)
                                                 ->where('user_id', $id_guru) // Pastikan cari di kelas sendiri
                                                 ->first();
                    
                    if($cekKelas) {
                        $idKelasFix = $cekKelas['id'];
                    } else {
                        $idKelasFix = 0; 
                    }
                }

                // Mapping Kolom Excel A-V ke Database
                $this->siswaModel->save([
                    'user_id'               => $id_guru, // WAJIB: STEMPEL KEPEMILIKAN
                    'kelas_id'              => $idKelasFix,
                    'nis'                   => $row[0] ?? null,
                    'nisn'                  => $row[1] ?? null,
                    'nama_lengkap'          => $row[2],
                    'jenis_kelamin'         => $row[3] ?? 'L',
                    'tempat_lahir'          => $row[4] ?? null,
                    'tanggal_lahir'         => $row[5] ?? null,
                    'agama'                 => $row[6] ?? null,
                    'pendidikan_sebelumnya' => $row[7] ?? null,
                    'alamat_peserta_didik'  => $row[8] ?? null,
                    'desa'                  => $row[9] ?? null,
                    'kecamatan'             => $row[10] ?? null,
                    'kota'                  => $row[11] ?? null,
                    'propinsi'              => $row[12] ?? null,
                    'nama_ayah'             => $row[13] ?? null,
                    'nama_ibu'              => $row[14] ?? null,
                    'pekerjaan_ayah'        => $row[15] ?? null,
                    'pekerjaan_ibu'         => $row[16] ?? null,
                    'nama_wali'             => $row[17] ?? null,
                    'pekerjaan_wali'        => $row[18] ?? null,
                    'alamat_wali'           => $row[19] ?? null,
                    'no_telephone'          => $row[20] ?? null,
                    'status_aktif'          => 1
                ]);
                $jumlahSukses++;
            }

            session()->setFlashdata('success', "Berhasil import $jumlahSukses data siswa lengkap!");
        } else {
            session()->setFlashdata('error', 'Gagal membaca file.');
        }

        return redirect()->to('/datamaster/siswa');
    }

    // --- FITUR 2: FORM EDIT ---
    public function edit($id)
    {
        $id_guru = session()->get('id');

        // Pastikan siswa yang diedit milik guru ini
        $siswa = $this->siswaModel->where(['id' => $id, 'user_id' => $id_guru])->first();
        
        // Pastikan opsi kelas yang muncul hanya kelas milik guru ini
        $dataKelas = $this->kelasModel->where('user_id', $id_guru)->findAll(); 

        if (empty($siswa)) {
            return redirect()->to('/datamaster/siswa')->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Siswa',
            's' => $siswa,
            'kelas' => $dataKelas
        ];
        return view('datamaster/siswa/edit', $data);
    }

    // --- FITUR 3: PROSES UPDATE ---
    public function update($id)
    {
        $id_guru = session()->get('id');

        // Cek kepemilikan sebelum update
        $cekPunyaSaya = $this->siswaModel->where(['id' => $id, 'user_id' => $id_guru])->first();
        if (!$cekPunyaSaya) {
             return redirect()->to('/datamaster/siswa');
        }

        $data = $this->request->getPost();
        
        // Update data
        $this->siswaModel->update($id, $data);
        
        session()->setFlashdata('success', 'Data siswa berhasil diperbarui.');
        return redirect()->to('/datamaster/siswa');
    }

    // --- FITUR 4: HAPUS ---
    public function delete($id)
    {
        $id_guru = session()->get('id');

        // Hapus hanya jika user_id cocok
        $this->siswaModel->where('user_id', $id_guru)->delete($id);

        session()->setFlashdata('success', 'Data siswa dihapus.');
        return redirect()->to('/datamaster/siswa');
    }
}