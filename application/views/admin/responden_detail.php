<?php
$this->load->view('templates/admin_header', array(
	'pengaturan' => $pengaturan, 'admin' => $admin, 'title' => 'Detail Responden',
));
?>

<a href="<?= base_url('admin/responden') ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>

<div class="row g-3">
	<div class="col-lg-4">
		<div class="card card-stat p-3">
			<h6 class="mb-3"><i class="bi bi-person-vcard text-primary me-1"></i> Data Responden</h6>
			<table class="table table-sm table-borderless mb-0">
				<tr><td class="text-muted" style="width:45%;">Kode Responden</td><td class="fw-semibold"><?= html_escape($responden['kode_responden']) ?></td></tr>
				<tr><td class="text-muted">Nama</td><td><?= ($responden['nama'] !== null && $responden['nama'] !== '') ? html_escape($responden['nama']) : '' ?></td></tr>
				<tr><td class="text-muted">Jenis Kelamin</td><td><?= label_jenis_kelamin($responden['jenis_kelamin']) ?></td></tr>
				<tr><td class="text-muted">Usia</td><td><?= (int) $responden['usia'] ?> tahun</td></tr>
				<tr><td class="text-muted">Pendidikan</td><td><?= html_escape($responden['pendidikan']) ?></td></tr>
				<tr><td class="text-muted">Pekerjaan</td><td><?= html_escape($responden['pekerjaan']) ?></td></tr>
				<tr><td class="text-muted">Unit Layanan</td><td><?= html_escape($responden['unit_layanan']) ?></td></tr>
				<tr><td class="text-muted">Waktu Mengisi</td><td><?= tanggal_indo($responden['created_at']) ?></td></tr>
				<tr><td class="text-muted">Alamat IP</td><td class="small text-muted"><?= html_escape($responden['ip_address']) ?></td></tr>
			</table>

			<?php if ( ! empty($responden['saran'])): ?>
				<hr>
				<h6 class="mb-2"><i class="bi bi-chat-left-text text-primary me-1"></i> Saran / Masukan</h6>
				<p class="small mb-0"><?= nl2br(html_escape($responden['saran'])) ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="col-lg-8">
		<div class="card card-stat p-3">
			<h6 class="mb-3"><i class="bi bi-list-check text-primary me-1"></i> Jawaban Kuesioner</h6>
			<div class="table-responsive">
				<table class="table table-sm table-bordered align-middle mb-0">
					<thead class="table-light">
						<tr>
							<th style="width:60px;">Kode</th>
							<th>Unsur Pelayanan</th>
							<th class="text-center" style="width:140px;">Jawaban</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($responden['jawaban'] as $j): ?>
						<tr>
							<td class="text-center fw-semibold"><?= html_escape($j['kode']) ?></td>
							<td><?= html_escape($j['pertanyaan']) ?></td>
							<td class="text-center">
								<span class="badge bg-primary"><?= (int) $j['nilai'] ?></span>
								<?= html_escape($j['label']) ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('templates/admin_footer', array('pengaturan' => $pengaturan)); ?>
