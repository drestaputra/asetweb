<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kolektor extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('kolektor');
        $crud->set_subject('Data Kolektor');
        $crud->set_language('indonesian');
        $crud->columns('Ubah Password','status','no_ktp','nama','username','id_owner','no_hp','email','alamat','provinsi','kabupaten','kecamatan','warga_negara');                                
	    $crud->set_relation('provinsi','provinsi','nama');
        $crud->set_relation('kabupaten','kabupaten','nama');
        $crud->order_by('id_kolektor','DESC');
        $action = $this->uri->segment(4,0);
        $where_kelurahan = $where_kecamatan = null;
        if (!empty($action) AND $action=="add") {
            $where_kecamatan = $where_kelurahan = "id<10";
        }else if(!empty($action) AND $action=="edit"){
            $id = $this->uri->segment(5,0);            
            $nasabahArr = $this->function_lib->get_row('kolektor','id_kolektor='.$this->db->escape($id).'');
            $id_kecamatan = isset($nasabahArr['kecamatan']) ? $nasabahArr['kecamatan'] : 0;            
            $where_kecamatan = 'id="'.$id_kecamatan.'"';            
        }
        if ($level == "owner") {            
            $crud->where("kolektor.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                // $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_kolektor)) {
                        redirect(base_url().'user/kolektor/index/');
                        exit();
                    }
                }
                
            }            
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else if ($level == "kasir") {            
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("kolektor.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                // $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_kolektor)) {
                        redirect(base_url().'user/kolektor/index/');
                        exit();
                    }
                }
                
            }            
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');                
        }
        $crud->set_relation('kecamatan','kecamatan','nama',$where_kecamatan);
        $crud->set_relation_dependency('kecamatan','kabupaten','id_kabupaten');

        $crud->display_as('nama','Nama')
             ->display_as('username','Username')
             ->display_as('id_owner','Koperasi')
             ->display_as('no_hp','No. HP')
             ->display_as('no_ktp','No. KTP')
             ->display_as('email','Email')
             ->display_as('alamat','Alamat')
             ->display_as('provinsi','Provinsi')
             ->display_as('kabupaten','Kabupaten')
             ->display_as('kecamatan','Kecamatan')
             ->display_as('warga_negara','Warganegara')
             ->display_as('status','Status');
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->change_field_type('password', 'password');
        $crud->unique_fields(['username','no_ktp','email']);        

        $crud->callback_column('Ubah Password', array($this, 'link_ubah_password'));        
        $crud->required_fields('no_ktp','nama','username','password','id_owner','email','provinsi','kabupaten','kecamatan','status');
        $crud->callback_after_insert(array($this, 'cpass'));
        $crud->unset_edit_fields('password');
        $data = $crud->render();
        $data->state = $crud->getState();
 
        $this->load->view('user/kolektor/index', $data, FALSE);
    }
    public function encrypt_password_callback($val) {
    	// return hash('sha512',$val . config_item('encryption_key'));		
    	return "tes";
    }
    public function link_ubah_password($value, $row){
        return '<a href="'.base_url("user/kolektor/ubah_password/".$row->id_kolektor).'" class="btn btn-info btn-sm"><i class="fa fa-key"></i></a>';
    }
    public function ubah_password($id_kolektor){
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        if ($level == "owner") {
            $id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'" AND id_owner="'.$id_user.'"');            
        }else if($level == "kasir"){
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'" AND id_owner="'.$id_user.'"');            
        }
        $id_kolektor = $this->function_lib->get_one('id_kolektor','kolektor','id_kolektor="'.$id_kolektor.'" AND id_owner="'.$id_user.'"');
        if (empty($id_kolektor) AND ($level!="owner" OR $level!="kasir")) {
            redirect(base_url().'user/kolektor/index/');
            exit();
        }else{
            $data['id_kolektor'] = $id_kolektor;
            $this->load->view('user/kolektor/ubah_password', $data, FALSE);
        }
        
    }
    public function cpass($post_array,$primary_key){
        $hash = hash('sha512',$post_array['password'] . config_item('encryption_key'));
        $this->db->set("password",$hash);
        $this->db->where('id_kolektor', $primary_key);
        $this->db->update('kolektor');
     
        return true;
    }
    public function change_password($id_kolektor){
        $this->function_lib->cek_auth(array('owner','admin','super_admin'));
        if($this->input->post('change_password')){
            $this->load->model('Mkolektor');
            $validasiChangePassword = $this->Mkolektor->changePassword($id_kolektor); 
            header('Content-Type: application/json');                       
            $status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
            $msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
            $error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
            echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
        }
    }
}

/* End of file Kolektor.php */
/* Location: ./application/controllers/Kolektor.php */