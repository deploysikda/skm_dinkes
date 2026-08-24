<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Ambil data pengaturan instansi (baris pertama)
	 */
	public function get_pengaturan()
	{
		$row = $this->db->get('tb_pengaturan')->row_array();
		if ( ! $row) {
			// fallback default jika tabel kosong, agar aplikasi tidak error
			return array(
				'nama_instansi'    => 'Dinas Kesehatan Kota Bandung',
				'nama_layanan'     => 'eSKM - Survei Kepuasan Masyarakat',
				'alamat'           => '',
				'kepala_instansi'  => '',
				'nip_kepala'       => '',
				'logo'             => '',
				'deskripsi_survei' => '',
			);
		}
		return $row;
	}

	public function update_pengaturan($id, $data)
	{
		$data['updated_at'] = date('Y-m-d H:i:s');
		return $this->db->where('id', $id)->update('tb_pengaturan', $data);
	}
}
