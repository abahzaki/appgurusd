<?php
namespace App\Controllers\Kuis;
use App\Controllers\BaseController;

class Home extends BaseController {
    public function index() {
        return "<h1>Halo, ini Aplikasi Kuis</h1>";
    }
}