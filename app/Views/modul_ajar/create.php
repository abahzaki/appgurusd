<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Generate Modul Ajar Baru (AI Deep Learning)</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input Modul</h6>
        </div>
        <div class="card-body">
            
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= base_url('modulajar/store'); ?>" method="post" id="formGenerate">
                <?= csrf_field(); ?>
                
                <div class="row">
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label>Mata Pelajaran</label>
                            <select name="mapel" id="mapelSelect" class="form-control" required>
                                <option value="">-- Pilih Mata Pelajaran --</option>
                                <option value="1">PAI & Budi Pekerti</option>
                                <option value="2">Pendidikan Pancasila</option>
                                <option value="3">Bahasa Indonesia</option>
                                <option value="4">Matematika</option>
                                <option value="5">IPAS</option>
                                <option value="6">PJOK</option>
                                <option value="8">Bahasa Inggris</option>
                                <option value="9">Muatan Lokal</option>
                                <option value="10">Seni Rupa</option>
                                <option value="11">Seni Musik</option>
                                <option value="12">Seni Tari</option>
                                <option value="13">Seni Teater</option>
                                <option value="14">Koding & Kecerdasan Artifisial (KKA)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Fase / Kelas</label>
                            <select name="fase" id="faseSelect" class="form-control">
                                <option value="A">Fase A (Kelas 1-2)</option>
                                <option value="B" selected>Fase B (Kelas 3-4)</option>
                                <option value="C">Fase C (Kelas 5-6)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Kelas Spesifik</label>
                            <select name="kelas" class="form-control">
                                <option value="1">Kelas 1</option>
                                <option value="2">Kelas 2</option>
                                <option value="3">Kelas 3</option>
                                <option value="4" selected>Kelas 4</option>
                                <option value="5">Kelas 5</option>
                                <option value="6">Kelas 6</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="text-primary font-weight-bold">Tujuan Pembelajaran (Pilih dari Bank Data)</label>
                            <select name="tp_id" id="tpSelect" class="form-control" required>
                                <option value="">-- Pilih Mapel & Fase Terlebih Dahulu --</option>
                                <?php if(isset($list_tp) && !empty($list_tp)): ?>
                                    <?php foreach($list_tp as $tp): ?>
                                        <option value="<?= $tp['id'] ?>" 
                                                data-fase="<?= $tp['fase'] ?>" 
                                                data-mapel="<?= $tp['mapel_id'] ?>">
                                            [Fase <?= $tp['fase'] ?>] <?= substr($tp['deskripsi_tp'], 0, 100) ?>...
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Data TP Kosong</option>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">TP otomatis difilter sesuai Mapel dan Fase.</small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Materi Pokok / Topik</label>
                            <input type="text" name="materi" class="form-control" placeholder="Contoh: Bangun Datar Segi Empat" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Alokasi Waktu</label>
                                    <input type="text" name="alokasi_waktu" class="form-control" value="2 x 35 Menit">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jml Pertemuan</label>
                                    <input type="number" name="jumlah_pertemuan" class="form-control" value="1" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Model Pembelajaran</label>
                            <select name="model_belajar" class="form-control">
                                <option value="Problem Based Learning (PBL)">Problem Based Learning (PBL)</option>
                                <option value="Project Based Learning (PjBL)">Project Based Learning (PjBL)</option>
                                <option value="Inquiry Learning">Inquiry Learning</option>
                                <option value="Discovery Learning">Discovery Learning</option>
                                <option value="Kooperatif Learning">Kooperatif Learning</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>
                
                <div class="form-group">
                    <label>Profil Pelajar Pancasila (Pilih Minimal 2)</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Beriman & Bertakwa">
                        <label class="form-check-label">Beriman & Bertakwa</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Mandiri" checked>
                        <label class="form-check-label">Mandiri</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Bernalar Kritis" checked>
                        <label class="form-check-label">Bernalar Kritis</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kreatif">
                        <label class="form-check-label">Kreatif</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Gotong Royong">
                        <label class="form-check-label">Gotong Royong</label>
                    </div>
                     <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Berkebinekaan Global">
                        <label class="form-check-label">Berkebinekaan Global</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block mt-4" onclick="showLoading()">
                    <i class="fas fa-robot"></i> Generate Modul Utama (Tahap 1)
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faseSelect = document.getElementById('faseSelect');
    const mapelSelect = document.getElementById('mapelSelect');
    const tpSelect   = document.getElementById('tpSelect');
    const allOptions = Array.from(tpSelect.querySelectorAll('option')); 

    function filterTp() {
        const selectedFase = faseSelect.value;
        const selectedMapel = mapelSelect.value;
        
        tpSelect.innerHTML = '<option value="">-- Pilih Tujuan Pembelajaran --</option>';

        if (!selectedMapel) return;

        let found = false;
        allOptions.forEach(option => {
            const optionFase = option.getAttribute('data-fase'); 
            const optionMapel = option.getAttribute('data-mapel');
            
            if (optionFase === selectedFase && optionMapel === selectedMapel) {
                tpSelect.appendChild(option); 
                found = true;
            }
        });

        if(!found) {
             const noData = document.createElement('option');
             noData.text = "-- Tidak ada TP untuk Mapel & Fase ini --";
             noData.disabled = true;
             tpSelect.appendChild(noData);
        }
    }

    faseSelect.addEventListener('change', filterTp);
    mapelSelect.addEventListener('change', filterTp);
    filterTp();
});

function showLoading() {
    const form = document.getElementById('formGenerate');
    if(form.checkValidity()) {
        Swal.fire({
            title: 'AI Sedang Bekerja...',
            html: 'Menyusun Modul Ajar Deep Learning...<br>Estimasi 1-2 Menit.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => { Swal.showLoading() }
        });
    }
}
</script>
<?= $this->endSection(); ?>