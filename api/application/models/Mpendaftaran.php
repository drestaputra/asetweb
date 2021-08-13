<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpendaftaran extends CI_Model {
	
	function get_all_pendaftaran_umroh(){
		$this->db->order_by('tgl_daftar', 'desc');
		$query=$this->db->get('pendaftaran_umroh');
		$data=$query->result_array();
		return $data;	
	}
	function get_all_pendaftaran_muslim_tour(){
		$this->db->order_by('tgl_daftar', 'desc');
		$query=$this->db->get('pendaftaran_muslim_tour');
		$data=$query->result_array();
		return $data;	
	}
	function get_all_pendaftaran_haji(){
		$this->db->order_by('tgl_daftar', 'desc');
		$query=$this->db->get('pendaftaran_haji');
		$data=$query->result_array();
		return $data;	
	}
	function get_all_pendaftaran_tabungan_umroh(){
		$this->db->order_by('tgl_daftar', 'desc');
		$query=$this->db->get('pendaftaran_tabungan_umroh');
		$data=$query->result_array();
		return $data;	
	}
	function get_pendaftaran_umroh($id){
		$this->db->where('id_pendaftaran', $id);
		$query=$this->db->get('pendaftaran_umroh');
		$data=$query->row_array();
		return $data;	
	}
	function get_pendaftaran_muslim_tour($id){
		$this->db->where('id_pendaftaran', $id);
		$query=$this->db->get('pendaftaran_muslim_tour');
		$data=$query->row_array();
		return $data;	
	}
	function get_pendaftaran_haji($id){
		$this->db->where('id_pendaftaran', $id);
		$query=$this->db->get('pendaftaran_haji');
		$data=$query->row_array();
		return $data;	
	}
	function get_pendaftaran_tabungan_umroh($id){
		$this->db->where('id_pendaftaran', $id);
		$query=$this->db->get('pendaftaran_tabungan_umroh');
		$data=$query->row_array();
		return $data;	
	}
	function edit_pendaftaran_umroh($id,$post){
		$config['upload_path'] = './assets/img/pendaftaran/umroh/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_kk=$this->upload->do_upload('kk');
		$upload_akte_nikah_lahir_ijazah=$this->upload->do_upload('akte_nikah_lahir_ijazah');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto","./assets/img/pendaftaran/umroh/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_kk) {		
			$nama_foto3a=$_FILES['kk']['name'];
			$nama_foto3=str_replace(' ', '_', $nama_foto3a);
			// $nama_fiks3=date('dmyHis').$nama_foto3;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto3","./assets/img/pendaftaran/umroh/$nama_fiks3");		
			$post['kk'] = $nama_foto3;
		}
		if ($upload_akte_nikah_lahir_ijazah) {		
			$nama_foto4a=$_FILES['akte_nikah_lahir_ijazah']['name'];
			$nama_foto4=str_replace(' ', '_', $nama_foto4a);
			// $nama_fiks4=date('dmyHis').$nama_foto4;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto4","./assets/img/pendaftaran/umroh/$nama_fiks4");		
			$post['akte_nikah_lahir_ijazah'] = $nama_foto4;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto2","./assets/img/pendaftaran/umroh/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}				
		$this->db->where('id_pendaftaran', $id);
		$this->db->update('pendaftaran_umroh', $post);
		return "sukses";
	}
	function edit_pendaftaran_muslim_tour($id,$post){
		$config['upload_path'] = './assets/img/pendaftaran/muslim_tour/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/muslim_tour/$nama_foto","./assets/img/pendaftaran/muslim_tour/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_foto) {
			$nama_foto2=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/muslim_tour/$nama_foto2","./assets/img/pendaftaran/muslim_tour/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}				
		$this->db->where('id_pendaftaran', $id);
		$this->db->update('pendaftaran_muslim_tour', $post);
		return "sukses";
	}
	function edit_pendaftaran_haji($id,$post){
		$config['upload_path'] = './assets/img/pendaftaran/haji/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_kk=$this->upload->do_upload('kk');
		$upload_akte_nikah_lahir_ijazah=$this->upload->do_upload('akte_nikah_lahir_ijazah');
		$upload_surat_kesehatan=$this->upload->do_upload('surat_kesehatan');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto","./assets/img/pendaftaran/haji/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_kk) {		
			$nama_foto3a=$_FILES['kk']['name'];			
			$nama_foto3=str_replace(' ', '_', $nama_foto3a);
			// $nama_fiks3=date('dmyHis').$nama_foto3;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto3","./assets/img/pendaftaran/haji/$nama_fiks3");		
			$post['kk'] = $nama_fiks3;
		}
		if ($upload_akte_nikah_lahir_ijazah) {		
			$nama_foto4a=$_FILES['akte_nikah_lahir_ijazah']['name'];
			$nama_foto4=str_replace(' ', '_', $nama_foto4a);
			// $nama_fiks4=date('dmyHis').$nama_foto4;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto4","./assets/img/pendaftaran/haji/$nama_fiks4");		
			$post['akte_nikah_lahir_ijazah'] = $nama_foto4;
		}
		if ($upload_surat_kesehatan) {		
			$nama_foto5a=$_FILES['surat_kesehatan']['name'];
			$nama_foto5=str_replace(' ', '_', $nama_foto5a);
			// $nama_fiks5=date('dmyHis').$nama_foto5;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto5","./assets/img/pendaftaran/haji/$nama_fiks5");		
			$post['surat_kesehatan'] = $nama_foto5;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto2","./assets/img/pendaftaran/haji/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}		
		$this->db->where('id_pendaftaran', $id);		
		$this->db->update('pendaftaran_haji', $post);
		return "sukses";
	}
	function edit_pendaftaran_tabungan_umroh($id,$post){
		$config['upload_path'] = './assets/img/pendaftaran/tabungan_umroh/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/tabungan_umroh/$nama_foto","./assets/img/pendaftaran/tabungan_umroh/$nama_fiks");		
			$post['foto_ktp'] = $nama_fiks;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/tabungan_umroh/$nama_foto2","./assets/img/pendaftaran/tabungan_umroh/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}		
		$this->db->where('id_pendaftaran', $id);
		$this->db->update('pendaftaran_tabungan_umroh', $post);
		return "sukses";
	}
	function daftar_muslim_tour($post){
		$config['upload_path'] = './assets/img/pendaftaran/muslim_tour/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/muslim_tour/$nama_foto","./assets/img/pendaftaran/muslim_tour/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/muslim_tour/$nama_foto2","./assets/img/pendaftaran/muslim_tour/$nama_fiks2");	
			$post['pas_foto'] = $nama_foto2;
		}		
		$post['tgl_daftar']=date('Y-m-d');
		$this->db->insert('pendaftaran_muslim_tour', $post);
		return "sukses";
	}
	function daftar_tabungan_umroh($post){
		$config['upload_path'] = './assets/img/pendaftaran/tabungan_umroh/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE; 
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/tabungan_umroh/$nama_foto","./assets/img/pendaftaran/tabungan_umroh/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/tabungan_umroh/$nama_foto2","./assets/img/pendaftaran/tabungan_umroh/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}		
		$post['tgl_daftar']=date('Y-m-d');
		$this->db->insert('pendaftaran_tabungan_umroh', $post);
		return "sukses";
	}
	function daftar_umroh($post){
		$config['upload_path'] = './assets/img/pendaftaran/umroh/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;
		
		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_kk=$this->upload->do_upload('kk');
		$upload_akte_nikah_lahir_ijazah=$this->upload->do_upload('akte_nikah_lahir_ijazah');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto","./assets/img/pendaftaran/umroh/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_kk) {		
			$nama_foto3a=$_FILES['kk']['name'];
			$nama_foto3=str_replace(' ', '_', $nama_foto3a);
			// $nama_fiks3=date('dmyHis').$nama_foto3;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto3","./assets/img/pendaftaran/umroh/$nama_fiks3");		
			$post['kk'] = $nama_foto3;
		}
		if ($upload_akte_nikah_lahir_ijazah) {		
			$nama_foto4a=$_FILES['akte_nikah_lahir_ijazah']['name'];
			$nama_foto4=str_replace(' ', '_', $nama_foto4a);
			// $nama_fiks4=date('dmyHis').$nama_foto4;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto4","./assets/img/pendaftaran/umroh/$nama_fiks4");		
			$post['akte_nikah_lahir_ijazah'] = $nama_foto4;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/umroh/$nama_foto2","./assets/img/pendaftaran/umroh/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}		
		$post['tgl_daftar']=date('Y-m-d');
		$this->db->insert('pendaftaran_umroh', $post);
		return "sukses";
	}
	function daftar_haji($post){
		$config['upload_path'] = './assets/img/pendaftaran/haji/';
		$config['allowed_types'] = 'gif|jpg|png|GIF|JPG|PNG|jpeg|JPEG';
		$config['max_size']  = '10000';		 
		$config['remove_spaces']= TRUE;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);
		
		$upload_ktp=$this->upload->do_upload('foto_ktp');
		$upload_kk=$this->upload->do_upload('kk');
		$upload_akte_nikah_lahir_ijazah=$this->upload->do_upload('akte_nikah_lahir_ijazah');
		$upload_surat_kesehatan=$this->upload->do_upload('surat_kesehatan');
		$upload_foto=$this->upload->do_upload('pas_foto');
		
		if ($upload_ktp) {		
			$nama_fotoa=$_FILES['foto_ktp']['name'];
			$nama_foto=str_replace(' ', '_', $nama_fotoa);
			// $nama_fiks=date('dmyHis').$nama_foto;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto","./assets/img/pendaftaran/haji/$nama_fiks");		
			$post['foto_ktp'] = $nama_foto;
		}
		if ($upload_kk) {		
			$nama_foto3a=$_FILES['kk']['name'];			
			$nama_foto3=str_replace(' ', '_', $nama_foto3a);
			// $nama_fiks3=date('dmyHis').$nama_foto3;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto3","./assets/img/pendaftaran/haji/$nama_fiks3");		
			$post['kk'] = $nama_foto3;
		}
		if ($upload_akte_nikah_lahir_ijazah) {		
			$nama_foto4a=$_FILES['akte_nikah_lahir_ijazah']['name'];
			$nama_foto4=str_replace(' ', '_', $nama_foto4a);
			// $nama_fiks4=date('dmyHis').$nama_foto4;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto4","./assets/img/pendaftaran/haji/$nama_fiks4");		
			$post['akte_nikah_lahir_ijazah'] = $nama_foto4;
		}
		if ($upload_surat_kesehatan) {		
			$nama_foto5a=$_FILES['surat_kesehatan']['name'];
			$nama_foto5=str_replace(' ', '_', $nama_foto5a);
			// $nama_fiks5=date('dmyHis').$nama_foto5;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto5","./assets/img/pendaftaran/haji/$nama_fiks5");		
			$post['surat_kesehatan'] = $nama_foto5;
		}
		if ($upload_foto) {
			$nama_foto2a=$_FILES['pas_foto']['name'];
			$nama_foto2=str_replace(' ', '_', $nama_foto2a);
			// $nama_fiks2=date('dmyHis').$nama_foto2;			
			// rename("./assets/img/pendaftaran/haji/$nama_foto2","./assets/img/pendaftaran/haji/$nama_fiks2");		
			$post['pas_foto'] = $nama_foto2;
		}		
		$post['tgl_daftar']=date('Y-m-d');
		$this->db->insert('pendaftaran_haji', $post);
		return "sukses";
	}
	// public function kirim_email($to,$to_name,$subjek,$pesan){
	// 	$this->load->library("phpmailer_library");
	// 	$mail = $this->phpmailer_library->load();
	// 	$mail->SMTPDebug = 3;                               
	// //Set PHPMailer to use SMTP.
	// 	$mail->isSMTP();            
	// //Set SMTP host name                          
	// $mail->Host = "tls://smtp.gmail.com"; //host mail server
	// //Set this to true if SMTP host requires authentication to send email
	// $mail->SMTPAuth = true;                          
	// //Provide username and password     
	// $mail->Username = "almakwacilacap@gmail.com";   //nama-email smtp          
	// $mail->Password = "02825551222";           //password email smtp
	// //If SMTP requires TLS encryption then set it
	// $mail->SMTPSecure = "tls";                           
	// //Set TCP port to connect to 
	// $mail->Port = 587;                                   

	// $mail->From = "drestaputra@gmail.com"; //email pengirim
	// $mail->FromName = "drestaputra"; //nama pengirim

	//  $mail->addAddress($to, $to_name); //email penerima

	//  $mail->isHTML(true);

	// $mail->Subject = $subjek; //subject
 //    $mail->Body    = $pesan; //isi email
 //        $mail->AltBody = "PHP mailer"; //body email (optional)

 //        if(!$mail->send()) 
 //        {
 //        	echo "Mailer Error: " . $mail->ErrorInfo;
 //        } 
 //        else 
 //        {
 //        	echo "Message has been sent successfully";
 //        }
 //    }	

}

/* End of file Mpendaftaran.php */
/* Location: ./application/models/Mpendaftaran.php */