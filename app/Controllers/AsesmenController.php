<?php

namespace App\Controllers;

use App\Models\AsesmenModel;
use App\Models\TpModel;          
use App\Models\ReferensiTpModel; 
use App\Models\KelasModel;       
use App\Models\MapelModel;
use App\Models\SekolahModel;
use App\Models\SettingRaporModel;
use CodeIgniter\API\ResponseTrait;

class AsesmenController extends BaseController
{
    use ResponseTrait;

    // =========================================================================
    // 1. HALAMAN INDEX (DAFTAR RIWAYAT)
    // =========================================================================
    public function index()
    {
        $asesmenModel = new AsesmenModel();
        $userId = session()->get('id') ?? 1;

        // Ambil Data (Join Table)
        $data['riwayat'] = $asesmenModel->select('asesmen.*, mapel.nama_mapel, kelas.nama_kelas, kelas.fase')
                                        ->join('mapel', 'mapel.id = asesmen.mapel_id')
                                        ->join('kelas', 'kelas.id = asesmen.kelas_id')
                                        ->where('asesmen.user_id', $userId)
                                        ->orderBy('asesmen.id', 'DESC')
                                        ->findAll();

        return view('asesmen/index_view', $data);
    }

    // =========================================================================
    // 2. HALAMAN FORM (BUAT BARU)
    // =========================================================================
    public function create()
    {
        $mapelModel = new MapelModel();
        $kelasModel = new KelasModel();
        $userId     = session()->get('id') ?? 1;

        // Ambil Mapel Unik
        $rawMapel = $mapelModel->where('user_id', $userId)->orderBy('nama_mapel', 'ASC')->findAll();
        $uniqueMapel = [];
        $seenNames   = [];
        
        foreach ($rawMapel as $m) {
            $nama = is_object($m) ? $m->nama_mapel : $m['nama_mapel'];
            if (!in_array($nama, $seenNames)) {
                $uniqueMapel[] = $m;
                $seenNames[]   = $nama;
            }
        }
        $data['mapel'] = $uniqueMapel;
        $data['kelas'] = $kelasModel->where('user_id', $userId)->orderBy('nama_kelas', 'ASC')->findAll();
        
        return view('asesmen/form_generate', $data);
    }

    // =========================================================================
    // 3. LIHAT HASIL (EDITOR VIEW)
    // =========================================================================
    public function viewResult($id)
    {
        $asesmenModel = new AsesmenModel();
        $sekolahModel = new SekolahModel();
        $settingModel = new SettingRaporModel();
        $kelasModel   = new KelasModel();
        $userId       = session()->get('id') ?? 1;

        $dataAsesmen = $asesmenModel->where('id', $id)->where('user_id', $userId)->first();

        if(!$dataAsesmen) {
            return redirect()->to('asesmen')->with('error', 'Data tidak ditemukan.');
        }

        // Helper Akses Data (Object/Array)
        $get = function($d, $k) { return is_object($d) ? ($d->$k ?? null) : ($d[$k] ?? null); };

        $jsonContent = $get($dataAsesmen, 'konten_json');
        $judulResult = $get($dataAsesmen, 'judul');
        $kelasId     = $get($dataAsesmen, 'kelas_id');

        // Ambil Data Header untuk Preview
        $sekolah = $sekolahModel->where('user_id', $userId)->first();
        $setting = $settingModel->where('user_id', $userId)->first();
        $kelas   = $kelasModel->find($kelasId);

        $headerData = [
            'sekolah'   => $get($sekolah, 'nama_sekolah') ?? 'Nama Sekolah',
            'kecamatan' => $get($sekolah, 'kecamatan') ?? 'Kecamatan',
            'tahun'     => $get($setting, 'tahun_ajaran') ?? date('Y'),
            'semester'  => $get($setting, 'semester') ?? '1',
            'guru'      => $get($kelas, 'wali_kelas') ?? 'Nama Guru',
            'nip'       => $get($kelas, 'nip_wali') ?? '-',
        ];

        return view('asesmen/result_view', [
            'data'       => json_decode($jsonContent, true),
            'id_asesmen' => $id,
            'judul'      => $judulResult,
            'header'     => $headerData
        ]);
    }

