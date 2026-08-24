<!DOCTYPE html>
<html lang="id">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= isset($title) ? html_escape($title) . ' - ' : '' ?>Admin eSKM</title>
	<link rel="icon" type="image/png" href="<?= base_url("assets/img/logo-dinkes.png") ?>">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
	<style>
		:root {
			--skm-primary: #0b3d91;
			--skm-primary-dark: #082a66;
			--skm-bg: #f2f5fb;
		}

		body {
			font-family: 'Poppins', sans-serif;
			background: var(--skm-bg);
		}

		.sidebar-admin {
			width: 250px;
			min-height: 100vh;
			background: linear-gradient(180deg, var(--skm-primary), var(--skm-primary-dark));
			position: fixed;
			top: 0;
			left: 0;
			color: #fff;
			padding: 1.5rem 0;
			z-index: 1030;
		}

		.sidebar-admin .brand {
			padding: 0 1.25rem 1.25rem;
			border-bottom: 1px solid rgba(255, 255, 255, .12);
			margin-bottom: 1rem;
			text-align: center;
		}

		.sidebar-admin .brand-logo {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: .55rem;
			background: #fff;
			border-radius: .7rem;
			padding: .5rem .7rem;
			margin: 0 auto .85rem;
			width: max-content;
		}

		.sidebar-admin .brand-logo img {
			height: 40px;
			width: auto;
			display: block;
		}

		.sidebar-admin .brand-logo .sep {
			width: 1px;
			align-self: stretch;
			background: rgba(11, 61, 145, .18);
		}

		.sidebar-admin .brand h5 {
			font-weight: 700;
			margin-bottom: 0;
		}

		.sidebar-admin .brand small {
			opacity: .75;
		}

		.sidebar-admin .nav-link {
			color: rgba(255, 255, 255, .85);
			padding: .65rem 1.25rem;
			border-radius: 0;
			display: flex;
			align-items: center;
			gap: .6rem;
		}

		.sidebar-admin .nav-link.active,
		.sidebar-admin .nav-link:hover {
			background: rgba(255, 255, 255, .12);
			color: #fff;
		}

		.main-admin {
			margin-left: 250px;
			padding: 1.75rem;
		}

		.topbar-admin {
			background: #fff;
			border-radius: .75rem;
			box-shadow: 0 4px 15px rgba(11, 61, 145, .06);
			padding: .9rem 1.25rem;
			margin-bottom: 1.5rem;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		.card-stat {
			border: none;
			border-radius: 1rem;
			box-shadow: 0 8px 25px rgba(11, 61, 145, .08);
		}

		.card-stat .icon-wrap {
			width: 3rem;
			height: 3rem;
			border-radius: .85rem;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.3rem;
		}

		@media (max-width: 991.98px) {
			.sidebar-admin {
				left: -250px;
				transition: left .2s;
			}

			.sidebar-admin.show {
				left: 0;
			}

			.main-admin {
				margin-left: 0;
			}
		}
	</style>
</head>

<body>

	<div class="sidebar-admin" id="sidebarAdmin">
		<div class="brand">
			<div class="brand-logo">
				<img src="<?= base_url("assets/img/lambang-bandung.svg") ?>" alt="Lambang Kota Bandung">
				<span class="sep"></span>
				<img src="<?= base_url("assets/img/logo-dinkes.png") ?>" alt="Logo Dinas Kesehatan Kota Bandung">
			</div>
			<h5>eSKM Admin</h5>
			<small><?= html_escape($pengaturan['nama_instansi']) ?></small>
		</div>
		<nav class="nav flex-column">
			<a class="nav-link <?= (uri_string() === 'admin/dashboard' || uri_string() === 'admin') ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
				<i class="bi bi-speedometer2"></i> Dashboard IKM
			</a>
			<a class="nav-link <?= (strpos(uri_string(), 'admin/responden') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/responden') ?>">
				<i class="bi bi-people"></i> Data Responden
			</a>
			<a class="nav-link <?= (strpos(uri_string(), 'admin/hasil') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/hasil') ?>">
				<i class="bi bi-table"></i> Hasil Survei
			</a>
			<a class="nav-link <?= (strpos(uri_string(), 'admin/export/pdf') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/export/pdf') ?>">
				<i class="bi bi-file-earmark-pdf"></i> Laporan IKM
			</a>
			<a class="nav-link <?= (strpos(uri_string(), 'admin/saran') === 0) ? 'active' : '' ?>" href="<?= base_url('admin/saran') ?>">
				<i class="bi bi-chat-left-text"></i> Rekap Saran / Masukan
			</a>
			<a class="nav-link" href="<?= base_url('survei') ?>" target="_blank">
				<i class="bi bi-box-arrow-up-right"></i> Lihat Form Survei
			</a>
			<hr class="mx-3" style="border-color:rgba(255,255,255,.15)">
			<a class="nav-link" href="<?= base_url('admin/logout') ?>">
				<i class="bi bi-box-arrow-right"></i> Keluar
			</a>
		</nav>
	</div>

	<div class="main-admin">
		<div class="topbar-admin">
			<button class="btn btn-sm btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebarAdmin').classList.toggle('show')">
				<i class="bi bi-list"></i>
			</button>
			<h5 class="mb-0 d-none d-lg-block"><?= isset($title) ? html_escape($title) : 'Dashboard' ?></h5>
			<div class="d-flex align-items-center gap-2">
				<i class="bi bi-person-circle fs-4 text-primary"></i>
				<span class="small fw-semibold"><?= html_escape($admin['nama_lengkap']) ?></span>
			</div>
		</div>