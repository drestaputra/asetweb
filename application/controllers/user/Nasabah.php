<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nasabah extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner","kasir"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $crud = new Ajax_grocery_CRUD();        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('nasabah');
        $crud->set_subject('Data Nasabah');
        $crud->set_language('indonesian');
        $crud->columns('status','nama_nasabah','username','foto_nasabah','id_owner','id_kolektor','no_hp','email','alamat_rumah','provinsi','kabupaten','kecamatan','kelurahan','warga_negara');                        
	    $crud->set_relation('provinsi','provinsi','nama');
        $crud->set_relation('provinsi','provinsi','nama');
        
        
        $crud->order_by("id_nasabah","DESC");

        $action = $this->uri->segment(4,0);
        $where_kelurahan = $where_kecamatan = null;
        if (!empty($action) AND $action=="add") {
            $where_kecamatan = $where_kelurahan = "id<10";
        }else if(!empty($action) AND $action=="edit"){
            $id = $this->uri->segment(5,0);            
            $nasabahArr = $this->function_lib->get_row('nasabah','id_nasabah='.$this->db->escape($id).'');
            $id_kecamatan = isset($nasabahArr['kecamatan']) ? $nasabahArr['kecamatan'] : 0;
            $id_kelurahan = isset($nasabahArr['kelurahan']) ? $nasabahArr['kelurahan'] : 0;
            $where_kecamatan = 'id="'.$id_kecamatan.'"';
            $where_kelurahan = 'id="'.$id_kelurahan.'"';
        }
        if ($level == "owner") {            
            $crud->where("nasabah.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                // $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_nasabah = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_nasabah)) {
                        redirect(base_url().'nasabah/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else if ($level == "kasir") {            
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("nasabah.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                // $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_nasabah = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_nasabah)) {
                        redirect(base_url().'nasabah/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');                
            $crud->set_relation('id_kolektor','kolektor','nama');                
        }
        $crud->set_relation('kecamatan','kecamatan','nama',$where_kecamatan);
        $crud->set_relation_dependency('kecamatan','kabupaten','id_kabupaten');
        $crud->set_relation('kelurahan','desa','nama',$where_kelurahan);        
        $crud->set_relation_dependency('kelurahan','kecamatan','id_kecamatan');
        // $crud->set_relation('id_nasabah','nasabah','username');
        $crud->set_relation_dependency('id_kolektor','id_owner','id_owner');
        $crud->set_field_upload('foto_nasabah','api/assets/foto_nasabah');        
        $crud->display_as('nama_nasabah','Nama')
             ->display_as('username','Username')
             ->display_as('foto_nasabah','Foto')
             ->display_as('id_owner','Koperasi')
             ->display_as('id_kolektor','Kolektor')
             ->display_as('no_hp','No. HP')
             // ->display_as('no_ktp','No. KTP')
             ->display_as('email','Email')
             ->display_as('alamat','Alamat')
             ->display_as('provinsi','Provinsi')
             ->display_as('kabupaten','Kabupaten')
             ->display_as('kecamatan','Kecamatan')             
             ->display_as('status','Status');
        $crud->unset_texteditor(array('alamat','full_text'));
        $crud->change_field_type('password', 'password');
        $crud->unique_fields(['username','email']);
        $crud->unset_texteditor(array('alamat_rumah','full_text'));                
        $crud->unset_texteditor(array('alamat_tempat_kerja','full_text'));                

        // $crud->callback_column('reward_item_timestamp', array($this, 'callback_date'));        
        $crud->required_fields('nama_nasabah','username','password','id_owner','id_kolektor','email','provinsi','kabupaten','kecamatan','kelurahan','status','tgl_bergabung');
        $crud->unset_add_fields('no_nasabah','tgl_bergabung','password','status');
        $crud->unset_edit_fields('no_nasabah','password','tgl_bergabung');
        // $crud->callback_column('password', 'encrypt_password_callback');        
        // $crud->unset_export();
        // $crud->unset_read();
        // $crud->unset_print();
        $data = $crud->render();        
        $data->state = $crud->getState();
 
        $this->load->view('user/nasabah/index', $data, FALSE);
    }
}

/* End of file Nasabah.php */
/* Location: ./application/controllers/Nasabah.php */