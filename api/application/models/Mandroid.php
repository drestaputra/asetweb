<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandroid extends CI_Model {

	function cek_login_user(){		
		$username=$this->input->post('username',TRUE);
		$password=$this->input->post('password',TRUE);
		$password = hash('sha512',$password . config_item('encryption_key'));        
		$where="username=".$this->db->escape($username)." AND password=".$this->db->escape($password);
		$this->db->where($where);
		$query=$this->db->get('user');		
		$jumlah_cocok=$query->num_rows();
		$data_user=array();
		
		if ($jumlah_cocok!=0) {
			$where_active = "username=".$this->db->escape($username)." AND password=".$this->db->escape($password)." AND status='aktif'";
			$cekAktif = $this->function_lib->get_one('id_user','user', $where_active);
			if (!empty($cekAktif)) {
				$status=200;
				$msg="Berhasil Login";
				$data_user=$query->row_array();
			}else{
				$status=500;
				$msg="User Anda belum aktif silahkan hubungi admin untuk meminta diaktifkan";
			}

		}else{
			$status=500;
			$msg="Username atau password salah";
		}
		return array("status"=>$status,"msg"=>$msg,"data"=> $data_user);
		
	}
	function forget_password(){
		$this->load->library('form_validation');					
		$data['status']=500;									
		$data['msg']="Gagal silahkan coba lagi";
		$this->form_validation->set_rules('email', 'email', 'trim|required');
		if ($this->form_validation->run() == TRUE) {				
			$email = $this->input->post('email', TRUE);
			$email = $this->security->sanitize_filename($email);
			$cek_email = $this->function_lib->get_one('email','user','status="aktif" AND email='.$this->db->escape($email).'');
			if (!empty($cek_email)) {
				$exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
				$jam_sekarang = date("Y-m-d H:i:s",strtotime($exp_datetime));
				$menit_lalu = date("Y-m-d H:i:s",strtotime('+590 minutes'));
				// BETWEEN '2016-01-23 00:00:00' AND '2016-01-24 00:00:00'
				// cek hitung batasan limit request forget password range 10 menit, limit 5 request
				$jumlah_request = $this->function_lib->get_one('count(id_forget_password)','forget_password','email='.$this->db->escape($email).' AND jenis_user="user" AND exp_datetime BETWEEN '.$this->db->escape($menit_lalu).' AND '.$this->db->escape($jam_sekarang).'');					
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
				$data['msg']="Pengguna dengan email tersebut tidak ditemukan."	;
			}
		} else {
			$data['status']=500;									
			$data['msg']="Gagal silahkan coba lagi";
		}		
		return $data;					
	}
	function insertKode($email){
		$id_user = $this->function_lib->get_one('id_user','user','email='.$this->db->escape($email).'');
		$configKey = "3mai1f0rg3t";
		$exp_datetime = date("Y-m-d H:i:s",strtotime('+10 hours'));
		$token = hash('sha512', $email . $configKey . $exp_datetime);
		$this->db->set('is_active','0');
		$this->db->where('email', $email);
		$this->db->where('jenis_user', "user");
		$this->db->update('forget_password');
		$columnInsert = array(
			"email" => $email,
			"jenis_user" => "user",
			"id_user" => $id_user,
			"token" => $token,
			"exp_datetime" => $exp_datetime
		);
		$insert = $this->db->insert('forget_password', $columnInsert);
		if ($insert) {
			$this->load->model('Mmail');
			$data_email['token']=$token;
			$data_email['base_url'] = "https://demo.artakita.com/";
			$message = $this->load->view('template_email_forget_password', $data_email, TRUE);			
			$this->Mmail->kirim_email($email,"Koperasi Artakita","Permintaan Perubahaan Password",$message);
		}
		
	}
	// function lupass($kode){
	// 	$password=$this->input->post('pwd',true);
	// 	$where="token='".$kode."' AND is_active='1'";
	// 	$this->db->where($where);
	// 	$query=$this->db->get('lupass');
	// 	$data_lupass=$query->row_array();

	// 	if ($data_lupass['jenis_user']=="jamaah") {
	// 		$data_update_jamaah=array(
	// 			"password"=>sha1($password)
	// 		);
	// 		$this->db->where('username', $data_lupass['username']);
	// 		$this->db->update('jamaah',$data_update_jamaah );
	// 	}
	// 	else if($data_lupass['jenis_user']=="agen") {
	// 		$data_update_agen=array(
	// 			"password"=>sha1($pwd)
	// 		);
	// 		$this->db->where('username', $data_lupass['username']);
	// 		$this->db->update('agen',$data_update_agen );
	// 	}		
	// 	$this->db->where($where);
	// 	$data_update_lupass=array(
	// 		"is_active"=>"0"
	// 	);
	// 	$this->db->update('lupass', $data_update_lupass);
	// 	return array("status"=>200,"msg"=>"Berhasil mengubah password, silahkan login");
	// }
	function validasi_daftar(){
		$status=200;
		$msg="";
		$this->load->library('form_validation');
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[100]',
			 array(
                'required'      => '%s masih kosong',
                'max_length'	=> '%s maksimal 100 karakter',
                'min_length'	=> '%s minimal 3 karakter'

        	)
		);
		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[20]|is_unique[user.username]',
			array(
                'required'      => '%s masih kosong',        
                'max_length'	=> '%s maksimal 20 karakter',
                'min_length'	=> '%s minimal 3 karakter',
                'is_unique'     => '%s sudah terdaftar.'
        	)
		);
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[40]',
			array(
                'required'      => '%s masih kosong',    
                'max_length'	=> '%s maksimal 40 karakter',
                'min_length'	=> '%s minimal 6 karakter'            
        	)
		);				
		if ($this->form_validation->run() == TRUE) {
			$status=200;
			$msg="Berhasil";
		} else {
			$status=500;
			$msg=validation_errors(' ',' ');
		}
		return array("status"=>$status,"msg"=>$msg);
	}
	function daftar(){
		$data_user=null;
		 $validasi=$this->validasi_daftar();
		 if ($validasi['status']==200) {
		 	$post=$this->input->post();
		 	$post['password'] = hash('sha512',$post['password'] . config_item('encryption_key'));    
		 	$dataInsert = array(
		 		"username" => $post['username'],
		 		"password" => $post['password'],
		 		"instansi" => (isset($post['instansi']) && !empty($post['instansi']) )? $post['instansi'] : "",
		 		"nama_lengkap" => $post['nama_lengkap']
		 	);
		 	$this->db->insert('user', $dataInsert);
		 	$id_user=$this->db->insert_id();
		 	if (trim($id_user)) {		 		
		 		$data_user=$this->function_lib->get_row('user','id_user="'.$id_user.'"');
		 	}
		 }
		 return array("status"=>$validasi['status'],"msg"=>$validasi['msg'],"data"=>$data_user);
	}

}

/* End of file Mandroid.php */
/* Location: ./application/models/Mandroid.php */