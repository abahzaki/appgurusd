<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModulAjarModel;
use App\Models\SekolahModel;
use App\Models\KelasModel;
use App\Models\ReferensiTpModel;

class ModulAjar extends BaseController
{
    protected $modulModel;
    protected $sekolahModel;
    protected $kelasModel;
    protected $referensiTpModel;

    public function __construct()
    {
        $this->modulModel       = new ModulAjarModel();
        $this->sekolahModel     = new SekolahModel();
        $this->kelasModel       = new KelasModel();
        $this->referensiTpModel = new ReferensiTpModel(); 
    }

    public function index()
    {
        $userId = session()->get('id');
        $data = ['title' => 'Daftar Modul Ajar', 'modul' => $this->modulModel->getAllByUser($userId)];
        return view('modul_ajar/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Buat Modul Ajar Baru (AI Powered)',
            'list_tp' => $this->referensiTpModel->findAll() 
        ];
        return view('modul_ajar/create', $data);
    }

    // --- TAHAP 1: GENERATE MODUL UTAMA ---
    public function store()
    {
        if (!$this->validate([
            'mapel' => 'required', 
            'kelas' => 'required',
            'tp_id' => 'required', 
            'jumlah_pertemuan' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userId = session()->get('id');
        $post   = $this->request->getPost();
        
        $mapelList = [
            1 => 'PAI & Budi Pekerti', 2 => 'Pendidikan Pancasila', 3 => 'Bahasa Indonesia',
            4 => 'Matematika', 5 => 'IPAS', 6 => 'PJOK', 8 => 'Bahasa Inggris',
            9 => 'Muatan Lokal', 10 => 'Seni Rupa', 11 => 'Seni Musik',
            12 => 'Seni Tari', 13 => 'Seni Teater', 14 => 'Koding & Kecerdasan Artifisial'
        ];
        $namaMapel = $mapelList[$post['mapel']] ?? 'Mata Pelajaran Umum';

        $tpData = $this->referensiTpModel->find($post['tp_id']);
        $tujuanPembelajaranText = $tpData ? $tpData['deskripsi_tp'] : 'Tujuan pembelajaran tidak ditemukan.';

        $rawProfil = $this->request->getPost('profil_pancasila'); 
        $stringProfil = is_array($rawProfil) ? implode(', ', $rawProfil) : "Mandiri, Bernalar Kritis";

        $prompt = "Kamu adalah seorang ahli kurikulum Merdeka dan Deep Learning yang berpengalaman selama 10 tahun dan fokus pada pendidikan di tingkat sekolah dasar.
        
        INFORMASI MODUL:
        - Mapel: {$namaMapel}
        - Materi: {$post['materi']}
        - Kelas/Fase: {$post['kelas']} / {$post['fase']}
        - Alokasi Waktu: {$post['alokasi_waktu']}
        - Jml Pertemuan: {$post['jumlah_pertemuan']}
        - Model Belajar: {$post['model_belajar']}
        - Profil Pancasila: {$stringProfil}
        - TUJUAN PEMBELAJARAN (FIX): {$tujuanPembelajaranText}

        TUGAS ANDA:
        Buat konten Modul Ajar profesional lengkap dalam format JSON.
        
        INSTRUKSI KHUSUS (WAJIB DIPATUHI):
        1. [Identifikasi Murid]: JANGAN KOSONG. Buatlah narasi realistis tentang kondisi umum siswa di fase ini yang memiliki kemampuan beragam (Auditori, Visual, Kinestetik) terkait materi ini.
        2. [Kerangka Pembelajaran]: Wajib diisi detail dengan poin-poin berikut:
           - Praktik Pedagogik: Sebutkan Model (misal PBL) dan Metode (misal Diskusi, Presentasi).
           - Kemitraan Pembelajaran: Jika ada (misal Orang Tua), jika tidak ada tulis '-'.
           - Lingkungan Pembelajaran: (misal: Kelas, Halaman Sekolah, Perpustakaan).
           - Pemanfaatan Digital: WAJIB DIPISAH menjadi 'Perencanaan' (cari bahan di YT/Canva) dan 'Pelaksanaan' (tayang video/kuis).
        3. [Langkah Pembelajaran]: Pecah jadi {$post['jumlah_pertemuan']} pertemuan. Setiap langkah inti WAJIB diakhiri label psikologis dalam kurung, contoh: (Memahami: Berkesadaran).

        OUTPUT JSON SCHEMA (HANYA JSON):
        {
            'identifikasi_murid': 'Narasi panjang tentang keragaman murid...',
            'profil_pancasila_deskripsi': [{'dimensi': 'Mandiri', 'deskripsi': 'Siswa mengerjakan LKPD sendiri...'}],
            'pemahaman_bermakna': ['Pertanyaan 1...', 'Pertanyaan 2...'],
            'kerangka_pembelajaran': {
                'praktik_pedagogik': '<ul><li>Problem Based Learning (PBL)</li><li>Diskusi Kelompok & Presentasi</li></ul>',
                'kemitraan': '-',
                'lingkungan': '<ul><li>Ruang Kelas yang diatur berkelompok</li><li>Lingkungan sekolah untuk observasi</li></ul>',
                'digital': '<ul><li><b>Perencanaan:</b> Menyiapkan slide Canva dan video YouTube.</li><li><b>Pelaksanaan:</b> Menayangkan video simulasi dan kuis interaktif.</li></ul>'
            },
            'langkah_pembelajaran': [
                {'pertemuan_ke': 1, 'pendahuluan': '...', 'inti': '...', 'penutup': '...'}
            ],
            'media_pembelajaran': 'HTML List (Alat, Bahan, Sumber Belajar)...',
            'asesmen_deskripsi': {'pengetahuan': '...', 'keterampilan': '...', 'sikap': '...'}
        }";

        set_time_limit(180);
        
        try {
            if(!getenv('OPENAI_API_KEY')) throw new \Exception("API Key belum dipasang di file .env");

            $aiResult = $this->callOpenAI($prompt);
            
            if (!$aiResult) throw new \Exception("Respon AI Kosong. Periksa Koneksi atau API Key.");

            $cleanJson = str_replace(['```json', '```'], '', $aiResult);
            $content = json_decode($cleanJson, true);

            if (!$content) throw new \Exception("Gagal membaca JSON AI. Raw: " . substr($aiResult, 0, 50) . "...");

            $htmlLangkah = "";
            if(isset($content['langkah_pembelajaran']) && is_array($content['langkah_pembelajaran'])) {
                foreach($content['langkah_pembelajaran'] as $sesi) {
                    $htmlLangkah .= "<h5><b>PERTEMUAN KE-{$sesi['pertemuan_ke']}</b></h5>";
                    $htmlLangkah .= "<b>Pendahuluan:</b><p>{$sesi['pendahuluan']}</p>";
                    $htmlLangkah .= "<b>Kegiatan Inti:</b><p>{$sesi['inti']}</p>";
                    $htmlLangkah .= "<b>Penutup:</b><p>{$sesi['penutup']}</p><br>";
                }
            }

            // Sanitasi Identifikasi Murid
            $rawIdentifikasi = $content['identifikasi_murid'] 
                            ?? $content['identifikasi_siswa'] 
                            ?? $content['identifikasi_peserta_didik'] 
                            ?? '-';

            if (is_array($rawIdentifikasi)) {
                $identifikasiMurid = implode("\n", array_map(function($item) {
                    return is_array($item) ? json_encode($item) : $item;
                }, $rawIdentifikasi));
            } else {
                $identifikasiMurid = (string) $rawIdentifikasi;
            }

            // Sanitasi Media Pembelajaran
            $rawMedia = $content['media_pembelajaran'] ?? '';
            if (is_array($rawMedia)) {
                $mediaPembelajaran = implode("\n", array_map(function($item) {
                     return is_array($item) ? json_encode($item) : $item;
                }, $rawMedia));
            } else {
                $mediaPembelajaran = (string) $rawMedia;
            }

            $this->modulModel->save([
                'user_id'             => $userId,
                'sekolah'             => 'UPTD SPF SDN Contoh', 
                'mapel'               => $namaMapel,
                'fase'                => $post['fase'],
                'kelas'               => $post['kelas'],
                'materi'              => $post['materi'],
                'alokasi_waktu'       => $post['alokasi_waktu'],
                'model_belajar'       => $post['model_belajar'],
                'tp_id'               => $post['tp_id'],
                'jumlah_pertemuan'    => $post['jumlah_pertemuan'],
                'identifikasi_murid'    => $identifikasiMurid,
                'profil_pancasila'      => json_encode($content['profil_pancasila_deskripsi'] ?? []),
                'tujuan_pembelajaran'   => $tujuanPembelajaranText,
                'pemahaman_bermakna'    => is_array($content['pemahaman_bermakna']) ? implode("<br>", $content['pemahaman_bermakna']) : ($content['pemahaman_bermakna'] ?? '-'),
                'kerangka_pembelajaran' => json_encode($content['kerangka_pembelajaran'] ?? []),
                'kegiatan_inti'         => $htmlLangkah, 
                'media_pembelajaran'    => $mediaPembelajaran,
                'asesmen_deskripsi'     => json_encode($content['asesmen_deskripsi'] ?? []),
                'lampiran_lkpd'         => null,
                'lampiran_materi'       => null,
                'lampiran_asesmen'      => null
            ]);

            return redirect()->to('/modulajar')->with('success', 'Modul Utama berhasil dibuat! Silakan buat Lampiran di menu Edit.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
    
    // --- TAHAP 2: GENERATE LAMPIRAN (DIPERKETAT JUMLAH SOALNYA) ---
    public function generateLampiran($id)
    {
        $userId = session()->get('id');
        $modul = $this->modulModel->getOneByUser($id, $userId);

        if (!$modul) return redirect()->to('/modulajar')->with('error', 'Data tidak ditemukan.');

        // PROMPT AGRESIF: MEMAKSA JUMLAH & STRUKTUR
        $prompt = "Kamu adalah staff ahli kementerian penddikan nasional di bidang kurikulum medeka yang ahli membuat perangkat asesmen khususnya tingkat pendidikan Sekolah Dasar.
        
        DATA MODUL:
        - Mapel: {$modul['mapel']} Kelas {$modul['kelas']}
        - Materi: {$modul['materi']}
        - TP: {$modul['tujuan_pembelajaran']}
        - Jml Pertemuan: {$modul['jumlah_pertemuan']}
        
        TUGAS ANDA (WAJIB LENGKAP):
        Buatlah 3 Lampiran dalam format JSON berisi HTML.
        
        INSTRUKSI KHUSUS (JANGAN DIKURANGI):
        1. [lampiran_materi]: Buat ringkasan materi bacaan yang menarik (min 500 kata).
        
        2. [lampiran_lkpd]:
           - Buatlah LKPD terpisah untuk SETIAP PERTEMUAN (Total {$modul['jumlah_pertemuan']} LKPD).
           - Struktur LKPD: Judul, Petunjuk, Aktivitas Kelompok.
           - WAJIB ADA: Minimal 5 Soal Isian/Essay Latihan di setiap LKPD per pertemuan. JANGAN CUMA 2 SOAL.
        
        3. [lampiran_asesmen]:
           - SUMATIF: Buat Minimal 5 Soal Pilihan Ganda (Lengkap dengan Kunci Jawaban & Skor).
           - URAIAN: Buat Minimal 5 Soal Uraian (Lengkap dengan Pedoman Penskoran per soal).
           - RUBRIK: Buat Tabel Rubrik Penilaian Sikap (Observasi) & Keterampilan.

        OUTPUT JSON SCHEMA (HANYA JSON):
        {
            'lampiran_materi': 'HTML Article...',
            'lampiran_lkpd': 'HTML Content (LKPD 1: 5 Soal, LKPD 2: 5 Soal)...',
            'lampiran_asesmen': 'HTML Content (5 PG + Kunci, 5 Uraian + Skor, Rubrik Tabel)...'
        }";

        // Tambah waktu eksekusi karena requestnya berat (Min 5 menit)
        set_time_limit(300); 

        try {
            $aiResult = $this->callOpenAI($prompt);
            
            if (!$aiResult) throw new \Exception("Respon AI Kosong.");

            $cleanJson = str_replace(['```json', '```'], '', $aiResult);
            $content = json_decode($cleanJson, true);

            if (!$content) throw new \Exception("Gagal membaca JSON Lampiran. Coba lagi.");

            // Fungsi helper untuk memastikan data jadi string
            $safeString = function($input) {
                if (is_array($input)) {
                    return implode("\n", array_map(function($i) { return is_array($i) ? json_encode($i) : $i; }, $input));
                }
                return (string) $input;
            };

            $this->modulModel->update($id, [
                'lampiran_materi'  => $safeString($content['lampiran_materi'] ?? ''),
                'lampiran_lkpd'    => $safeString($content['lampiran_lkpd'] ?? ''),
                'lampiran_asesmen' => $safeString($content['lampiran_asesmen'] ?? ''),
                
                // Isi juga kolom legacy untuk jaga-jaga
                'asesmen_sumatif'  => $safeString($content['lampiran_asesmen'] ?? '')
            ]);

            return redirect()->to('/modulajar/edit/' . $id)->with('success', 'Lampiran LENGKAP (LKPD 5 Soal, Sumatif, Rubrik) berhasil dibuat!');

        } catch (\Exception $e) {
            return redirect()->to('/modulajar/edit/' . $id)->with('error', 'Gagal generate lampiran: ' . $e->getMessage());
        }
    }
    
    public function edit($id) { $userId = session()->get('id'); $modul = $this->modulModel->getOneByUser($id, $userId); if (!$modul) return redirect()->to('/modulajar'); return view('modul_ajar/edit', ['title'=>'Edit', 'modul'=>$modul]); }
    public function update($id) { $userId = session()->get('id'); if (!$this->modulModel->isOwner($id, $userId)) return redirect()->back(); $this->modulModel->update($id, $this->request->getPost()); return redirect()->to('/modulajar')->with('success', 'Disimpan'); }
    public function delete($id) { $userId = session()->get('id'); $modul = $this->modulModel->getOneByUser($id, $userId); if ($modul) $this->modulModel->delete($id); return redirect()->to('/modulajar')->with('success', 'Dihapus'); }
    
    public function word($id) {
        $userId = session()->get('id'); $modul = $this->modulModel->getOneByUser($id, $userId);
        if (!$modul) return redirect()->to('/modulajar');
        $sekolah = $this->sekolahModel->where('user_id', $userId)->first(); 
        $dataKelas = $this->kelasModel->where('user_id', $userId)->where('nama_kelas', $modul['kelas'])->first();
        if (!$dataKelas) $dataKelas = $this->kelasModel->where('user_id', $userId)->first();
        $cleanMateri = preg_replace('/[^A-Za-z0-9]/', '_', $modul['materi']); $filename = "Modul_Ajar_" . $cleanMateri . ".doc"; 
        header("Content-type: application/vnd.ms-word"); header("Content-Disposition: attachment;Filename=" . $filename); header("Pragma: no-cache"); header("Expires: 0");
        echo view('modul_ajar/cetak_word_html', ['modul' => $modul, 'sekolah' => $sekolah, 'dataKelas' => $dataKelas]); exit;
    }

    private function callOpenAI($prompt)
    {
        $apiKey = getenv('OPENAI_API_KEY');
        if (empty($apiKey)) throw new \Exception("API Key Kosong. Cek file .env Anda.");

        $url = 'https://api.openai.com/v1/chat/completions';
        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that outputs only JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'response_format' => ['type' => 'json_object'],
            'temperature' => 0.7
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: Bearer ' . $apiKey]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            throw new \Exception("Koneksi ke OpenAI Gagal: " . $error_msg);
        }
        
        curl_close($ch);

        $json = json_decode($response, true);

        if (is_null($json)) throw new \Exception("Respon OpenAI bukan JSON valid. Raw: " . $response);
        if (isset($json['error'])) throw new \Exception("API Error: " . $json['error']['message']);
        if (!isset($json['choices'][0]['message']['content'])) throw new \Exception("Struktur JSON tidak sesuai. Cek log.");

        return $json['choices'][0]['message']['content'];
    }
}