<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
		
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
		if (!empty($this->session->userdata('admin'))) {
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
		redirect(base_url());
	}
	public function profil(){
		$this->load->model('Madmin');
		$this->load->model('Mopd');
		if (empty($this->session->userdata('admin'))) {
			redirect(base_url('admin/login'));
			exit();
		}
		$idadmin = $this->session->userdata('admin')['id_admin'];                
		if ($this->input->post('edit')) {
			$response = $this->Madmin->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/admin/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/admin/profil');
			}
		}else if($this->input->post('change_password')){
			$response = $this->Madmin->changePassword($idadmin);
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/admin/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/admin/profil');
			}
		}
		$data['opd'] = $this->Mopd->getAllOpd();
		$data['profil'] = $this->function_lib->get_row('admin','id_admin="'.$idadmin.'"');
		$this->load->view('user/admin/profil',$data,FALSE);
	}
	/*report data admin, hanya boleh diakses {super} admin*/
	public function getData(){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
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
	public function index() {
		$this->function_lib->cek_auth(array("super_admin"));
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('admin');
        $crud->set_subject('Data Admin');
        $crud->set_language('indonesian');
        $crud->set_relation('id_opd_admin','opd','label_opd', 'status_opd!="deleted"');
        $crud->columns('id_opd_admin', 'Ubah Password','username','email','status');                 
	    
        $crud->order_by('id_admin','DESC');
        $crud->where('status != "deleted"');
        $action = $this->uri->segment(4,0);
        
        $crud->display_as('id_opd_admin','OPD')
             ->display_as('username','Username')
             ->display_as('email','Email')
             ->display_as('status','STATUS') ;                                      

        $crud->change_field_type('password', 'password');
        $crud->unique_fields(['username','email']);        

        $crud->callback_column('Ubah Password', array($this, 'link_ubah_password'));        
        $crud->required_fields('id_opd_admin','username','password','email','status');
        $crud->callback_after_insert(array($this, 'cpass'));
        $crud->unset_edit_fields('password');
        $crud->unset_add_fields('status');
        $data = $crud->render();
        $data->state = $crud->getState();
        $this->load->model('Mopd');
        $data->dataOpd = $this->Mopd->getAllOpd();

        
 
        $this->load->view('user/admin/index', $data, FALSE);
    }
    public function link_ubah_password($value, $row){
        return '<a href="'.base_url("user/admin/ubah_password/".$row->id_admin).'" class="btn btn-info btn-sm"><i class="fa fa-key"></i></a>';
    }
	public function delete($id_admin){
		$this->function_lib->cek_auth(array('super_admin'));
		$this->load->model('Madmin');
		$status = 500;
		$msg = "Gagal";
		if (empty($this->session->userdata('admin'))) {
			echo json_encode(array("status"=>$status,"msg"=>"Akses ditolak"));
		}
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
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->model('Madmin');
		if ($this->input->post('edit')) {
			$cek = $this->function_lib->get_one('id_admin','admin','id_admin="'.$id_admin.'"');
			if (trim($cek)!="") {		
				$response = $this->Madmin->edit($id_admin);
				$status = $response['status'];
				$msg = $response['msg'];
				
			}else{
				$status = 500;
				$msg = "Data tidak ditemukan";
			}		
			redirect(base_url().'user/admin?status='.$status.'&msg='.base64_encode($msg));
		}
		$data['admin'] = $this->function_lib->get_row('admin','id_admin="'.$id_admin.'"');
		$this->load->view('user/admin/edit', $data, FALSE);
	}
	public function cpass($post_array,$primary_key){
		$this->function_lib->cek_auth(array('super_admin'));
        $hash = hash('sha512',$post_array['password'] . config_item('encryption_key'));
        $this->db->set("password",$hash);
        $this->db->where('id_admin', $primary_key);
        $this->db->update('admin');
     
        return true;
    }
	public function tambah(){
		if (empty($this->session->userdata('admin'))) {
			redirect('admin/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh admin"));
		}
		$this->load->model('Madmin');
		if ($this->input->post('tambah')) {
			$validasi = $this->Madmin->validasi();
			if (trim($validasi['status'])==200) {		
				$response = $this->Madmin->tambah();
				$status = $response['status'];
				$msg = $response['msg'];
				redirect(base_url().'user/admin?status='.$status.'&msg='.base64_encode($msg));
			}else{
				$status = 500;
				$msg = $validasi['msg'];
				redirect(base_url().'user/admin/tambah?status='.$status.'&msg='.base64_encode($msg));
			}		
		}		
		$data=array();
		$this->load->view('user/admin/tambah', $data, FALSE);
	}
	public function ubah_password($id_admin){
		$this->function_lib->cek_auth(array('super_admin'));
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $id_admin = $this->function_lib->get_one('id_admin','admin','id_admin="'.$id_admin.'"');
        if (empty($id_admin) AND ($level!="owner" OR $level!="kasir")) {
            redirect(base_url().'user/admin/index/');
            exit();
        }else{
            $data['id_admin'] = $id_admin;
            $this->load->view('user/admin/ubah_password', $data, FALSE);
        }
        
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
}

/* End of file admin.php */
/* Location: ./application/controllers/user/admin.php */