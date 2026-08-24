<?php
$this->load->view('templates/admin_header', array(
	'pengaturan' => $pengaturan, 'admin' => $admin, 'title' => 'Dashboard IKM',
));
?>

<form method="get" class="row g-2 align-items-end mb-4">
	<div class="col-auto">
		<label class="form-label small mb-1">Bulan</label>
		<select name="bulan" class="form-select form-select-sm">
			<option value="">Semua Bulan</option>
			<?php foreach ($bulan_list as $num => $nama): ?>
				<option value="<?= $num ?>" <?= ((string) $bulan === (string) $num) ? 'selected' : '' ?>><?= $nama ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-auto">
		<label class="form-label small mb-1">Tahun</label>
		<select name="tahun" class="form-select form-select-sm">
			<option value="">Semua Tahun</option>
			<?php foreach ($tahun_list as $th): ?>
				<option value="<?= $th ?>" <?= ((string) $tahun === (string) $th) ? 'selected' : '' ?>><?= $th ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-auto">
		<button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Terapkan</button>
		<a href="<?= base_url('admin/dashboard') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
	</div>
	<div class="col-auto ms-auto">
		<button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal" data-bs-target="#modalQr">
			<i class="bi bi-qr-code"></i> QR Code Survei
		</button>
	</div>
</form>

<!-- Modal QR Code menuju form survei publik -->
<div class="modal fade" id="modalQr" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title fw-bold"><i class="bi bi-qr-code me-1"></i> QR Code Pengisian Survei</h6>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
			</div>
			<div class="modal-body text-center">
				<p class="text-muted small">
					Pindai atau cetak QR ini agar masyarakat dapat langsung membuka form survei.
				</p>
				<div class="border rounded-3 p-3 d-inline-block bg-white">
					<canvas id="qrCanvas" width="600" height="600" style="width:260px;height:260px;"></canvas>
				</div>
				<div id="qrGagal" class="alert alert-warning small mt-3 d-none">
					QR code gagal dibuat. Pastikan koneksi internet aktif karena pustaka QR dimuat dari CDN.
				</div>
				<div class="d-grid mt-3">
					<button type="button" id="btnUnduhQr" class="btn btn-primary">
						<i class="bi bi-download me-1"></i> Download QR Code
					</button>
				</div>
				<div class="mt-3 small">
					<a href="<?= base_url('survei') ?>" target="_blank" class="text-decoration-none fw-semibold">
						<i class="bi bi-link-45deg"></i> Link Survei
					</a>
					<div class="input-group input-group-sm mt-2">
						<input type="text" class="form-control" id="qrLink" readonly value="<?= base_url('survei') ?>">
						<button class="btn btn-outline-secondary" type="button" id="btnSalinLink">
							<i class="bi bi-clipboard"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row g-3 mb-4">
	<div class="col-md-3">
		<div class="card card-stat p-3 h-100">
			<div class="d-flex align-items-center gap-3">
				<div class="icon-wrap bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></div>
				<div>
					<div class="text-muted small">Jumlah Responden</div>
					<div class="fs-4 fw-bold"><?= number_format($ikm['jumlah_responden']) ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stat p-3 h-100">
			<div class="d-flex align-items-center gap-3">
				<div class="icon-wrap bg-success bg-opacity-10 text-success"><i class="bi bi-graph-up-arrow"></i></div>
				<div>
					<div class="text-muted small">Nilai IKM</div>
					<div class="fs-4 fw-bold"><?= $ikm['jumlah_responden'] > 0 ? number_format($ikm['ikm'], 2) : '-' ?></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stat p-3 h-100">
			<div class="d-flex align-items-center gap-3">
				<div class="icon-wrap <?= badge_mutu_class($ikm['mutu']['mutu']) ?> bg-opacity-10 text-dark"><i class="bi bi-award-fill"></i></div>
				<div>
					<div class="text-muted small">Mutu Pelayanan</div>
					<div class="fs-4 fw-bold">
						<?php if ($ikm['jumlah_responden'] > 0): ?>
							<span class="badge <?= badge_mutu_class($ikm['mutu']['mutu']) ?>"><?= $ikm['mutu']['mutu'] ?></span>
						<?php else: ?> - <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card card-stat p-3 h-100">
			<div class="d-flex align-items-center gap-3">
				<div class="icon-wrap bg-warning bg-opacity-10 text-warning"><i class="bi bi-emoji-smile-fill"></i></div>
				<div>
					<div class="text-muted small">Kinerja Unit</div>
					<div class="fs-6 fw-bold"><?= $ikm['jumlah_responden'] > 0 ? $ikm['mutu']['kinerja'] : '-' ?></div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	/* Tinggi grafik dikunci supaya kedua kartu sejajar; tanpa ini Chart.js
	   memakai rasio bawaan (batang 2:1, donat 1:1) sehingga tingginya berbeda */
	.chart-box{ position:relative; height:300px; }
