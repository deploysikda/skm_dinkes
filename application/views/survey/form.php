<?php $this->load->view('templates/public_header', array('pengaturan' => $pengaturan)); ?>

<section class="hero-skm">
	<div class="container text-center">
		<span class="step-pill mb-3 d-inline-block"><i class="bi bi-patch-check-fill"></i> Survei Kepuasan Masyarakat</span>
		<h1 class="mt-2"><?= html_escape($pengaturan['nama_layanan']) ?></h1>
		<p class="mb-0 col-lg-8 mx-auto"><?= html_escape($pengaturan['nama_instansi']) ?></p>
	</div>
</section>

<div class="container">
	<div class="card card-form">
		<div class="card-header">
			<h5 class="mb-2"><i class="bi bi-info-circle text-primary me-1"></i> Petunjuk Pengisian</h5>
			<p class="text-muted mb-0 small">
				<?= isset($pengaturan['deskripsi_survei']) ? nl2br(html_escape($pengaturan['deskripsi_survei'])) : 'Mohon isi survei berikut dengan jujur berdasarkan pengalaman pelayanan yang Anda terima.' ?>
			</p>
		</div>

		<div class="card-body p-4 p-md-5">

			<?php if ( ! empty($errors)): ?>
				<div class="alert alert-danger">
					<i class="bi bi-exclamation-triangle-fill me-1"></i>
					<?= $errors ?>
				</div>
			<?php endif; ?>

			<?= form_open('survei/kirim', array('id' => 'formSurvei')) ?>

				<h6 class="text-uppercase text-primary fw-bold mb-3"><i class="bi bi-person-vcard me-1"></i> Data Responden</h6>
				<div class="row g-3 mb-4">
					<div class="col-md-6">
						<label class="form-label">Nama <span class="text-muted">(opsional)</span></label>
						<input type="text" name="nama" maxlength="150" class="form-control"
							value="<?= isset($old['nama']) ? html_escape($old['nama']) : '' ?>"
							placeholder="Nama lengkap">
					</div>
					<div class="col-md-6">
						<label class="form-label">Jenis Kelamin <span class="text-muted">(opsional)</span></label>
						<div class="d-flex gap-4 pt-2">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_kelamin" id="jkL" value="L"
									<?= (isset($old['jenis_kelamin']) && $old['jenis_kelamin'] === 'L') ? 'checked' : '' ?>>
								<label class="form-check-label" for="jkL">Laki-laki</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="jenis_kelamin" id="jkP" value="P"
									<?= (isset($old['jenis_kelamin']) && $old['jenis_kelamin'] === 'P') ? 'checked' : '' ?>>
								<label class="form-check-label" for="jkP">Perempuan</label>
							</div>
						</div>
					</div>

					<div class="col-md-4">
						<label class="form-label">Usia <span class="text-muted">(opsional)</span></label>
						<input type="number" name="usia" min="1" max="119" class="form-control"
							value="<?= isset($old['usia']) ? html_escape($old['usia']) : '' ?>" placeholder="Contoh: 30">
					</div>
					<div class="col-md-4">
						<label class="form-label">Pendidikan Terakhir <span class="text-muted">(opsional)</span></label>
						<select name="pendidikan" class="form-select">
							<option value="">Pilih pendidikan</option>
							<?php foreach ($pendidikan_list as $p): ?>
								<option value="<?= html_escape($p) ?>" <?= (isset($old['pendidikan']) && $old['pendidikan'] === $p) ? 'selected' : '' ?>><?= html_escape($p) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="col-md-4">
						<label class="form-label">Pekerjaan <span class="text-muted">(opsional)</span></label>
						<input type="text" name="pekerjaan" maxlength="100" class="form-control"
							value="<?= isset($old['pekerjaan']) ? html_escape($old['pekerjaan']) : '' ?>" placeholder="Contoh: Wiraswasta">
					</div>

					<div class="col-12">
						<label class="form-label">Unit / Jenis Layanan yang Diterima <span class="text-muted">(opsional)</span></label>
						<input type="text" name="unit_layanan" maxlength="150" class="form-control"
							value="<?= isset($old['unit_layanan']) ? html_escape($old['unit_layanan']) : '' ?>"
							placeholder="Contoh: Pelayanan Puskesmas, Perizinan Praktik Kesehatan, dll.">
					</div>
				</div>

				<hr class="my-4">

				<h6 class="text-uppercase text-primary fw-bold mb-3"><i class="bi bi-list-check me-1"></i> Penilaian Unsur Pelayanan</h6>

				<?php foreach ($unsur_list as $i => $unsur): ?>
					<div class="unsur-block">
						<div class="d-flex align-items-start">
							<span class="badge-unsur"><?= $i + 1 ?></span>
							<div class="flex-grow-1">
								<label class="form-label fw-semibold mb-0"><?= html_escape($unsur['pertanyaan']) ?> <span class="text-danger">*</span></label>
								<div class="opsi-jawaban">
									<?php foreach ($unsur['pilihan'] as $pilihan): ?>
										<?php $rid = 'jwb-' . $unsur['id'] . '-' . $pilihan['nilai']; ?>
										<div class="form-check opsi-item">
											<input class="form-check-input" type="radio" required
												name="jawaban[<?= $unsur['id'] ?>]"
												id="<?= $rid ?>"
												value="<?= $pilihan['nilai'] ?>"
												<?= (isset($old['jawaban'][$unsur['id']]) && (int) $old['jawaban'][$unsur['id']] === (int) $pilihan['nilai']) ? 'checked' : '' ?>>
											<label class="form-check-label" for="<?= $rid ?>">
												<span class="opsi-nilai"><?= $pilihan['nilai'] ?></span>
												<?= html_escape($pilihan['label']) ?>
											</label>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="mb-4">
					<label class="form-label fw-semibold">Saran / Masukan <span class="text-muted">(opsional)</span></label>
					<textarea name="saran" rows="3" maxlength="1000" class="form-control"
						placeholder="Tuliskan saran atau masukan Anda untuk peningkatan pelayanan kami"><?= isset($old['saran']) ? html_escape($old['saran']) : '' ?></textarea>
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-skm-primary btn-lg text-white">
						<i class="bi bi-send-check me-1"></i> Kirim Survei
					</button>
				</div>
			<?= form_close() ?>
		</div>
	</div>
</div>

<?php $this->load->view('templates/public_footer', array('pengaturan' => $pengaturan)); ?>
