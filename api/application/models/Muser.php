<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Muser extends CI_Model {


    public function edit_profil($id_user){
        $validasi = $this->validasi($id_user);
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {             
            $nama_lengkap = $this->input->post('nama_lengkap',true);
            $email = $this->input->post('email',true);
            $no_hp = $this->input->post('no_hp',true);
            $provinsi = $this->input->post('provinsi',true);
            $kabupaten = $this->input->post('kabupaten',true);
            $kecamatan = $this->input->post('kecamatan',true);
            $alamat = $this->input->post('alamat',true);            
            $columnUpdate = array(
                "nama_lengkap" => $this->security->sanitize_filename($nama_lengkap),
                "email" => $this->security->sanitize_filename($email),
                "no_hp" => $this->security->sanitize_filename($no_hp),
                "provinsi" => $this->security->sanitize_filename($provinsi),
                "kabupaten" => $this->security->sanitize_filename($kabupaten),
                "kecamatan" => $this->security->sanitize_filename($kecamatan),
                "alamat" => $this->security->sanitize_filename($alamat),
            );
            $this->db->where('id_user', $id_user);
            $this->db->update('user', $columnUpdate);
        }

        return array("status"=>$status,"msg"=>$msg);
    }
    public function validasi($id_user){
        $status=200;
        $msg="";        
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('nama_lengkap', 'Nama', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );           
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'trim|required|numeric|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        
        $this->form_validation->set_rules('provinsi', 'Provinsi', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        $this->form_validation->set_rules('kabupaten', 'Kabupaten', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        ); 
        $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        
        if ($this->form_validation->run() == TRUE) {            
            $email = $this->input->post('email',true);
            // validasi email unique
            $cek_email = $this->function_lib->get_one('email','user','id_user!='.$this->db->escape($id_user).' AND email='.$this->db->escape($email).'');
            if (empty($cek_email)) {
                $status=200;
                $msg="Profil berhasil diperbarui";
            }else{
                $status = 500;
                $msg = "Mohon pilih email lain, email tersebut sudah dipakai";
            }
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function ganti_password($id_user){
        $validasi = $this->validasi_ganti_password($id_user);
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        $password_baru = $this->input->post('password_baru', true);
        $hashed_password_baru = hash('sha512',$password_baru . config_item('encryption_key'));        
        if ($status == 200) {
            $columnUpdate = array("password"=> $hashed_password_baru);
            $this->db->where('id_user', $id_user);
            $this->db->update('user', $columnUpdate);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function validasi_ganti_password($id_user){
          $status=200;
        $msg="";        
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'trim|required|min_length[1]|max_length[30]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'trim|required|min_length[1]|max_length[30]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                   
        $this->form_validation->set_rules('password_konfirmasi', 'Konfirmasi password', 'trim|required|min_length[1]|max_length[30]|matches[password_baru]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                   
        if ($this->form_validation->run() == TRUE) {                       
            // cek password lama
            $password_lama = $this->input->post('password_lama', true);
            $hashed_password_lama = hash('sha512',$password_lama . config_item('encryption_key'));        
            $password_baru = $this->input->post('password_baru', true);
            $password_konfirmasi = $this->input->post('password_konfirmasi', true);            
            $cek_password_lama = $this->function_lib->get_one('id_user','user','id_user='.$this->db->escape($id_user).' AND password='.$this->db->escape($hashed_password_lama).'');
            if (!empty($cek_password_lama)) {
                $status=200;
                $msg="Berhasil mengubah password";           
            }else{
                $status = 500;
                $msg = "Password lama tidak sesuai";
            }
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
}

/* End of file Muser.php */
/* Location: ./application/models/Muser.php */