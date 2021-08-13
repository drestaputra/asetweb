<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Moper_berkas extends CI_Model {


    public function data_oper_berkas($params,$custom_select='',$count=false,$additional_where='', $order_by="id_oper_berkas DESC")
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
            $where.=' AND id_nasabah IN (SELECT id_nasabah FROM nasabah WHERE nama_nasabah like "%'.$this->db->escape_str($pencarian).'%")';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'oper_berkas';
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
    public function proses_oper_berkas($id_kolektor){
    	$status_model = 500;
    	$msg = "";
    	$id_oper_berkas = $this->input->post('id_oper_berkas', true);
    	$status = $this->input->post('status', true);
    	$data_oper_berkas = $this->function_lib->get_row('oper_berkas','id_oper_berkas="'.$this->security->sanitize_filename($id_oper_berkas).'"');
    	if (!empty($data_oper_berkas)) {
	    	// update table oper berkas
	    	$columnUpdate = array("status" => $status);
	    	$this->db->where('id_oper_berkas', $id_oper_berkas);
	    	$this->db->update('oper_berkas', $columnUpdate);

	    	// update table pinjaman
	    	if ($status == "done") {
		    	$id_kolektor_baru = isset($data_oper_berkas['id_kolektor_ke']) ? $data_oper_berkas['id_kolektor_ke'] : "0";
		    	$id_nasabah = isset($data_oper_berkas['id_nasabah']) ? $data_oper_berkas['id_nasabah'] : "0";
                // update nasabah
                $this->db->set('id_kolektor',$id_kolektor_baru);
                $this->db->where('id_nasabah', $id_nasabah);
                $is_success = $this->db->update('nasabah');
                if ($is_success) {                    
    		    	// update pinjaman
                    $columnUpdatePinjaman = array("id_kolektor"=>$id_kolektor_baru,"last_update"=>date("Y-m-d H:i:s"));
                    $this->db->where('id_nasabah', $id_nasabah);
                    $this->db->update('pinjaman', $columnUpdatePinjaman);
                    // update simpanan
                    $columnUpdateSimpanan = array("id_kolektor"=>$id_kolektor_baru,"last_update"=>date("Y-m-d H:i:s"));
    		    	$this->db->where('id_nasabah', $id_nasabah);
    		    	$this->db->update('simpanan', $columnUpdateSimpanan);
                }

				
	    	}
	    	$status_model = 200;
			$msg = "Berhasil mengubah status pengajuan oper berkas";
    	}
    	return array("status"=>$status_model,"msg"=>$msg);
    }   

}

/* End of file Moper_berkas.php */
/* Location: ./application/models/Moper_berkas.php */