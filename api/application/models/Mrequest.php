<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mrequest extends CI_Model {


    public function data_request($params,$custom_select='',$count=false,$additional_where='', $order_by="tgl_request DESC")
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
            $where.=' AND no_invoice like "%'.$this->db->escape_str($pencarian).'%"';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'request_owner';
        $params['select'] = '*';
        
        if(trim($custom_select)!='')
        {
            $params['select'] = $custom_select;
        }
        $params['where_detail'] =" 1
        ".$where_detail.' '.$where;
        
        return array(
            'status'=>200,
            'msg'=>"sukses",
            'query'=>$this->function_lib->db_query_execution($params,false),
            'total'=>$this->function_lib->db_query_execution($params, true),
        );
    } 
    public function upload_bukti_pembayaran(){        
        $config['upload_path']          = 'assets/bukti_pembayaran';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 5000;        
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $id_request = $this->input->post('id_request',TRUE);
        $id_request = $this->security->sanitize_filename($id_request);
        $device_id = $this->input->post('device_id',TRUE);
        $device_id = $this->security->sanitize_filename($device_id);

        $dateNow = date("Y-m-d H:i:s");
        $date30 = date("Y-m-d H:i:s", strtotime("-30 minutes"));
        $cekLimit = $this->function_lib->get_one('count(id_pembayaran)','pembayaran','id_request='.$this->db->escape($id_request).' AND tgl_pembayaran BETWEEN "'.$date30.'" AND "'.$dateNow.'"');
        if (intval($cekLimit) >= 4) {
            $data['status'] = 500;
            $data['msg'] = "Maaf Anda telah mencapai limit upload konfirmasi, silahkan menunggu 30 menit untuk mencoba lagi";
            $data['data'] = "0";
            return $data;
        }
        $upload = $this->upload->do_upload('bukti_pembayaran');      
        if ($upload) {
            $cek = $this->function_lib->get_one('id_request','request_owner','id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).'');
            if (!empty($cek)) {                
                $nama_file = $this->upload->data('file_name');
                $dataPembayaran = array(
                    "id_request" => $id_request,
                    "tgl_pembayaran" => date("Y-m-d H:i:s"),
                    "bukti_pembayaran" => $nama_file,
                );
                $this->db->insert('pembayaran',$dataPembayaran);
                $id_pembayaran = $this->db->insert_id();
                $data['status'] = 200;
                $data['msg'] = $id_pembayaran;
                $data['data'] = $id_pembayaran;
            }else{
                $data['status'] = 500;
                $data['msg'] = "Data permintaan request tidak ditemukan, silahkan hubungi admin di halaman contact";
                $data['data'] = "0";
            }
        }else{
            $data['status'] = 500;
            $data['msg'] = $this->upload->display_errors('','');
            $data['data'] = "0";
        }
        return $data;
    }          
    public function validasi_konfirmasi(){
        $status=200;
        $msg="";        
        /*@Field("id_pembayaran") String id_pembayaran,
                @Field("nama_pembayar") String nama_pembayar,
                @Field("no_hp_pembayar") String no_hp_pembayar,
                @Field("email_pembayar") String email_pembayar,
                @Field("jumlah_pembayaran") String jumlah_pembayaran,
                @Field("id_request") String id_request,
                @Field("device_id") String device_id*/
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('nama_pembayar', 'Nama Pembayar', 'trim|required|min_length[1]|max_length[100]',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('no_hp_pembayar', 'No HP Pembayar', 'trim|required|min_length[1]|max_length[100]|numeric',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('email_pembayar', 'Email Pembayar', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        $this->form_validation->set_rules('jumlah_pembayaran', 'Jumlah Pembayaran', 'trim|required|min_length[1]|max_length[250]|numeric',
             array(
                'required'      => '%s masih kosong',                
            )
        );  
        if ($this->form_validation->run() == TRUE) {   
            $id_request = $this->input->post('id_request');
            $device_id = $this->input->post('device_id');
            $id_pembayaran = $this->input->post('id_pembayaran');
            $cek = $this->function_lib->get_one('bukti_pembayaran','pembayaran','
            id_request IN (SELECT id_request FROM request_owner WHERE id_request='.$this->db->escape($id_request).' AND device_id='.$this->db->escape($device_id).') AND
            id_pembayaran='.$this->db->escape($id_pembayaran).'');
            
            if (empty($cek)) {
                $status = 500;
                $msg = "Data pembayaran tidak ditemukan";                                
            }else{
                $status = 200;
                $msg = "Konfirmasi berhasil, Anda akan segera kami hubungi";
            }            
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
            return array("status"=>$status,"msg"=>$msg);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function konfirmasi_pembayaran(){
        $validasi = $this->validasi_konfirmasi();
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {
            $id_pembayaran = $this->input->post('id_pembayaran',TRUE);
            $nama_pembayar = $this->input->post('nama_pembayar');
            $no_hp_pembayar = $this->input->post('no_hp_pembayar');
            $email_pembayar = $this->input->post('email_pembayar');
            $jumlah_pembayaran = $this->input->post('jumlah_pembayaran');

            $columnUpdate = array(
                "nama_pembayar" => $nama_pembayar,
                "no_hp_pembayar" => $no_hp_pembayar,
                "email_pembayar" => $email_pembayar,
                "jumlah_pembayaran" => $jumlah_pembayaran,
                "status_pembayaran" => "proses"
            );
            $this->db->where('id_pembayaran', $id_pembayaran);
            $this->db->update('pembayaran', $columnUpdate);

            /*kirim email konfirmasi*/
            $this->db->select('pembayaran.*, request_owner.no_invoice,request_owner.tgl_request');
            $this->db->join('request_owner', 'pembayaran.id_request = request_owner.id_request', 'right');
            $this->db->where('pembayaran.id_pembayaran', $id_pembayaran);
            $queryPembayaran = $this->db->get('pembayaran', 1, 0);
            $dataPembayaran = $queryPembayaran->row_array();
            $this->kirimEmail($email_pembayar, "Invoice Pembayaran Request Akun Owner", $dataPembayaran); //ke pembayar
            $this->kirimEmail("cs@artakita.com", "Invoice Pembayaran Request Akun Owner (DUPLIKAT)", $dataPembayaran); //ke admin
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function kirimEmail($email, $subject = "Notifikasi Email", $dataPembayaran = array()){
        $this->load->model('Mmail');
        $data_email['subject'] = $subject;
        $data_email['pembayaran'] = $dataPembayaran;
        $data_email['base_url'] = "https://demo.artakita.com/";
        $message = $this->load->view('template_email_invoice', $data_email, TRUE);          
        $this->Mmail->kirim_email($email,"Koperasi Artakita", $subject ,$message);
    }
    function ubah_rekening(){
        $id_request = $this->input->get_post('id_request');
        $device_id = $this->input->get_post('device_id');
        $id_rekening = $this->input->post('id_rekening');
        $cek_rekening = $this->function_lib->get_one('id_rekening','rekening','id_rekening='.$this->db->escape($id_rekening).'');
        if (empty($cek_rekening)) {
            return array("status"=>500, "msg"=>"Rekening tidak ditemukan");
        }
        $this->db->set("id_rekening", $id_rekening);
        $this->db->where('id_request', $id_request);
        $this->db->where('device_id', $device_id);
        $this->db->update('request_owner');
        if ($this->db->affected_rows()>0) {
            return array("status"=>200, "msg"=>"Sukses mengubah rekening pembayaran");
        }else{
            return array("status"=>200, "msg"=>"Tidak ada perubahan rekening");
        }
    }
}

/* End of file Mrequest.php */
/* Location: ./application/models/Mrequest.php */