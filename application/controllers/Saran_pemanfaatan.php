<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Saran_pemanfaatan extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
		$this->function_lib->cek_auth(array("super_admin","admin","koordinator","pengurus_barang"));
		$this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
		$this->load->model('Msaran_pemanfaatan');
	}		
 
    public function index() {
          $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_xss_clean', false);
        $crud = new Ajax_grocery_CRUD();

        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        
        $crud->set_theme('adminlte');
        $crud->set_table('saran_pemanfaatan');        
        $crud->set_subject('Data Saran Pemanfaatan');
		$crud->set_language('indonesian');
        $crud->where("status_saran_pemanfaatan != 'deleted'");

        $crud->columns('isi_saran_pemanfaatan');
        $crud->unset_fields('status_saran_pemanfaatan');
        $crud->unique_fields(array('isi_saran_pemanfaatan'));

        $crud->callback_delete(array($this,'delete_data'));

        $data = $crud->render();
        // $data->dataSaranPemanfaatan = 
        $data->id_user = $id_user;
        $data->level = $level;
        $data->state_data = $crud->getState();
 
 
        $this->load->view('saran_pemanfaatan/index', $data, FALSE);

    }   
    function get_all_saran_pemanfaatan(){
        header('Content-Type: application/json');
        $this->db->where('status_saran_pemanfaatan != "deleted"');
        $query = $this->db->get('saran_pemanfaatan');
        $data = $query->result_array();
        echo(json_encode($data));
    }
    function tambah_baru(){
        header('Content-Type: application/json');
        $status = 500;
        $msg = "";
        $isi_saran_pemanfaatan = $this->input->post('isi_saran_pemanfaatan');
        if (!empty($isi_saran_pemanfaatan)) {
            $status = 200;
            $msg = "Sukses";
            $this->db->set('isi_saran_pemanfaatan', $isi_saran_pemanfaatan);
            $this->db->insert('saran_pemanfaatan');
        }else{
            $status = 500;
            $msg = "Saran pemanfaatan masih kosong";
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
            'status_saran_pemanfaatan' => 'deleted'
        );
        $this->db->where('id_saran_pemanfaatan', $primary_key);
        return $this->db->update('saran_pemanfaatan', $columnUpdate);       
    } 
}

/* End of file Berita.php */
/* Location: ./application/controllers/Berita.php */