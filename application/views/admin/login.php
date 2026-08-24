<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin - eSKM <?= html_escape($pengaturan['nama_instansi']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
	body{
		font-family:'Poppins',sans-serif;
		min-height:100vh; display:flex; align-items:center;
		background:linear-gradient(135deg,#0b3d91 0%,#082a66 100%);
	}
	.login-card{ border:none; border-radius:1rem; box-shadow:0 20px 50px rgba(0,0,0,.25); overflow:hidden; }
	.login-side{ background:#08245a; color:#fff; padding:2.5rem; }
	.login-logo{ display:flex; align-items:center; gap:.6rem; background:#fff; border-radius:.75rem; padding:.55rem .8rem; margin-bottom:1.25rem; width:max-content; }
	.login-logo img{ height:46px; width:auto; display:block; }
	.login-logo .sep{ width:1px; align-self:stretch; background:rgba(11,61,145,.18); }
	.btn-skm-primary{ background:#0b3d91; border-color:#0b3d91; }
	.btn-skm-primary:hover{ background:#082a66; border-color:#082a66; }
</style>
</head>
<body>
<div class="container">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<div class="card login-card">
				<div class="row g-0">
					<div class="col-md-5 login-side d-none d-md-flex flex-column justify-content-center">
						<div class="login-logo">
							<img src="<?= base_url("assets/img/lambang-bandung.svg") ?>" alt="Lambang Kota Bandung">
							<span class="sep"></span>
							<img src="<?= base_url("assets/img/logo-dinkes.png") ?>" alt="Logo Dinas Kesehatan Kota Bandung">
						</div>
						<h4 class="fw-bold">Panel Admin</h4>
						<p class="small mb-0 opacity-75">eSKM &mdash; <?= html_escape($pengaturan['nama_instansi']) ?></p>
						<p class="small opacity-75 mt-3">Kelola hasil Survei Kepuasan Masyarakat, pantau Nilai IKM, dan unduh laporan.</p>
					</div>
					<div class="col-md-7">
						<div class="p-4 p-md-5">
							<h5 class="fw-bold mb-1">Masuk ke Dashboard</h5>
							<p class="text-muted small mb-4">Silakan login menggunakan akun admin Anda.</p>

							<?php if ( ! empty($error)): ?>
								<div class="alert alert-danger py-2 small"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= $error ?></div>
							<?php endif; ?>

							<?= form_open('admin/login') ?>
								<div class="mb-3">
									<label class="form-label">Username</label>
									<input type="text" name="username" class="form-control" required autofocus placeholder="admin">
								</div>
								<div class="mb-4">
									<label class="form-label">Password</label>
									<input type="password" name="password" class="form-control" required placeholder="&bull;&bull;&bull;&bull;&bull;&bull;">
								</div>
								<div class="d-grid">
									<button type="submit" class="btn btn-skm-primary text-white"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>
								</div>
							<?= form_close() ?>

							<div class="text-center mt-4">
								<a href="<?= base_url('survei') ?>" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke halaman survei</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
