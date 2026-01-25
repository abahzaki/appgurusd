<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // Jika sudah login, lempar langsung ke dashboard
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function process()
    {
        $session = session();
        $model = new UserModel();
        
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $model->where('email', $email)->first();
        
        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);
            
            if ($verify_pass) {
                
                // 1. Cek Status Aktif
                if ($data['is_active'] == 0) {
                    
                    // --- LOGIKA PESAN WHATSAPP UNTUK LOGIN GAGAL ---
                    $no_wa = "6285123572422";
                    $pesan = "Halo Admin, saya sudah mendaftar tapi belum bisa login (Akun Belum Aktif). Mohon bantuannya untuk aktivasi. Email saya: " . $data['email'];
                    
                    $pesan_encoded = rawurlencode($pesan);
                    $link_wa = "https://wa.me/{$no_wa}?text={$pesan_encoded}";

                    // Pesan HTML Alert
                    $alert_html = "<strong>Login Gagal!</strong> Akun Anda belum diaktifkan oleh Admin.<br>
                                   Silakan <a href='{$link_wa}' target='_blank' class='fw-bold text-success' style='text-decoration:underline;'>
                                   HUBUNGI ADMIN DI SINI</a> untuk aktivasi.";
                    
                    $session->setFlashdata('msg', $alert_html);
                    return redirect()->to('/login');
                }

                // 2. Cek Masa Berlaku (Expired)
                $today = date('Y-m-d');
                if ($data['expired_date'] != null && $data['expired_date'] < $today) {
                      // Opsional: Matikan status aktif di database
                      $model->save(['id' => $data['id'], 'is_active' => 0]); 

                      $no_wa = "6285123572422";
                      $pesan_exp = "Halo Admin, masa aktif akun saya sudah habis. Email: " . $data['email'];
                      $link_wa_exp = "https://wa.me/{$no_wa}?text=" . rawurlencode($pesan_exp);
                      
                      $alert_exp = "Masa aktif langganan Anda telah berakhir.<br>
                                    Silakan <a href='{$link_wa_exp}' target='_blank' class='fw-bold text-primary'>KLIK DI SINI</a> untuk perpanjang.";

                      $session->setFlashdata('msg', $alert_exp);
                      return redirect()->to('/login');
                }

                // 3. Simpan Session (Login Berhasil)
                $ses_data = [
                    'id'       => $data['id'],
                    'nama'     => $data['nama_lengkap'],
                    'email'    => $data['email'],
                    'role'     => $data['role'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);
                
                return redirect()->to('/dashboard');

            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Email tidak ditemukan');
            return redirect()->to('/login');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // --- BAGIAN REGISTRASI (INI YANG KITA MODIFIKASI) ---
    
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        // [MODIFIKASI 1] Tangkap Tracking Iklan dari URL
        // Jika linknya trendimedia.my.id/register?source=fb_ads
        if ($this->request->getGet('source')) {
            session()->set('traffic_source', $this->request->getGet('source'));
        }

        return view('auth/register');
    }

    public function process_register()
    {
        $userModel = new UserModel();

        // 1. Validasi Input
        $rules = [
            'nama_lengkap' => 'required',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'password'     => 'required|min_length[6]',
            'conf_password'=> 'matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // [MODIFIKASI 2] Ambil Tracking Source dari Session
        $traffic_source = session()->get('traffic_source') ?? 'organic';

        // 2. Siapkan Data
        $userData = [
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'email'        => $this->request->getVar('email'),
            'password'     => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'         => 'guru',
            'is_active'    => 0,
            'expired_date' => null,
            'traffic_source' => $traffic_source // Simpan sumber traffic (organic/fb_ads)
        ];

        // 3. Simpan ke Database
        if ($userModel->save($userData)) {
            
            // [MODIFIKASI 3] Redirect ke Halaman SUCCESS (Bukan Login)
            
            // Kita simpan data sementara (Flashdata) untuk ditampilkan di halaman Success
            // Supaya kita bisa menyapa: "Terima kasih, Pak Budi"
            $dataSession = [
                'email_baru' => $userData['email'],
                'nama_baru'  => $userData['nama_lengkap']
            ];
            session()->setFlashdata('register_success', $dataSession);

            // LEMPAR KE METHOD SUCCESS DI BAWAH
            return redirect()->to('auth/success'); 

        } else {
            // Gagal Simpan
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }

    // [MODIFIKASI 4] Method Baru: Halaman Success Page
    // Ini halaman yang akan muncul setelah user berhasil daftar
    public function success()
    {
        // Pengecekan Keamanan Sederhana:
        // Jika tidak ada data 'register_success', berarti orang ini nyasar (bukan habis daftar)
        // Kembalikan ke login
        if (!session()->getFlashdata('register_success')) {
            return redirect()->to('/login');
        }
        
        // Panggil View Halaman Terima Kasih
        return view('auth/success');
    }
}