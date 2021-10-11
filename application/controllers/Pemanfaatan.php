<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Pemanfaatan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","koordinator","pengurus_barang"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
		$this->load->model('Mpemanfaatan');
	}		
 
    public function index() {
          $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $crud->set_theme('adminlte');
        $crud->set_table('pemanfaatan');        
        $crud->set_subject('Data pemanfaatan');
		$crud->set_language('indonesian');
        $crud->where("status_pemanfaatan != 'deleted'");

        $crud->columns('isi_pemanfaatan');
        $crud->unset_fields('status_pemanfaatan');
        $crud->unique_fields(array('isi_pemanfaatan'));

        $crud->callback_delete(array($this,'delete_data'));

        $data = $crud->render();
        // $data->dataPemanfaatan = 
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
 
        $this->load->view('pemanfaatan/index', $data, FALSE);

    }   
    function get_all_pemanfaatan(){
        header('Content-Type: application/json');
        $this->db->where('status_pemanfaatan != "deleted"');
        $query = $this->db->get('pemanfaatan');
        $data = $query->result_array();
        echo(json_encode($data));
    }
    function tambah_baru(){
        header('Content-Type: application/json');
        $status = 500;
        $msg = "";
        $isi_pemanfaatan = $this->input->post('isi_pemanfaatan');
        if (!empty($isi_pemanfaatan)) {
            $status = 200;
            $msg = "Sukses";
            $this->db->set("isi_pemanfaatan", $isi_pemanfaatan); 
            $this->db->insert('pemanfaatan');
        }else{
            $status = 500;
            $msg = "Gagal menambah pemanfaatan baru";
        }

        $data = array(
            "status" => $status,
            "msg" => $msg
        );
        echo(json_encode($data));
    }

    function delete_data($primary_key){        
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $columnUpdate = array(
            'status_pemanfaatan' => 'deleted'
        );
        $this->db->where('id_pemanfaatan', $primary_key);
        return $this->db->update('pemanfaatan', $columnUpdate);       
    } 
}

/* End of file Berita.php */
/* Location: ./application/controllers/Berita.php */