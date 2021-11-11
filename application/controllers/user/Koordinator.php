<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class koordinator extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
		
	}
	public function login()
	{
		$this->load->model('Mkoordinator');
		if (!empty($this->session->userdata('koordinator'))) {
			redirect(base_url('koordinator/dashboard'));
		}
		$this->load->view('koordinator/login');
		if ($this->input->post()) {			
			$response=$this->Mkoordinator->cekLogin();			
			if ($response['status']==200) {
				redirect(base_url('koordinator/dashboard'));
			}else{
				redirect(base_url().'koordinator/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
			}
		}
	}
	public function lupass(){
		header("Content-type: Application/json");
		$this->load->model('Mkoordinator');
		$status = 500;
		$msg = "";
		if (!empty($this->session->userdata('koordinator'))) {
			$status = 500;
			$msg = "Anda sudah login";
		}		
		if (!empty($this->input->post('email'))) {			
			$lupass=$this->Mkoordinator->lupass();			
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
		$this->session->sess_destroy('koordinator');
		redirect(base_url());
	}
	public function profil(){
		$this->load->model('Mkoordinator');
		if (empty($this->session->userdata('koordinator'))) {
			redirect(base_url('koordinator/login'));
			exit();
		}
		$idkoordinator = $this->session->userdata('koordinator')['id_koordinator'];                
		if ($this->input->post('edit')) {
			$response = $this->Mkoordinator->editProfil();
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/koordinator/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/koordinator/profil');
			}
		}else if($this->input->post('change_password')){
			$response = $this->Mkoordinator->changePassword($idkoordinator);
			if (!empty($response)) {
				$status = $response['status'];
				$msg = $response['msg'];
				redirect('user/koordinator/profil?status='.$status.'&msg='.base64_encode($msg).'');
			}else{
				redirect('user/koordinator/profil');
			}
		}
		$data['profil'] = $this->function_lib->get_row('koordinator','id_koordinator="'.$idkoordinator.'"');
		$this->load->view('user/koordinator/profil',$data,FALSE);
	}
	/*report data koordinator, hanya boleh diakses {super} koordinator*/
	public function getData(){
		if (empty($this->session->userdata('koordinator'))) {
			redirect('koordinator/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh koordinator"));
		}
		$this->load->model('Mkoordinator');
		$data = $this->Mkoordinator->getData();
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

			$actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/koordinator/edit/'.$id_koordinator.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_koordinator('.$id_koordinator.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        

			$entry = array('id' => $id_koordinator,
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
		$this->function_lib->cek_auth(array("super_admin","admin"));
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('koordinator');
        $crud->set_subject('Data Koordinator');
        $crud->set_language('indonesian');
        
        
        $crud->columns('id_admin_koordinator', 'Ubah Password','username_koordinator','email_koordinator','nama_koordinator','no_hp_koordinator','alamat_koordinator','status_koordinator');                 
	    
	    $crud->where('status_koordinator != "deleted"');
        $crud->order_by('id_koordinator','DESC');
        $action = $this->uri->segment(4,0);

        $this->load->model('Mopd');

        if ($level == "admin") {  
        	$crud->set_relation('id_admin_koordinator','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)','status!="deleted" AND id_admin="'.$id_user.'"');          
            $crud->where("koordinator.id_admin_koordinator",$id_user);
            // $crud->field_type('id_admin_koordinator', 'readonly', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                // $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_kolektor = $this->function_lib->get_one('id_koordinator','koordinator','id_koordinator="'.$pk.'" AND id_admin_koordinator="'.$id_user.'"');
                    if (empty($id_kolektor)) {
                        redirect(base_url().'user/koordinator/index/');
                        exit();
                    }
                }
                
            }            
            // $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        	$dataOpd = $this->Mopd->getAllOpd("id_opd IN (SELECT id_opd_admin FROM admin WHERE id_admin=".$this->db->escape($id_user).")");
        }else{
        	$dataOpd = $this->Mopd->getAllOpd();
        	$crud->set_relation('id_admin_koordinator','admin','id_opd_admin,(SELECT label_opd FROM opd where id_opd=id_opd_admin)','status!="deleted"');
        }
        
        $crud->display_as('id_admin_koordinator','OPD Admin')
             ->display_as('nama_koordinator','Nama')
             ->display_as('username_koordinator','Username')
             ->display_as('email_koordinator','Email')
             ->display_as('no_hp_koordinator','No HP')             
             ->display_as('alamat_koordinator','Alamat')             
             ->display_as('status_koordinator','STATUS') ;                                      

        $crud->unset_texteditor(array('alamat_koordinator','full_text'));
        $crud->change_field_type('password_koordinator', 'password');
        $crud->unique_fields(['username_koordinator','email_koordinator']);        

        $crud->callback_column('Ubah Password', array($this, 'link_ubah_password'));        
        $crud->required_fields('id_admin_koordinator','nama_koordinator','username_koordinator','password_koordinator','email_koordinator','status_koordinator');
        $crud->callback_after_insert(array($this, 'cpass'));
        $crud->unset_edit_fields('password_koordinator');
        $crud->unset_add_fields('status_koordinator');
        $data = $crud->render();
        $data->state = $crud->getState();
        
        $data->dataOpd = $dataOpd;

        
 
        $this->load->view('user/koordinator/index', $data, FALSE);
    }
    public function link_ubah_password($value, $row){
    	$this->function_lib->cek_auth(array("super_admin","admin"));
        return '<a href="'.base_url("user/koordinator/ubah_password/".$row->id_koordinator).'" class="btn btn-info btn-sm"><i class="fa fa-key"></i></a>';
    }
    public function ubah_password($id_koordinator){
    	$this->function_lib->cek_auth(array("super_admin","admin"));
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $id_koordinator = $this->function_lib->get_one('id_koordinator','koordinator','id_koordinator="'.$id_koordinator.'"');
        if (empty($id_koordinator) AND ($level!="owner" OR $level!="kasir")) {
            redirect(base_url().'user/koordinator/index/');
            exit();
        }else{
            $data['id_koordinator'] = $id_koordinator;
            $this->load->view('user/koordinator/ubah_password', $data, FALSE);
        }
        
    }
    public function cpass($post_array,$primary_key){
    	$this->function_lib->cek_auth(array("super_admin","admin"));
        $hash = hash('sha512',$post_array['password_koordinator'] . config_item('encryption_key'));
        $this->db->set("password_koordinator",$hash);
        $this->db->where('id_koordinator', $primary_key);
        $this->db->update('koordinator');
     
        return true;
    }
    public function change_password($id_koordinator){
        $this->function_lib->cek_auth(array('admin','super_admin'));
        if($this->input->post('change_password')){
            $this->load->model('Mkoordinator');
            $validasiChangePassword = $this->Mkoordinator->changePassword($id_koordinator); 
            header('Content-Type: application/json');                       
            $status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
            $msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
            $error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
            echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
        }
    }
    
	public function delete($id_koordinator){
		$this->function_lib->cek_auth(array('super_admin'));
		$this->load->model('Mkoordinator');
		$status = 500;
		$msg = "Gagal";
		if (empty($this->session->userdata('koordinator'))) {
			echo json_encode(array("status"=>$status,"msg"=>"Akses ditolak"));
		}
		header("Content-type:application/json");
		$cek = $this->function_lib->get_one('id_koordinator','koordinator','id_koordinator="'.$id_koordinator.'"');
		if (trim($cek)!="") {		
			$response = $this->Mkoordinator->delete($id_koordinator);
			$status = $response['status'];
			$msg = $response['msg'];
		}else{
			$status = 500;
			$msg = "Data tidak ditemukan";
		}

		echo json_encode(array("status"=>$status,"msg"=>$msg));
	}
	public function edit($id_koordinator){
		if (empty($this->session->userdata('koordinator'))) {
			redirect('koordinator/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh koordinator"));
		}
		$this->load->model('Mkoordinator');
		if ($this->input->post('edit')) {
			$cek = $this->function_lib->get_one('id_koordinator','koordinator','id_koordinator="'.$id_koordinator.'"');
			if (trim($cek)!="") {		
				$response = $this->Mkoordinator->edit($id_koordinator);
				$status = $response['status'];
				$msg = $response['msg'];
				
			}else{
				$status = 500;
				$msg = "Data tidak ditemukan";
			}		
			redirect(base_url().'user/koordinator?status='.$status.'&msg='.base64_encode($msg));
		}
		$data['koordinator'] = $this->function_lib->get_row('koordinator','id_koordinator="'.$id_koordinator.'"');
		$this->load->view('user/koordinator/edit', $data, FALSE);
	}
	public function tambah(){
		if (empty($this->session->userdata('koordinator'))) {
			redirect('koordinator/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh koordinator"));
		}
		$this->load->model('Mkoordinator');
		if ($this->input->post('tambah')) {
			$validasi = $this->Mkoordinator->validasi();
			if (trim($validasi['status'])==200) {		
				$response = $this->Mkoordinator->tambah();
				$status = $response['status'];
				$msg = $response['msg'];
				redirect(base_url().'user/koordinator?status='.$status.'&msg='.base64_encode($msg));
			}else{
				$status = 500;
				$msg = $validasi['msg'];
				redirect(base_url().'user/koordinator/tambah?status='.$status.'&msg='.base64_encode($msg));
			}		
		}		
		$data=array();
		$this->load->view('user/koordinator/tambah', $data, FALSE);
	}
	
}

/* End of file koordinator.php */
/* Location: ./application/controllers/user/koordinator.php */