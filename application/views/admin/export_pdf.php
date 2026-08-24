<?php
$this->load->view('templates/admin_header', array(
	'pengaturan' => $pengaturan, 'admin' => $admin, 'title' => 'Laporan IKM',
));

$ada_data = $ikm['jumlah_responden'] > 0;
$bobot    = 1 / max(count($ikm['per_unsur']), 1);

// Biodata responden bersifat opsional, jadi isian kosong dikelompokkan
// tersendiri supaya tidak muncul sebagai baris tanpa nama.
$jk = array('L' => 0, 'P' => 0, 'kosong' => 0);
foreach ($demografi['jenis_kelamin'] as $row) {
	$kunci = empty($row['jenis_kelamin']) ? 'kosong' : $row['jenis_kelamin'];
	$jk[$kunci] += (int) $row['jml'];
}

$pend = array_fill_keys($pendidikan_list, 0);
$pend_kosong = 0;
foreach ($demografi['pendidikan'] as $row) {
	if (empty($row['pendidikan'])) { $pend_kosong += (int) $row['jml']; continue; }
	$pend[$row['pendidikan']] = (int) $row['jml'];
}
if ($pend_kosong > 0) $pend['Tidak diisi'] = $pend_kosong;
?>

<style>
	.lembar{
		background:#fff; border:1px solid #e3e8f0; border-radius:.75rem;
		padding:2.5rem 3rem; margin-bottom:1.5rem; width:100%;
		font-size:.92rem; line-height:1.6; color:#1c2733;
	}
	.lembar .kop{ text-align:center; font-weight:700; line-height:1.5; margin-bottom:1.75rem; }
	.lembar h6{ font-weight:700; margin:1.5rem 0 .6rem; }
	.lembar p{ text-align:justify; }
	.tabel-laporan{ width:100%; border-collapse:collapse; margin:1rem 0; }
	.tabel-laporan th, .tabel-laporan td{ border:1px solid #9aa7bd; padding:.45rem .6rem; }
	.tabel-laporan thead th{ text-align:center; font-weight:700; background:#f2f5fb; }
	.tabel-laporan td.no{ text-align:center; width:42px; }
	.tabel-laporan td.kode{ text-align:center; width:56px; }
	.tabel-laporan td.nrr{ text-align:center; width:110px; }
	.tabel-laporan tr.baris-ekstrem td{ font-weight:700; }
	.rumus{ background:#f7f9fd; border-left:3px solid var(--skm-primary); padding:.7rem 1rem; margin:.8rem 0; }
	.daftar-hasil{ list-style:none; padding-left:1.2rem; }
	.daftar-hasil li{ display:flex; gap:.5rem; }
	.daftar-hasil li span:first-child{ width:230px; }
	.ttd{ display:flex; justify-content:space-around; text-align:center; margin-top:3rem; }
	.ttd .garis{ margin-top:4.5rem; }

	/* Lampiran */
	.lampiran-judul{ text-align:center; color:#8a94a6; letter-spacing:.2em; font-size:.78rem; margin-bottom:1.5rem; }
	.kartu-lampiran{ border:1px solid #c9d3e4; border-radius:.75rem; padding:1.5rem 2rem; }
	.kartu-lampiran .judul{ text-align:center; font-weight:700; line-height:1.5; }
	.kartu-ikm{
		border:1px solid #c9d3e4; border-radius:.75rem; padding:1.5rem;
		text-align:center; height:100%; display:flex; flex-direction:column; justify-content:center;
	}
	.kartu-ikm .angka{ font-size:3.4rem; font-weight:700; color:#1a56db; line-height:1.1; }
	.blok-responden dt{ float:left; width:150px; font-weight:400; }
	.blok-responden dd{ margin-left:150px; margin-bottom:.2rem; }
	.ucapan{ text-align:center; font-size:.85rem; line-height:1.6; }

	/* Kertas F4 / Folio 210 x 330 mm, satu lembar = satu halaman */
	@page{ size:210mm 330mm; margin:12mm 15mm; }

	@media print{
		.sidebar-admin, .topbar-admin, .no-print{ display:none !important; }
		.main-admin{ margin-left:0 !important; padding:0 !important; }
		.main-admin > p.text-center{ display:none !important; }
		html, body{ background:#fff !important; }

		.lembar{
			border:none; border-radius:0; padding:0; margin:0; width:100%;
			font-size:9.5pt; line-height:1.4;
			page-break-after:always; break-after:page;
		}
		.lembar p{ margin-bottom:.5rem; }
		.lembar:last-child{ page-break-after:auto; break-after:auto; }

		.lembar .kop{ margin-bottom:.9rem; }
		.lembar h6{ margin:.8rem 0 .35rem; }
		.tabel-laporan{ margin:.55rem 0; }
		.tabel-laporan th, .tabel-laporan td{ padding:.22rem .45rem; }
		.rumus{ margin:.5rem 0; padding:.45rem .7rem; }
		.daftar-hasil{ margin-bottom:.4rem; }
		.ttd{ margin-top:1.6rem; }
		.ttd .garis{ margin-top:2.8rem; }

		/* Lebar cetak di bawah breakpoint md, jadi kolom Bootstrap dipaksa
		   tetap berdampingan supaya lampiran tidak menumpuk ke bawah */
		.kartu-lampiran .row{ display:flex; flex-wrap:nowrap; }
		.kartu-lampiran .col-md-5{ flex:0 0 41.6667%; max-width:41.6667%; }
		.kartu-lampiran .col-md-7{ flex:0 0 58.3333%; max-width:58.3333%; }
		.kartu-ikm .angka{ font-size:3rem; }
	}
</style>

<div class="no-print">
	<form method="get" class="row g-2 align-items-end mb-3">
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
			<a href="<?= base_url('admin/export/pdf') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
		</div>
		<div class="col-auto ms-auto">
			<button type="button" class="btn btn-sm btn-dark" onclick="window.print()">
				<i class="bi bi-printer"></i> Cetak / Simpan PDF
			</button>
		</div>
	</form>
	<p class="text-muted small">
		<i class="bi bi-info-circle me-1"></i>
		Ini pratinjau laporan. Tekan <strong>Cetak / Simpan PDF</strong> bila isinya sudah sesuai &mdash;
		menu sidebar dan filter di atas tidak ikut tercetak.
	</p>
</div>

<?php if ( ! $ada_data): ?>
	<div class="lembar text-center text-muted">
		<i class="bi bi-inbox fs-1 d-block mb-2"></i>
		Belum ada data responden untuk periode ini, laporan belum dapat dibuat.
	</div>
<?php else: ?>

<!-- ============ Halaman 1: Laporan ============ -->
<div class="lembar">
	<div class="kop">
		LAPORAN HASIL SURVEY INDEKS KEPUASAN MASYARAKAT (IKM)<br>
		PADA PERANGKAT DAERAH : <?= strtoupper(html_escape($pengaturan['nama_instansi'])) ?><br>
		KOTA BANDUNG<br>
		<?= $tahun ? html_escape($tahun) : date('Y') ?>
	</div>

	<h6>1. Indeks Per Unsur Pelayanan</h6>
	<p>
		Berdasarkan hasil penghitungan indeks kepuasan masyarakat, jumlah nilai pada unit pelayanan
		diperoleh jumlah nilai rata-rata setiap unsur pelayanan. Sedangkan nilai indeks komposit
		(gabungan) untuk setiap unit pelayanan, merupakan jumlah nilai rata-rata dari setiap unsur
		pelayanan dikalikan dengan penimbang yang sama, yaitu <?= number_format($bobot, 2) ?>, sebagai berikut:
	</p>

	<table class="tabel-laporan">
		<thead>
			<tr>
				<th style="width:42px;">No.</th>
				<th colspan="2">Unsur Pelayanan</th>
				<th style="width:110px;">Nilai Unsur Pelayanan (NRR)</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ikm['per_unsur'] as $i => $u): ?>
				<tr>
					<td class="no"><?= $i + 1 ?></td>
					<td title="<?= html_escape($u['pertanyaan']) ?>"><?= html_escape(nama_unsur($u['kode'], $u['pertanyaan'])) ?></td>
					<td class="kode">(<?= html_escape($u['kode']) ?>)</td>
					<td class="nrr"><?= number_format($u['nrr'], 3) ?></td>
				</tr>
			<?php endforeach; ?>
			<tr class="baris-ekstrem">
				<td class="no"><?= count($ikm['per_unsur']) + 1 ?></td>
				<td colspan="2">Nilai Unsur Tertinggi</td>
				<td class="nrr"><?= number_format($prioritas['nilai_max'], 3) ?></td>
			</tr>
			<tr class="baris-ekstrem">
				<td class="no"><?= count($ikm['per_unsur']) + 2 ?></td>
				<td colspan="2">Nilai Unsur Terendah</td>
				<td class="nrr"><?= number_format($prioritas['nilai_min'], 3) ?></td>
			</tr>
		</tbody>
	</table>

	<p>Keterangan : Jumlah Responden sebanyak : <?= number_format($ikm['jumlah_responden']) ?> orang</p>
	<p>Maka untuk mengetahui nilai indeks unit pelayanan dihitung dengan cara sebagai berikut:</p>

	<div class="rumus">
		<?php $bagian = array(); ?>
		<?php foreach ($ikm['per_unsur'] as $u): ?>
			<?php $bagian[] = '(NRR ' . html_escape($u['kode']) . ' x ' . number_format($bobot, 2) . ')'; ?>
		<?php endforeach; ?>
		<?= implode(' + ', $bagian) ?> = <strong><?= number_format($ikm['nilai_index'], 3) ?></strong>
	</div>

	<p>Dengan demikian nilai indeks unit pelayanan hasilnya dapat disimpulkan sebagai berikut:</p>
	<ul class="daftar-hasil">
		<li><span>a. Nilai IKM setelah dikonversi</span><span>= <strong><?= number_format($ikm['ikm'], 2) ?></strong></span></li>
		<li><span>b. Mutu Pelayanan</span><span>= <strong><?= html_escape($ikm['mutu']['mutu']) ?></strong></span></li>
		<li><span>c. Kinerja unit pelayanan adalah</span><span><strong><?= html_escape($ikm['mutu']['kinerja']) ?></strong></span></li>
	</ul>

	<h6>2. Prioritas Peningkatan Kualitas Pelayanan</h6>
	<p>
		Dalam peningkatan kualitas pelayanan, diprioritaskan pada unsur yang mempunyai nilai paling
		rendah, sedangkan unsur yang mempunyai nilai cukup tinggi harus tetap dipertahankan.
	</p>
	<ul class="daftar-hasil" style="display:block;">
		<li style="display:list-item;">a. Nilai dalam kelompok paling rendah yang harus ditingkatkan yaitu pada unsur pelayanan sebagai berikut : <strong><?= html_escape($prioritas['terendah']) ?></strong></li>
		<li style="display:list-item;">b. Nilai dalam kelompok paling tinggi yang harus tetap dipertahankan yaitu pada unsur pelayanan sebagai berikut : <strong><?= html_escape($prioritas['tertinggi']) ?></strong></li>
		<li style="display:list-item;">c. Upaya untuk <u>meningkatkan</u> unsur pelayanan :</li>
		<li style="display:list-item;">d. Upaya untuk <u>mempertahankan</u> unsur pelayanan :</li>
	</ul>

	<div class="ttd">
		<div>
			Mengetahui :<br>
			Kepala SKPD / Unit Kerja
			<div class="garis">( ............................................. )</div>
		</div>
		<div>
			Bandung, <?= html_escape($tanggal_cetak) ?><br>
			Penanggung Jawab
			<div class="garis">( ............................................. )</div>
		</div>
	</div>
</div>

<!-- ============ Halaman 2: Lampiran ============ -->
<div class="lembar">
	<div class="lampiran-judul">&middot; &middot; &middot; &middot; &middot; Lampiran &middot; &middot; &middot; &middot; &middot;</div>

	<div class="kartu-lampiran">
		<div class="judul">
			INDEKS KEPUASAN MASYARAKAT<br>
			<?= strtoupper(html_escape($pengaturan['nama_instansi'])) ?><br>
			<span class="fw-normal">Kota Bandung</span><br>
			<span class="fw-normal"><?= html_escape($periode) ?></span>
		</div>

		<div class="row g-3 mt-3">
			<div class="col-md-5">
				<div class="kartu-ikm">
					<div class="fw-bold">NILAI IKM</div>
					<div class="angka"><?= number_format($ikm['ikm'], 2) ?></div>
					<div class="small text-muted">Mutu <?= html_escape($ikm['mutu']['mutu']) ?> &mdash; <?= html_escape($ikm['mutu']['kinerja']) ?></div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="kartu-ikm" style="text-align:left; display:block;">
					<div class="fw-bold text-center mb-3">RESPONDEN</div>
					<dl class="blok-responden mb-0">
						<dt>Jumlah</dt>
						<dd>: <?= number_format($ikm['jumlah_responden']) ?></dd>
						<dt>Jenis Kelamin</dt>
						<dd>: L = <?= $jk['L'] ?> Orang / P = <?= $jk['P'] ?> Orang<?= $jk['kosong'] > 0 ? ' / Tidak diisi = ' . $jk['kosong'] . ' Orang' : '' ?></dd>
						<dt>Pendidikan</dt>
						<dd>
							<?php $baris = array(); foreach ($pend as $jenjang => $jml) $baris[] = html_escape($jenjang) . ' = ' . $jml; ?>
							: <?= implode('<br>&nbsp;&nbsp;', $baris) ?>
						</dd>
						<dt>Survei Periode</dt>
						<dd>: <?= $tahun ? html_escape($tahun) : date('Y') ?></dd>
					</dl>
				</div>
			</div>
		</div>

		<div class="ucapan mt-4">
			<strong>TERIMAKASIH ATAS PENILAIAN YANG ANDA BERIKAN</strong><br>
			MASUKAN ANDA SANGAT BERMANFAAT UNTUK KEMAJUAN UNIT KAMI AGAR TERUS MEMPERBAIKI<br>
			DAN MENINGKATKAN KUALITAS PELAYANAN BAGI MASYARAKAT
		</div>
	</div>
</div>

<?php endif; ?>

<?php $this->load->view('templates/admin_footer'); ?>
