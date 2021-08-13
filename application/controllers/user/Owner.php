<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class owner extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
	}
	public function login()
	{
		$this->load->model('Mowner');
		if (!empty($this->session->userdata('owner'))) {
			redirect(base_url('owner/dashboard'));
		}
		$this->load->view('owner/login');
		if ($this->input->post()) {			
			$response=$this->Mowner->cekLogin();			
			if ($response['status']==200) {
				redirect(base_url('owner/dashboard'));
			}else{
				redirect(base_url().'owner/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function lupass(){
		header("Content-type: Application/json");
		$this->load->model('Mowner');
		$status = 500;
		$msg = "";
		if (!empty($this->session->userdata('super_admin'))) {
			$status = 500;
			$msg = "Anda sudah login";
		}		
		if (!empty($this->input->post('email'))) {			
			$lupass=$this->Mowner->lupass();			
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
		$this->session->sess_destroy('owner');
		redirect(base_url('owner/login'));
	}
	public function profil(){
		$this->load->model('Mowner');
		if (empty($this->session->userdata('owner'))) {
			redirect(base_url('owner/login'));
			exit();
		}
		$idowner = $this->session->userdata('owner')['id_owner'];                
		if ($this->input->post('edit')) {
			$response = $this->Mowner->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/owner/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/owner/profil');
			}
		}else if($this->input->post('change_password')){
			$response = $this->Mowner->changePassword($idowner);
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/owner/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/owner/profil');
			}
		}
		$data['profil'] = $this->function_lib->get_row('owner','id_owner="'.$idowner.'"');
		$this->load->view('user/owner/profil',$data,FALSE);
	}
	/*report data owner, hanya boleh diakses {super} admin*/
	public function getData(){
		$this->function_lib->cek_auth(array("admin","super_admin"));		
		$this->load->model('Mowner');
		$data = $this->Mowner->getData();
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

			$actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/owner/edit/'.$id_owner.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_owner('.$id_owner.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        

			$entry = array('id' => $id_owner,
				'cell' => array(
					'actions' =>  $actions,
					'no' =>  $no,                    
					'username' =>(trim($username)!="")?$username:"",                    
					'email' =>(trim($email)!="")?$email:"",                                        
					'nama_owner' =>(trim($nama_owner)!="")?$nama_owner:"",                                                            
					'nama_koperasi' =>(trim($nama_koperasi)!="")?$nama_koperasi:"",
					'no_hp' =>(trim($no_hp)!="")?$no_hp:"",                                                            
					'alamat' =>(trim($alamat)!="")?$alamat:"",                                                            
					'kecamatan' =>(trim($kecamatan)!="")?$kecamatan:"",                                                            
					'kabupaten' =>(trim($kabupaten)!="")?$kabupaten:"",                                                            
					'provinsi' =>(trim($provinsi)!="")?$provinsi:"",                                                            
					'no_badan_hukum' =>(trim($no_badan_hukum)!="")?$no_badan_hukum:"",
					'biaya_administrasi' =>(trim($biaya_administrasi)!="")?$biaya_administrasi.'%':"",
					'biaya_simpanan' =>(trim($biaya_simpanan)!="")?$biaya_simpanan.'%':"",
					'bunga_pinjaman' =>(trim($bunga_pinjaman)!="")?$bunga_pinjaman."%":"",
					'biaya_sewa_aplikasi' =>(trim($biaya_sewa_aplikasi)!="")?"Rp. ".number_format($biaya_sewa_aplikasi,0,'.','.'):"",
					'status' =>(trim($status)!="")?$status:"",                                                            
					'hari_kerja' =>(trim($hari_kerja)!="")?$hari_kerja:"",                                                            
				),
			);
			$json_data['rows'][] = $entry;
		}
		echo json_encode($json_data);
	}
	public function index(){
		$this->function_lib->cek_auth(array("admin","super_admin"));		
		$this->load->view('user/owner/index');
	}
	public function delete($id_owner){
		$this->load->model('Mowner');
		$status = 500;
		$msg = "Gagal";
		$this->function_lib->cek_auth(array("admin","super_admin"));		
		header("Content-type:application/json");
		$cek = $this->function_lib->get_one('id_owner','owner','id_owner="'.$id_owner.'"');
		if (trim($cek)!="") {		
			$response = $this->Mowner->delete($id_owner);
			$status = $response['status'];
			$msg = $response['msg'];
		}else{
			$status = 500;
			$msg = "Data tidak ditemukan";
		}

		echo json_encode(array("status"=>$status,"msg"=>$msg));
	}
	public function edit($id_owner){
		$this->function_lib->cek_auth(array("admin","super_admin"));		
		$data['id_owner'] = $id_owner;
		$cek_id_owner = $this->function_lib->get_one('id_owner','owner','id_owner='.$this->db->escape($id_owner).'');
		if (empty($cek_id_owner)) {
			redirect(base_url('user/owner'),'refresh');
			return false;
			exit();
		}
		$this->load->model('Mowner');
		if ($this->input->post('edit')) {
			$validasi = $this->Mowner->validasi($id_owner);	
			$status = isset($validasi['status']) ? $validasi['status'] : 500;
			$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
			if ($status == 200) {
				$response = $this->Mowner->edit($id_owner);
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
		$data['owner'] = $this->function_lib->get_row('owner','id_owner="'.$id_owner.'"');
		$this->load->view('user/owner/edit', $data, FALSE);
	}
	public function change_password($id_owner){		
		$this->function_lib->cek_auth(array("owner","admin","super_admin"));
		if($this->input->post('change_password')){
			$this->load->model('Mowner');
			$validasiChangePassword = $this->Mowner->changePassword($id_owner);	
			header('Content-Type: application/json');						
			$status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
			$msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
			$error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
			echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
		}
	}
	public function tambah($id_request=""){	
		$this->function_lib->cek_auth(array("admin","super_admin"));		
		$this->load->model('Mowner');
		if ($this->input->post('tambah')) {
			$validasi = $this->Mowner->validasi();
			if (trim($validasi['status'])==200) {		
				$response = $this->Mowner->tambah();
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
		$data['temp'] = array();
		if (isset($id_request) AND !empty($id_request)) {
			$data['temp'] = $this->function_lib->get_row('request_owner','status="proses" AND id_request='.$this->db->escape($id_request).'');
		}
		$this->load->view('user/owner/tambah', $data, FALSE);
	}
}

/* End of file owner.php */
/* Location: ./application/controllers/user/owner.php */