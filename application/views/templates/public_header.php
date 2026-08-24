<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pengaturan['nama_layanan']) ? html_escape($pengaturan['nama_layanan']) : 'eSKM' ?> - <?= isset($pengaturan['nama_instansi']) ? html_escape($pengaturan['nama_instansi']) : '' ?></title>
<meta name="description" content="Survei Kepuasan Masyarakat (SKM) <?= isset($pengaturan['nama_instansi']) ? html_escape($pengaturan['nama_instansi']) : '' ?>">
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
	:root{
		--skm-primary:#0b3d91;
		--skm-primary-dark:#082a66;
		--skm-accent:#00a651;
		--skm-bg:#f2f5fb;
	}
	body{ font-family:'Poppins',sans-serif; background:var(--skm-bg); color:#1c2733; }
	.navbar-skm{ background:linear-gradient(90deg,var(--skm-primary),var(--skm-primary-dark)); box-shadow:0 2px 10px rgba(0,0,0,.15); }
	.navbar-skm .navbar-brand{ color:#fff; font-weight:700; letter-spacing:.3px; }
	.navbar-skm .navbar-brand small{ display:block; font-weight:400; font-size:.72rem; opacity:.85; }
	.navbar-skm .brand-logo{ display:flex; align-items:center; gap:.45rem; background:#fff; border-radius:.55rem; padding:.35rem .55rem; flex:none; }
	.navbar-skm .brand-logo img{ height:34px; width:auto; display:block; }
	.navbar-skm .brand-logo .sep{ width:1px; align-self:stretch; background:rgba(11,61,145,.18); }
	.hero-skm{
		background:linear-gradient(135deg,var(--skm-primary) 0%,var(--skm-primary-dark) 100%);
		color:#fff; padding:3rem 0 4.5rem;
		position:relative;
	}
	.hero-skm h1{ font-weight:700; font-size:1.9rem; }
	.hero-skm p{ opacity:.92; }
	.card-form{
		border:none; border-radius:1rem; box-shadow:0 15px 40px rgba(11,61,145,.15);
		margin-top:-3.5rem; position:relative; z-index:2;
	}
	.card-form .card-header{
		background:#fff; border-bottom:1px solid #eef1f6; border-radius:1rem 1rem 0 0;
		padding:1.5rem 1.75rem;
	}
	.badge-unsur{
		display:inline-flex; align-items:center; justify-content:center;
		width:2.1rem; height:2.1rem; border-radius:50%;
		background:var(--skm-primary); color:#fff; font-weight:600; font-size:.85rem;
		margin-right:.6rem; flex:none;
	}
	.unsur-block{ border:1px solid #e7ebf3; border-radius:.75rem; padding:1.1rem 1.25rem; margin-bottom:1rem; background:#fbfcfe; }
	.unsur-block .form-select{ margin-top:.5rem; }
	.opsi-jawaban{
		display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
		gap:.5rem; margin-top:.7rem;
	}
	.opsi-item{
		margin:0; min-height:100%; background:#fff; border:1px solid #dde4f0; border-radius:.6rem;
		padding:.6rem .8rem .6rem 2.25rem; transition:border-color .15s, background .15s, box-shadow .15s;
	}
	.opsi-item:hover{ border-color:var(--skm-primary); background:#f7faff; }
	.opsi-item .form-check-input{ margin-top:.2rem; cursor:pointer; }
	.opsi-item .form-check-input:checked{ background-color:var(--skm-primary); border-color:var(--skm-primary); }
	.opsi-item .form-check-label{ cursor:pointer; font-size:.92rem; line-height:1.35; display:block; }
	.opsi-item .opsi-nilai{
		display:inline-block; min-width:1.35rem; margin-right:.3rem; padding:0 .25rem;
		border-radius:.3rem; background:#eaf1ff; color:var(--skm-primary);
		font-size:.75rem; font-weight:600; text-align:center;
	}
	.opsi-item:has(.form-check-input:checked){
		border-color:var(--skm-primary); background:#f2f7ff; box-shadow:0 0 0 2px rgba(11,61,145,.12);
	}
	.opsi-item:has(.form-check-input:checked) .form-check-label{ color:var(--skm-primary); font-weight:600; }
	.btn-skm-primary{ background:var(--skm-primary); border-color:var(--skm-primary); }
	.btn-skm-primary:hover{ background:var(--skm-primary-dark); border-color:var(--skm-primary-dark); }
	footer.footer-skm{ background:#0a2e6b; color:#cfd9ee; padding:2rem 0; margin-top:3rem; font-size:.9rem; }
	footer.footer-skm a{ color:#fff; }
	.step-pill{ background:#eaf1ff; color:var(--skm-primary); border-radius:2rem; padding:.3rem .9rem; font-size:.78rem; font-weight:600; }
</style>
</head>
<body>
<nav class="navbar navbar-skm navbar-dark sticky-top">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center gap-2" href="<?= base_url('survei') ?>">
			<span class="brand-logo">
				<img src="<?= base_url("assets/img/lambang-bandung.svg") ?>" alt="Lambang Kota Bandung">
				<span class="sep"></span>
				<img src="<?= base_url("assets/img/logo-dinkes.png") ?>" alt="Logo Dinas Kesehatan Kota Bandung">
			</span>
			<span>
				eSKM Kota Bandung
				<small><?= isset($pengaturan['nama_instansi']) ? html_escape($pengaturan['nama_instansi']) : '' ?></small>
			</span>
		</a>
	</div>
</nav>