    // =========================================================================
    // 4. UPDATE HASIL EDITAN
    // =========================================================================
    public function updateResult($id)
    {
        $asesmenModel = new AsesmenModel();
        $userId = session()->get('id') ?? 1;
        $input  = $this->request->getPost();

        // Rekonstruksi Struktur JSON dari Form Input
        $newData = [
            'kisi_kisi' => [],
            'soal'      => []
        ];

        // Proses Kisi-Kisi
        if(isset($input['kisi_materi'])) {
            foreach($input['kisi_materi'] as $i => $val) {
                $newData['kisi_kisi'][] = [
                    'materi'         => $input['kisi_materi'][$i],
                    'cp'             => $input['kisi_cp'][$i] ?? '-', // Support Kolom CP Baru
                    'tp_kode'        => $input['kisi_tp_kode'][$i],
                    'tp_deskripsi'   => $input['kisi_tp_desc'][$i],
                    'indikator_soal' => $input['kisi_indikator'][$i],
                    'bentuk_soal'    => $input['kisi_bentuk'][$i],
                    'nomor_soal'     => $input['kisi_nomor'][$i],
                ];
            }
        }

        // Proses Soal
        if(isset($input['soal_tanya'])) {
            foreach($input['soal_tanya'] as $i => $val) {
                $opsi = null;
                if($input['soal_bentuk'][$i] == 'PG') {
                    $opsi = [
                        'A' => $input['soal_opsi_a'][$i] ?? '',
                        'B' => $input['soal_opsi_b'][$i] ?? '',
                        'C' => $input['soal_opsi_c'][$i] ?? '',
                        'D' => $input['soal_opsi_d'][$i] ?? ''
                    ];
                }

                $newData['soal'][] = [
                    'nomor'      => $input['soal_nomor'][$i],
                    'bentuk'     => $input['soal_bentuk'][$i],
                    'pertanyaan' => $input['soal_tanya'][$i],
                    'opsi'       => $opsi,
                    'kunci'      => $input['soal_kunci'][$i]
                ];
            }
        }

        // Simpan Perubahan ke Database
        $asesmenModel->update($id, ['konten_json' => json_encode($newData)]);
        
        return redirect()->to('asesmen/lihat/' . $id)->with('success', 'Perubahan berhasil disimpan!');
    }

    // =========================================================================
    // 5. EXPORT WORD (METODE HTML HEADER - SESUAI REQUEST)
    // =========================================================================
    public function exportWord($id)
    {
        $asesmenModel = new AsesmenModel();
        $sekolahModel = new SekolahModel();
        $settingModel = new SettingRaporModel();
        $kelasModel   = new KelasModel();
        $mapelModel   = new MapelModel();
        $userId       = session()->get('id') ?? 1;

        // 1. Ambil Data Asesmen
        $dataAsesmen = $asesmenModel->where('id', $id)->where('user_id', $userId)->first();
        if(!$dataAsesmen) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        // Helper Akses Data
        $get = function($d, $k) { return is_object($d) ? ($d->$k ?? null) : ($d[$k] ?? null); };

        $jsonContent = $get($dataAsesmen, 'konten_json');
        $mapelId     = $get($dataAsesmen, 'mapel_id');
        $kelasId     = $get($dataAsesmen, 'kelas_id');
        $jenisUjian  = $get($dataAsesmen, 'jenis_ujian');
        $judul       = $get($dataAsesmen, 'judul');

        $content = json_decode($jsonContent, true);
        
        // 2. Ambil Data Pendukung
        $sekolah = $sekolahModel->where('user_id', $userId)->first();
        $setting = $settingModel->where('user_id', $userId)->first();
        $kelas   = $kelasModel->find($kelasId);
        $mapel   = $mapelModel->find($mapelId);

        // Siapkan Data View
        $data = [
            'content'   => $content,
            'judul'     => $judul,
            'jenis'     => ($jenisUjian == 'UH') ? 'SUMATIF HARIAN' : (($jenisUjian == 'STS') ? 'SUMATIF TENGAH SEMESTER' : 'SUMATIF AKHIR SEMESTER'),
            'sekolah'   => $get($sekolah, 'nama_sekolah') ?? 'Nama Sekolah Belum Diset',
            'kecamatan' => $get($sekolah, 'kecamatan') ?? 'Kecamatan',
            'tahun'     => $get($setting, 'tahun_ajaran') ?? date('Y'),
            'semester'  => $get($setting, 'semester') ?? '1',
            'mapel'     => $get($mapel, 'nama_mapel') ?? 'Mata Pelajaran',
            'kelas'     => $get($kelas, 'nama_kelas') ?? 'Kelas',
            'guru'      => $get($kelas, 'wali_kelas') ?? 'Nama Guru',
            'nip'       => $get($kelas, 'nip_wali') ?? '-'
        ];

        // 3. FORCE DOWNLOAD WORD
        $namaFile = "Soal_" . preg_replace('/[^A-Za-z0-9]/', '_', $data['mapel']) . ".doc";
        
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=" . $namaFile);
        header("Pragma: no-cache");
        header("Expires: 0");
        
        echo view('asesmen/cetak_word', $data);
        exit;
    }

