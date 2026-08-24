<?php
$jml_unsur = count($ikm['per_unsur']);

if ( ! empty($bulan) && ! empty($tahun))      $periode = bulan_indo($bulan) . ' ' . $tahun;
elseif ( ! empty($bulan))                     $periode = bulan_indo($bulan) . ' (Semua Tahun)';
elseif ( ! empty($tahun))                     $periode = 'Tahun ' . $tahun;
else                                          $periode = 'Seluruh Periode';
?>
<html>
<head><meta charset="UTF-8"></head>
<body>
<table border="1" cellspacing="0">
	<tr><td colspan="<?= $jml_unsur + 1 ?>" style="font-weight:bold;font-size:14px;"><?= html_escape($pengaturan['nama_instansi']) ?></td></tr>
	<tr><td colspan="<?= $jml_unsur + 1 ?>" style="font-weight:bold;">Hasil IKM Berdasarkan Responden</td></tr>
	<tr><td colspan="<?= $jml_unsur + 1 ?>">Periode: <?= html_escape($periode) ?> - <?= (int) $ikm['jumlah_responden'] ?> responden</td></tr>
	<tr><td colspan="<?= $jml_unsur + 1 ?>"></td></tr>

	<tr style="font-weight:bold;">
		<td rowspan="2" style="background:#6cc48c;text-align:center;vertical-align:middle;">PENILAIAN</td>
		<td colspan="<?= $jml_unsur ?>" style="background:#00b0f0;text-align:center;">PENILAIAN PER UNSUR</td>
	</tr>
	<tr style="font-weight:bold;">
		<?php foreach ($ikm['per_unsur'] as $i => $u): ?>
			<td style="background:#ffc000;text-align:center;">UNSUR <?= $i + 1 ?></td>
		<?php endforeach; ?>
	</tr>

	<?php foreach ($responden as $no => $r): ?>
		<tr>
			<td><?= html_escape(responden_label($r['nama'], $r['id'], $r['urutan'] ?? ($no + 1))) ?> (<?= html_escape($r['kode_responden']) ?>)</td>
			<?php foreach ($ikm['per_unsur'] as $u): ?>
				<td style="text-align:center;"><?= isset($r['jawaban'][$u['id']]) ? (int) $r['jawaban'][$u['id']] : '-' ?></td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>

	<tr style="font-weight:bold;">
		<td>Jumlah Nilai Unsur</td>
		<?php foreach ($ikm['per_unsur'] as $u): ?>
			<td style="text-align:center;"><?= (int) $u['jumlah_nilai'] ?></td>
		<?php endforeach; ?>
	</tr>
	<tr style="font-weight:bold;">
		<td>NRR Per Unsur</td>
		<?php foreach ($ikm['per_unsur'] as $u): ?>
			<td style="text-align:center;"><?= number_format($u['nrr'], 3) ?></td>
		<?php endforeach; ?>
	</tr>
	<tr style="font-weight:bold;">
		<td>NRR Tertimbang Per Unsur</td>
		<?php foreach ($ikm['per_unsur'] as $u): ?>
			<td style="text-align:center;"><?= number_format($u['nrr_tertimbang'], 3) ?></td>
		<?php endforeach; ?>
	</tr>
	<tr style="font-weight:bold;">
		<td>Nilai Index</td>
		<td colspan="<?= $jml_unsur ?>" style="text-align:center;"><?= number_format($ikm['nilai_index'], 3) ?></td>
	</tr>
	<tr style="font-weight:bold;">
		<td>IKM Unit Pelayanan</td>
		<td colspan="<?= $jml_unsur ?>" style="text-align:center;"><?= number_format($ikm['ikm'], 2) ?></td>
	</tr>
	<tr style="font-weight:bold;">
		<td>Mutu Pelayanan</td>
		<td colspan="<?= $jml_unsur ?>" style="text-align:center;"><?= html_escape($ikm['mutu']['mutu']) ?></td>
	</tr>
	<tr style="font-weight:bold;">
		<td>Kinerja Unit Pelayanan</td>
		<td colspan="<?= $jml_unsur ?>" style="text-align:center;"><?= html_escape($ikm['mutu']['kinerja']) ?></td>
	</tr>

	<tr><td colspan="<?= $jml_unsur + 1 ?>"></td></tr>
	<tr><td colspan="<?= $jml_unsur + 1 ?>" style="font-weight:bold;">Keterangan Unsur</td></tr>
	<?php foreach ($ikm['per_unsur'] as $i => $u): ?>
		<tr>
			<td>Unsur <?= $i + 1 ?> (<?= html_escape($u['kode']) ?>)</td>
			<td colspan="<?= $jml_unsur ?>"><?= html_escape($u['pertanyaan']) ?></td>
		</tr>
	<?php endforeach; ?>
</table>
</body>
</html>
