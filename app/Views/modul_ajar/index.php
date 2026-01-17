<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bank Modul Ajar Saya</h1>
        <a href="<?= base_url('modulajar/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg text-white-50"></i> Buat Modul Baru (AI)
        </a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Modul Tersimpan</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Mata Pelajaran</th>
                            <th>Materi Pokok</th>
                            <th>Fase / Kelas</th>
                            <th>Tanggal Dibuat</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($modul as $m): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($m['mapel']) ?></td>
                            <td>
                                <b><?= esc($m['materi']) ?></b><br>
                                <small class="text-muted"><?= esc($m['model_belajar']) ?></small>
                            </td>
                            <td><?= esc($m['fase']) ?> - Kls <?= esc($m['kelas']) ?></td>
                            <td><?= date('d/m/Y', strtotime($m['created_at'])) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('modulajar/edit/' . $m['id']) ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= base_url('modulajar/delete/' . $m['id']) ?>" 
                                    class="btn btn-danger btn-sm" 
                                        title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus Modul Ajar ini? Data yang dihapus tidak dapat dikembalikan.');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($modul)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada modul ajar. Silakan buat baru!</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection(); ?>