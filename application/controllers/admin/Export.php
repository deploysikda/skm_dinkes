<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';

class Export extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Ikm_model');
	}

	/**
	 * Laporan IKM. Ditampilkan sebagai halaman web dulu; pengguna yang
	 * menekan tombol "Cetak" akan mendapat versi bersih lewat @media print
	 * (Print > Save as PDF pada browser).
	 */
	public function pdf()
	{
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');

		$ikm = $this->Ikm_model->hitung_ikm($bulan, $tahun);

		$data['admin']          = $this->admin;
		$data['ikm']            = $ikm;
		$data['pengaturan']     = $this->pengaturan;
		$data['bulan']          = $bulan;
		$data['tahun']          = $tahun;
		$data['bulan_list']     = bulan_indo();
		$data['tahun_list']     = $this->Ikm_model->daftar_tahun();
		$data['bulan_nama']     = $bulan ? bulan_indo($bulan) : 'Semua Bulan';
		$data['tanggal_cetak']  = tanggal_indo(date('Y-m-d H:i:s'));
		$data['demografi']      = $this->Ikm_model->statistik_demografi($bulan, $tahun);
		$data['pendidikan_list'] = array('SD ke bawah', 'SLTP/Sederajat', 'SLTA/Sederajat', 'D1/D2/D3', 'S1', 'S2 ke atas');
		$data['periode']        = $this->label_periode($bulan, $tahun);
		$data['prioritas']      = $this->prioritas_unsur($ikm['per_unsur']);

		$this->load->view('admin/export_pdf', $data);
	}

	/**
	 * Teks periode: "SEMESTER : 1 - TAHUN : 2026 - TRIWULAN : 2"
	 */
	protected function label_periode($bulan, $tahun)
	{
		$bagian = array();

		if ( ! empty($bulan)) {
			$bulan = (int) $bulan;
			$bagian[] = 'SEMESTER : ' . ($bulan <= 6 ? 1 : 2);
			$bagian[] = 'TAHUN : ' . ( ! empty($tahun) ? $tahun : date('Y'));
			$bagian[] = 'TRIWULAN : ' . (int) ceil($bulan / 3);
			$bagian[] = 'BULAN : ' . strtoupper(bulan_indo($bulan));
		} elseif ( ! empty($tahun)) {
			$bagian[] = 'TAHUN : ' . $tahun;
		} else {
			$bagian[] = 'SELURUH PERIODE';
		}

		return implode(' - ', $bagian);
	}

	/**
	 * Dua unsur dengan nilai terendah (perlu ditingkatkan) dan dua tertinggi
	 * (perlu dipertahankan), untuk bagian "Prioritas Peningkatan Kualitas".
	 */
	protected function prioritas_unsur($per_unsur)
	{
		$nilai = array();
		foreach ($per_unsur as $u) {
			$nilai[] = array('kode' => $u['kode'], 'nrr' => $u['nrr']);
		}

		usort($nilai, function ($a, $b) {
			if ($a['nrr'] == $b['nrr']) return 0;
			return ($a['nrr'] < $b['nrr']) ? -1 : 1;
		});

		$terendah = array_slice($nilai, 0, 2);
		$tertinggi = array_reverse(array_slice($nilai, -2));

		$kode = function ($rows) {
			$out = array();
			foreach ($rows as $r) $out[] = $r['kode'];
			return implode(', ', $out);
		};

		return array(
			'terendah'      => $kode($terendah),
			'tertinggi'     => $kode($tertinggi),
			'nilai_min'     => $nilai ? $nilai[0]['nrr'] : 0,
			'nilai_max'     => $nilai ? $nilai[count($nilai) - 1]['nrr'] : 0,
		);
	}


	/**
	 * Export tabel "Hasil IKM Berdasarkan Responden" (halaman admin/hasil)
	 * ke Excel, memakai susunan kolom yang sama persis dengan tampilan web.
	 */
	public function hasil()
	{
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');

		$data['ikm']        = $this->Ikm_model->hitung_ikm($bulan, $tahun);
		$data['responden']  = $this->Ikm_model->matriks_responden($bulan, $tahun);
		$data['pengaturan'] = $this->pengaturan;
		$data['bulan']      = $bulan;
		$data['tahun']      = $tahun;

		$filename = 'hasil-ikm-' . date('Y-m-d_His') . '.xls';

		header('Content-Type: application/vnd.ms-excel; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		header('Pragma: public');

		echo "\xEF\xBB\xBF"; // BOM agar karakter UTF-8 tampil benar di Excel
		$this->load->view('admin/export_hasil', $data);
	}
}
