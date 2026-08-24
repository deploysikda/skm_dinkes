<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Pengaturan_model');
	}

	public function login()
	{
		// jika sudah login, langsung ke dashboard
		if ($this->session->userdata('admin_id')) {
			redirect('admin/dashboard');
			return;
		}

		if ($this->input->method() === 'post') {
			$this->form_validation->set_rules('username', 'Username', 'required|trim');
			$this->form_validation->set_rules('password', 'Password', 'required');

			if ($this->form_validation->run() !== FALSE) {
				$admin = $this->Admin_model->verifikasi_login(
					$this->input->post('username', TRUE),
					$this->input->post('password', TRUE)
				);

				if ($admin) {
					$this->session->set_userdata(array(
						'admin_id'       => $admin['id'],
						'admin_username' => $admin['username'],
						'admin_nama'     => $admin['nama_lengkap'],
					));
					redirect('admin/dashboard');
					return;
				}

				$this->session->set_flashdata('login_error', 'Username atau password salah.');
			} else {
				$this->session->set_flashdata('login_error', validation_errors());
			}

			redirect('admin/login');
			return;
		}

		$data['pengaturan'] = $this->Pengaturan_model->get_pengaturan();
		$data['error'] = $this->session->flashdata('login_error');
		$this->load->view('admin/login', $data);
	}

	public function logout()
	{
		$this->session->unset_userdata(array('admin_id', 'admin_username', 'admin_nama'));
		$this->session->sess_destroy();
		redirect('admin/login');
	}
}
