<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AdExpenseModel; // <--- Panggil Model Baru

class AdsReport extends BaseController
{
    protected $userModel;
    protected $adExpenseModel;

    public function __construct()
    {
        // Load Model di awal agar bisa dipakai di semua fungsi
        $this->userModel = new UserModel();
        $this->adExpenseModel = new AdExpenseModel();
    }

    public function index()
    {
        // --- SECURITY CHECK ---
        // Tendang user biasa (guru) kembali ke dashboard mereka
        if (session()->get('role') != 'admin') {
            return redirect()->to('/dashboard');
        }
    
        // --- 1. HITUNG TOTAL BIAYA IKLAN (BULAN INI) ---
        // Cara Elegan pakai Model CI4 (Tanpa Query SQL Manual)
        $queryBiaya = $this->adExpenseModel
                           ->selectSum('amount') // Hitung total kolom amount
                           ->where("MONTH(tanggal)", date('m')) // Bulan ini
                           ->where("YEAR(tanggal)", date('Y'))  // Tahun ini
                           ->first();
        
        $totalBiaya = $queryBiaya['amount'] ?? 0;


        // --- 2. HITUNG SALES DARI IKLAN (BULAN INI) ---
        // Syarat: User Aktif (Paid) DAN Datang dari 'fb_ads'
        $totalSalesAds = $this->userModel
                              ->where('is_active', 1)
                              ->where('traffic_source', 'fb_ads')
                              ->where("MONTH(created_at)", date('m'))
                              ->where("YEAR(created_at)", date('Y'))
                              ->countAllResults();
        
        // --- 3. HITUNG OMSET & ROI ---
        $hargaProduk = 197500;
        $omsetAds    = $totalSalesAds * $hargaProduk;

        $roi = 0;
        if ($totalBiaya > 0) {
            $roi = (($omsetAds - $totalBiaya) / $totalBiaya) * 100;
        }

        // --- 4. AMBIL DATA HISTORY (Untuk Tabel) ---
        // Ambil 10 data terakhir, urutkan tanggal terbaru
        $history = $this->adExpenseModel
                        ->orderBy('tanggal', 'DESC')
                        ->findAll(10);

        $data = [
            'title'         => 'Laporan Performa Iklan',
            'active_menu'   => 'ads_report', // Untuk highlight sidebar
            'total_biaya'   => $totalBiaya,
            'total_sales'   => $totalSalesAds,
            'omset_ads'     => $omsetAds,
            'roi'           => $roi,
            'history_biaya' => $history
        ];

        return view('admin/ads_report', $data);
    }

    // Fungsi Simpan Biaya Harian
    public function store_cost()
    {
        // Validasi Input
        if (!$this->validate([
            'tanggal' => 'required',
            'amount'  => 'required|numeric'
        ])) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        // Simpan Pakai Model (Lebih Aman)
        $this->adExpenseModel->save([
            'tanggal'  => $this->request->getPost('tanggal'),
            'amount'   => $this->request->getPost('amount'),
            'platform' => 'Facebook Ads'
        ]);
        
        return redirect()->to('/adsreport')->with('msg', 'Biaya Iklan Berhasil Disimpan');
    }
}