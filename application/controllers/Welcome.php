<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Madmin');
	}	
	public function lupass(){
		$this->load->model('Mandroid');
		$kode = $this->input->get('kode',true);
		$cek_kode = $this->function_lib->get_one('id_forget_password','forget_password','token='.$this->db->escape($kode).' AND is_active="1" AND unix_timestamp(exp_datetime)>unix_timestamp(NOW())');
		if (!empty($cek_kode)) {
			if ($this->input->post()) {
				$response = $this->Mandroid->lupass($kode);
				$status = isset($response['status']) ? $response['status'] : 500;
				$msg = isset($response['msg']) ? $response['msg'] : "";
				if ($status ==200) {
					redirect(base_url().'lupass?status=200&msg='.base64_encode($msg));
				}else{
					redirect(base_url().'lupass?kode='.$kode.'&status=500&msg='.base64_encode($msg));
				}
			}
		}
		$data['cek_kode'] = $cek_kode;
		$this->load->view('lupass', $data, FALSE);
	}
	public function not_found(){
		$this->load->view('404.php', null, FALSE);
	}
	public function index(){
		$user_sess = $this->function_lib->get_user_level();
		$level = isset($user_sess['level']) ? $user_sess['level'] : "";
		if ($level == "super_admin") {
			redirect('super_admin/dashboard','refresh');
		}else if($level == "admin"){
			redirect('admin/dashboard','refresh');			
		}else if($level == "owner"){
			redirect('owner/dashboard','refresh');			
		}else if($level == "kasir"){
			redirect('kasir/dashboard','refresh');			
		}else{
			redirect('owner/login');
		}
	}
}
