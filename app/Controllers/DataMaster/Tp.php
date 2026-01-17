<?php

namespace App\Controllers\DataMaster;

use App\Controllers\BaseController;
use App\Models\TpModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\ReferensiTpModel;

class Tp extends BaseController
{
    protected $tpModel;
    protected $mapelModel;
    protected $kelasModel;
    protected $referensiTpModel;

    public function __construct()
    {
        $this->tpModel = new TpModel();
        $this->mapelModel = new MapelModel();
        $this->kelasModel = new KelasModel();
        $this->referensiTpModel = new ReferensiTpModel();
    }

    public function index()
    {
        $id_guru = session()->get('id');

        // Menggantikan fungsi getLengkap() model agar filter user_id lebih aman
        // Kita Join manual disini
        $dataTp = $this->tpModel->select('tujuan_pembelajaran.*, mapel.nama_mapel, kelas.nama_kelas')
                                ->join('mapel', 'mapel.id = tujuan_pembelajaran.mapel_id')
                                ->join('kelas', 'kelas.id = tujuan_pembelajaran.kelas_id')
                                ->where('tujuan_pembelajaran.user_id', $id_guru) // FILTER UTAMA
                                ->orderBy('mapel.no_urut', 'ASC')
                                ->orderBy('tujuan_pembelajaran.kode_tp', 'ASC')
                                ->findAll();

        $data = [
            'title' => 'Tujuan Pembelajaran (TP)',
            'tp'    => $dataTp,
            // Dropdown hanya menampilkan Mapel & Kelas milik guru ini
            'mapel' => $this->mapelModel->where('user_id', $id_guru)->findAll(),
            'kelas' => $this->kelasModel->where('user_id', $id_guru)->findAll()
        ];
        return view('datamaster/tp/index', $data);
    }

    public function store()
    {
        $id_guru = session()->get('id');

        $this->tpModel->save([
            'user_id'      => $id_guru, // WAJIB
            'mapel_id'     => $this->request->getPost('mapel_id'),
            'kelas_id'     => $this->request->getPost('kelas_id'),
            'kode_tp'      => $this->request->getPost('kode_tp'),
            'deskripsi_tp' => $this->request->getPost('deskripsi_tp'),
        ]);
        
        session()->setFlashdata('success', 'TP berhasil ditambahkan.');
        return redirect()->to('/datamaster/tp');
    }

    public function update($id)
    {
        $id_guru = session()->get('id');

        // Cek kepemilikan
        $cek = $this->tpModel->where(['id' => $id, 'user_id' => $id_guru])->first();
        if(!$cek) return redirect()->to('/datamaster/tp');

        $this->tpModel->update($id, [
            'mapel_id'     => $this->request->getPost('mapel_id'),
            'kelas_id'     => $this->request->getPost('kelas_id'),
            'kode_tp'      => $this->request->getPost('kode_tp'),
            'deskripsi_tp' => $this->request->getPost('deskripsi_tp'),
        ]);

        session()->setFlashdata('success', 'TP berhasil diupdate.');
        return redirect()->to('/datamaster/tp');
    }

    public function delete($id)
    {
        $id_guru = session()->get('id');
        
        // Hapus aman
        $this->tpModel->where('user_id', $id_guru)->delete($id);
        
        session()->setFlashdata('success', 'TP dihapus.');
        return redirect()->to('/datamaster/tp');
    }

    // --- FITUR BANK TP ---