</style>

<div class="row g-3 mb-4">
	<div class="col-lg-7">
		<div class="card card-stat p-3 h-100">
			<h6 class="mb-3">Nilai Rata-Rata (NRR) per Unsur Pelayanan</h6>
			<div class="chart-box"><canvas id="chartUnsur"></canvas></div>
		</div>
	</div>
	<div class="col-lg-5">
		<div class="card card-stat p-3 h-100">
			<h6 class="mb-3">Responden per Jenis Kelamin</h6>
			<div class="chart-box"><canvas id="chartGender"></canvas></div>
		</div>
	</div>
</div>

<div class="card card-stat p-3 mb-4">
	<h6 class="mb-3">Tabel Perhitungan Nilai IKM per Unsur</h6>
	<div class="table-responsive">
		<table class="table table-sm table-bordered align-middle mb-0">
			<thead class="table-light">
				<tr>
					<th style="width:60px;">Kode</th>
					<th>Unsur Pelayanan</th>
					<th class="text-center" style="width:110px;">NRR</th>
					<th class="text-center" style="width:130px;">NRR Tertimbang</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($ikm['per_unsur'] as $u): ?>
				<tr>
					<td class="text-center fw-semibold"><?= html_escape($u['kode']) ?></td>
					<td><?= html_escape($u['pertanyaan']) ?></td>
					<td class="text-center"><?= number_format($u['nrr'], 3) ?></td>
					<td class="text-center"><?= number_format($u['nrr_tertimbang'], 3) ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<tr class="table-light fw-bold">
					<td colspan="3" class="text-end">Nilai IKM (Total NRR Tertimbang &times; 25)</td>
					<td class="text-center"><?= number_format($ikm['ikm'], 2) ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
	<p class="text-muted small mt-3 mb-0">
		Perhitungan mengacu pada Peraturan Menteri PANRB Nomor 14 Tahun 2017. Bobot nilai rata-rata tertimbang = 1 / 9 unsur = 0,111.
	</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>
<script>
const unsurLabels = <?= json_encode(array_column($ikm['per_unsur'], 'kode')) ?>;
const unsurData   = <?= json_encode(array_column($ikm['per_unsur'], 'nrr')) ?>;

new Chart(document.getElementById('chartUnsur'), {
	type: 'bar',
	data: {
		labels: unsurLabels,
		datasets: [{
			label: 'Nilai Rata-Rata (skala 1-4)',
			data: unsurData,
			backgroundColor: '#0b3d91',
			borderRadius: 6,
		}]
	},
	options: {
		responsive: true,
		maintainAspectRatio: false,
		scales: { y: { beginAtZero: true, max: 4 } },
		plugins: { legend: { display: false } }
	}
});

const genderLabels = <?php
	$map = array('L' => 'Laki-laki', 'P' => 'Perempuan');
	echo json_encode(array_map(function($d) use ($map){
		// jenis kelamin bersifat opsional, jadi bisa kosong
		if (empty($d['jenis_kelamin'])) return 'Tidak diisi';
		return isset($map[$d['jenis_kelamin']]) ? $map[$d['jenis_kelamin']] : $d['jenis_kelamin'];
	}, $demografi['jenis_kelamin']));
?>;
const genderData = <?= json_encode(array_column($demografi['jenis_kelamin'], 'jml')) ?>;

new Chart(document.getElementById('chartGender'), {
	type: 'doughnut',
	data: {
		labels: genderLabels,
		datasets: [{
			data: genderData,
			backgroundColor: ['#0b3d91', '#00a651', '#f4a300'],
		}]
	},
	options: {
		responsive: true,
		maintainAspectRatio: false,
		plugins: { legend: { position: 'bottom' } }
	}
});

