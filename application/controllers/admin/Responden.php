<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';

class Responden extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Ikm_model');
		$this->load->library('pagination');
	}

	public function index()
	{
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');
		$page  = (int) $this->input->get('page');
		$page  = $page > 0 ? $page : 1;
		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		$total = $this->Ikm_model->count_responden_filtered($bulan, $tahun);

		$config['base_url']   = base_url('admin/responden');
		$config['total_rows'] = $total;
		$config['per_page']   = $per_page;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['num_links'] = 3;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_link'] = 'Awal';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_link'] = 'Akhir';
		$config['last_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_link'] = 'Berikutnya';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_link'] = 'Sebelumnya';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['attributes'] = array('class' => 'page-link');

		$this->pagination->initialize($config);

		$data['admin']       = $this->admin;
		$data['pengaturan']  = $this->pengaturan;
		$data['responden_list'] = $this->Ikm_model->get_responden_list($per_page, $offset, $bulan, $tahun);
		$data['pagination']  = $this->pagination->create_links();
		$data['total']       = $total;
		$data['bulan']       = $bulan;
		$data['tahun']       = $tahun;
		$data['bulan_list']  = bulan_indo();
		$data['tahun_list']  = $this->Ikm_model->daftar_tahun();

		$this->load->view('admin/responden_list', $data);
	}

	public function detail($id)
	{
		$responden = $this->Ikm_model->get_responden_detail($id);
		if ( ! $responden) {
			show_404();
			return;
		}

		$data['admin']      = $this->admin;
		$data['pengaturan'] = $this->pengaturan;
		$data['responden']  = $responden;

		$this->load->view('admin/responden_detail', $data);
	}

	public function hapus($id)
	{
		$this->Ikm_model->hapus_responden($id);
		$this->session->set_flashdata('sukses', 'Data responden berhasil dihapus.');
		redirect('admin/responden');
	}
}
