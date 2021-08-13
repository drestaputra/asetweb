<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandroid extends CI_Model {

	function lupass($kode){
		$validasi = $this->validasi_lupass($kode);
		$status = isset($validasi['status']) ? $validasi['status'] : 500;
		$msg = isset($validasi['msg']) ? $validasi['msg'] : "";
		if ($status == 200) {
			$cek_lupass = $this->function_lib->get_row('forget_password','token='.$this->db->escape($kode).' AND is_active="1" AND unix_timestamp(exp_datetime)>unix_timestamp(NOW())');
            $id_user = isset($cek_lupass['id_user']) ? $cek_lupass['id_user'] : "";
            $jenis_user = isset($cek_lupass['jenis_user']) ? $cek_lupass['jenis_user'] : "";
			$pwd = $this->input->post('pwd',TRUE);
			$pwd_hashed = hash('sha512',$this->security->sanitize_filename($pwd) . config_item('encryption_key'));        
			// update ubah kode jadi non aktif
			$this->db->set('is_active','0');
			$this->db->where('token', $kode);
			$this->db->update('forget_password');            
        	$id_user = $this->function_lib->get_one('id_'.$jenis_user,$jenis_user,'id_'.$jenis_user.'='.$this->db->escape($id_user).'');

			$columnUpdatePassword = array(
				"password"=> $pwd_hashed
			);
			$this->db->where('id_'.$jenis_user, $id_user);
			$this->db->update($jenis_user, $columnUpdatePassword);
		}		
		return array("status"=>$status,"msg"=>$msg);        
	}
	function validasi_lupass($kode){		
        $status=200;
        $msg="";       
        $this->load->library('form_validation');        
               
 		$this->form_validation->set_rules('pwd', 'Password Baru', 'required|min_length[5]|max_length[25]',
                array(                
                    'required'     => '%s masih kosong.',
                    'min_length'   => '%s harus lebih dari 5 karakter.',
                    'max_length'   => '%s harus lebih dari 25 karakter.'
                )
            );  
            $this->form_validation->set_rules('repwd', 'Konfirmasi Password Baru', 'required|matches[pwd]',
                array(                
                    'required'     => '%s masih kosong.',
                    'matches'      => '%s tidak cocok'
                )
            );  
        if ($this->form_validation->run() == TRUE) {
        	$cek_lupass = $this->function_lib->get_row('forget_password','token='.$this->db->escape($kode).' AND is_active="1" AND unix_timestamp(exp_datetime)>unix_timestamp(NOW())');
            $id_user = isset($cek_lupass['id_user']) ? $cek_lupass['id_user'] : "";
            $jenis_user = isset($cek_lupass['jenis_user']) ? $cek_lupass['jenis_user'] : "";
        	$cek_user = $this->function_lib->get_one('id_'.$jenis_user,$jenis_user,'id_'.$jenis_user.'='.$this->db->escape($id_user).'');

        	if (!empty($cek_lupass) AND !empty($cek_user)) {        		
	            $status=200;
	            $msg="Berhasil";           
        	}else{
        		$status = 500;
        		$msg = "Kode ubah password sudah tidak bisa digunakan silahkan lakukan request ubah password kembali";
        	}
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            
        }
        return array("status"=>$status,"msg"=>$msg);        
    
	}

}

/* End of file Mandroid.php */
/* Location: ./application/models/Mandroid.php */