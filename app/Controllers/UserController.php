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
        // dd(session()->get());
        // Cek apakah yang login adalah Admin? (Security sederhana)
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Manajemen User',
            // Kita ambil semua user, urutkan dari yang terbaru daftar
            'users' => $this->userModel->orderBy('id', 'DESC')->findAll()
        ];
        
        // Pastikan Bapak menyesuaikan nama file layout dashboard Bapak di sini
        return view('user/index', $data);
    }

    // --- FITUR UTAMA: AKTIVASI ---
    public function activate($id)
    {
        // 1. Hitung tanggal 6 bulan ke depan dari HARI INI
        // Jika Bapak jual per tahun, ganti '+6 months' jadi '+1 year'
        $masaAktif = date('Y-m-d', strtotime('+6 months'));

        // 2. Update status User
        $this->userModel->save([
            'id'           => $id,
            'is_active'    => 1,          // Hidupkan akun
            'expired_date' => $masaAktif  // Set tanggal kadaluarsa
        ]);

        // 3. Kembali ke tabel dengan pesan sukses
        session()->setFlashdata('success', 'User berhasil diaktifkan hingga tanggal: ' . date('d-m-Y', strtotime($masaAktif)));
        return redirect()->to('/user');
    }

    // Fitur Non-Aktifkan (Jika ada yang melanggar/batal bayar)
    public function deactivate($id)
    {
        $this->userModel->save([
            'id'        => $id,
            'is_active' => 0
        ]);

        session()->setFlashdata('success', 'User telah dinonaktifkan.');
        return redirect()->to('/user');
    }

    // Fitur Reset Password (Oleh Admin)
    public function resetPassword($id)
    {
        // Password default baru
        $passwordDefault = '123456'; 

        $this->userModel->save([
            'id'       => $id,
            'password' => password_hash($passwordDefault, PASSWORD_DEFAULT) // Hash ulang
        ]);

        session()->setFlashdata('success', 'Password user berhasil direset menjadi: <b>' . $passwordDefault . '</b>');
        return redirect()->to('/user');
    }

    // --- FITUR GANTI PASSWORD (USER MANDIRI) ---

    public function profile()
    {
        $data = [
            'title' => 'Profil Saya',
            // Ambil data user yang sedang login
            'user' => $this->userModel->find(session()->get('id')) 
        ];
        return view('user/profile', $data);
    }

    public function updatePassword()
    {
        // 1. Validasi Input
        if (!$this->validate([
            'pass_lama' => 'required',
            'pass_baru' => 'required|min_length[6]',
            'pass_conf' => 'matches[pass_baru]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Ambil data user saat ini
        $idUser = session()->get('id');
        $user = $this->userModel->find($idUser);

        // 3. Cek apakah Password Lama benar?
        $passLamaInput = $this->request->getVar('pass_lama');
        
        if (!password_verify($passLamaInput, $user['password'])) {
            return redirect()->back()->with('error', 'Password Lama salah!');
        }

        // 4. Jika benar, simpan Password Baru
        $this->userModel->save([
            'id' => $idUser,
            'password' => password_hash($this->request->getVar('pass_baru'), PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/user/profile')->with('success', 'Password berhasil diperbarui!');
    }
}