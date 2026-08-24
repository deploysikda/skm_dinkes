<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';

/**
 * Rekap hasil IKM dalam bentuk tabel Permenpan RB No. 14 Tahun 2017
 */
class Hasil extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Ikm_model');
	}

	/**
	 * @param int|null $bulan
	 * @param int|null $tahun
	 */
	public function index($bulan = null, $tahun = null)
	{
		$bulan = $bulan ?: $this->input->get('bulan');
		$tahun = $tahun ?: $this->input->get('tahun');

		$data['admin']      = $this->admin;
		$data['pengaturan'] = $this->pengaturan;
		$data['bulan']      = $bulan;
		$data['tahun']      = $tahun;
		$data['bulan_list'] = bulan_indo();
		$data['tahun_list'] = $this->Ikm_model->daftar_tahun();
		$data['periode']    = $this->label_periode($bulan, $tahun);

		$data['ikm'] = $this->Ikm_model->hitung_ikm($bulan, $tahun);
		$data['responden'] = $this->Ikm_model->matriks_responden($bulan, $tahun);

		$this->load->view('admin/hasil', $data);
	}

	/**
	 * Teks periode untuk catatan di atas tabel.
	 */
	protected function label_periode($bulan, $tahun)
	{
		$nama_bulan = ! empty($bulan) ? bulan_indo((int) $bulan) : null;

		if ($nama_bulan && ! empty($tahun)) return $nama_bulan . ' ' . $tahun;
		if ($nama_bulan)                    return $nama_bulan . ' (Semua Tahun)';
		if ( ! empty($tahun))               return 'Tahun ' . $tahun;

		return 'Seluruh Periode';
	}
}
