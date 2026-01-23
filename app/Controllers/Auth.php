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
                
                // 1. Cek Status Aktif (MODIFIKASI DI SINI)
                if ($data['is_active'] == 0) {
                    
                    // --- LOGIKA PESAN WHATSAPP UNTUK LOGIN GAGAL ---
                    $no_wa = "6285123572422";
                    // Pesan khusus untuk user yang gagal login
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

                      // --- LOGIKA WA UNTUK EXPIRED (OPSIONAL) ---
                      $no_wa = "6285123572422";
                      $pesan_exp = "Halo Admin, masa aktif akun saya sudah habis. Saya ingin perpanjang langganan. Email: " . $data['email'];
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
                
                // Login Sukses -> Ke Dashboard
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

    // --- FITUR BARU: REGISTRASI ---
    
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
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
            // Kalau input salah, balik ke register
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Siapkan Data
        $userData = [
            'nama_lengkap' => $this->request->getVar('nama_lengkap'),
            'email'        => $this->request->getVar('email'),
            'password'     => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'         => 'guru',
            'is_active'    => 0,
            'expired_date' => null
        ];

        // 3. Simpan ke Database (DENGAN PENGECEKAN)
        // Kita bungkus dalam IF agar tahu sukses atau gagal
        if ($userModel->save($userData)) {
            
            // --- SUKSES SIMPAN ---
            
            // Link WA
            $no_wa = "6285123572422"; // Ganti dengan No HP Anda
            $pesan = "Halo Admin, saya baru saja mendaftar di App Guru SD. Mohon aktivasi akun saya. Email: " . $userData['email'];
            $link_wa = "https://wa.me/{$no_wa}?text=" . rawurlencode($pesan);

            // Pesan Alert HTML
            $alert_html = "<strong>Registrasi Berhasil!</strong><br> 
                           Akun Anda belum aktif. Silakan 
                           <a href='{$link_wa}' target='_blank' class='fw-bold text-success' style='text-decoration:underline;'>
                           KLIK DI SINI UNTUK AKTIVASI VIA WA</a>";

            // Set Flashdata dan lempar ke Login
            session()->setFlashdata('msg', $alert_html); 
            return redirect()->to('/login');

        } else {
            // --- GAGAL SIMPAN ---
            // Kembalikan ke halaman register dan kasih tau errornya apa (misal: database error)
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }
}