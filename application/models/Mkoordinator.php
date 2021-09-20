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
                    $id_koordinator = $this->function_lib->get_one('id_koordinator','koordinator','password_koordinator='.$this->db->escape($oldPasswordHash).' AND id_koordinator='.$this->db->escape($id_koordinator).'');
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
    function cekLogin(){
        $pwd = $this->input->post('pwd',TRUE);
        $username = $this->input->post('username_koordinator',TRUE);
        $password = hash('sha512',$pwd . config_item('encryption_key'));        
        $this->db->where('username_koordinator', $username);
        $this->db->where('password_koordinator', $password);
        $this->db->where('status_koordinator="aktif"');
        $query=$this->db->get('koordinator');
        if ($query->num_rows()!=null) {         
            $data=$query->row_array();                  
            $this->session->set_userdata("koordinator",$data);        
            return array("status"=>200,"msg"=>"Berhasil Login");
        }else{
            return array("status"=>500,"msg"=>"Data User tidak ditemukan");         
        }
    }
    function validasi($id_koordinator=0){
        $status=200;
        $msg="";
        // $function_lib=$this->load->library('function_lib');        
        
        // exit();
        $username = $this->input->post('username_koordinator',TRUE);        
        $email_koordinator = $this->input->post('email_koordinator',TRUE);        
        if ($id_koordinator==0) {            
        $id_koordinator = isset($this->session->userdata('koordinator')['id_koordinator']) ? $this->session->userdata('koordinator')['id_koordinator'] : null;
        }
        // dapatkan data untuk edit
        $usernameOri = $this->function_lib->get_one('username_koordinator','koordinator','id_koordinator="'.$id_koordinator.'"');        
        $email_koordinatorOri = $this->function_lib->get_one('email_koordinator','koordinator','id_koordinator="'.$id_koordinator.'"');                
        $is_unique = ($username != $usernameOri)? '|is_unique[koordinator.username_koordinator]':'';
        $is_uniqueEmail = ($email_koordinator != $email_koordinatorOri)? '|is_unique[koordinator.email_koordinator]':'';
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username_koordinator', 'Username', 'required'.$is_unique,
             array(                
                'is_unique'     => 'Username sudah terpakai.'
            )
        );        
        $this->form_validation->set_rules('email_koordinator', 'Email', 'required'.$is_uniqueEmail,
            array(                
                'is_unique'     => 'Email sudah terpakai.'
            )
        );  
        // validasi tambah
        if ($this->input->post('tambah')) {
            $this->form_validation->set_rules('status_koordinator', 'Status', 'required',
                array(                
                    'required'     => '%s masih kosong.'
                )
            );  
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]',
                array(                
                    'required'     => '%s masih kosong.',
                    'min_length'   => '%s harus lebih dari 5 karakter.'
                )
            );  
            $this->form_validation->set_rules('conf_password', 'Konfirmasi Password', 'required|matches[password]',
                array(                
                    'required'     => '%s masih kosong.',
                    'matches'      => '%s tidak cocok'
                )
            );  
        }      
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil";
            $error = array(
                "username_koordinator" => form_error('username_koordinator'),
                "email_koordinator" => form_error('email_koordinator'),
                "status_koordinator" => form_error('status_koordinator'),
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
            );
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            $error = array(
                "username_koordinator" => form_error('username_koordinator'),
                "email_koordinator" => form_error('email_koordinator'),
                "status_koordinator" => form_error('status_koordinator'),
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
            );
        }
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);        
    }
    

    function editProfil(){
        $username_koordinator = $this->input->post('username_koordinator',TRUE);        
        $email_koordinator = $this->input->post('email_koordinator',TRUE);        
        $idKoordinator = $this->session->userdata('koordinator')['id_koordinator'];                
        $validasi = $this->validasi();      
        $status = 500;
        $msg = "";
        if ($validasi['status']==200) {
    
            $columnUpdate = array(
                "email_koordinator"=> $email_koordinator,
                "username_koordinator"=> $username_koordinator,
            );
            $this->db->where('id_koordinator="'.$idKoordinator.'"');
            $this->db->update('koordinator', $columnUpdate);
            $status = 200;
            $msg = "Berhasil Update";
        
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg);
    }
	
}

/* End of file Mkoordinator.php */
/* Location: ./application/models/Mkoordinator.php */