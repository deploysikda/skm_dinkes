<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ikm_model extends CI_Model
{
	// Bobot nilai rata-rata tertimbang = 1 / jumlah unsur (9 unsur -> 0.111)
	const JUMLAH_UNSUR = 9;

	public function __construct()
	{
		parent::__construct();
	}

	protected function bobot()
	{
		return 1 / self::JUMLAH_UNSUR;
	}

	/**
	 * Terapkan filter periode (bulan/tahun) pada query builder aktif.
	 * Filter dikenakan pada tb_responden.created_at
	 */
	protected function terapkan_filter_periode($bulan = null, $tahun = null)
	{
		if ( ! empty($tahun)) {
			$this->db->where('YEAR(tb_responden.created_at)', (int) $tahun);
		}
		if ( ! empty($bulan)) {
			$this->db->where('MONTH(tb_responden.created_at)', (int) $bulan);
		}
	}

	/**
	 * Hitung IKM per unsur + IKM total untuk periode tertentu.
	 * Return array:
	 *  [
	 *    'jumlah_responden' => int,
	 *    'per_unsur' => [ ['kode'=>'U1','pertanyaan'=>..,'nrr'=>3.2,'nrr_tertimbang'=>0.355], ... ],
	 *    'ikm' => 81.42,
	 *    'mutu' => ['mutu'=>'B','kinerja'=>'Baik']
	 *  ]
	 */
	public function hitung_ikm($bulan = null, $tahun = null)
	{
		$unsur_list = $this->db->order_by('urutan', 'ASC')->get('tb_unsur')->result_array();

		$jumlah_responden = $this->jumlah_responden($bulan, $tahun);

		$per_unsur   = array();
		$total_tertimbang = 0;

		foreach ($unsur_list as $unsur) {
			$this->db->select('AVG(tb_jawaban.nilai) AS rata2, SUM(tb_jawaban.nilai) AS total, COUNT(tb_jawaban.id) AS jml');
			$this->db->from('tb_jawaban');
			$this->db->join('tb_responden', 'tb_responden.id = tb_jawaban.responden_id');
			$this->db->where('tb_jawaban.unsur_id', $unsur['id']);
			$this->terapkan_filter_periode($bulan, $tahun);
			$row = $this->db->get()->row_array();

			$nrr = $row && $row['rata2'] !== null ? (float) $row['rata2'] : 0;
			$nrr_tertimbang = $nrr * $this->bobot();
			$total_tertimbang += $nrr_tertimbang;

			$per_unsur[] = array(
				'id'             => $unsur['id'],
				'kode'           => $unsur['kode'],
				'pertanyaan'     => $unsur['pertanyaan'],
				'jumlah_nilai'   => $row ? (int) $row['total'] : 0,
				'nrr'            => round($nrr, 3),
				'nrr_tertimbang' => round($nrr_tertimbang, 3),
			);
		}

		$ikm = round($total_tertimbang * 25, 2);
		$mutu = mutu_ikm($ikm);

		return array(
			'jumlah_responden' => $jumlah_responden,
			'per_unsur'        => $per_unsur,
			'nilai_index'      => round($total_tertimbang, 3),
			'ikm'              => $ikm,
			'mutu'             => $mutu,
		);
	}

	/**
	 * Matriks jawaban per responden untuk tabel "Hasil IKM Berdasarkan Responden".
	 * Return array:
	 *  [ ['id'=>1,'kode_responden'=>'RSP-..','nama'=>..,'created_at'=>..,
	 *     'jawaban'=>[unsur_id => nilai], 'total'=>36], ... ]
	 */
	public function matriks_responden($bulan = null, $tahun = null)
	{
		$this->db->select('tb_responden.id, tb_responden.kode_responden, tb_responden.nama, tb_responden.created_at');
		$this->db->from('tb_responden');
		$this->terapkan_filter_periode($bulan, $tahun);
		$this->db->order_by('tb_responden.created_at', 'ASC');
		$this->db->order_by('tb_responden.id', 'ASC');
		$responden = $this->db->get()->result_array();

		if (empty($responden)) return array();

		$peta = $this->peta_urutan();
		foreach ($responden as $i => $r) {
			$responden[$i]['urutan'] = isset($peta[(int) $r['id']]) ? $peta[(int) $r['id']] : $i + 1;
		}

		$ids = array();
		foreach ($responden as $r) $ids[] = $r['id'];

		// Ambil seluruh jawaban dalam satu query, hindari query per responden
		$this->db->select('responden_id, unsur_id, nilai')->from('tb_jawaban');
		$this->db->where_in('responden_id', $ids);
		$rows = $this->db->get()->result_array();

		$peta = array();
		foreach ($rows as $row) {
			$peta[$row['responden_id']][$row['unsur_id']] = (int) $row['nilai'];
		}

		foreach ($responden as $i => $r) {
			$jawaban = isset($peta[$r['id']]) ? $peta[$r['id']] : array();
			$responden[$i]['jawaban'] = $jawaban;
			$responden[$i]['total']   = array_sum($jawaban);
		}

		return $responden;
	}

	/**
	 * Peta [id responden => nomor urut] berdasarkan urutan kronologis seluruh
	 * responden, lepas dari filter maupun pagination. Dihitung ulang tiap kali
	 * sehingga nomornya selalu rapat walau ada data yang dihapus, dan satu
	 * responden memakai nomor yang sama di semua halaman.
	 */
	public function peta_urutan()
	{
		$this->db->select('id')->from('tb_responden');
		$this->db->order_by('created_at', 'ASC');
		$this->db->order_by('id', 'ASC');
		$rows = $this->db->get()->result_array();

		$peta = array();
		foreach ($rows as $i => $row) {
			$peta[(int) $row['id']] = $i + 1;
		}
		return $peta;
	}

	public function jumlah_responden($bulan = null, $tahun = null)
	{
		$this->db->from('tb_responden');
		$this->terapkan_filter_periode($bulan, $tahun);
		return (int) $this->db->count_all_results();
	}

	/**
	 * Statistik demografi responden (untuk chart pendukung di dashboard)
	 */
	public function statistik_demografi($bulan = null, $tahun = null)
	{
		$hasil = array();

		// jenis kelamin
		$this->db->select('jenis_kelamin, COUNT(*) AS jml')->from('tb_responden');
		$this->terapkan_filter_periode($bulan, $tahun);
		$this->db->group_by('jenis_kelamin');
		$hasil['jenis_kelamin'] = $this->db->get()->result_array();

		// pendidikan
		$this->db->select('pendidikan, COUNT(*) AS jml')->from('tb_responden');
		$this->terapkan_filter_periode($bulan, $tahun);
		$this->db->group_by('pendidikan');
		$hasil['pendidikan'] = $this->db->get()->result_array();

		// tren 12 bulan terakhir (jumlah responden per bulan, tahun berjalan)
		$this->db->select("MONTH(created_at) AS bulan, COUNT(*) AS jml")->from('tb_responden');
		if ( ! empty($tahun)) {
			$this->db->where('YEAR(created_at)', (int) $tahun);
		} else {
			$this->db->where('YEAR(created_at)', date('Y'));
		}
		$this->db->group_by('MONTH(created_at)');
		$this->db->order_by('bulan', 'ASC');
		$hasil['tren_bulanan'] = $this->db->get()->result_array();

		return $hasil;
	}

	/**
	 * Daftar tahun yang tersedia dari data responden (untuk dropdown filter)
	 */
	public function daftar_tahun()
	{
		$this->db->select('DISTINCT YEAR(created_at) AS tahun')->from('tb_responden');
		$this->db->order_by('tahun', 'DESC');
		$rows = $this->db->get()->result_array();
		$tahun = array();
		foreach ($rows as $r) $tahun[] = $r['tahun'];
		if (empty($tahun)) $tahun[] = date('Y');
		return $tahun;
	}

	/**
	 * List responden dengan pagination + filter periode
	 */
	public function get_responden_list($limit = 10, $offset = 0, $bulan = null, $tahun = null)
	{
		$this->db->from('tb_responden');
		$this->terapkan_filter_periode($bulan, $tahun);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$rows = $this->db->get()->result_array();

		$peta = $this->peta_urutan();
		foreach ($rows as $i => $row) {
			$rows[$i]['urutan'] = isset($peta[(int) $row['id']]) ? $peta[(int) $row['id']] : $offset + $i + 1;
		}
		return $rows;
	}

	public function count_responden_filtered($bulan = null, $tahun = null)
	{
		return $this->jumlah_responden($bulan, $tahun);
	}

	public function get_responden_detail($id)
	{
		$responden = $this->db->where('id', $id)->get('tb_responden')->row_array();
		if ( ! $responden) return null;

		$peta = $this->peta_urutan();
		$responden['urutan'] = isset($peta[(int) $id]) ? $peta[(int) $id] : 1;

		$this->db->select('tb_jawaban.nilai, tb_unsur.kode, tb_unsur.pertanyaan, tb_unsur_pilihan.label');
		$this->db->from('tb_jawaban');
		$this->db->join('tb_unsur', 'tb_unsur.id = tb_jawaban.unsur_id');
		$this->db->join('tb_unsur_pilihan', 'tb_unsur_pilihan.unsur_id = tb_jawaban.unsur_id AND tb_unsur_pilihan.nilai = tb_jawaban.nilai');
		$this->db->where('tb_jawaban.responden_id', $id);
		$this->db->order_by('tb_unsur.urutan', 'ASC');
		$responden['jawaban'] = $this->db->get()->result_array();

		return $responden;
	}

	public function hapus_responden($id)
	{
		// tb_jawaban ikut terhapus otomatis (ON DELETE CASCADE)
		return $this->db->where('id', $id)->delete('tb_responden');
	}

	/**
	 * List responden yang mengisi saran/masukan, dengan pagination + filter periode
	 */
	public function get_saran_list($limit = 10, $offset = 0, $bulan = null, $tahun = null)
	{
		$this->db->from('tb_responden');
		$this->db->where('saran IS NOT NULL', null, false);
		$this->db->where('saran !=', '');
		$this->terapkan_filter_periode($bulan, $tahun);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $offset);
		$rows = $this->db->get()->result_array();

		$peta = $this->peta_urutan();
		foreach ($rows as $i => $row) {
			$rows[$i]['urutan'] = isset($peta[(int) $row['id']]) ? $peta[(int) $row['id']] : null;
		}
		return $rows;
	}

	public function count_saran_filtered($bulan = null, $tahun = null)
	{
		$this->db->from('tb_responden');
		$this->db->where('saran IS NOT NULL', null, false);
		$this->db->where('saran !=', '');
		$this->terapkan_filter_periode($bulan, $tahun);
		return (int) $this->db->count_all_results();
	}
}
