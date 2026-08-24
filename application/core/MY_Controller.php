<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base controller publik (form survei, dsb.)
 */
class Public_Controller extends CI_Controller
{
	public $pengaturan;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Pengaturan_model');
		$this->pengaturan = $this->Pengaturan_model->get_pengaturan();
	}
}

/**
 * Base controller untuk seluruh halaman admin.
 * Memastikan admin sudah login sebelum mengakses controller turunan ini.
 */
class Admin_Controller extends CI_Controller
{
	public $admin;
	public $pengaturan;

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Pengaturan_model');
		$this->pengaturan = $this->Pengaturan_model->get_pengaturan();

		if ( ! $this->session->userdata('admin_id')) {
			redirect('admin/login');
			return;
		}

		$this->admin = array(
			'id'           => $this->session->userdata('admin_id'),
			'username'     => $this->session->userdata('admin_username'),
			'nama_lengkap' => $this->session->userdata('admin_nama'),
		);
	}
}
