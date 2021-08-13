<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpanan extends CI_Controller {

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
        $crud->set_table('simpanan');
        $crud->where('status_simpanan','aktif');
        $crud->order_by('tgl_simpanan','DESC');
        $crud->set_subject('Data Simpanan');
        $crud->set_language('indonesian');
        $unset = array('input_oleh','input_oleh_id','update_oleh','update_oleh_id','id_simpanan','last_update');
        if ($level == "owner") {            
            $crud->where("simpanan.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_simpanan)) {
                        redirect(base_url().'simpanan/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else if ($level == "kasir") {            
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("simpanan.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_simpanan)) {
                        redirect(base_url().'simpanan/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');
            $crud->set_relation('id_nasabah','nasabah','username');
            $crud->set_relation('id_kolektor','kolektor','username');                                
        }

        $crud->columns('id_simpanan','status_simpanan','id_owner','id_kolektor','id_nasabah','Laporan','jumlah_simpanan','tgl_simpanan','input_oleh','last_update');                
        
        $crud->set_relation_dependency('id_nasabah','id_kolektor','id_kolektor');        
        $crud->set_relation('id_kolektor','kolektor','username');                                
        $crud->set_relation_dependency('id_kolektor','id_owner','id_owner');
        $crud->field_type('jumlah_simpanan','integer');                        
        $crud->unset_texteditor(array('note','full_text'));        
        $crud->display_as('id_owner','Koperasi')
             ->display_as('id_kolektor','Kolektor')
             ->display_as('id_nasabah','Nasabah')
             ->display_as('jumlah_simpanan','Simpanan (Rp.)')             
             ->display_as('status_simpanan','STATUS') ;                         
             // jika insert cek id_nasabah harus uniq, 
        $operation = $crud->getState();
        if($crud->getState() == 'add' || $crud->getState() == 'insert_validation') 
        {          
          $crud->set_rules('id_nasabah', 'Nasabah', array(                            
            'required',
             array(
                    'id_nasabah',
                    function ($str) {
                        $id_nasabah = $this->input->post('id_nasabah',true);
                        $id_nasabah = $this->function_lib->get_one('id_nasabah','simpanan','id_nasabah="'.$id_nasabah.'" AND status_simpanan="aktif"');
                                            
                        if ( !empty($id_nasabah)) {
                            $this->form_validation->set_message('id_nasabah', 'Nasabah sudah mempunyai data simpanan aktif.');
                            return false;
                        }  else{
                            return true;
                        }                      
                    }
                )
            ));
        }else{                                    
            array_push($unset,"id_nasabah","id_owner","id_kolektor","jumlah_simpanan");            
        }
                
        $crud->callback_column('Laporan',array($this,'link_laporan'));        
        $crud->callback_after_insert(array($this, 'insert_baru'));
        $crud->callback_after_update(array($this, 'hitung_jumlah_simpanan_setelah_bunga'));        
        $crud->callback_delete(array($this,'delete_data'));
        $crud->required_fields('id_nasabah','id_owner','id_kolektor','jumlah_simpanan','status_simpanan');
        $crud->unset_add_fields(array('input_oleh','input_oleh_id','update_oleh','update_oleh_id','id_simpanan','last_update','tgl_simpanan','status_simpanan'));
        $crud->unset_edit_fields($unset);        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('simpanan/index', $data, FALSE);

    } 
    public function non_aktif(){
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('simpanan');
        $crud->where('status_simpanan','non_aktif');
        $crud->order_by('tgl_simpanan','DESC');
        $crud->set_subject('Data Simpanan');
        $crud->set_language('indonesian');
        $unset = array('input_oleh','input_oleh_id','update_oleh','update_oleh_id','id_simpanan','last_update');
        if ($level == "owner") {            
            $crud->where("simpanan.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_simpanan)) {
                        redirect(base_url().'simpanan/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
         }else if ($level == "kasir") {            
            $id_user = $this->function_lib->get_one('id_owner','kasir','id_kasir='.$this->db->escape($id_user).'');
            $crud->where("simpanan.id_owner",$id_user);
            $crud->field_type('id_owner', 'hidden', $id_user);            
            if($crud->getState() != 'add' AND $crud->getState() != 'list') {
                $crud->set_relation('id_owner','owner','nama_koperasi');
                if ($crud->getState() == "read" OR $crud->getState() == "edit") {
                    $stateInfo = (array) $crud->getStateInfo();
                    $pk = isset($stateInfo['primary_key']) ? $stateInfo['primary_key'] : 0;
                    $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan="'.$pk.'" AND id_owner="'.$id_user.'"');
                    if (empty($id_simpanan)) {
                        redirect(base_url().'simpanan/index/');
                        exit();
                    }
                }
                
            }
            $crud->set_relation('id_nasabah','nasabah','username','id_nasabah IN (SELECT id_nasabah FROM nasabah where id_owner="'.$id_user.'")');
            $crud->set_relation('id_kolektor','kolektor','username','id_kolektor IN (SELECT id_kolektor FROM kolektor WHERE id_owner="'.$id_user.'")');                                
        }else{
            $crud->set_relation('id_owner','owner','nama_koperasi');
            $crud->set_relation('id_nasabah','nasabah','username');
            $crud->set_relation('id_kolektor','kolektor','username');                                
        }

        $crud->columns('id_simpanan','status_simpanan','id_owner','id_kolektor','id_nasabah','Laporan','jumlah_simpanan','tgl_simpanan','input_oleh','last_update');                
        
        $crud->set_relation_dependency('id_nasabah','id_kolektor','id_kolektor');        
        $crud->set_relation('id_kolektor','kolektor','username');                                
        $crud->set_relation_dependency('id_kolektor','id_owner','id_owner');
        $crud->field_type('jumlah_simpanan','integer');                        
        $crud->unset_texteditor(array('note','full_text'));        
        $crud->display_as('id_owner','Koperasi')
             ->display_as('id_kolektor','Kolektor')
             ->display_as('id_nasabah','Nasabah')
             ->display_as('jumlah_simpanan','Simpanan (Rp.)')             
             ->display_as('status_simpanan','STATUS') ;                         
             // jika insert cek id_nasabah harus uniq, 
        $operation = $crud->getState();
        if($crud->getState() == 'add' || $crud->getState() == 'insert_validation') 
        {
          $crud->set_rules('id_nasabah', 'Nasabah','trim|required|is_unique[simpanan.id_nasabah]',array("is_unique"=>"Nasabah sudah mempunyai data simpanan"));
        }else{                                    
            array_push($unset,"id_nasabah","id_owner","id_kolektor","jumlah_simpanan");                                 
        }
                
        $crud->callback_column('Laporan',array($this,'link_laporan'));        
        $crud->callback_after_insert(array($this, 'insert_baru'));
        $crud->callback_after_update(array($this, 'hitung_jumlah_simpanan_setelah_bunga'));        
        $crud->callback_delete(array($this,'delete_data'));
        $crud->required_fields('id_nasabah','id_owner','id_kolektor','jumlah_simpanan','status_simpanan');
        $crud->unset_add_fields(array('input_oleh','input_oleh_id','update_oleh','update_oleh_id','id_simpanan','last_update','tgl_simpanan','status_simpanan'));
        $crud->unset_edit_fields($unset);        
        $data = $crud->render();
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
        $this->load->view('simpanan/index', $data, FALSE);
    }
    public function link_laporan($value, $row){
        return '<a href="'.base_url("riwayat_simpanan/index?id_simpanan=".$row->id_simpanan).'" class="btn btn-info btn-sm"><i class="fa fa-list-alt"></i></a>';
    }
    function hitung_jumlah_simpanan_setelah_bunga($post_array,$primary_key)
    {                
        $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
        $oleh = $username_sess. " - ". $level_sess;
        $oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;        
        $this->db->query('UPDATE `simpanan` SET             
            `last_update` = "'.date("Y-m-d H:i:s").'",
            `update_oleh_id` = "'.$oleh_id.'",
            `update_oleh` = "'.$oleh.'"
             WHERE `id_simpanan` = '.$primary_key.''
        );        
        return true;
    }
    function insert_baru($post_array,$primary_key){
        $user_sess = $this->function_lib->get_user_level();
        $username_sess = isset($user_sess['username']) ? $user_sess['username'] : "";
        $level_sess = isset($user_sess['level']) ? $user_sess['level'] : "";
        $oleh = $username_sess. " - ". $level_sess;
        $oleh_id = isset($user_sess['id_user']) ? $user_sess['id_user'] : 0;    
        $jumlah_simpanan = isset($post_array['jumlah_simpanan']) ? $post_array['jumlah_simpanan'] : "0";
        $this->db->query('UPDATE `simpanan` SET `jumlah_simpanan` = "'.$jumlah_simpanan.'",`input_oleh_id` = "'.$oleh_id.'",`input_oleh` = "'.$oleh.'",`tgl_simpanan` = "'.date("Y-m-d H:i:s").'",`last_update` = "'.date("Y-m-d H:i:s").'" WHERE `id_simpanan` = '.$primary_key.'');        
        $columnInsert = array("id_simpanan" => $primary_key,"jumlah_riwayat_simpanan" => $jumlah_simpanan,"tipe_riwayat" => "simpanan","input_oleh" => $oleh,"input_oleh_id" => $oleh_id, "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),"keterangan_riwayat" => "Simpanan pertama dengan ID Simpanan : ".$primary_key);
        $this->db->insert('riwayat_simpanan', $columnInsert);
        return true;
    }
    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        if ($level == "owner") {            
            $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan="'.$primary_key.'" AND id_owner="'.$id_user.'"');
            
            if (empty($id_simpanan)) {
                return false;
            }else{
                $columnUpdate = array(
                    'status_simpanan' => 'non_aktif'
                );
                $this->db->where('id_simpanan', $primary_key);
                return $this->db->update('simpanan', $columnUpdate);            
            }
        }else{
            $columnUpdate = array(
                'status_simpanan' => 'non_aktif'
            );
            $this->db->where('id_simpanan', $primary_key);
            return $this->db->update('simpanan', $columnUpdate);        
        }
    } 
}

/* End of file Simpanan.php */
/* Location: ./application/controllers/Simpanan.php */