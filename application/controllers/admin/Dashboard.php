<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}
	public function index()
	{
		$this->function_lib->cek_auth(array('admin'));		
		$data = array();
		$data['nasabah_hari_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','date(tgl_bergabung)='.$this->db->escape(date("Y-m-d")).'');
		$data['nasabah_bulan_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','YEAR(tgl_bergabung)="'.date("Y").'" AND month(tgl_bergabung)='.$this->db->escape(date("m")).'');
		$data['nasabah_tahun_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','year(tgl_bergabung)='.$this->db->escape(date("Y")).'');
		$data['angsuran_pinjaman_today'] = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','DATE(tgl_riwayat_pinjaman)='.$this->db->escape(date("Y-m-d")).'');
		$data['angsuran_simpanan_today'] = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','DATE(tgl_riwayat_simpanan)='.$this->db->escape(date("Y-m-d")).'');
		$data['angsuran_simpanan'] = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','month(tgl_riwayat_simpanan)="'.date("m").'" AND YEAR(tgl_riwayat_simpanan)="'.date("Y").'"');
		$data['angsuran_pinjaman'] = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','month(tgl_riwayat_pinjaman)="'.date("m").'" AND YEAR(tgl_riwayat_pinjaman)="'.date("Y").'"');
		$data['koperasiArr'] = $this->function_lib->get_all('id_owner,nama_koperasi','owner','status="aktif"','nama_koperasi ASC');				
		$this->load->view('admin/dashboard/index',$data,false);	
	}
	public function get_grafik_user(){
		header('Content-Type: application/json');	
		$this->function_lib->cek_auth(array("admin"));
		$this->load->model('Madmin');
		$bulan = array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0,"6"=>0,"7"=>0,"8"=>0,"9"=>0,"10"=>0,"11"=>0,"12"=>0);
		$tahun = $this->input->post('tahun');
		$bulanKoperasi = $bulanKolektor = $bulanKasir = $bulanNasabah = $bulan;
		$dataOwner = $this->Madmin->get_grafik_user_owner($tahun);
		$dataKolektor = $this->Madmin->get_grafik_user_kolektor($tahun);
		$dataKasir = $this->Madmin->get_grafik_user_kasir($tahun);
		$dataNasabah = $this->Madmin->get_grafik_user_nasabah($tahun);
		foreach ($dataOwner as $key => $value) {
			$bulanKoperasi[$value['bulan']] = floatval($value['total']);
		}		
		foreach ($dataKolektor as $key => $value) {
			$bulanKolektor[$value['bulan']] = floatval($value['total']);
		}
		foreach ($dataKasir as $key => $value) {
			$bulanKasir[$value['bulan']] = floatval($value['total']);
		}		
		foreach ($dataNasabah as $key => $value) {
			$bulanNasabah[$value['bulan']] = floatval($value['total']);
		}		
		$response = array(
			"koperasi" => isset($bulanKoperasi) ? $bulanKoperasi : array(),
			"kolektor" => isset($bulanKolektor) ? $bulanKolektor : array(),
			"kasir" => isset($bulanKasir) ? $bulanKasir : array(),
			"nasabah" => isset($bulanNasabah) ? $bulanNasabah : array(),
		);
		echo (json_encode($response));
	}
	// grafik transaksi angsuran simpan pinjam per bulan, segmentasi hari
	// diambil dari table riwayat_pinjman dan riwayat_angsuran
	public function get_grafik_transaksi(){
		header('Content-Type: application/json');	
		$this->function_lib->cek_auth(array("admin"));
		$this->load->model('Madmin');
		$id_owner = $this->input->post('id_owner');
		$riwayat_pinjman = $this->Madmin->get_grafik_riwayat_pinjaman(date("m"),$id_owner);
		$riwayat_simpanan = $this->Madmin->get_grafik_riwayat_simpanan(date("m"),$id_owner);
		$hari_pinjaman = array();
		$hari_simpanan = array();
		for ($i=1; $i <= intval(date("t")); $i++) { 
			$hari_pinjaman[$i] = 0;
			$hari_simpanan[$i] = 0;
		}
		foreach ($riwayat_pinjman as $key => $value) {
			$hari_pinjaman[$value['hari']] = floatval($value['total']);
		}		
		foreach ($riwayat_simpanan as $key => $value) {
			$hari_simpanan[$value['hari']] = floatval($value['total']);
		}		
		$response = array(
			"riwayat_simpanan" => $hari_simpanan,
			"riwayat_pinjaman" => $hari_pinjaman,
		);
		echo (json_encode($response));
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/admin/Dashboard.php */