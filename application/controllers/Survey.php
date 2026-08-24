<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'core/MY_Controller.php';

class Survey extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Survey_model');
	}

	/**
	 * Halaman utama: intro + form kuesioner SKM
	 */
	public function index()
	{
		$data['pengaturan'] = $this->pengaturan;
		$data['unsur_list'] = $this->Survey_model->get_unsur_dengan_pilihan();
		$data['pendidikan_list'] = $this->daftar_pendidikan();
		$data['old'] = $this->session->flashdata('old_input');
		$data['errors'] = $this->session->flashdata('errors');

		$this->load->view('survey/form', $data);
	}

	/**
	 * Kembalikan NULL bila isian kosong, selain itu nilainya yang sudah dirapikan.
	 *
	 * @param string $nilai
	 * @param bool   $angka  TRUE bila nilainya harus disimpan sebagai integer
	 */
	protected function isi_atau_null($nilai, $angka = FALSE)
	{
		$nilai = trim((string) $nilai);

		if ($nilai === '') return NULL;

		return $angka ? (int) $nilai : $nilai;
	}

	protected function daftar_pendidikan()
	{
		return array(
			'SD ke bawah', 'SLTP/Sederajat', 'SLTA/Sederajat',
			'D1/D2/D3', 'S1', 'S2 ke atas',
		);
	}

	/**
	 * Proses simpan jawaban survei
	 */
	public function submit()
	{
		if ($this->input->method() !== 'post') {
			redirect('survei');
			return;
		}

		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'in_list[L,P]');
		$this->form_validation->set_rules('usia', 'Usia', 'numeric|greater_than[0]|less_than[120]');
		$this->form_validation->set_rules('pendidikan', 'Pendidikan Terakhir', 'max_length[50]');
		$this->form_validation->set_rules('pekerjaan', 'Pekerjaan', 'max_length[100]');
		$this->form_validation->set_rules('unit_layanan', 'Unit/Jenis Layanan yang Diterima', 'max_length[150]');
		$this->form_validation->set_rules('nama', 'Nama', 'max_length[150]');
		$this->form_validation->set_rules('saran', 'Saran/Masukan', 'max_length[1000]');

		$unsur_list = $this->Survey_model->get_unsur_list();
		foreach ($unsur_list as $unsur) {
			$this->form_validation->set_rules(
				'jawaban[' . $unsur['id'] . ']',
				$unsur['kode'],
				'required|integer|in_list[1,2,3,4]'
			);
		}

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('old_input', $this->input->post());
			redirect('survei');
			return;
		}

		// Seluruh biodata bersifat opsional. Isian kosong disimpan sebagai NULL,
		// bukan string kosong / angka 0, agar tidak terbaca sebagai jawaban asli
		// (kolom jenis_kelamin juga bertipe ENUM yang menolak string kosong).
		$biodata = array(
			'nama'         => $this->isi_atau_null($this->input->post('nama', TRUE)),
			'jenis_kelamin'=> $this->isi_atau_null($this->input->post('jenis_kelamin', TRUE)),
			'usia'         => $this->isi_atau_null($this->input->post('usia', TRUE), TRUE),
			'pendidikan'   => $this->isi_atau_null($this->input->post('pendidikan', TRUE)),
			'pekerjaan'    => $this->isi_atau_null($this->input->post('pekerjaan', TRUE)),
			'unit_layanan' => $this->isi_atau_null($this->input->post('unit_layanan', TRUE)),
			'saran'        => $this->isi_atau_null($this->input->post('saran', TRUE)),
		);

		$jawaban = $this->input->post('jawaban', TRUE); // [unsur_id => nilai]

		$kode = $this->Survey_model->simpan_survei($biodata, $jawaban);

		if ($kode === false) {
			$this->session->set_flashdata('errors', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
			$this->session->set_flashdata('old_input', $this->input->post());
			redirect('survei');
			return;
		}

		$this->session->set_flashdata('kode_responden', $kode);
		redirect('survei/terima-kasih');
	}

	public function thanks()
	{
		$data['pengaturan'] = $this->pengaturan;
		$data['kode'] = $this->session->flashdata('kode_responden');
		if (empty($data['kode'])) {
			redirect('survei');
			return;
		}
		$this->load->view('survey/thanks', $data);
	}
}
