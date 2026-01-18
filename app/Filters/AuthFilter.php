<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // KITA GUNAKAN 'isLoggedIn' SESUAI CONTROLLER AUTH ANDA
        if (!session()->get('isLoggedIn')) {
            // Kalau tidak ada session 'isLoggedIn', tendang ke login
            return redirect()->to('login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing here
    }
}