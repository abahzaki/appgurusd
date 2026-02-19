<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MapelModel;
use App\Models\TpModel;
use App\Models\SekolahModel;
use App\Models\KelasModel;
use App\Models\AtpHistoryModel;

class CetakAtp extends BaseController
{
    protected $mapelModel;
    protected $tpUserModel;
    protected $sekolahModel;
    protected $kelasModel;
    protected $atpHistoryModel;

    public function __construct()
    {
        $this->mapelModel      = new MapelModel();
        $this->tpUserModel     = new TpModel(); 
        $this->sekolahModel    = new SekolahModel();
        $this->kelasModel      = new KelasModel();
        $this->atpHistoryModel = new AtpHistoryModel();
    }

    public function index()
    {
        $userId = session()->get('id');
        $kelas  = $this->kelasModel->where('user_id', $userId)->first();
        
        $mapelReady = $this->tpUserModel
                           ->select('mapel.id, mapel.nama_mapel')
                           ->join('mapel', 'mapel.id = tujuan_pembelajaran.mapel_id')
                           ->where('tujuan_pembelajaran.user_id', $userId)
                           ->groupBy('tujuan_pembelajaran.mapel_id')
                           ->findAll();

        $data = [
            'title'   => 'Bank Alur Tujuan Pembelajaran (ATP)',
            'history' => $this->atpHistoryModel->getHistoryByUser($userId),
            'mapel_tersedia' => $mapelReady,
            'kelas'   => $kelas
        ];
        return view('cetak_atp/index', $data);
    }

    public function generate_ai()
    {
        $mapel_id = $this->request->getPost('mapel_id');
        $userId   = session()->get('id');

        $kelas = $this->kelasModel->where('user_id', $userId)->first();
        if (!$kelas) return redirect()->back()->with('error', 'Anda belum disetting sebagai Wali Kelas.');

        $myTP = $this->tpUserModel->where('user_id', $userId)
                                  ->where('mapel_id', $mapel_id)
                                  ->findAll();

        if (empty($myTP)) return redirect()->back()->with('error', 'Data TP Kosong.');

        $tpListText = "";
        foreach ($myTP as $tp) {
            $tpListText .= "- " . $tp['deskripsi_tp'] . "\n";
        }

        // --- PROMPT LEBIH GALAK & DETAIL ---
        $prompt = "Saya guru SD Kelas {$kelas['nama_kelas']} (Fase {$kelas['fase']}). 
        Berikut adalah Tujuan Pembelajaran (TP) UTAMA:\n$tpListText\n
        
        Tugas Anda adalah melakukan SCAFFOLDING (Pemecehan Materi).
        1. JANGAN mengembalikan kalimat TP asli mentah-mentah.
        2. PECAH setiap 1 TP Utama menjadi 2 sampai 5 Indikator/Tujuan Kecil (Sub-ATP) yang spesifik dan terukur.
        3. Gunakan Kata Kerja Operasional (KKO) yang bervariasi (C1-C6).
        4. Awalan wajib setiap kalimat: 'Murid dapat...'.
        5. Output HANYA JSON Array of Strings murni. 
        
        Contoh Output yang diinginkan:
        [\"Murid dapat mengidentifikasi...\", \"Murid dapat menjelaskan...\", \"Murid dapat mempraktikkan...\"]";

        $aiResponse = $this->callOpenAI($prompt);
        
        // --- PEMBERSIH JSON (PENTING!) ---
        // Seringkali AI membungkus dengan ```json ... ``` yang bikin error. Kita bersihkan.
        $cleanJson = preg_replace('/^```json\s*|\s*```$/', '', trim($aiResponse));
        $tpRephrased = json_decode($cleanJson, true);

        // Jika JSON masih gagal, log errornya tapi tetap tampilkan data asli (Fallback)
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($tpRephrased)) {
            log_message('error', 'JSON Decode Error: ' . json_last_error_msg() . ' Raw: ' . $aiResponse);
            // Fallback: Tampilkan TP asli tapi beri tanda [Asli]
            $tpRephrased = array_map(function($item) { return $item . " (Gagal memecah TP)"; }, array_column($myTP, 'deskripsi_tp'));
        }

        $dtMapel = $this->mapelModel->find($mapel_id);

        $data = [
            'title' => 'Review Hasil AI',
            'mapel' => $dtMapel,
            'kelas' => $kelas,
            'tp_ai' => $tpRephrased
        ];

        return view('cetak_atp/preview', $data);
    }

    public function save_atp()
    {
        $userId = session()->get('id');
        $inputs = $this->request->getPost('items');

        $sem1 = [];
        $sem2 = [];

        if ($inputs) {
            foreach ($inputs as $item) {
                $dataItem = ['text' => $item['text'], 'jp' => $item['jp']];
                if (isset($item['semester'])) {
                    if ($item['semester'] == '1') $sem1[] = $dataItem;
                    elseif ($item['semester'] == '2') $sem2[] = $dataItem;
                }
            }
        }

        $this->atpHistoryModel->save([
            'user_id'      => $userId,
            'mapel_id'     => $this->request->getPost('mapel_id'),
            'kelas_id'     => $this->request->getPost('kelas_id'),
            'tahun_ajar'   => date('Y') . '/' . (date('Y') + 1),
            'content_sem1' => json_encode($sem1),
            'content_sem2' => json_encode($sem2)
        ]);

        return redirect()->to('cetak_atp')->with('success', 'ATP Berhasil Disimpan!');
    }

