<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek Admin
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Manajemen User',
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll()
        ];
        
        return view('user/index', $data);
    }

    // --- FITUR UTAMA: AKTIVASI DENGAN CAPI ---
    public function activate($id)
    {
        // 1. Ambil Data User DULU (Penting untuk CAPI)
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan!');
        }

        // 2. Hitung Tanggal Expired (6 Bulan)
        $masaAktif = date('Y-m-d', strtotime('+6 months'));

        // 3. Update Status User di Database (Aktifkan)
        $this->userModel->save([
            'id'           => $id,
            'is_active'    => 1,           // Hidupkan akun
            'expired_date' => $masaAktif   // Set tanggal kadaluarsa
        ]);

        // 4. --- LOGIKA FACEBOOK CAPI (SERVER-SIDE TRACKING) ---
        $pesan_tambahan = "";
        
        try {
            // Kita panggil fungsi khusus pengirim sinyal ke FB
            // Ini akan mengirim event 'Purchase' senilai Rp 197.500
            $response = $this->sendPurchaseEvent($user);
            
            // Cek sekilas apakah berhasil (opsional)
            $resData = json_decode($response, true);
            if(isset($resData['events_received'])) {
                $pesan_tambahan = " & Data Purchase Terkirim ke FB!";
            }
        } catch (\Exception $e) {
            // Jika koneksi ke FB gagal, jangan biarkan aplikasi error.
            // Biarkan user tetap aktif, cuma laporannya gagal.
            $pesan_tambahan = " (User aktif, tapi gagal lapor FB)";
        }

        // 5. Kembali ke tabel dengan pesan sukses
        session()->setFlashdata('success', 'User berhasil diaktifkan hingga: ' . date('d-m-Y', strtotime($masaAktif)) . $pesan_tambahan);
        return redirect()->to('/user');
    }

    // --- FUNGSI PRIVAT KHUSUS KIRIM CAPI (JANGAN DIUBAH KECUALI TOKEN) ---
    private function sendPurchaseEvent($user)
    {
        // === KONFIGURASI META ADS ===
        $pixelId = '1524088888679277'; // ID Pixel Bapak
        
        // [PENTING] GANTI TEKS DI BAWAH INI DENGAN TOKEN ASLI DARI EVENTS MANAGER
        $token   = 'EAARNdMNrex0BQtEDQzPszwOZARsdkEwTGiJML0VQwEWL1j9jN2CLWoagI8mIi7nsokz4n7RZAsZCbfNMKXd67vCrjsU5UbivKHumZBTBefBJs3iDihp6cpf5wOe1X12zkBSiZBMNdS10nUhSkdbAY53vCkRrf5Tc3s5KZAETZCgw3MntpLKgADPRcLQZBLOcOqsCsAZDZD'; 

        // Data Hash (Wajib di-hash SHA256 sesuai aturan privasi Meta)
        $email_hash = hash('sha256', strtolower(trim($user['email'])));
        
        // Data Payload
        $data = [
            'data' => [
                [
                    'event_name' => 'Purchase',
                    'event_time' => time(), // Waktu sekarang (Unix Timestamp)
                    'action_source' => 'website',
                    'user_data'  => [
                        'em' => $email_hash, // Email user yang di-hash
                        'external_id' => hash('sha256', $user['id']) // ID unik user
                    ],
                    'custom_data' => [
                        'currency' => 'IDR',
                        'value'    => 197500, // Nilai Konversi Real
                        'content_name' => 'Paket Premium App Guru SD'
                    ]
                ]
            ]
            // Jika mau tracking source juga, bisa ditambahkan di 'opt_out' atau parameter lain,
            // tapi payload standar di atas sudah cukup untuk trigger 'Sales'.
        ];

        // Kirim Request via cURL (Standar PHP untuk kirim data ke server lain)
        $ch = curl_init("https://graph.facebook.com/v16.0/{$pixelId}/events?access_token={$token}");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    // Fitur Non-Aktifkan
    public function deactivate($id)
    {
        $this->userModel->save([
            'id'        => $id,
            'is_active' => 0
        ]);

        session()->setFlashdata('success', 'User telah dinonaktifkan.');
        return redirect()->to('/user');
    }

    // Fitur Reset Password
    public function resetPassword($id)
    {
        $passwordDefault = '123456'; 

        $this->userModel->save([
            'id'       => $id,
            'password' => password_hash($passwordDefault, PASSWORD_DEFAULT)
        ]);

        session()->setFlashdata('success', 'Password user berhasil direset menjadi: <b>' . $passwordDefault . '</b>');
        return redirect()->to('/user');
    }

    // --- FITUR GANTI PASSWORD ---

    public function profile()
    {
        $data = [
            'title' => 'Profil Saya',
            'user' => $this->userModel->find(session()->get('id')) 
        ];
        return view('user/profile', $data);
    }

    public function updatePassword()
    {
        if (!$this->validate([
            'pass_lama' => 'required',
            'pass_baru' => 'required|min_length[6]',
            'pass_conf' => 'matches[pass_baru]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idUser = session()->get('id');
        $user = $this->userModel->find($idUser);

        $passLamaInput = $this->request->getVar('pass_lama');
        
        if (!password_verify($passLamaInput, $user['password'])) {
            return redirect()->back()->with('error', 'Password Lama salah!');
        }

        $this->userModel->save([
            'id' => $idUser,
            'password' => password_hash($this->request->getVar('pass_baru'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/user/profile')->with('success', 'Password berhasil diperbarui!');
    }
}