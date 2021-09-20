<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpengurus_barang extends CI_Model {

	function changePassword($id_pengurus_barang=0){
        $status = 500;
        $msg = "";
        $old_password = $this->input->post('old_password',TRUE);        
        $new_password = $this->input->post('new_password',TRUE);        
        $repeat_password = $this->input->post('repeat_password',TRUE);        
        $error = array();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|matches[repeat_password]');  
        if (!empty($this->session->userdata('pengurus_barang'))) {
	        $oldPasswordHash = hash('sha512',$old_password . config_item('encryption_key'));        
	        $this->form_validation->set_rules('old_password', 'Password Lama', 'required');  
        }
        $this->form_validation->set_rules('repeat_password', 'Konfirmasi Password', 'required');  
        if ($this->form_validation->run() == TRUE) {            
		    	if (!empty($this->session->userdata('pengurus_barang'))) {
                	$id_pengurus_barang = $this->session->userdata('pengurus_barang')['id_pengurus_barang'];                    
                    $id_pengurus_barang = $this->function_lib->get_one('id_pengurus_barang','pengurus_barang','password_pengurus_barang='.$this->db->escape($oldPasswordHash).' AND id_pengurus_barang='.$this->db->escape($id_pengurus_barang).'');
		    	}
                if (floatval($id_pengurus_barang) != 0) {     
                    $columnUpdate = array(
                        "password_pengurus_barang" => hash('sha512',$new_password . config_item('encryption_key')),   
                    );                    
                    $this->db->where('id_pengurus_barang', $id_pengurus_barang);
                    $this->db->update('pengurus_barang', $columnUpdate);
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
        $username = $this->input->post('username',TRUE);
        $password = hash('sha512',$pwd . config_item('encryption_key'));        
        $this->db->where('username_pengurus_barang', $username);
        $this->db->where('password_pengurus_barang', $password);
        $this->db->where('status_pengurus_barang="aktif"');
        $query=$this->db->get('pengurus_barang');
        if ($query->num_rows()!=null) {         
            $data=$query->row_array();                  
            $this->session->set_userdata("pengurus_barang",$data);        
            return array("status"=>200,"msg"=>"Berhasil Login");
        }else{
            return array("status"=>500,"msg"=>"Data User tidak ditemukan");         
        }
    }
    function validasi($id_pengurus_barang=0){
        $status=200;
        $msg="";
        // $function_lib=$this->load->library('function_lib');        
        
        // exit();
        $username = $this->input->post('username_pengurus_barang',TRUE);        
        $email_pengurus_barang = $this->input->post('email_pengurus_barang',TRUE);        
        if ($id_pengurus_barang==0) {            
        $id_pengurus_barang = isset($this->session->userdata('pengurus_barang')['id_pengurus_barang']) ? $this->session->userdata('pengurus_barang')['id_pengurus_barang'] : null;
        }
        // dapatkan data untuk edit
        $usernameOri = $this->function_lib->get_one('username_pengurus_barang','pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');        
        $email_pengurus_barangOri = $this->function_lib->get_one('email_pengurus_barang','pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');                
        $is_unique = ($username != $usernameOri)? '|is_unique[pengurus_barang.username_pengurus_barang]':'';
        $is_uniqueEmail = ($email_pengurus_barang != $email_pengurus_barangOri)? '|is_unique[pengurus_barang.email_pengurus_barang]':'';
        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username_pengurus_barang', 'Username', 'required'.$is_unique,
             array(                
                'is_unique'     => 'Username sudah terpakai.'
            )
        );        
        $this->form_validation->set_rules('email_pengurus_barang', 'Email', 'required'.$is_uniqueEmail,
            array(                
                'is_unique'     => 'Email sudah terpakai.'
            )
        );  
        // validasi tambah
        if ($this->input->post('tambah')) {
            $this->form_validation->set_rules('status_pengurus_barang', 'Status', 'required',
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
                "username_pengurus_barang" => form_error('username_pengurus_barang'),
                "email_pengurus_barang" => form_error('email_pengurus_barang'),
                "status_pengurus_barang" => form_error('status_pengurus_barang'),
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
            );
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            $error = array(
                "username_pengurus_barang" => form_error('username_pengurus_barang'),
                "email_pengurus_barang" => form_error('email_pengurus_barang'),
                "status_pengurus_barang" => form_error('status_pengurus_barang'),
                "password" => form_error('password'),
                "conf_password" => form_error('conf_password'),
            );
        }
        return array("status"=>$status,"msg"=>$msg,"error"=>$error);        
    }
    

    function editProfil(){
        $username_pengurus_barang = $this->input->post('username_pengurus_barang',TRUE);        
        $email_pengurus_barang = $this->input->post('email_pengurus_barang',TRUE);        
        $idPengurusBarang = $this->session->userdata('pengurus_barang')['id_pengurus_barang'];                
        $validasi = $this->validasi();      
        $status = 500;
        $msg = "";
        if ($validasi['status']==200) {
    
            $columnUpdate = array(
                "email_pengurus_barang"=> $email_pengurus_barang,
                "username_pengurus_barang"=> $username_pengurus_barang,
            );
            $this->db->where('id_pengurus_barang="'.$idPengurusBarang.'"');
            $this->db->update('pengurus_barang', $columnUpdate);
            $status = 200;
            $msg = "Berhasil Update";
        
        }else{
            $status = $validasi['status'];
            $msg = $validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function lupass(){
        $this->load->library('form_validation');                    
        $data['status']=500;                                    
        $data['msg']="Gagal silahkan coba lagi";
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        if ($this->form_validation->run() == TRUE) {                
            $email = $this->input->post('email', TRUE);
            $email = $this->security->sanitize_filename($email);
            $cek_email = $this->function_lib->get_one('email_pengurus_barang','pengurus_barang','status_pengurus_barang="aktif" AND email_pengurus_barang='.$this->db->escape($email).'');
            if (!empty($cek_email)) {
                $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
                $jam_sekarang = date("Y-m-d H:i:s",strtotime($exp_datetime));
                $menit_lalu = date("Y-m-d H:i:s",strtotime('+590 minutes'));
                // cek hitung batasan limit request forget password range 10 menit, limit 5 request
                $jumlah_request = $this->function_lib->get_one('count(id_forget_password)','forget_password','email='.$this->db->escape($email).' AND jenis_user="pengurus_barang" AND exp_datetime BETWEEN '.$this->db->escape($menit_lalu).' AND '.$this->db->escape($jam_sekarang).'');                 
                if (intval($jumlah_request)<5) {
                    $this->insertKode($email);
                    $data['status']=200;                                    
                    $data['msg']="Email telah dikirim, silahkan cek email untuk mengubah password"; 
                }else{                  
                    $data['status'] = 500;                                  
                    $data['msg'] = "Anda terlalu banyak melakukan request perubahan password, silahkan tunggu 10 menit lagi.";  
                }
            }else{
                $data['status']=500;                                    
                $data['msg']="Pengguna dengan email tersebut tidak ditemukan."  ;
            }
        } else {
            $data['status']=500;                                    
            $data['msg']="Gagal silahkan coba lagi";
        }       
        return $data;       
    }
    function insertKode($email){
        $id_user = $this->function_lib->get_one('id_pengurus_barang','pengurus_barang','email_pengurus_barang='.$this->db->escape($email).'');
        $configKey = "3mai1f0rg3t";
        $exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
        $token = hash('sha512', $email . $configKey . $exp_datetime);
        $this->db->set('is_active','0');
        $this->db->where('email', $email);
        $this->db->where('jenis_user', "pengurus_barang");
        $this->db->update('forget_password');
        $columnInsert = array(
            "email" => $email,
            "jenis_user" => "pengurus_barang",
            "id_user" => $id_user,
            "token" => $token,
            "exp_datetime" => $exp_datetime
        );
        $insert = $this->db->insert('forget_password', $columnInsert);
        if ($insert) {
            $this->load->model('Mmail');
            $data_email['token']=$token;
            $data_email['base_url'] = base_url();
            $message = $this->load->view('template_email_forget_password', $data_email, TRUE);          
            $this->Mmail->kirim_email($email,"Siana - Sistem Manajemen Aset Tanah Kabupaten Kebumen","Permintaan Perubahaan Password",$message);
        }
        
    }
	
}

/* End of file Mpengurus_barang.php */
/* Location: ./application/models/Mpengurus_barang.php */