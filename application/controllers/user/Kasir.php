<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class kasir extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function login()
	{
		$this->load->model('Mkasir');
		if (!empty($this->session->userdata('kasir'))) {
			redirect(base_url('kasir/dashboard'));
		}
		$this->load->view('kasir/login');
		if ($this->input->post()) {			
			$response=$this->Mkasir->cekLogin();			
			if ($response['status']==200) {
				redirect(base_url('kasir/dashboard'));
			}else{
				redirect(base_url().'kasir/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function lupass(){
		header("Content-type: Application/json");
		$this->load->model('Mkasir');
		$status = 500;
		$msg = "";
		if (!empty($this->session->userdata('super_admin'))) {
			$status = 500;
			$msg = "Anda sudah login";
		}		
		if (!empty($this->input->post('email'))) {			
			$lupass=$this->Mkasir->lupass();			
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
		$this->session->sess_destroy('kasir');
		redirect(base_url('kasir/login'));
	}
	public function profil(){
		$this->load->model('Mkasir');
		$this->function_lib->cek_auth(array('kasir'));
		$idkasir = $this->session->userdata('kasir')['id_kasir'];                
		if ($this->input->post('edit')) {
			$response = $this->Mkasir->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/kasir/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/kasir/profil');
			}
		}
		$data['profil'] = $this->function_lib->get_row('kasir','id_kasir="'.$idkasir.'"');
		$this->load->view('user/kasir/profil',$data,FALSE);
	}
	/*report data kasir, hanya boleh diakses {super} admin*/
	public function getData(){
		$this->function_lib->cek_auth(array('owner','admin','super_admin'));
		$this->load->model('Mkasir');
		$data = $this->Mkasir->getData();
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

			$actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/kasir/edit/'.$id_kasir.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_kasir('.$id_kasir.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        
			$nama_koperasi = $this->function_lib->get_one('nama_koperasi','owner','id_owner='.$this->db->escape($id_owner).'');
			$entry = array('id' => $id_kasir,
				'cell' => array(
					'actions' =>  $actions,
					'no' =>  $no,                    
					'username' =>(trim($username)!="")?$username:"",                    
					'koperasi' =>(trim($nama_koperasi)!="")?$nama_koperasi:"",                    
					'email' =>(trim($email)!="")?$email:"",                                        
					'no_hp' =>(trim($no_hp)!="")?$no_hp:"",                                        
					'nama' =>(trim($nama)!="")?$nama:"",                                        
					'no_ktp' =>(trim($no_ktp)!="")?$no_ktp:"",                                        
					'status' =>(trim($status)!="")? ucfirst($status):"",                                                            
				),
			);
			$json_data['rows'][] = $entry;
		}
		echo json_encode($json_data);
	}
	public function index(){
		$this->function_lib->cek_auth(array('owner','super_admin','admin'));
		$this->load->view('user/kasir/index');
	}
	public function delete($id_kasir){
		$this->load->model('Mkasir');
		$status = 500;
		$msg = "Gagal";
		$this->function_lib->cek_auth(array('admin','super_admin'));
		header("Content-type:application/json");
		$cek = $this->function_lib->get_one('id_kasir','kasir','id_kasir="'.$id_kasir.'"');
		if (trim($cek)!="") {		
			$response = $this->Mkasir->delete($id_kasir);
			$status = $response['status'];
			$msg = $response['msg'];
		}else{
			$status = 500;
			$msg = "Data tidak ditemukan";
		}

		echo json_encode(array("status"=>$status,"msg"=>$msg));
	}
	public function edit($id_kasir){
		$this->function_lib->cek_auth(array("owner","admin","super_admin"));		
		$cek = $this->function_lib->get_one('id_kasir','kasir','id_kasir="'.$id_kasir.'"');
		if (trim($cek)=="") {
			redirect(base_url().'user/kasir?status=500&msg='.base64_encode("Data kasir tidak ditemukan"));
		}
		$this->load->model('Mkasir');
		$data['id_kasir'] = $id_kasir;
		if ($this->input->post('edit')) {
			$validasi = $this->Mkasir->validasi($id_kasir);	
			$status = isset($validasi['status']) ? $validasi['status'] : 500;
			$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
			if ($status == 200) {
				$response = $this->Mkasir->edit($id_kasir);
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
		$data['owner'] = $this->function_lib->get_all('id_owner,nama_koperasi','owner','status="aktif"','nama_koperasi ASC');
		$data['kasir'] = $this->function_lib->get_row('kasir','id_kasir="'.$id_kasir.'"');
		$this->load->view('user/kasir/edit', $data, FALSE);
	}
	public function change_password($id_kasir){
		$this->function_lib->cek_auth(array('owner','admin','super_admin'));
		if($this->input->post('change_password')){
			$this->load->model('Mkasir');
			$validasiChangePassword = $this->Mkasir->changePassword($id_kasir);	
			header('Content-Type: application/json');						
			$status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
			$msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
			$error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
			echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
		}
	}
	public function tambah(){
		$this->function_lib->cek_auth(array('owner','admin','super_admin'));
		foreach($this->input->post() AS $variable=>$value)
        {
            $data[$variable]=$value;
        }
        $data=array();
        $data['owner'] = $this->function_lib->get_all('id_owner,nama_koperasi','owner','status="aktif"','nama_koperasi ASC');
		$this->load->model('Mkasir');
		if ($this->input->post('tambah')) {
			$validasi = $this->Mkasir->validasi();
			$status = isset($validasi['status']) ? $validasi['status'] : 500;
			$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
			if ($status==200) {		
				$response = $this->Mkasir->tambah();
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
		
		$this->load->view('user/kasir/tambah', $data, FALSE);
	}
}

/* End of file kasir.php */
/* Location: ./application/controllers/user/kasir.php */