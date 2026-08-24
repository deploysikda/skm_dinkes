<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Verifikasi login admin. Return data admin (tanpa password) jika valid, null jika tidak.
	 */
	public function verifikasi_login($username, $password)
	{
		$admin = $this->db
			->where('username', $username)
			->where('is_active', 1)
			->get('tb_admin')
			->row_array();

		if ($admin && password_verify($password, $admin['password'])) {
			unset($admin['password']);
			return $admin;
		}
		return null;
	}

	public function get_by_id($id)
	{
		$row = $this->db->where('id', $id)->get('tb_admin')->row_array();
		if ($row) unset($row['password']);
		return $row;
	}

	public function update_password($id, $password_baru)
	{
		return $this->db->where('id', $id)->update('tb_admin', array(
			'password' => password_hash($password_baru, PASSWORD_BCRYPT),
		));
	}
}
