<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Msimpanan extends CI_Model {


    public function data_simpanan($params,$custom_select='',$count=false,$additional_where='', $order_by="id_simpanan DESC")
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
        $pencarian=$this->input->post('pencarian',TRUE);                
        if (trim($pencarian)!="") {
            $where.=' AND (id_simpanan LIKE '.$this->db->escape($pencarian).' OR id_nasabah IN (SELECT id_nasabah from nasabah WHERE username LIKE "%'.$this->db->escape_str($pencarian).'%"))';
        }        
           
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'simpanan';
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
    function get_all_id_simpanan($id_kolektor){
    	$id_simpanan = $this->input->post('id_simpanan',TRUE);
        // SELECT id_simpanan,id_nasabah, nama_nasabah, jumlah_perangsuran
        if (!empty($id_simpanan)) {        	
        	$this->db->where('simpanan.id_simpanan', $id_simpanan,TRUE);
        }
        $this->db->where('simpanan.id_kolektor', $id_kolektor);
        $this->db->where('status_simpanan', "aktif");
        $this->db->select('id_simpanan,simpanan.id_nasabah, nasabah.nama_nasabah, jumlah_simpanan');
        $this->db->join('nasabah', 'simpanan.id_nasabah = nasabah.id_nasabah', 'left');
        $query = $this->db->get('simpanan');
        $data = $query->result_array();
        return $data;
    }
    function get_all_id_nasabah_for_simpanan($id_kolektor){    	        
        $this->db->where('id_nasabah NOT IN (SELECT id_nasabah FROM simpanan)');
        $this->db->where('id_kolektor', $id_kolektor,TRUE);
        $this->db->where('status', "aktif");                
        $this->db->select('id_nasabah,username,nama_nasabah');
        $query = $this->db->get('nasabah');
        $data = $query->result_array();
        return $data;
    }
    function tambah_simpan($id_kolektor) {    
        $status = 500;
        $msg = "";
        $id_simpanan = $this->input->post('id_simpanan',TRUE);
        $jumlah_riwayat_simpanan = $this->input->post('jumlah_riwayat_simpanan',TRUE);
        $keterangan = $this->input->post('keterangan',TRUE);
        
        $jumlah_pembayaran_sementara = $this->function_lib->get_one('sum(jumlah_riwayat_simpanan)','riwayat_simpanan','id_simpanan='.$this->security->sanitize_filename($id_simpanan).' ORDER BY id_riwayat_simpanan DESC');
        $jumlah_pembayaran_sementara = floatval($jumlah_pembayaran_sementara)+floatval($jumlah_riwayat_simpanan);        
        // validasi cek simpanan aktif atau tidak, data simpanan tidak ditemukan atau angsuran telah lunas
        $cek_id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_simpanan='.$this->db->escape($id_simpanan).' AND id_kolektor='.$this->db->escape($id_kolektor).'');
        if (!empty($cek_id_simpanan)) {
            $status = 200;
            $msg = "Berhasil, simpanan telah masuk ke sistem...";
            $columnInsert = array(
                "id_simpanan" => $this->security->sanitize_filename($id_simpanan),                
                "jumlah_riwayat_simpanan" => (float) $this->security->sanitize_filename($jumlah_riwayat_simpanan),
                "tipe_riwayat" => "simpanan",
                "input_oleh" => "kolektor",
                "input_oleh_id" => $id_kolektor,
                "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
                "keterangan_riwayat" => $this->security->sanitize_filename($keterangan)
            );
            $this->db->insert('riwayat_simpanan', $columnInsert);        
            // update table simpanan
            $columnUpdate = array(                
               "jumlah_simpanan" => (float) $jumlah_pembayaran_sementara,    		
	    		"last_update" => date("Y-m-d H:i:s"),
	            "update_oleh" => "kolektor",
	            "update_oleh_id" => $id_kolektor
            );
            $this->db->where('id_simpanan', $this->security->sanitize_filename($id_simpanan));
            $this->db->update('simpanan', $columnUpdate);
        }else{
            $status = 500;
            $msg = "Data Simpanan tidak ditemukan atau STATUS SIMPANAN tersebut telah NON-AKTIF";
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function tambah_simpanan($id_kolektor) {    
        $status = 500;
        $msg = "";
        $id_nasabah = $this->input->post('id_nasabah',TRUE);
        $jumlah_simpanan = $this->input->post('jumlah_simpanan',TRUE);
        $note = $this->input->post('note',TRUE);
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
       	$tgl_sekarang = date("Y-m-d H:i:s");
       	$validasi = $this->validasi();
       	$status = isset($validasi['status']) ? $validasi['status'] : 500;
       	$msg = isset($validasi['msg']) ? $validasi['msg'] : "Nasabah tersebut sudah mempunyai akun tabungan";
        if ($status==200) {
            $status = 200;
            $msg = "Berhasil, simpanan telah masuk ke sistem...";
            $columnInsertSimpanan = array(
            	"id_owner" => $id_owner,
            	"id_kolektor" =>$id_kolektor,
            	"id_nasabah" => $this->security->sanitize_filename($id_nasabah),
            	"jumlah_simpanan" => (float) $this->security->sanitize_filename($jumlah_simpanan),
            	"tgl_simpanan" => $tgl_sekarang,
            	"status_simpanan" => "aktif",
            	"input_oleh" => "kolektor",
            	"input_oleh_id"=> $id_kolektor,
            	"note" => $this->security->sanitize_filename($note)
            );
            $this->db->insert('simpanan', $columnInsertSimpanan);
            $id_simpanan = $this->db->insert_id();
            if (!empty($id_simpanan)) {
	           	$columnInsertRiwayat = array(
		            "id_simpanan" => $id_simpanan,
		            "jumlah_riwayat_simpanan" => (float) $this->security->sanitize_filename($jumlah_simpanan),
		            "tipe_riwayat" => "simpanan",
		            "input_oleh" => "kolektor",
		            "input_oleh_id" => $id_kolektor,
		            "tgl_riwayat_simpanan" => $tgl_sekarang,
		            "keterangan_riwayat" => "Simpanan pertama dengan ID Simpanan : ".$id_simpanan. ". Tambahan note: ". $this->security->sanitize_filename($note)
		        );
		        $this->db->insert('riwayat_simpanan', $columnInsertRiwayat);
            }
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function validasi(){
    	$status=200;
        $msg="";
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('id_nasabah', 'Nasabah', 'trim|required|is_unique[simpanan.id_nasabah]',
             array(
                'required'      => '%s masih kosong',                
                'is_unique'      => '%s sudah terpakai silahkan pilih yang lain',                
            )
        );           
        $this->form_validation->set_rules('jumlah_simpanan', 'Jumlah simpanan awal', 'trim|required|numeric|min_length[1]|max_length[18]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil, simpanan telah masuk ke sistem..";
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }

}

/* End of file Msimpanan.php */
/* Location: ./application/models/Msimpanan.php */