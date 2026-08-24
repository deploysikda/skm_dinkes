<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';

class Dashboard extends Admin_Controller
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

		$data['ikm']        = $this->Ikm_model->hitung_ikm($bulan, $tahun);
		$data['demografi']  = $this->Ikm_model->statistik_demografi($bulan, $tahun);

		$this->load->view('admin/dashboard', $data);
	}
}
