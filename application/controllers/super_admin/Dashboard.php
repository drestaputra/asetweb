<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
		$this->load->model('Malamat');
	}
	public function index()
	{
		$this->load->model('Mopd');
		$this->load->model('Maset');
		$this->function_lib->cek_auth(array("super_admin"));
		$data = array();
		$data['dataOpd'] = $this->Mopd->getAllOpd();
		$data['dataKecamatan'] = $this->Malamat->getAllKecamatan("3305");
		$data['count_aset_idle'] = $this->Maset->count_aset_by_status("non_idle");
		$data['count_aset_non_idle'] = $this->Maset->count_aset_by_status("idle");

		$data['count_aset_valid'] = $this->Maset->count_aset_by_status_verifikasi("valid");
		$data['count_aset_tidak_valid'] = $this->Maset->count_aset_by_status_verifikasi("tidak_valid");
		$data['count_aset_sedang_diverifikasi'] = $this->Maset->count_aset_by_status_verifikasi("sedang_diverifikasi");
		
		$data['count_aset_by_opd'] = $this->Maset->count_aset_by_opd();
		$this->load->view('super_admin/dashboard/index',$data,false);	
	}
	public function get_desa_by_kecamatan($id_kecamatan = "0"){
		header("Content-type: Application/json");
		$dataDesa = $this->Malamat->getAllDsea($id_kecamatan);
		echo(json_encode($dataDesa));
	}
	public function cari_aset()
	{
		$this->load->model('Maset');
		header("Content-type: Application/json");
		$data = $this->input->post();
		$data_aset = $this->Maset->cari_aset($data);
		echo(json_encode($data_aset));
	}
	

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/admin/Dashboard.php */