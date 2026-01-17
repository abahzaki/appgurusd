<?php
namespace App\Controllers\DeskripsiRaport;
use App\Controllers\BaseController;

class Home extends BaseController {
    public function index() {
        return "<h1>Halo, ini Aplikasi Deskripsi Raport</h1>";
    }
}