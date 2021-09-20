<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muser extends CI_Model {

	function changePassword($id_user=0){
        $status = 500;
        $msg = "";
        $old_password = $this->input->post('old_password',TRUE);        
        $new_password = $this->input->post('new_password',TRUE);        
        $repeat_password = $this->input->post('repeat_password',TRUE);        
        $error = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|matches[repeat_password]');  
        if (!empty($this->session->userdata('user'))) {
	        $oldPasswordHash = hash('sha512',$old_password . config_item('encryption_key'));        
	        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');  
        }
        $this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required');  
        if ($this->form_validation->run() == TRUE) {            
		    	if (!empty($this->session->userdata('user'))) {
                	$id_user = $this->session->userdata('user')['id_user'];                    
                    $id_user = $this->function_lib->get_one('id_user','user','password='.$this->db->escape($oldPasswordHash).' AND id_user = '.$this->db->escape($id_user).'');
		    	}
                if (floatval($id_user) != 0) {     
                    $columnUpdate = array(
                        "password" => hash('sha512',$new_password . config_item('encryption_key')),   
                    );                    
                    $this->db->where('id_user', $id_user);
                    $this->db->update('user', $columnUpdate);
                    $status=200;
                    $msg="Berhasil mengubah password";
                }else{
                    $status = 500;
                    $msg = "Password lama tidak sesuai";
                }
            $error = array(
                "old_password" => form_error('old_password'),
                "new_password" => form_error('new_password'),
                "repeat_password" => form_error('repeat_password'),
            );
                

        } else {
            $status=500;
            $msg="Gagal, ".validation_errors(' ',' ');
            $error = array(
                "old_password" => form_error('old_password'),
                "new_password" => form_error('new_password'),
                "repeat_password" => form_error('repeat_password'),
            );
        }            
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);            
    }
	
}

/* End of file Muser.php */
/* Location: ./application/models/Muser.php */