    // =========================================================================
    // 6. LOGIKA GENERATE AI (DENGAN CP & KISI-KISI DETAIL)
    // =========================================================================
    public function generate()
    {
        $input = $this->request->getPost();
        if (!$input) return redirect()->back()->with('error', 'Data input tidak ditemukan.');

        $userId     = session()->get('id') ?? 1;
        $tpModel    = new TpModel();
        $refTpModel = new ReferensiTpModel();
        $finalTpIds = []; 

        // Handle TP Existing
        if (isset($input['existing_tp_ids'])) {
            $finalTpIds = array_merge($finalTpIds, $input['existing_tp_ids']);
        }

        // Handle TP Referensi
        if (!empty($input['ref_tp_ids'])) {
            foreach ($input['ref_tp_ids'] as $refId) {
                $refData = $refTpModel->find($refId);
                if ($refData) {
                    $k = is_object($refData)?$refData->kode_tp:$refData['kode_tp'];
                    $d = is_object($refData)?$refData->deskripsi_tp:$refData['deskripsi_tp'];
                    $existing = $tpModel->where('user_id', $userId)->where('kode_tp', $k)->where('mapel_id', $input['mapel_id'])->first();
                    
                    if ($existing) { 
                        $finalTpIds[] = is_object($existing)?$existing->id:$existing['id']; 
                    } else {
                        $tpModel->save(['user_id'=>$userId, 'mapel_id'=>$input['mapel_id'], 'kelas_id'=>$input['kelas_id'], 'kode_tp'=>$k, 'deskripsi_tp'=>$d]);
                        $finalTpIds[] = $tpModel->getInsertID();
                    }
                }
            }
        }

        // Handle Manual
        if (!empty($input['manual_deskripsi_tp'])) {
            $tpModel->save(['user_id'=>$userId, 'mapel_id'=>$input['mapel_id'], 'kelas_id'=>$input['kelas_id'], 'kode_tp'=>$input['manual_kode_tp']??'MANUAL', 'deskripsi_tp'=>$input['manual_deskripsi_tp']]);
            $finalTpIds[] = $tpModel->getInsertID();
        }

        $finalTpIds = array_unique($finalTpIds);
        if (empty($finalTpIds)) return redirect()->back()->with('error', 'Pilih minimal satu TP.');

        // Persiapan Data Prompt
        $selectedTp = $tpModel->whereIn('id', $finalTpIds)->findAll();
        $listTP = "";
        foreach($selectedTp as $tp) { 
            $k = is_object($tp) ? $tp->kode_tp : $tp['kode_tp'];
            $d = is_object($tp) ? $tp->deskripsi_tp : $tp['deskripsi_tp'];
            $listTP .= "- Kode: {$k}, Deskripsi: {$d}\n"; 
        }

        $mapelInfo = (new MapelModel())->find($input['mapel_id']);
        $kelasInfo = (new KelasModel())->find($input['kelas_id']);
        
        $namaMapel = is_object($mapelInfo) ? $mapelInfo->nama_mapel : ($mapelInfo['nama_mapel'] ?? 'Mata Pelajaran');
        $namaKelas = is_object($kelasInfo) ? $kelasInfo->nama_kelas : ($kelasInfo['nama_kelas'] ?? 'Kelas');

        // PROMPT AI (UPDATE: Minta CP & Kisi-kisi Detail)
        $prompt = "
        Bertindaklah sebagai Guru Profesional Kurikulum Merdeka.
        Tugas: Buat Paket Soal {$input['jenis_ujian']} Mapel '$namaMapel' untuk $namaKelas.
        
        Materi Pokok: {$input['materi']}
        Daftar TP yang diujikan:
        $listTP
        
        Instruksi Struktur:
        1. [PENTING] Untuk setiap baris kisi-kisi, tentukan 'Capaian Pembelajaran (CP)' yang sesuai dengan Fase kelas tersebut. Jika ragu, buat kalimat CP yang relevan.
        2. Buat {$input['jumlah_pg']} Soal Pilihan Ganda (PG).
        3. Buat {$input['jumlah_isian']} Soal Isian.
        4. [PENTING] Bagian Kisi-Kisi HARUS MENDETAIL. Jangan menggabung semua soal dalam 1 baris. 
           Setiap indikator soal yang berbeda harus punya baris sendiri.
           Pastikan total nomor soal di kisi-kisi mencakup SEMUA nomor.

        Format Output (JSON Only):
        {
            \"kisi_kisi\": [
                {
                    \"cp\": \"...isi CP disini...\",
                    \"tp_kode\": \"...\",
                    \"tp_deskripsi\": \"...\",
                    \"materi\": \"...\",
                    \"indikator_soal\": \"...\",
                    \"bentuk_soal\": \"PG/Isian\",
                    \"nomor_soal\": \"1\" 
                }
                // Lanjutkan untuk soal berikutnya...
            ],
            \"soal\": [
                {
                    \"nomor\": 1,
                    \"bentuk\": \"PG\",
                    \"pertanyaan\": \"...\",
                    \"opsi\": {\"A\":\"...\", \"B\":\"...\", \"C\":\"...\", \"D\":\"...\"},
                    \"kunci\": \"A\"
                },
                {
                    \"nomor\": 2,
                    \"bentuk\": \"Isian\",
                    \"pertanyaan\": \"...\",
                    \"opsi\": null,
                    \"kunci\": \"Jawaban\"
                }
            ]
        }
        ";

        $generatedData = $this->callOpenAI($prompt);

        if (!$generatedData) return redirect()->back()->with('error', 'Gagal generate. Coba lagi atau cek API Key.');

        // Simpan Hasil
        $asesmenModel = new AsesmenModel();
        $saveData = [
            'user_id'     => $userId,
            'mapel_id'    => $input['mapel_id'],
            'kelas_id'    => $input['kelas_id'],
            'jenis_ujian' => $input['jenis_ujian'],
            'judul'       => "Soal {$input['jenis_ujian']} $namaMapel",
            'konten_json' => json_encode($generatedData),
            'tanggal_buat'=> date('Y-m-d H:i:s')
        ];
        
        $asesmenModel->save($saveData);
        $insertId = $asesmenModel->getInsertID();

        return redirect()->to('asesmen/lihat/' . $insertId)->with('success', 'Soal berhasil digenerate!');
    }

    // =========================================================================
    // 7. HELPER & UTILS
    // =========================================================================
    
    public function getExistingTp($mapelId) {
        $tpModel = new TpModel();
        $userId  = session()->get('id') ?? 1;
        $data = $tpModel->where('mapel_id', $mapelId)->where('user_id', $userId)->findAll();
        return $this->response->setJSON($data);
    }

    public function getReferensiTp($userMapelId, $fase = 'A') {
        $refModel   = new ReferensiTpModel();
        $mapelModel = new MapelModel();
        
        $userMapel = $mapelModel->find($userMapelId);
        if (!$userMapel) return $this->response->setJSON([]);
        
        $namaMapel = is_object($userMapel) ? $userMapel->nama_mapel : $userMapel['nama_mapel'];

        $data = $refModel->select('referensi_tp.*')
                         ->join('mapel', 'mapel.id = referensi_tp.mapel_id')
                         ->like('mapel.nama_mapel', $namaMapel, 'both')
                         ->where('referensi_tp.fase', $fase)
                         ->findAll();
        
        return $this->response->setJSON($data);
    }

    public function delete($id) {
        $asesmenModel = new AsesmenModel();
        $userId = session()->get('id') ?? 1;
        $cek = $asesmenModel->where('id', $id)->where('user_id', $userId)->first();
        if($cek) {
            $asesmenModel->delete($id);
            return redirect()->to('asesmen')->with('success', 'Data berhasil dihapus.');
        }
        return redirect()->to('asesmen')->with('error', 'Gagal menghapus data.');
    }

    private function callOpenAI($prompt) {
        $apiKey = getenv('OPENAI_API_KEY');
        if (!$apiKey) return false;
        
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => ['Authorization'=>'Bearer '.$apiKey, 'Content-Type'=>'application/json'],
                'json' => ['model'=>'gpt-4o-mini', 'messages'=>[['role'=>'system','content'=>'JSON only generator.'],['role'=>'user','content'=>$prompt]], 'temperature'=>0.7],
                'timeout' => 90
            ]);
            $content = json_decode($response->getBody())->choices[0]->message->content;
            return json_decode(str_replace(['```json', '```'], '', trim($content)), true);
        } catch (\Exception $e) { return false; }
    }
}