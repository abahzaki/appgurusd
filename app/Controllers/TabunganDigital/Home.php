<?php
namespace App\Controllers\TabunganDigital;
use App\Controllers\BaseController;

class Home extends BaseController {
    public function index() {
        return "<h1>Halo, ini Aplikasi Tabungan DIGITAL</h1>";
    }
}