    // 1. Tampilkan Daftar TP dari Bank 
    // CATATAN: Karena Referensi TP itu MILIK UMUM (Admin), maka TIDAK PERLU filter user_id disini.
   // --- FITUR BANK TP (Updated: MAPPING ID 1-14) ---
    public function get_referensi()
    {
        $id_guru_mapel = $this->request->getGet('mapel_id'); 
        $fase          = $this->request->getGet('fase');

        if(!$id_guru_mapel || !$fase) return "";

        // 1. Ambil NAMA MAPEL dari database Guru
        $mapel_guru = $this->mapelModel->find($id_guru_mapel);
        if(!$mapel_guru) return '<tr><td colspan="4" class="text-danger">Mapel tidak ditemukan.</td></tr>';

        // Ubah ke huruf kecil biar pencocokan akurat
        $nama = strtolower($mapel_guru['nama_mapel']); 
        $ref_id = 0;

        // 2. LOGIKA PENERJEMAH (Sesuai Daftar Bapak)
        
        // 1 = PAI & Budi Pekerti
        if (str_contains($nama, 'agama') || str_contains($nama, 'pai') || str_contains($nama, 'budi pekerti')) { 
            $ref_id = 1; 
        }
        // 2 = Pendidikan Pancasila
        elseif (str_contains($nama, 'pancasila') || str_contains($nama, 'ppkn') || str_contains($nama, 'pkn')) { 
            $ref_id = 2; 
        }
        // 3 = Bahasa Indonesia
        elseif (str_contains($nama, 'indonesia')) { 
            $ref_id = 3; 
        }
        // 4 = Matematika
        elseif (str_contains($nama, 'matematika') || str_contains($nama, 'mtk')) { 
            $ref_id = 4; 
        }
        // 5 = IPAS
        elseif (str_contains($nama, 'ipas') || str_contains($nama, 'alam') || str_contains($nama, 'sosial')) { 
            $ref_id = 5; 
        }
        // 6 = PJOK
        elseif (str_contains($nama, 'pjok') || str_contains($nama, 'jasmani') || str_contains($nama, 'olahraga')) { 
            $ref_id = 6; 
        }
        // 8 = Bahasa Inggris
        elseif (str_contains($nama, 'inggris')) { 
            $ref_id = 8; 
        }
        // 9 = Muatan Lokal (Bahasa Daerah, Jawa, Sunda, dll)
        elseif (str_contains($nama, 'lokal') || str_contains($nama, 'mulok') || str_contains($nama, 'daerah') || str_contains($nama, 'jawa') || str_contains($nama, 'sunda')) { 
            $ref_id = 9; 
        }
        // 10 = Seni Rupa
        elseif (str_contains($nama, 'rupa')) { 
            $ref_id = 10; 
        }
        // 11 = Seni Musik
        elseif (str_contains($nama, 'musik')) { 
            $ref_id = 11; 
        }
        // 12 = Seni Tari
        elseif (str_contains($nama, 'tari')) { 
            $ref_id = 12; 
        }
        // 13 = Seni Teater
        elseif (str_contains($nama, 'teater')) { 
            $ref_id = 13; 
        }
        // 14 = Koding dan Kecerdasan Artifisial
        elseif (str_contains($nama, 'koding') || str_contains($nama, 'komputer') || str_contains($nama, 'informatika')) { 
            $ref_id = 14; 
        }

        // 3. Query ke Bank Data (referensi_tp)
        $data = $this->referensiTpModel->where([
            'mapel_id' => $ref_id, 
            'fase' => $fase
        ])->findAll();

        // 4. Render Hasil
        if(empty($data)) {
            return '<tr><td colspan="4" class="text-center text-muted">
                        <i class="bi bi-search"></i><br>
                        Belum ada referensi TP untuk mapel <b>"'.$mapel_guru['nama_mapel'].'"</b> (ID Bank: '.$ref_id.') pada Fase '.$fase.'.
                    </td></tr>';
        }

        $html = '';
        foreach($data as $d) {
            $html .= '<tr>
                        <td class="text-center">
                            <input type="checkbox" name="tp_pilih[]" value="'.$d['kode_tp'].'|'.$d['deskripsi_tp'].'" class="form-check-input tp-checkbox">
                        </td>
                        <td>'.$d['kode_tp'].'</td>
                        <td>'.$d['deskripsi_tp'].'</td>
                      </tr>';
        }
        return $html;
    }
    // 2. Proses Copy dari Referensi ke Tabel Guru
    public function proses_ambil()
    {
        $id_guru  = session()->get('id'); // Ambil ID Guru
        $kelas_id = $this->request->getPost('kelas_id');
        $mapel_id = $this->request->getPost('mapel_id');
        $tp_pilih = $this->request->getPost('tp_pilih'); // Array checkbox

        if(empty($tp_pilih)) {
            session()->setFlashdata('error', 'Tidak ada TP yang dipilih.');
            return redirect()->to('/datamaster/tp');
        }

        $jumlah = 0;
        foreach($tp_pilih as $item) {
            // Pecah value (Kode|Deskripsi)
            $parts = explode('|', $item);
            $kode = $parts[0];
            $deskripsi = $parts[1];

            // Cek duplikasi SPESIFIK UNTUK GURU INI
            // "Apakah SAYA sudah punya TP ini?"
            $cek = $this->tpModel->where([
                'user_id'  => $id_guru, // Filter User
                'kelas_id' => $kelas_id,
                'mapel_id' => $mapel_id,
                'kode_tp'  => $kode
            ])->first();

            if(!$cek) {
                $this->tpModel->save([
                    'user_id'      => $id_guru, // SIMPAN SEBAGAI MILIK SAYA
                    'kelas_id'     => $kelas_id,
                    'mapel_id'     => $mapel_id,
                    'kode_tp'      => $kode,
                    'deskripsi_tp' => $deskripsi
                ]);
                $jumlah++;
            }
        }

        session()->setFlashdata('success', "Berhasil menarik $jumlah TP dari Bank Data.");
        return redirect()->to('/datamaster/tp');
    }
}