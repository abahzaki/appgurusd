<?php

namespace App\Controllers;

class Tutorial extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Pusat Bantuan & Tutorial',
            'active_menu' => 'tutorial' // Untuk highlight di sidebar
        ];
        return view('tutorial/index', $data);
    }

    // Method untuk membuka halaman detail tutorial tertentu
    public function read($page = 'cara-buat-modul')
    {
        // Cek apakah file view ada
        if (!is_file(APPPATH . 'Views/tutorial/pages/' . $page . '.php')) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        $data = [
            'title' => 'Baca Tutorial',
            'active_menu' => 'tutorial'
        ];

        return view('tutorial/pages/' . $page, $data);
    }
}