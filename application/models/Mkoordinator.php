<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mkoordinator extends CI_Model {

	function changePassword($id_koordinator=0){
        $status = 500;
        $msg = "";
        $old_password = $this->input->post('old_password',TRUE);        
        $new_password = $this->input->post('new_password',TRUE);        
        $repeat_password = $this->input->post('repeat_password',TRUE);        
        $error = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|matches[repeat_password]');  
        if (!empty($this->session->userdata('koordinator'))) {
	        $oldPasswordHash = hash('sha512',$old_password . config_item('encryption_key'));        
	        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');  
        }
        $this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required');  
        if ($this->form_validation->run() == TRUE) {            
		    	if (!empty($this->session->userdata('koordinator'))) {
                	$id_koordinator = $this->session->userdata('koordinator')['id_koordinator'];                    
                    $id_koordinator = $this->function_lib->get_one('id_koordinator','koordinator','password_koordinator='.$this->db->escape($oldPasswordHash).'');
		    	}
                if (floatval($id_koordinator) != 0) {     
                    $columnUpdate = array(
                        "password_koordinator" => hash('sha512',$new_password . config_item('encryption_key')),   
                    );                    
                    $this->db->where('id_koordinator', $id_koordinator);
                    $this->db->update('koordinator', $columnUpdate);
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

/* End of file Mkoordinator.php */
/* Location: ./application/models/Mkoordinator.php */