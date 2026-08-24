<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Survey_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Ambil daftar 9 unsur pelayanan beserta pilihan jawabannya (urut sesuai kolom urutan)
	 */
	public function get_unsur_dengan_pilihan()
	{
		$unsur_list = $this->db->order_by('urutan', 'ASC')->get('tb_unsur')->result_array();

		foreach ($unsur_list as &$unsur) {
			$unsur['pilihan'] = $this->db
				->where('unsur_id', $unsur['id'])
				->order_by('nilai', 'ASC')
				->get('tb_unsur_pilihan')
				->result_array();
		}
		return $unsur_list;
	}

	public function get_unsur_list()
	{
		return $this->db->order_by('urutan', 'ASC')->get('tb_unsur')->result_array();
	}

	/**
	 * Simpan data responden + jawaban dalam satu transaksi DB
	 *
	 * @param array $biodata  data responden (nama, jenis_kelamin, usia, pendidikan, pekerjaan, unit_layanan, saran)
	 * @param array $jawaban  associative array [unsur_id => nilai]
	 * @return string|false   kode_responden jika sukses, false jika gagal
	 */
	public function simpan_survei($biodata, $jawaban)
	{
		$this->db->trans_begin();

		$kode = generate_kode_responden();

		$biodata['kode_responden'] = $kode;
		$biodata['ip_address']     = $this->input->ip_address();
		$biodata['created_at']     = date('Y-m-d H:i:s');

		$this->db->insert('tb_responden', $biodata);
		$responden_id = $this->db->insert_id();

		if ($responden_id) {
			$rows = array();
			foreach ($jawaban as $unsur_id => $nilai) {
				$rows[] = array(
					'responden_id' => $responden_id,
					'unsur_id'     => (int) $unsur_id,
					'nilai'        => (int) $nilai,
				);
			}
			if ( ! empty($rows)) {
				$this->db->insert_batch('tb_jawaban', $rows);
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();
		return $kode;
	}
}
