<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Trendi App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
        .card-login { width: 100%; max-width: 400px; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
  </head>
  <body>
    
    <div class="card card-login bg-white">
        <h3 class="text-center mb-4">Login Guru</h3>
        
            <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-whatsapp text-success me-2" style="font-size: 1.2rem;"></i>
                
                <span><?= session()->getFlashdata('msg') ?></span>
                
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif;?>

        <form action="<?= base_url('login/process') ?>" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="d-flex justify-content-end mb-3">
    <?php
        $wa_admin = "6285123572422"; // Nomor WA Bapak
        $pesan_lupa = "Halo Admin Trendi App, saya lupa password akun saya. Mohon bantuan reset.";
        $link_lupa = "https://wa.me/{$wa_admin}?text=" . rawurlencode($pesan_lupa);
    ?>
    <a href="<?= $link_lupa ?>" target="_blank" class="small text-muted text-decoration-none">
        Lupa Password?
    </a>
</div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Masuk Aplikasi</button>
        </form>

        <div class="text-center">
            <p class="small">Belum punya akun? <a href="<?= base_url('register') ?>" class="text-decoration-none">Daftar di sini</a></p>
        </div>

        <div class="text-center mt-3">
            <small class="text-muted">Trendi Media Digital &copy; 2026</small>
        </div>
    </div>

  </body>
</html>