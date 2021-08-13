<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class super_admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function login()
	{
		$this->load->model('Msuper_admin');
		if (!empty($this->session->userdata('super_admin'))) {
			redirect(base_url('super_admin/dashboard'));
		}
		$this->load->view('super_admin/login');
		if ($this->input->post()) {			
			$response=$this->Msuper_admin->cekLogin();			
			if ($response['status']==200) {
				redirect(base_url('super_admin/dashboard'));
			}else{
				redirect(base_url().'super_admin/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function lupass(){
		header("Content-type: Application/json");
		$this->load->model('Msuper_admin');
		$status = 500;
		$msg = "";
		if (!empty($this->session->userdata('super_admin'))) {
			$status = 500;
			$msg = "Anda sudah login";
		}		
		if (!empty($this->input->post('email'))) {			
			$lupass=$this->Msuper_admin->lupass();			
			$status = isset($lupass['status']) ? $lupass['status'] : 500;
			$msg = isset($lupass['msg']) ? $lupass['msg'] : "";			
		}
		$response = array(
			"status" => $status,
			"msg" => $msg,
		);
		echo(json_encode($response));
	}
	public function logout(){
		$this->session->sess_destroy('super_admin');
		redirect(base_url('super_admin/login'));
	}
	public function profil(){
		$this->load->model('Msuper_admin');
		if (empty($this->session->userdata('super_admin'))) {
			redirect(base_url('super_admin/login'));
			exit();
		}
		$idsuper_admin = $this->session->userdata('super_admin')['id_super_admin'];                
		if ($this->input->post('edit')) {
			$response = $this->Msuper_admin->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/super_admin/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/super_admin/profil');
			}
		}else if($this->input->post('change_password')){
			$response = $this->Msuper_admin->changePassword($idsuper_admin);
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/super_admin/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/super_admin/profil');
			}
		}
		$data['profil'] = $this->function_lib->get_row('super_admin','id_super_admin="'.$idsuper_admin.'"');
		$this->load->view('user/super_admin/profil',$data,FALSE);
	}
	/*report data super_admin, hanya boleh diakses {super} admin*/
	public function getData(){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->model('Msuper_admin');
		$data = $this->Msuper_admin->getData();
		$query = $data['query'];
		$total = $data['total'];
		header("Content-type: application/json");
		$_POST['rp'] = isset($_POST['rp'])?$_POST['rp']:20;
		$page = isset($_POST['page']) ? $_POST['page'] : 1;
		$json_data = array('page' => $page, 'total' => $total, 'rows' => array());
		$prev_trx = '';
		$no = 0 + ($_POST['rp'] * ($page - 1));
		foreach ($query->result() as $row) {

			foreach($row AS $variable=>$value)
			{
				${$variable}=$value;
			}
			$no++;

			$actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/super_admin/edit/'.$id_super_admin.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_super_admin('.$id_super_admin.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        

			$entry = array('id' => $id_super_admin,
				'cell' => array(
					'actions' =>  $actions,
					'no' =>  $no,                    
					'username' =>(trim($username)!="")?$username:"",                    
					'email' =>(trim($email)!="")?$email:"",                                        
					'status' =>(trim($status)!="")?$status:"",                                                            
				),
			);
			$json_data['rows'][] = $entry;
		}
		echo json_encode($json_data);
	}
	public function index(){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->view('user/super_admin/index');
	}
	public function delete($id_super_admin){
		$this->load->model('Msuper_admin');
		$status = 500;
		$msg = "Gagal";
		if (empty($this->session->userdata('admin'))) {
			echo json_encode(array("status"=>$status,"msg"=>"Akses ditolak"));
		}
		header("Content-type:application/json");
		$cek = $this->function_lib->get_one('id_super_admin','super_admin','id_super_admin="'.$id_super_admin.'"');
		if (trim($cek)!="") {		
			$response = $this->Msuper_admin->delete($id_super_admin);
			$status = $response['status'];
			$msg = $response['msg'];
		}else{
			$status = 500;
			$msg = "Data tidak ditemukan";
		}

		echo json_encode(array("status"=>$status,"msg"=>$msg));
	}
	public function edit($id_super_admin){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->model('Msuper_admin');
		if ($this->input->post('edit')) {
			$cek = $this->function_lib->get_one('id_super_admin','super_admin','id_super_admin="'.$id_super_admin.'"');
			if (trim($cek)!="") {		
				$response = $this->Msuper_admin->edit($id_super_admin);
				$status = $response['status'];
				$msg = $response['msg'];
				
			}else{
				$status = 500;
				$msg = "Data tidak ditemukan";
			}		
			redirect(base_url().'user/super_admin?status='.$status.'&msg='.base64_encode($msg));
		}
		$data['super_admin'] = $this->function_lib->get_row('super_admin','id_super_admin="'.$id_super_admin.'"');
		$this->load->view('user/super_admin/edit', $data, FALSE);
	}
	public function tambah(){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->model('Msuper_admin');
		if ($this->input->post('tambah')) {
			$validasi = $this->Msuper_admin->validasi();
			if (trim($validasi['status'])==200) {		
				$response = $this->Msuper_admin->tambah();
				$status = $response['status'];
				$msg = $response['msg'];
				redirect(base_url().'user/super_admin?status='.$status.'&msg='.base64_encode($msg));
			}else{
				$status = 500;
				$msg = $validasi['msg'];
				redirect(base_url().'user/super_admin/tambah?status='.$status.'&msg='.base64_encode($msg));
			}		
		}		
		$data=array();
		$this->load->view('user/super_admin/tambah', $data, FALSE);
	}
}

/* End of file super_admin.php */
/* Location: ./application/controllers/user/super_admin.php */