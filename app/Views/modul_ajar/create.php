<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Generate Perencanaan Pembelajaran Mendalam (PPM)</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Input Modul (Standar Permendikdasmen 13/2025)</h6>
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
                            <label class="text-primary font-weight-bold">Referensi Tujuan Pembelajaran (Bank Data)</label>
                            <select name="tp_id_ref" id="tpSelect" class="form-control">
                                <option value="">-- Pilih Referensi TP (Opsional) --</option>
                                <?php if(isset($list_tp) && !empty($list_tp)): ?>
                                    <?php foreach($list_tp as $tp): ?>
                                        <option value="<?= $tp['id'] ?>" 
                                                data-fase="<?= $tp['fase'] ?>" 
                                                data-mapel="<?= $tp['mapel_id'] ?>"
                                                data-deskripsi="<?= esc($tp['deskripsi_tp']) ?>"> [Fase <?= $tp['fase'] ?>] <?= substr($tp['deskripsi_tp'], 0, 80) ?>...
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted">Pilih referensi untuk mengisi otomatis, atau ketik manual di bawah.</small>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tujuan Pembelajaran (Bisa Diedit Manual)</label>
                            <textarea name="tujuan_pembelajaran_manual" id="tpManual" class="form-control" rows="4" placeholder="Ketik Tujuan Pembelajaran di sini atau pilih dari referensi di atas..." required></textarea>
                            <small class="text-danger font-italic">*Teks inilah yang akan digunakan oleh AI.</small>
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
                    <label class="font-weight-bold text-dark">8 Dimensi Profil Lulusan (Pilih Minimal 2)</label><br>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Keimanan dan Ketakwaan terhadap Tuhan YME">
                                <label class="form-check-label">Keimanan dan Ketakwaan thd Tuhan YME</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kewargaan">
                                <label class="form-check-label">Kewargaan <small class="text-muted">(Ex: Kebhinekaan Global)</small></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Penalaran Kritis" checked>
                                <label class="form-check-label">Penalaran Kritis</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kreativitas">
                                <label class="form-check-label">Kreativitas</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kolaborasi" checked>
                                <label class="form-check-label">Kolaborasi <small class="text-muted">(Ex: Gotong Royong)</small></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kemandirian">
                                <label class="form-check-label">Kemandirian</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Kesehatan">
                                <label class="form-check-label">Kesehatan <span class="badge badge-info">Baru</span></label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="profil_pancasila[]" value="Komunikasi">
                                <label class="form-check-label">Komunikasi <span class="badge badge-info">Baru</span></label>
                            </div>
                        </div>
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
    const tpManual   = document.getElementById('tpManual'); // Textarea
    const allOptions = Array.from(tpSelect.querySelectorAll('option')); 

    function filterTp() {
        const selectedFase = faseSelect.value;
        const selectedMapel = mapelSelect.value;
        
        tpSelect.innerHTML = '<option value="">-- Pilih Referensi TP (Opsional) --</option>';

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
             noData.text = "-- Tidak ada Referensi TP --";
             noData.disabled = true;
             tpSelect.appendChild(noData);
        }
    }

    // LOGIKA COPY TEXT: Saat Dropdown Dipilih -> Copy ke Textarea
    tpSelect.addEventListener('change', function() {
        const selectedOption = tpSelect.options[tpSelect.selectedIndex];
        const deskripsi = selectedOption.getAttribute('data-deskripsi');
        if(deskripsi) {
            tpManual.value = deskripsi; // Copy teks
        }
    });

    faseSelect.addEventListener('change', filterTp);
    mapelSelect.addEventListener('change', filterTp);
    filterTp();
});

function showLoading() {
    const form = document.getElementById('formGenerate');
    if(form.checkValidity()) {
        Swal.fire({
            title: 'AI Sedang Bekerja...',
            html: 'Menyusun Modul Ajar Deep Learning dengan Standar Terbaru...<br>Estimasi 1-2 Menit.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => { Swal.showLoading() }
        });
    }
}
</script>
<?= $this->endSection(); ?>