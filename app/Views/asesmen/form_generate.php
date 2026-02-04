<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<style>
    /* Paksa warna Tab agar terlihat jelas */
    .nav-tabs .nav-link {
        background-color: #e9ecef; /* Abu-abu muda untuk tab tidak aktif */
        color: #495057;           /* Teks abu-abu gelap */
        border: 1px solid #dee2e6;
        margin-right: 2px;
    }
    
    /* Style saat mouse diarahkan (Hover) */
    .nav-tabs .nav-link:hover {
        background-color: #dbe0e5;
        color: #0d6efd;
    }

    /* Style Tab Aktif (Sedang dipilih) */
    .nav-tabs .nav-link.active {
        background-color: #ffffff !important; /* Putih bersih */
        color: #0d6efd !important;            /* Teks Biru Primary */
        border-bottom-color: transparent;     /* Hilangkan garis bawah agar menyatu */
        font-weight: bold;
        border-top: 3px solid #0d6efd;        /* Aksen biru di atas */
    }
</style>

<div class="container-fluid px-4">
    <h3 class="mt-4 text-primary"><i class="bi bi-robot"></i> Generator Asesmen & Kisi-Kisi AI</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active">Generator Soal</li>
    </ol>

    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 font-weight-bold text-primary">Konfigurasi Ujian</h5>
        </div>
        <div class="card-body">
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('asesmen/generate') ?>" method="post" id="formAsesmen">
                <?= csrf_field() ?>

                <div class="row">
                    <div class="col-lg-4 col-md-12 border-end">
                        <h6 class="text-uppercase text-secondary mb-3 fw-bold" style="font-size: 0.8rem;">1. Data Umum</h6>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mata Pelajaran</label>
                            <select name="mapel_id" id="mapel_id" class="form-select" required>
                                <option value="">-- Pilih Mapel --</option>
                                <?php if(!empty($mapel)): ?>
                                    <?php foreach($mapel as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= $m['nama_mapel'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kelas</label>
                            <select name="kelas_id" id="kelas_id" class="form-select" required>
                                <option value="" data-fase="">-- Pilih Kelas --</option>
                                <?php if(!empty($kelas)): ?>
                                    <?php foreach($kelas as $k): ?>
                                        <option value="<?= $k['id'] ?>" data-fase="<?= $k['fase'] ?? 'A' ?>">
                                            <?= $k['nama_kelas'] ?> (Fase <?= $k['fase'] ?? '-' ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div id="fase-info" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Ujian</label>
                            <select name="jenis_ujian" class="form-select" required>
                                <option value="UH">Ulangan Harian (Formatif)</option>
                                <option value="STS">Sumatif Tengah Semester</option>
                                <option value="SAS">Sumatif Akhir Semester</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Materi Pokok</label>
                            <textarea name="materi" class="form-control" rows="3" placeholder="Contoh: Pecahan Senilai, Penjumlahan..." required></textarea>
                            <div class="form-text text-muted">Materi spesifik yang akan diujikan.</div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <label class="form-label fw-bold">Jml PG</label>
                                <input type="number" name="jumlah_pg" value="10" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-bold">Jml Isian</label>
                                <input type="number" name="jumlah_isian" value="5" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-12 ps-lg-4">
                        <h6 class="text-uppercase text-secondary mb-3 fw-bold" style="font-size: 0.8rem;">2. Tujuan Pembelajaran (TP)</h6>
                        <p class="text-muted small mb-3">Pilih sumber TP yang akan diujikan. Anda bisa mengkombinasikan ketiganya.</p>
                        
                        <ul class="nav nav-tabs nav-fill" id="tpTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" id="exist-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button" role="tab">
                                    <i class="bi bi-hdd-stack"></i> TP Tersimpan
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="ref-tab" data-bs-toggle="tab" data-bs-target="#referensi" type="button" role="tab">
                                    <i class="bi bi-cloud-download"></i> Ambil Referensi
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab">
                                    <i class="bi bi-pencil-square"></i> Input Manual
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content border border-top-0 p-3 bg-white shadow-sm rounded-bottom" id="tpTabContent" style="min-height: 350px;">
                            
                            <div class="tab-pane fade show active" id="existing" role="tabpanel">
                                <div id="msg-exist" class="alert alert-warning py-2 small border-0 shadow-sm">
                                    <i class="bi bi-info-circle"></i> Silakan pilih <b>Mata Pelajaran</b> terlebih dahulu untuk memuat TP Anda.
                                </div>
                                <div id="list-tp-container" style="max-height: 400px; overflow-y: auto;">
                                    </div>
                            </div>

                            <div class="tab-pane fade" id="referensi" role="tabpanel">
                                <div id="msg-ref" class="alert alert-info py-2 small border-0 shadow-sm">
                                    <i class="bi bi-info-circle"></i> Pilih <b>Mapel</b> & <b>Kelas</b> agar sistem dapat menyesuaikan Fase (A/B/C).
                                </div>
                                <div id="list-ref-container" style="max-height: 400px; overflow-y: auto;">
                                    </div>
                            </div>

                            <div class="tab-pane fade" id="manual" role="tabpanel">
                                <div class="alert alert-secondary small border-0">
                                    <i class="bi bi-lightbulb"></i> TP yang Anda tulis disini akan otomatis disimpan ke database Anda.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kode TP (Opsional)</label>
                                    <input type="text" name="manual_kode_tp" class="form-control" placeholder="Contoh: 4.1.1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Deskripsi TP</label>
                                    <textarea name="manual_deskripsi_tp" class="form-control" rows="4" placeholder="Ketik kalimat Tujuan Pembelajaran yang lengkap..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg shadow fw-bold">
                        <i class="bi bi-magic me-2"></i> GENERATE SOAL & KISI-KISI SEKARANG
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapelSelect = document.getElementById('mapel_id');
    const kelasSelect = document.getElementById('kelas_id');
    const faseInfo    = document.getElementById('fase-info');
    
    // Fungsi Utama Load Data
    function loadTpData() {
        const mapelId = mapelSelect.value;
        let fase = 'A'; // Default Fase A
        
        // Ambil data-fase dari option kelas yang dipilih
        if (kelasSelect.value) {
            const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
            fase = selectedOption.getAttribute('data-fase');
            // Fallback jika kosong
            if (!fase) fase = 'A';
        }

        // Tampilkan Info Fase
        if(fase) {
            faseInfo.innerHTML = `<span class="badge bg-info text-dark shadow-sm">Fase ${fase}</span>`;
        } else {
            faseInfo.innerHTML = '';
        }

        if(!mapelId) return; // Jika mapel kosong, stop

        // 1. LOAD EXISTING TP (TP Saya)
        fetch('<?= base_url("asesmen/get-existing-tp") ?>/' + mapelId)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.length === 0) {
                    html = '<div class="text-muted text-center mt-5"><i class="bi bi-inbox fs-1"></i><p>Belum ada TP tersimpan untuk mapel ini.<br>Silakan pindah ke tab <b>Ambil Referensi</b> atau <b>Input Manual</b>.</p></div>';
                } else {
                    data.forEach(tp => {
                        html += `
                        <div class="card mb-2 border-0 shadow-sm">
                            <div class="card-body py-2 d-flex align-items-center">
                                <div class="form-check w-100">
                                    <input class="form-check-input" type="checkbox" name="existing_tp_ids[]" value="${tp.id}" id="tp_${tp.id}">
                                    <label class="form-check-label w-100 cursor-pointer" for="tp_${tp.id}">
                                        <span class="fw-bold text-primary">${tp.kode_tp}</span>
                                        <div class="small text-dark">${tp.deskripsi_tp}</div>
                                    </label>
                                </div>
                            </div>
                        </div>`;
                    });
                }
                document.getElementById('list-tp-container').innerHTML = html;
                document.getElementById('msg-exist').style.display = 'none';
            })
            .catch(err => console.error("Error loading TP:", err));

        // 2. LOAD REFERENSI TP (Master Data)
        if(mapelId && fase) {
            const urlRef = '<?= base_url("asesmen/get-referensi-tp") ?>/' + mapelId + '/' + fase;
            
            fetch(urlRef)
                .then(res => res.json())
                .then(data => {
                    let html = '';
                    if(data.length === 0) {
                        html = '<div class="text-muted text-center mt-5"><i class="bi bi-search fs-1"></i><p>Tidak ada Referensi TP ditemukan untuk Fase '+fase+'.<br>Silakan gunakan <b>Input Manual</b>.</p></div>';
                    } else {
                        data.forEach(ref => {
                            html += `
                            <div class="card mb-2 border-0 shadow-sm bg-white">
                                <div class="card-body py-2 d-flex align-items-center">
                                    <div class="form-check w-100">
                                        <input class="form-check-input" type="checkbox" name="ref_tp_ids[]" value="${ref.id}" id="ref_${ref.id}">
                                        <label class="form-check-label w-100 cursor-pointer" for="ref_${ref.id}">
                                            <span class="badge bg-success mb-1">Referensi</span> <span class="fw-bold">${ref.kode_tp}</span>
                                            <div class="small text-muted">${ref.deskripsi_tp}</div>
                                        </label>
                                    </div>
                                </div>
                            </div>`;
                        });
                    }
                    document.getElementById('list-ref-container').innerHTML = html;
                    document.getElementById('msg-ref').style.display = 'none';
                })
                .catch(err => console.error("Error loading Referensi:", err));
        }
    }

    // Trigger load saat Mapel atau Kelas berubah
    mapelSelect.addEventListener('change', loadTpData);
    kelasSelect.addEventListener('change', loadTpData);

    // --- TAMBAHAN BARU: SCRIPT LOADING ANIMATION (SWEETALERT2) ---
    const form = document.getElementById('formAsesmen');
    if(form) {
        form.addEventListener('submit', function(e) {
            // Cek validasi form HTML5 dulu (Required fields)
            if (!form.checkValidity()) {
                // Jika tidak valid, biarkan browser menampilkan pesan error bawaan
                return;
            }

            // Jika valid, tampilkan Loading
            Swal.fire({
                title: 'Sedang Memproses AI...',
                html: 'Mohon tunggu, sistem sedang menyusun kisi-kisi dan butir soal.<br><b>Proses ini memakan waktu sekitar 20-40 detik.</b>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    }
});
</script>

<?= $this->endSection(); ?>