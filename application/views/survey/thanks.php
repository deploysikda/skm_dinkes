<?php $this->load->view('templates/public_header', array('pengaturan' => $pengaturan)); ?>

<section class="hero-skm">
	<div class="container text-center">
		<span class="step-pill mb-3 d-inline-block"><i class="bi bi-check-circle-fill"></i> Survei Terkirim</span>
		<h1 class="mt-2">Terima Kasih atas Partisipasi Anda</h1>
		<p class="mb-0 col-lg-8 mx-auto">Jawaban Anda sangat berarti bagi kami untuk terus meningkatkan kualitas pelayanan.</p>
	</div>
</section>

<div class="container">
	<div class="card card-form">
		<div class="card-body p-4 p-md-5 text-center">
			<i class="bi bi-patch-check-fill text-success" style="font-size:4rem;"></i>
			<h4 class="mt-3">Survei Anda Berhasil Disimpan</h4>
			<p class="text-muted">Nomor bukti pengisian Anda:</p>
			<div class="d-inline-block bg-light border rounded-3 px-4 py-2 fw-bold fs-5 mb-4" style="letter-spacing:1px;">
				<?= html_escape($kode) ?>
			</div>
			<div>
				<a href="<?= base_url('survei') ?>" class="btn btn-skm-primary text-white px-4">
					<i class="bi bi-arrow-repeat me-1"></i> Isi Survei Lain
				</a>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('templates/public_footer', array('pengaturan' => $pengaturan)); ?>
