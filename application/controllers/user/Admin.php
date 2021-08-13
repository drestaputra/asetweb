<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function login()
	{
		$this->load->model('Madmin');
		if (!empty($this->session->userdata('admin'))) {
			redirect(base_url('admin/dashboard'));
		}
		$this->load->view('admin/login');
		if ($this->input->post()) {			
			$response=$this->Madmin->cekLogin();			
			if ($response['status']==200) {
				redirect(base_url('admin/dashboard'));
			}else{
				redirect(base_url().'admin/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function lupass(){
		header("Content-type: Application/json");
		$this->load->model('Madmin');
		$status = 500;
		$msg = "";
		if (!empty($this->session->userdata('super_admin'))) {
			$status = 500;
			$msg = "Anda sudah login";
		}		
		if (!empty($this->input->post('email'))) {			
			$lupass=$this->Madmin->lupass();			
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
		$this->session->sess_destroy('admin');
		redirect(base_url('admin/login'));
	}
	public function profil(){
		$this->load->model('Madmin');
		$this->function_lib->cek_auth(array('admin'));		
		$idAdmin = $this->session->userdata('admin')['id_admin'];                
		if ($this->input->post('edit')) {
			$response = $this->Madmin->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/admin/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/admin/profil');
			}
		}
		$data['profil'] = $this->function_lib->get_row('admin','id_admin="'.$idAdmin.'"');
		$this->load->view('user/admin/profil',$data,FALSE);
	}
	/*report data admin, hanya boleh diakses {super} admin*/
	public function getData(){
		$this->function_lib->cek_auth(array('super_admin'));		
		$this->load->model('Madmin');
		$data = $this->Madmin->getData();
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

			$actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/admin/edit/'.$id_admin.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_admin('.$id_admin.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        

			$entry = array('id' => $id_admin,
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
		$this->function_lib->cek_auth(array('super_admin'));
		$this->load->view('user/admin/index');
	}
	public function delete($id_admin){
		$this->load->model('Madmin');
		$status = 500;
		$msg = "Gagal";
		$this->function_lib->cek_auth(array('super_admin'));
		header("Content-type:application/json");
		$cek = $this->function_lib->get_one('id_admin','admin','id_admin="'.$id_admin.'"');
		if (trim($cek)!="") {		
			$response = $this->Madmin->delete($id_admin);
			$status = $response['status'];
			$msg = $response['msg'];
		}else{
			$status = 500;
			$msg = "Data tidak ditemukan";
		}

		echo json_encode(array("status"=>$status,"msg"=>$msg));
	}
	public function edit($id_admin){
		$this->function_lib->cek_auth(array('super_admin'));
		$cek = $this->function_lib->get_one('id_admin','admin','id_admin="'.$id_admin.'"');
		if (trim($cek)=="") {
			redirect(base_url().'user/admin?status=500&msg='.base64_encode("Data admin tidak ditemukan"));
		}
		$this->load->model('Madmin');
		$data['id_admin'] = $id_admin;
		if ($this->input->post('edit')) {
			$validasi = $this->Madmin->validasi($id_admin);	
			$status = isset($validasi['status']) ? $validasi['status'] : 500;
			$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
			if ($status == 200) {
				$response = $this->Madmin->edit($id_admin);
				$status = $response['status'];
				$msg = $response['msg'];
				$error = isset($validasi['error']) ? $validasi['error'] : array();
				header('Content-Type: application/json');			
				echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
				exit();		
			}else{
				$status = 500;
				$msg = $validasi['msg'];
				$error = isset($validasi['error']) ? $validasi['error'] : array();
				header('Content-Type: application/json');			
				echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
				exit();		
			}		
		}
		$data['admin'] = $this->function_lib->get_row('admin','id_admin="'.$id_admin.'"');
		$this->load->view('user/admin/edit', $data, FALSE);
	}
	public function change_password($id_admin){
		$this->function_lib->cek_auth(array('super_admin'));
		if($this->input->post('change_password')){
			$this->load->model('Madmin');
			$validasiChangePassword = $this->Madmin->changePassword($id_admin);	
			header('Content-Type: application/json');						
			$status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
			$msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
			$error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
			echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
		}
	}
	public function tambah(){
		$this->function_lib->cek_auth(array('super_admin'));
		foreach($this->input->post() AS $variable=>$value)
        {
            $this->data[$variable]=$value;
        }
		$this->load->model('Madmin');
		if ($this->input->post('tambah')) {
			$validasi = $this->Madmin->validasi();
			$status = isset($validasi['status']) ? $validasi['status'] : 500;
			$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
			if ($status==200) {		
				$response = $this->Madmin->tambah();
				$status = $response['status'];
				$msg = $response['msg'];
				$error = isset($validasi['error']) ? $validasi['error'] : array();
				header('Content-Type: application/json');			
				echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
				exit();		
			}else{
				$status = 500;
				$msg = $validasi['msg'];
				$error = isset($validasi['error']) ? $validasi['error'] : array();
				header('Content-Type: application/json');			
				echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
				exit();				
			}		
		}		
		$data=array();
		$this->load->view('user/admin/tambah', $data, FALSE);
	}
}

/* End of file Admin.php */
/* Location: ./application/controllers/user/Admin.php */