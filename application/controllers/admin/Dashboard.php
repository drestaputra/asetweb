<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct();		
	}
	public function index()
	{
		$this->load->model('Mopd');
		$this->load->model('Maset');
		$this->load->model('Malamat');
		$user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
		$this->function_lib->cek_auth(array('admin'));		
		$data = array();
		$id_opd_admin = $this->function_lib->get_one('id_opd_admin','admin','id_admin='.$this->db->escape($id_user).'');
		$data['dataOpd'] = $this->Mopd->getAllOpd('id_opd = '.$this->db->escape($id_opd_admin).'');
		$data['dataKecamatan'] = $this->Malamat->getAllKecamatan("3305");
		$data['count_aset_aktif'] = $this->Maset->count_aset_by_status("aktif",'id_opd_aset = '.$this->db->escape($id_opd_admin).'');
		$data['count_aset_non_aktif'] = $this->Maset->count_aset_by_status("non_aktif", 'id_opd_aset = '.$this->db->escape($id_opd_admin).'');
		$data['count_aset_valid'] = $this->Maset->count_aset_by_status("valid", 'id_opd_aset = '.$this->db->escape($id_opd_admin).'');
		$data['count_aset_tidak_valid'] = $this->Maset->count_aset_by_status("tidak_valid", 'id_opd_aset = '.$this->db->escape($id_opd_admin).'');
		$data['count_aset_sedang_diverifikasi'] = $this->Maset->count_aset_by_status_verifikasi("sedang_diverifikasi", 'id_opd_aset = '.$this->db->escape($id_opd_admin).'');
		$data['count_aset_by_opd'] = $this->Maset->count_aset_by_opd('id_opd ='.$this->db->escape($id_opd_admin).'');
		$this->load->view('admin/dashboard/index',$data,false);	
	}



}

/* End of file Dashboard.php */
/* Location: ./application/controllers/admin/Dashboard.php */