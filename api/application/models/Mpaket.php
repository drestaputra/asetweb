<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpaket extends CI_Model {


    public function data_paket($params,$custom_select='',$count=false,$additional_where='', $order_by="id_paket DESC")
    {
        
        $where_detail=' ';
        $where=" ";        
        if($count==false)
        {
            $params['order_by'] =$order_by;
        }
        $order_by=$this->input->post('order_by');
        if (trim($order_by)!="") {
            $params['order_by'] = $order_by;
        }
      
      	$pencarian = $this->input->post('pencarian',TRUE);
      	 if (trim($pencarian)!="") {
            $where.=' AND judul_paket like "%'.$this->db->escape_str($pencarian).'%"';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'paket';
        $params['select'] = '*';
        
        if(trim($custom_select)!='')
        {
            $params['select'] = $custom_select;
        }
        $params['limit'] = "3";
        $params['where_detail'] =" 1
        ".$where_detail.' '.$where;
        
        return array(
            'status'=>200,
            'msg'=>"sukses",
            'query'=>$this->function_lib->db_query_execution($params,false),
            'total'=>$this->function_lib->db_query_execution($params, true),
        );
    }    
    public function request(){
        // Call<ResponsePojo> requestOwner(@Field("username") String username, @Field("email") String email, @Field("no_hp") String no_hp, @Field("password") String password);
        $validasi = $this->validasi_request();
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {
            $username = $this->input->post('username',true);
            $email = $this->input->post('email',true);
            $password = $this->input->post('password',true);
            $no_hp = $this->input->post('no_hp',true);
            $id_paket = $this->input->post('id_paket',true);
            $id_rekening = $this->input->post('id_rekening',true);
            $device_id = $this->input->post('device_id',true);
            $countInv = $this->function_lib->get_one('count(id_request)','request_owner','date(tgl_request)="'.date("Y-m-d").'"');
            $countInv = $countInv+1;
            $invoice_code = "INV/OWNER/".date("Y/m/d/").str_pad($countInv, 4, '0', STR_PAD_LEFT);
            $uniq_code = (float) rand(0,999);
            $tagihan = (float) $this->function_lib->get_one('harga_paket','paket','id_paket='.$this->db->escape($id_paket).'');
            $total_tagihan = $uniq_code+$tagihan;
            $columnInsert = array(
                "username"=> $username,
                "email"=> $email,
                "password"=> $password,
                "no_hp"=> $no_hp,
                "id_paket"=> $id_paket,
                "tgl_request" => date("Y-m-d H:i:s"),
                "status" => "proses",
                "no_invoice" => $invoice_code,
                "total_tagihan_invoice" => $total_tagihan,
                "id_rekening" => $id_rekening,
                "device_id" => $device_id
            );
            $this->db->insert('request_owner', $columnInsert);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function validasi_request(){
        $status=200;
        $msg="";        
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[1]|max_length[100]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[1]|max_length[100]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'trim|required|numeric|min_length[5]|max_length[15]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
              
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        $this->form_validation->set_rules('id_paket', 'Paket', 'trim|required',
             array(
                'required'      => '%s pendaftaran koperasi tidak ditemukan',                
            )
        );          
        if ($this->form_validation->run() == TRUE) {            
            $email = $this->input->post('email',true);
            // validasi email unique
            $cek_email = $this->function_lib->get_one('email','owner','email='.$this->db->escape($email).'');

            // validasi id_paket
            $id_paket = $this->input->post('id_paket',true);
            $cek_paket = $this->function_lib->get_one('id_paket','paket','id_paket='.$this->db->escape($id_paket).'');

            // validasi username unique
            $username = $this->input->post('username',true);
            $cek_username = $this->function_lib->get_one('username','owner','username='.$this->db->escape($username).'');
            if (!empty($cek_email)) {                
                $status = 500;
                $msg = "Mohon pilih email lain, email tersebut sudah dipakai";                                
            }else if (!empty($cek_username)) {                
                $status = 500;
                $msg = "Mohon pilih username lain, username tersebut sudah dipakai";                                
            }else if (empty($cek_paket)) {
                $status = 500;
                $msg = "Paket pendaftaran koperasi tidak ditemukan";                                
            }else{                
                $status = 200;
                $msg = "Permintaan berhasil, Anda akan segera kami hubungi";                
            }            
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            return array("status"=>$status,"msg"=>$msg);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
}

/* End of file Mpaket.php */
/* Location: ./application/models/Mpaket.php */