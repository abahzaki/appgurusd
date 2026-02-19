<?= $this->extend('layout/template') ?>
<?= $this->section('content') ?>

<form action="<?= base_url('cetak_atp/save_atp') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="mapel_id" value="<?= $mapel['id'] ?>">
    <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Distribusi & Alokasi Waktu ATP</h1>
        <button type="submit" class="btn btn-success shadow-sm">
            <i class="bi bi-save me-2"></i> Simpan ATP
        </button>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i> 
        AI telah memecah TP menjadi langkah-langkah kecil. 
        Silakan <b>Edit Kalimat</b>, tentukan <b>Jumlah Jam (JP)</b>, dan pilih <b>Semester</b>.
    </div>

    <div class="card shadow mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="55%">Alur Tujuan Pembelajaran (Sub-ATP)</th>
                            <th width="15%">Est. JP</th>
                            <th width="25%">Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=0; foreach($tp_ai as $kalimat): ?>
                        <tr>
                            <td class="text-center bg-light fw-bold"><?= $no+1 ?></td>
                            <td>
                                <textarea name="items[<?= $no ?>][text]" class="form-control" rows="2"><?= $kalimat ?></textarea>
                            </td>
                            <td>
                                <div class="input-group">
                                    <input type="number" name="items[<?= $no ?>][jp]" class="form-control text-center jp-input" value="2" min="1">
                                    <span class="input-group-text small">JP</span>
                                </div>
                            </td>
                            <td class="text-center bg-light">
                                <div class="btn-group w-100" role="group">
                                    <input type="radio" class="btn-check" name="items[<?= $no ?>][semester]" id="sem1_<?= $no ?>" value="1" autocomplete="off">
                                    <label class="btn btn-outline-primary btn-sm" for="sem1_<?= $no ?>">Sem 1</label>

                                    <input type="radio" class="btn-check" name="items[<?= $no ?>][semester]" id="sem2_<?= $no ?>" value="2" autocomplete="off">
                                    <label class="btn btn-outline-success btn-sm" for="sem2_<?= $no ?>">Sem 2</label>
                                </div>
                            </td>
                        </tr>
                        <?php $no++; endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between fw-bold">
            <span class="text-primary">Estimasi Total Sem 1: <span id="total-s1">0</span> JP</span>
            <span class="text-success">Estimasi Total Sem 2: <span id="total-s2">0</span> JP</span>
        </div>
    </div>
</form>

<script>
    function hitungTotal() {
        let s1 = 0;
        let s2 = 0;
        
        // Loop semua baris
        $('tbody tr').each(function() {
            let jp = parseInt($(this).find('.jp-input').val()) || 0;
            
            if ($(this).find('input[value="1"]').is(':checked')) {
                s1 += jp;
            } else if ($(this).find('input[value="2"]').is(':checked')) {
                s2 += jp;
            }
        });

        $('#total-s1').text(s1);
        $('#total-s2').text(s2);
    }

    // Trigger hitung saat ada perubahan
    $(document).on('change input', '.jp-input, input[type="radio"]', function() {
        hitungTotal();
    });
</script>

<?= $this->endSection() ?>