    public function download($id)
    {
        $atp = $this->atpHistoryModel->find($id);
        if (!$atp) return redirect()->back();

        $sem1 = json_decode($atp['content_sem1'], true) ?? [];
        $sem2 = json_decode($atp['content_sem2'], true) ?? [];

        $total_jp_1 = 0; foreach ($sem1 as $item) $total_jp_1 += intval($item['jp']);
        $total_jp_2 = 0; foreach ($sem2 as $item) $total_jp_2 += intval($item['jp']);

        $userId  = session()->get('id');
        $sekolah = $this->sekolahModel->where('user_id', $userId)->first();
        $kelas   = $this->kelasModel->find($atp['kelas_id']);
        $mapel   = $this->mapelModel->find($atp['mapel_id']);

        $data = [
            'mapel' => $mapel, 'kelas' => $kelas, 'sem1' => $sem1, 'sem2' => $sem2,
            'total_jp_1' => $total_jp_1, 'total_jp_2' => $total_jp_2,
            'nama_sekolah' => $sekolah['nama_sekolah'] ?? 'Sekolah',
            'kabupaten' => $sekolah['kabupaten_kota'] ?? 'Kota',
            'kepsek_nama' => $sekolah['nama_kepsek'] ?? '......................',
            'kepsek_nip' => $sekolah['nip_kepsek'] ?? '......................',
            'guru_nama' => $kelas['wali_kelas'] ?? session()->get('nama'),
            'guru_nip' => $kelas['nip_wali'] ?? '......................',
            'tanggal' => date('d F Y')
        ];

        $filename = 'ATP_' . str_replace(' ', '_', $mapel['nama_mapel']) . '.doc';
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=" . $filename);
        return view('cetak_atp/word_view', $data);
    }
    
    public function delete($id) { $this->atpHistoryModel->delete($id); return redirect()->to('cetak_atp'); }

    private function callOpenAI($prompt)
    {
        $apiKey = getenv('OPENAI_API_KEY');
        if (!$apiKey) return "[]";

        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an expert curriculum developer.'], // Role diperkuat
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.7
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? '[]';
    }

                // ... method lainnya ...

    // 6. FITUR BARU: CETAK PROMES (PROGRAM SEMESTER)
    public function download_promes($id)
    {
        $atp = $this->atpHistoryModel->find($id);
        if (!$atp) return redirect()->back();

        // Ambil Data TP & JP
        $sem1 = json_decode($atp['content_sem1'], true) ?? [];
        $sem2 = json_decode($atp['content_sem2'], true) ?? [];

        // Hitung Total JP (Termasuk Cadangan STS/SAS nanti)
        // Kita asumsikan STS = 2 JP, SAS = 2 JP (Bisa disesuaikan)
        $total_jp_1 = 0;
        foreach ($sem1 as $item) $total_jp_1 += intval($item['jp']);
        $total_jp_1 += 4; // Tambahan untuk STS & SAS

        $total_jp_2 = 0;
        foreach ($sem2 as $item) $total_jp_2 += intval($item['jp']);
        $total_jp_2 += 4; // Tambahan untuk STS & SAS

        $userId  = session()->get('id');
        $sekolah = $this->sekolahModel->where('user_id', $userId)->first();
        $kelas   = $this->kelasModel->find($atp['kelas_id']);
        $mapel   = $this->mapelModel->find($atp['mapel_id']);

        $data = [
            'mapel'      => $mapel,
            'kelas'      => $kelas,
            'sem1'       => $sem1,
            'sem2'       => $sem2,
            'total_jp_1' => $total_jp_1,
            'total_jp_2' => $total_jp_2,
            'nama_sekolah' => $sekolah['nama_sekolah'] ?? 'Sekolah',
            'kabupaten'    => $sekolah['kabupaten_kota'] ?? 'Kota',
            'kepsek_nama'  => $sekolah['nama_kepsek'] ?? '......................',
            'kepsek_nip'   => $sekolah['nip_kepsek'] ?? '......................',
            'guru_nama'    => $kelas['wali_kelas'] ?? session()->get('nama'),
            'guru_nip'     => $kelas['nip_wali'] ?? '......................',
            'tanggal'      => date('d F Y')
        ];

        $filename = 'PROMES_' . str_replace(' ', '_', $mapel['nama_mapel']) . '.doc';
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=" . $filename);
        
        return view('cetak_atp/promes_word_view', $data);
    }

                        // Tambahkan method ini di dalam Controller CetakAtp
        public function index_promes()
    {
        $userId = session()->get('id');
        $kelas  = $this->kelasModel->where('user_id', $userId)->first();
    
        $data = [
                'title'   => 'Bank Program Semester (Promes)',
                'history' => $this->atpHistoryModel->getHistoryByUser($userId),
                'kelas'   => $kelas
                ];
        return view('cetak_atp/index_promes', $data);
    }
}