/* ---------- QR Code menuju form survei ---------- */
(function () {
	var linkSurvei = <?= json_encode(base_url('survei')) ?>;
	var urlLogo    = <?= json_encode(base_url('assets/img/logo-dinkes.png')) ?>;
	var modal      = document.getElementById('modalQr');
	var canvas     = document.getElementById('qrCanvas');
	var sudahGambar = false;

	function kotakBulat(ctx, x, y, w, h, r) {
		r = Math.min(r, w / 2, h / 2);
		ctx.beginPath();
		ctx.moveTo(x + r, y);
		ctx.arcTo(x + w, y,     x + w, y + h, r);
		ctx.arcTo(x + w, y + h, x,     y + h, r);
		ctx.arcTo(x,     y + h, x,     y,     r);
		ctx.arcTo(x,     y,     x + w, y,     r);
		ctx.closePath();
	}

	function gambarLogo() {
		var logo = new Image();
		logo.onload = function () {
			var ctx   = canvas.getContext('2d');
			var kotak = canvas.width * 0.22;

			// contain: sisi terpanjang logo pas di kotak, rasio asli dipertahankan
			var skala = Math.min(kotak / logo.naturalWidth, kotak / logo.naturalHeight);
			var lw = logo.naturalWidth  * skala;
			var lh = logo.naturalHeight * skala;
			var lx = (canvas.width  - lw) / 2;
			var ly = (canvas.height - lh) / 2;
			var pad = kotak * 0.12;

			ctx.fillStyle = '#ffffff';
			kotakBulat(ctx, lx - pad, ly - pad, lw + pad * 2, lh + pad * 2, kotak * 0.2);
			ctx.fill();
			ctx.drawImage(logo, lx, ly, lw, lh);
		};
		logo.onerror = function () { /* QR tetap terpakai walau logo gagal dimuat */ };
		logo.src = urlLogo;
	}

	function buatQr() {
		if (sudahGambar) return;

		if (typeof qrcode === 'undefined') {
			document.getElementById('qrGagal').classList.remove('d-none');
			document.getElementById('btnUnduhQr').disabled = true;
			return;
		}

		// 0 = ukuran otomatis, 'H' = koreksi kesalahan tertinggi (30%),
		// menyisakan ruang aman untuk logo di tengah
		var qr = qrcode(0, 'H');
		qr.addData(linkSurvei);
		qr.make();

		var jml  = qr.getModuleCount();
		var tepi = 4;                                // quiet zone, satuan modul
		var sisi = canvas.width / (jml + tepi * 2);
		var ctx  = canvas.getContext('2d');

		function px(n) { return (n + tepi) * sisi; }

		// tiga kotak pencari di sudut digambar terpisah sebagai bingkai membulat
		function areaMata(r, c) {
			return (r < 7 && c < 7)
				|| (r < 7 && c >= jml - 7)
				|| (r >= jml - 7 && c < 7);
		}

		ctx.fillStyle = '#ffffff';
		ctx.fillRect(0, 0, canvas.width, canvas.height);
		ctx.fillStyle = '#000000';

		// modul biasa digambar sebagai titik bulat
		for (var r = 0; r < jml; r++) {
			for (var c = 0; c < jml; c++) {
				if ( ! qr.isDark(r, c) || areaMata(r, c)) continue;
				ctx.beginPath();
				ctx.arc(px(c) + sisi / 2, px(r) + sisi / 2, sisi * 0.46, 0, Math.PI * 2);
				ctx.fill();
			}
		}

		var sudut = [[0, 0], [0, jml - 7], [jml - 7, 0]];
		for (var i = 0; i < sudut.length; i++) {
			var x = px(sudut[i][1]);
			var y = px(sudut[i][0]);
			var s = sisi * 7;

			ctx.fillStyle = '#000000';
			kotakBulat(ctx, x, y, s, s, sisi * 2);
			ctx.fill();

			ctx.fillStyle = '#ffffff';
			kotakBulat(ctx, x + sisi, y + sisi, s - sisi * 2, s - sisi * 2, sisi * 1.4);
			ctx.fill();

			ctx.fillStyle = '#000000';
			kotakBulat(ctx, x + sisi * 2, y + sisi * 2, s - sisi * 4, s - sisi * 4, sisi * 0.9);
			ctx.fill();
		}

		sudahGambar = true;
		gambarLogo();
	}

	if (modal) modal.addEventListener('shown.bs.modal', buatQr);

	document.getElementById('btnUnduhQr').addEventListener('click', function () {
		var a = document.createElement('a');
		a.href = canvas.toDataURL('image/png');
		a.download = 'qr-survei-eskm.png';
		a.click();
	});

	document.getElementById('btnSalinLink').addEventListener('click', function () {
		var input = document.getElementById('qrLink');
		input.select();
		input.setSelectionRange(0, 99999);
		if (navigator.clipboard) navigator.clipboard.writeText(input.value);
		else document.execCommand('copy');

		var ikon = this.querySelector('i');
		ikon.className = 'bi bi-check2';
		setTimeout(function () { ikon.className = 'bi bi-clipboard'; }, 1500);
	});
})();
</script>

<?php $this->load->view('templates/admin_footer', array('pengaturan' => $pengaturan)); ?>
