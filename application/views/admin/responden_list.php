<?php
$this->load->view('templates/admin_header', array(
	'pengaturan' => $pengaturan, 'admin' => $admin, 'title' => 'Data Responden',
));
?>

<?php if ($this->session->flashdata('sukses')): ?>
	<div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i> <?= $this->session->flashdata('sukses') ?></div>
<?php endif; ?>

<div class="card card-stat p-3 mb-3">
	<form method="get" class="row g-2 align-items-end">
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
			<a href="<?= base_url('admin/responden') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
		</div>
		<div class="col-auto ms-auto text-muted small">
			Total: <strong><?= number_format($total) ?></strong> responden
		</div>
	</form>
</div>

<div class="card card-stat p-3">
	<div class="table-responsive">
		<table class="table table-hover align-middle mb-0">
			<thead class="table-light">
				<tr>
					<th>Kode</th>
					<th>Nama</th>
					<th>JK</th>
					<th>Usia</th>
					<th>Unit Layanan</th>
					<th>Tanggal Mengisi</th>
					<th class="text-end">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php if (empty($responden_list)): ?>
					<tr><td colspan="7" class="text-center text-muted py-4">Belum ada data responden.</td></tr>
				<?php endif; ?>
				<?php foreach ($responden_list as $r): ?>
				<tr>
					<td><span class="badge bg-light text-dark border"><?= html_escape($r['kode_responden']) ?></span></td>
					<td><?= html_escape(responden_label($r['nama'], $r['id'], $r['urutan'] ?? null)) ?></td>
					<td><?= label_jenis_kelamin($r['jenis_kelamin']) === 'Laki-laki' ? 'L' : 'P' ?></td>
					<td><?= (int) $r['usia'] ?></td>
					<td><?= html_escape($r['unit_layanan']) ?></td>
					<td class="small text-muted"><?= tanggal_indo($r['created_at']) ?></td>
					<td class="text-end">
						<a href="<?= base_url('admin/responden/detail/' . $r['id']) ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
						<a href="<?= base_url('admin/responden/hapus/' . $r['id']) ?>" class="btn btn-sm btn-outline-danger"
							onclick="return confirm('Hapus data responden <?= html_escape($r['kode_responden']) ?>? Tindakan ini tidak dapat dibatalkan.');">
							<i class="bi bi-trash"></i>
						</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="mt-3">
		<?= $pagination ?>
	</div>
</div>

<?php $this->load->view('templates/admin_footer', array('pengaturan' => $pengaturan)); ?>
