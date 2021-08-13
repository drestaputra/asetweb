<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}
	public function index()
	{
		$this->function_lib->cek_auth(array('kasir'));		
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $id_owner = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
		$data = array();
		$where_koperasi = ' id_owner = '.$this->db->escape($id_owner).'';
		$data['nasabah_hari_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','date(tgl_bergabung)='.$this->db->escape(date("Y-m-d")).' AND '.$where_koperasi);
		$data['nasabah_bulan_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','YEAR(tgl_bergabung)="'.date("Y").'" AND month(tgl_bergabung)='.$this->db->escape(date("m")).' AND '.$where_koperasi);
		$data['nasabah_tahun_ini'] = $this->function_lib->get_one('count(id_nasabah)','nasabah','year(tgl_bergabung)='.$this->db->escape(date("Y")).' AND '.$where_koperasi);
		$data['angsuran_pinjaman_today'] = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','DATE(tgl_riwayat_pinjaman)='.$this->db->escape(date("Y-m-d")).' AND id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE '.$where_koperasi.')');
		$data['angsuran_simpanan_today'] = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','DATE(tgl_riwayat_simpanan)='.$this->db->escape(date("Y-m-d")).' AND id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE '.$where_koperasi.')');
		$data['angsuran_simpanan'] = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','YEAR(tgl_riwayat_simpanan)="'.date("Y").'" AND month(tgl_riwayat_simpanan)="'.date("m").'" AND id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE '.$where_koperasi.')');
		$data['angsuran_pinjaman'] = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','YEAR(tgl_riwayat_pinjaman)="'.date("Y").'" AND month(tgl_riwayat_pinjaman)="'.date("m").'" AND id_pinjaman IN (SELECT id_pinjaman FROM pinjaman WHERE '.$where_koperasi.')');
		$data['koperasiArr'] = $this->function_lib->get_all('id_owner,nama_koperasi,tgl_jatuh_tempo_pembayaran_sistem,bunga_pinjaman,biaya_administrasi,biaya_simpanan','owner','status="aktif" AND '.$where_koperasi,'nama_koperasi ASC');				
		$dataView['koperasiArr'] = $data['koperasiArr'];
		$this->load->view('kasir/dashboard/index',$dataView,false);	
	}
	public function get_grafik_user(){
		header('Content-Type: application/json');	
		$this->function_lib->cek_auth(array("kasir"));
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir="'.$this->db->escape($id_user).'"');
		$this->load->model('Mkasir');
		$bulan = array("1"=>0,"2"=>0,"3"=>0,"4"=>0,"5"=>0,"6"=>0,"7"=>0,"8"=>0,"9"=>0,"10"=>0,"11"=>0,"12"=>0);
		$tahun = $this->input->post('tahun');
		$bulanNasabah = $bulan;		
		$dataNasabah = $this->Mkasir->get_grafik_user_nasabah($tahun,$id_user);	
		foreach ($dataNasabah as $key => $value) {
			$bulanNasabah[$value['bulan']] = floatval($value['total']);
		}		
		$response = array(			
			"nasabah" => isset($bulanNasabah) ? $bulanNasabah : array(),
		);
		echo (json_encode($response));
	}
	// grafik transaksi angsuran simpan pinjam per bulan, segmentasi hari
	// diambil dari table riwayat_pinjman dan riwayat_angsuran
	public function get_grafik_transaksi(){
		header('Content-Type: application/json');	
		$this->function_lib->cek_auth(array("kasir"));
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";;
        $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir="'.$this->db->escape($id_user).'"');
		$this->load->model('Mkasir');
		$id_kasir = $id_user;
		$riwayat_pinjman = $this->Mkasir->get_grafik_riwayat_pinjaman(date("m"),$id_kasir);
		$riwayat_simpanan = $this->Mkasir->get_grafik_riwayat_simpanan(date("m"),$id_kasir);
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