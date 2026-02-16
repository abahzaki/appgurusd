<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selesaikan Pembayaran - Trendi App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { background-color: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px 0; }
        .card-success { width: 100%; max-width: 550px; border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .bank-box { background-color: #f8f9fa; border: 1px dashed #ced4da; border-radius: 10px; padding: 15px; margin-bottom: 10px; transition: all 0.2s; }
        .bank-box:hover { background-color: #e9ecef; border-color: #adb5bd; }
        .bank-logo { font-weight: 900; font-size: 1.2rem; color: #0f172a; }
        .rek-number { font-size: 1.2rem; font-weight: bold; letter-spacing: 1px; color: #333; }
        .copy-btn { cursor: pointer; font-size: 0.8rem; }
    </style>

        <!-- TikTok Pixel Code Start -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(
var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script")
;n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};


  ttq.load('D68IJS3C77UDEMUU337G');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- TikTok Pixel Code End -->

    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1907089003240531');
    
    // EVENT PURCHASE: Merekam user yang sudah berkomitmen daftar
    fbq('track', 'Lead');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=1907089003240531&ev=PageView&noscript=1"
    /></noscript>
    </head>
<body>

    <div class="card card-success bg-white overflow-hidden">
        <div class="bg-success text-white text-center py-4">
            <div class="mb-2">
                <i class="bi bi-check-circle-fill" style="font-size: 3.5rem;"></i>
            </div>
            <h4 class="fw-bold m-0">Registrasi Berhasil!</h4>
            <p class="mb-0 opacity-75">Halo, <?= session()->getFlashdata('register_success')['nama_baru'] ?? 'Guru Hebat' ?></p>
        </div>

        <div class="card-body p-4 p-md-5">
            
            <div class="alert alert-warning border-warning d-flex align-items-start mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-warning me-3 fs-4 mt-1"></i>
                <div>
                    <strong>Akun Anda Belum Aktif (On Hold)</strong><br>
                    <span class="small text-muted">Untuk membuka kunci fitur AI & E-Raport serta mendapatkan <strong>Support Prioritas</strong>, silakan selesaikan pembayaran.</span>
                </div>
            </div>

            <div class="text-center mb-4">
                <p class="text-muted mb-1 small text-uppercase fw-bold ls-1">Total Tagihan</p>
                <h2 class="display-5 fw-bold text-primary">Rp 197.500,-</h2>
            </div>

            <p class="text-center small text-muted mb-3">Silakan transfer ke salah satu rekening resmi kami:</p>

            <div class="bank-box d-flex align-items-center justify-content-between">
                <div>
                    <div class="bank-logo text-primary">BCA</div>
                    <div class="small text-muted">A.n Mohammad Arie Iswadi</div>
                </div>
                <div class="text-end">
                    <div class="rek-number">1200515486</div>
                    <span class="badge bg-light text-dark border copy-btn" onclick="copyToClipboard('1200515486')"><i class="bi bi-clipboard"></i> Salin</span>
                </div>
            </div>

            <div class="bank-box d-flex align-items-center justify-content-between">
                <div>
                    <div class="bank-logo text-primary">MANDIRI</div>
                    <div class="small text-muted">A.n Mohammad Arie Iswadi</div>
                </div>
                <div class="text-end">
                    <div class="rek-number">1430029991690</div>
                    <span class="badge bg-light text-dark border copy-btn" onclick="copyToClipboard('1430029991690')"><i class="bi bi-clipboard"></i> Salin</span>
                </div>
            </div>

            <div class="mt-4">
                <?php
                    $no_wa = "6285123572422";
                    $nama_user = session()->getFlashdata('register_success')['nama_baru'] ?? 'User';
                    $email_user = session()->getFlashdata('register_success')['email_baru'] ?? '-';
                    
                    // Format Pesan Otomatis yang rapi
                    $pesan = "Halo Admin, saya sudah transfer Rp 197.500 ke rekening (BCA/Mandiri).\n\n";
                    $pesan .= "Mohon segera aktifkan akun saya:\n";
                    $pesan .= "Nama: " . $nama_user . "\n";
                    $pesan .= "Email: " . $email_user . "\n\n";
                    $pesan .= "Terlampir bukti transfernya. Terima kasih.";
                    
                    $link_wa = "https://wa.me/{$no_wa}?text=" . rawurlencode($pesan);
                ?>
                <a href="<?= $link_wa ?>" target="_blank" class="btn btn-success w-100 btn-lg fw-bold shadow-sm py-3">
                    <i class="bi bi-whatsapp me-2"></i> KIRIM BUKTI TRANSFER
                </a>
                <p class="text-center small text-muted mt-2">
                    <i class="bi bi-stopwatch"></i> Akun akan aktif dalam 5-10 menit setelah konfirmasi.
                </p>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none text-muted small">
                    Sudah dikonfirmasi? Masuk Aplikasi
                </a>
            </div>

        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Nomor Rekening Berhasil Disalin!');
            }, function(err) {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>

</body>
</html>