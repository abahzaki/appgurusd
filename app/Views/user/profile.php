<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3" src="https://ui-avatars.com/api/?name=<?= $user['nama_lengkap'] ?>&background=0D6EFD&color=fff&size=128">
                    <h5 class="font-weight-bold text-dark"><?= $user['nama_lengkap'] ?></h5>
                    <p class="text-muted"><?= $user['email'] ?></p>
                    <span class="badge badge-primary"><?= ucfirst($user['role']) ?></span>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ganti Password</h6>
                </div>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0 pl-3">
                                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                                    <li><?= $err ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('user/updatePassword') ?>" method="post">
                        <div class="form-group mb-3">
                            <label>Password Lama</label>
                            <input type="password" name="pass_lama" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label>Password Baru</label>
                            <input type="password" name="pass_baru" class="form-control" required>
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                        <div class="form-group mb-4">
                            <label>Ulangi Password Baru</label>
                            <input type="password" name="pass_conf" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>