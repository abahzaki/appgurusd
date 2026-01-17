<?php

namespace App\Controllers; // Pastikan ini App\Controllers (bukan App\Controllers\Dashboard)

class Dashboard extends BaseController
{
    public function index()
    {
        // Cek login manual
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Dashboard Guru',
            'nama_user' => session()->get('nama')
        ];

        return view('dashboard/index', $data);
    }
}