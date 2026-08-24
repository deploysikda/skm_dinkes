<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helper umum untuk aplikasi SKM Dinas Kesehatan Kota Bandung
 */

if ( ! function_exists('mutu_ikm'))
{
	/**
	 * Menentukan Mutu Pelayanan & Kinerja Unit Pelayanan berdasarkan Nilai IKM
	 * Acuan: Lampiran Permenpan RB No. 14 Tahun 2017
	 *
	 * @param float $nilai_ikm Nilai IKM setelah dikonversi (skala 0-100)
	 * @return array ['mutu' => 'A', 'kinerja' => 'Sangat Baik']
	 */
	function mutu_ikm($nilai_ikm)
	{
		if ($nilai_ikm >= 88.31 && $nilai_ikm <= 100) {
			return array('mutu' => 'A', 'kinerja' => 'Sangat Baik');
		} elseif ($nilai_ikm >= 76.61 && $nilai_ikm <= 88.30) {
			return array('mutu' => 'B', 'kinerja' => 'Baik');
		} elseif ($nilai_ikm >= 65.00 && $nilai_ikm <= 76.60) {
			return array('mutu' => 'C', 'kinerja' => 'Kurang Baik');
		} elseif ($nilai_ikm >= 25.00 && $nilai_ikm <= 64.99) {
			return array('mutu' => 'D', 'kinerja' => 'Tidak Baik');
		}
		return array('mutu' => '-', 'kinerja' => 'Belum Ada Data');
	}
}

if ( ! function_exists('bulan_indo'))
{
	/**
	 * Nama bulan dalam Bahasa Indonesia
	 */
	function bulan_indo($bulan = null)
	{
		$nama = array(
			1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
			5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
			9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
		);
		if ($bulan === null) return $nama;
		return isset($nama[(int) $bulan]) ? $nama[(int) $bulan] : '-';
	}
}

if ( ! function_exists('tanggal_indo'))
{
	/**
	 * Format tanggal Indonesia dari datetime MySQL
	 */
	function tanggal_indo($datetime, $with_time = true)
	{
		if (empty($datetime)) return '-';
		$ts = strtotime($datetime);
		$hasil = date('d', $ts) . ' ' . bulan_indo(date('n', $ts)) . ' ' . date('Y', $ts);
		if ($with_time) {
			$hasil .= ', ' . date('H:i', $ts) . ' WIB';
		}
		return $hasil;
	}
}

if ( ! function_exists('generate_kode_responden'))
{
	/**
	 * Membuat kode unik responden, format: RSP-YYYYMMDD-XXXX
	 */
	function generate_kode_responden()
	{
		return 'RSP-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 4));
	}
}

if ( ! function_exists('label_jenis_kelamin'))
{
	function label_jenis_kelamin($kode)
	{
		return $kode === 'L' ? 'Laki-laki' : ($kode === 'P' ? 'Perempuan' : '-');
	}
}

if ( ! function_exists('responden_label'))
{
	/**
	 * Nama tampilan responden mengikuti urutan berkelanjutan, bukan ID dari DB.
	 */
	function responden_label($nama = null, $id = null, $urutan = null)
	{
		if ($urutan !== null && $urutan !== '') {
			return 'Responden ' . (int) $urutan;
		}
		return 'Responden';
	}
}

if ( ! function_exists('badge_mutu_class'))
{
	/**
	 * Kelas warna badge Bootstrap sesuai huruf mutu A/B/C/D
	 */
	function badge_mutu_class($huruf)
	{
		$map = array(
			'A' => 'bg-success',
			'B' => 'bg-primary',
			'C' => 'bg-warning text-dark',
			'D' => 'bg-danger',
		);
		return isset($map[$huruf]) ? $map[$huruf] : 'bg-secondary';
	}
}

if ( ! function_exists('nama_unsur'))
{
	/**
	 * Nama ringkas unsur pelayanan sesuai Permenpan RB No. 14 Tahun 2017,
	 * dipakai di laporan (tabel kuesioner hanya menyimpan pertanyaan panjang).
	 *
	 * @param string      $kode     Kode unsur (U1..U9)
	 * @param string|null $fallback Dipakai bila kode di luar daftar
	 */
	function nama_unsur($kode, $fallback = null)
	{
		$nama = array(
			'U1' => 'Persyaratan',
			'U2' => 'Sistem, Mekanisme, dan Prosedur',
			'U3' => 'Waktu Penyelesaian',
			'U4' => 'Biaya/Tarif',
			'U5' => 'Produk Spesifikasi Jenis Pelayanan',
			'U6' => 'Kompetensi Pelaksana',
			'U7' => 'Perilaku Pelaksana',
			'U8' => 'Sarana dan Prasarana',
			'U9' => 'Penanganan Pengaduan, Saran dan Masukan',
		);
		$kode = strtoupper(trim($kode));

		if (isset($nama[$kode])) return $nama[$kode];

		return $fallback !== null ? $fallback : $kode;
	}
}
