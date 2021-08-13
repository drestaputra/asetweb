<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hari_libur extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","owner"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
	}		
 
    public function index() {
        $this->load->config('grocery_crud');        
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('hari_libur');        
        $crud->set_subject('Hari Libur');
        $crud->set_language('indonesian');        
        
        if ($level == "owner") {            
            $crud->where("hari_libur.id_owner",$id_user);
            $crud->or_where("hari_libur.id_owner", null);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {                    
                    $crud->unset_edit_fields(array('id_owner'));
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_hari_libur = $this->function_lib->get_one('id_hari_libur','hari_libur','id_hari_libur="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_hari_libur)) {
                        redirect(base_url().'hari_libur/index/');
                        exit();
                    }
                }else{
                    $crud->set_relation('id_owner','owner','nama_koperasi');
                }
                
            }
            $crud->columns('tgl_hari_libur','deskripsi_hari_libur');                 
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');            
            $crud->required_fields('id_owner');
            $crud->columns('tgl_hari_libur','deskripsi_hari_libur','id_owner');                 
        }

        
        $crud->display_as('tgl_hari_libur','Tanggal Libur')             
             ->display_as('deskripsi_hari_libur','Keterangan Libur')             
             ->display_as('id_owner','Koperasi');             
        $crud->set_rules('tgl_hari_libur', 'Tgl Hari Libur', array(                            
        'required',
         array(
                'tgl_hari_libur',
                function ($str) {
                    $tgl_hari_libur = $this->input->post('tgl_hari_libur');
                    $tgl_hari_libur = str_replace("/","-",$tgl_hari_libur);
                    $user_sess = $this->function_lib->get_user_level();
                    $level = isset($user_sess['level']) ? $user_sess['level'] : "";
                    $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
                    $addition_where = "";
                    if ($level == "owner") {   
                        $addition_where .= " AND id_owner='".$id_user."'";
                    }
                    // tgl hari libur tidak boleh sama, beda owner boleh, jika perusahaan sudah set hari libur tidak boleh sama
                    $cek_tgl = $this->function_lib->get_one('tgl_hari_libur','hari_libur','(tgl_hari_libur="'.date("Y-m-d", strtotime($tgl_hari_libur)).'" '.$addition_where.') OR (tgl_hari_libur="'.date("Y-m-d", strtotime($tgl_hari_libur)).'" AND id_owner IS NULL)');
                                        
                    if ( !empty($cek_tgl)) {
                        $this->form_validation->set_message('tgl_hari_libur', 'Tanggal tersebut sudah di set libur.');
                        return false;
                    }  else{
                        return true;
                    }                      
                }
            )
        ));
        // $crud->callback_field('deskripsi_request',array($this,'clearhtml'));
        $crud->unset_texteditor(array('deskripsi_hari_libur','full_text'));
                    
        $crud->required_fields('tgl_hari_libur','deskripsi_hari_libur');        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('hari_libur/index', $data, FALSE);

    }          
}

/* End of file Hari_libur.php */
/* Location: ./application/controllers/Hari_libur.php */