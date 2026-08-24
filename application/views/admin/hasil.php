<?php
$this->load->view('templates/admin_header', array(
	'pengaturan' => $pengaturan, 'admin' => $admin, 'title' => 'Hasil Survei',
));

$ada_data = $ikm['jumlah_responden'] > 0;
?>

<style>
	.tabel-ikm{ border-collapse:collapse; width:100%; min-width:900px; margin-bottom:0; }
	.tabel-ikm th, .tabel-ikm td{ border:1px solid #dfe4ec; padding:.7rem .6rem; text-align:center; vertical-align:middle; }
	.tabel-ikm thead th{ font-size:.78rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#1c2733; }
	.tabel-ikm .th-penilaian{ background:#6cc48c; min-width:210px; }
	.tabel-ikm .th-grup{ background:#00b0f0; }
	.tabel-ikm .th-unsur{ background:#ffc000; min-width:92px; }
	.tabel-ikm .col-label{ text-align:left; font-size:.92rem; padding-left:1rem; }
	.tabel-ikm tbody tr:nth-child(even) td{ background:#fbfcfe; }
	.tabel-ikm .baris-ringkas td{ font-size:1.05rem; font-weight:700; background:#fff !important; }
	.tabel-ikm .baris-ringkas td.col-label{ font-weight:400; font-size:.92rem; }
	.tabel-ikm .baris-responden td{ font-size:.88rem; }
	.tabel-ikm .baris-responden .col-label a{ color:#1c2733; text-decoration:none; }
	.tabel-ikm .baris-responden .col-label a:hover{ color:var(--skm-primary, #0b3d91); text-decoration:underline; }
	.tabel-ikm .baris-agregat td{ border-top:2px solid #aab7cc; }
</style>

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
		<a href="<?= base_url('admin/hasil') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
	</div>
	<div class="col-auto ms-auto">
		<a class="btn btn-sm btn-success"
			href="<?= base_url('admin/export/hasil') . '?' . http_build_query(array('bulan' => $bulan, 'tahun' => $tahun)) ?>">
			<i class="bi bi-file-earmark-excel"></i> Export Excel
		</a>
	</div>
</form>

<div class="card card-stat">
	<div class="card-header bg-white border-0 pt-3 pb-0 px-4">
		<h5 class="fw-bold mb-3"><i class="bi bi-table me-2"></i>Hasil IKM Berdasarkan Responden</h5>
	</div>
	<div class="card-body px-4 pt-0">
		<div class="alert alert-info d-flex gap-3 align-items-start">
			<i class="bi bi-info-circle-fill fs-5"></i>
			<div>
				<div class="fw-semibold">Informational Notes</div>
				<div class="small">
					Hasil Survei Periode <?= html_escape($periode) ?>
					&mdash; <?= number_format($ikm['jumlah_responden']) ?> responden
				</div>
			</div>
		</div>
	</div>

	<?php if ( ! $ada_data): ?>
		<div class="px-4 pb-4">
			<div class="text-center text-muted py-4">
				<i class="bi bi-inbox fs-1 d-block mb-2"></i>
				Belum ada data responden untuk periode ini.
			</div>
		</div>
	<?php else: ?>
		<div class="table-responsive px-4 pb-4">
			<table class="tabel-ikm">
				<thead>
					<tr>
						<th class="th-penilaian" rowspan="2">Penilaian</th>
						<th class="th-grup" colspan="<?= count($ikm['per_unsur']) ?>">Penilaian Per Unsur</th>
					</tr>
					<tr>
						<?php foreach ($ikm['per_unsur'] as $i => $u): ?>
							<th class="th-unsur" title="<?= html_escape($u['pertanyaan']) ?>">Unsur <?= $i + 1 ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($responden as $no => $r): ?>
						<tr class="baris-responden">
							<td class="col-label">
								<a href="<?= base_url('admin/responden/detail/' . $r['id']) ?>"
									title="<?= html_escape($r['kode_responden'] . ' - ' . tanggal_indo($r['created_at'])) ?>">
									<?= html_escape(responden_label($r['nama'], $r['id'], $r['urutan'] ?? ($no + 1))) ?>
								</a>
							</td>
							<?php foreach ($ikm['per_unsur'] as $u): ?>
								<td><?= isset($r['jawaban'][$u['id']]) ? (int) $r['jawaban'][$u['id']] : '-' ?></td>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
					<tr class="baris-agregat">
						<td class="col-label">Jumlah Nilai Unsur</td>
						<?php foreach ($ikm['per_unsur'] as $u): ?>
							<td><?= number_format($u['jumlah_nilai']) ?></td>
						<?php endforeach; ?>
					</tr>
					<tr>
						<td class="col-label">NRR Per Unsur</td>
						<?php foreach ($ikm['per_unsur'] as $u): ?>
							<td><?= number_format($u['nrr'], 3) ?></td>
						<?php endforeach; ?>
					</tr>
					<tr>
						<td class="col-label">NRR Tertimbang Per Unsur</td>
						<?php foreach ($ikm['per_unsur'] as $u): ?>
							<td><?= number_format($u['nrr_tertimbang'], 3) ?></td>
						<?php endforeach; ?>
					</tr>
					<tr class="baris-ringkas">
						<td class="col-label">Nilai Index</td>
						<td colspan="<?= count($ikm['per_unsur']) ?>"><?= number_format($ikm['nilai_index'], 3) ?></td>
					</tr>
					<tr class="baris-ringkas">
						<td class="col-label">IKM Unit Pelayanan</td>
						<td colspan="<?= count($ikm['per_unsur']) ?>"><?= number_format($ikm['ikm'], 2) ?></td>
					</tr>
					<tr class="baris-ringkas">
						<td class="col-label">Mutu Pelayanan</td>
						<td colspan="<?= count($ikm['per_unsur']) ?>"><?= html_escape($ikm['mutu']['mutu']) ?></td>
					</tr>
					<tr class="baris-ringkas">
						<td class="col-label">Kinerja Unit Pelayanan</td>
						<td colspan="<?= count($ikm['per_unsur']) ?>"><?= html_escape($ikm['mutu']['kinerja']) ?></td>
					</tr>
				</tbody>
			</table>

			<p class="small text-muted mt-3 mb-0">
				<i class="bi bi-lightbulb me-1"></i>
				Setiap baris di bagian atas tabel adalah satu responden &mdash; klik namanya untuk membuka detail jawaban,
				arahkan kursor untuk melihat kode dan waktu pengisian. Arahkan kursor ke judul kolom untuk melihat
				pertanyaan unsur yang bersangkutan.
				Nilai tertimbang memakai bobot <?= number_format(1 / count($ikm['per_unsur']), 3) ?>
				(1 dibagi <?= count($ikm['per_unsur']) ?> unsur) sesuai Permenpan RB No. 14 Tahun 2017.
			</p>
		</div>
	<?php endif; ?>
</div>

<?php $this->load->view('templates/admin_footer'); ?>
