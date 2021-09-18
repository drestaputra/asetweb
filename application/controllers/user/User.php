<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('user');
        $crud->set_subject('Data User');
        $crud->set_language('indonesian');
        $crud->columns('Ubah Password','status','nama_lengkap','username','instansi','no_hp','email','alamat','provinsi','kabupaten','kecamatan');                                
	    $crud->set_relation('provinsi','provinsi','nama');
        $crud->set_relation('kabupaten','kabupaten','nama');
        $crud->order_by('id_user','DESC');
        $action = $this->uri->segment(4,0);
        $where_kelurahan = $where_kecamatan = null;
        if (!empty($action) AND $action=="add") {
            $where_kecamatan = $where_kelurahan = "id<10";
        }else if(!empty($action) AND $action=="edit"){
            $id = $this->uri->segment(5,0);            
            $nasabahArr = $this->function_lib->get_row('user','id_user='.$this->db->escape($id).'');
            $id_kecamatan = isset($nasabahArr['kecamatan']) ? $nasabahArr['kecamatan'] : 0;            
            $where_kecamatan = 'id="'.$id_kecamatan.'"';            
        }
        $crud->set_relation('kecamatan','kecamatan','nama',$where_kecamatan);
        $crud->set_relation_dependency('kecamatan','kabupaten','id_kabupaten');

        $crud->display_as('nama_lengkap','Nama Lengkap')
             ->display_as('username','Username')
             ->display_as('instansi','instansi')
             ->display_as('no_hp','No. HP')
             ->display_as('email','Email')
             ->display_as('alamat','Alamat')
             ->display_as('provinsi','Provinsi')
             ->display_as('kabupaten','Kabupaten')
             ->display_as('kecamatan','Kecamatan')
             ->display_as('status','Status');
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->change_field_type('password', 'password');
        $crud->unique_fields(['username','no_ktp','email']);        

        $crud->callback_column('Ubah Password', array($this, 'link_ubah_password'));        
        $crud->callback_after_insert(array($this, 'cpass'));
        $crud->unset_edit_fields('password');
        $crud->unset_add_fields('status');
        $data = $crud->render();
        $data->state = $crud->getState();
 
        $this->load->view('user/user/index', $data, FALSE);
    }
    public function encrypt_password_callback($val) {
    	// return hash('sha512',$val . config_item('encryption_key'));		
    	return "tes";
    }
    public function link_ubah_password($value, $row){
        return '<a href="'.base_url("user/user/ubah_password/".$row->id_user).'" class="btn btn-info btn-sm"><i class="fa fa-key"></i></a>';
    }
    public function ubah_password($id_user){
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        
        $id_user = $this->function_lib->get_one('id_user','user','id_user="'.$id_user.'"');
        if (empty($id_user)) {
            redirect(base_url().'user/user/index/');
            exit();
        }else{
            $data['id_user'] = $id_user;
            $this->load->view('user/user/ubah_password', $data, FALSE);
        }
        
    }
    public function cpass($post_array,$primary_key){
        $hash = hash('sha512',$post_array['password'] . config_item('encryption_key'));
        $this->db->set("password",$hash);
        $this->db->where('id_user', $primary_key);
        $this->db->update('user');
     
        return true;
    }
    public function change_password($id_user){
        $this->function_lib->cek_auth(array('owner','admin','super_admin'));
        if($this->input->post('change_password')){
            $this->load->model('Muser');
            $validasiChangePassword = $this->Muser->changePassword($id_user); 
            header('Content-Type: application/json');                       
            $status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
            $msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
            $error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
            echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
        }
    }
}

/* End of file User.php */
/* Location: ./application/controllers/User.php */