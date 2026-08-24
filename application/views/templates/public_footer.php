<footer class="footer-skm">
	<div class="container">
		<div class="row gy-3">
			<div class="col-md-6">
				<h6 class="text-white mb-2">
					<i class="bi bi-clipboard2-pulse me-1"></i>
					eSKM &mdash; <?= isset($pengaturan['nama_instansi']) ? html_escape($pengaturan['nama_instansi']) : '' ?>
				</h6>
				<p class="mb-0 small"><?= isset($pengaturan['alamat']) ? html_escape($pengaturan['alamat']) : '' ?></p>
			</div>
			<div class="col-md-6 text-md-end small d-flex align-items-center justify-content-md-end">
				<span>&copy; <?= date('Y') ?> Survei Kepuasan Masyarakat. Dibangun sesuai Permenpan RB No. 14 Tahun 2017.</span>
			</div>
		</div>
	</div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
