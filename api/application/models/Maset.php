<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maset extends CI_Model {

	public function data($params,$custom_select='',$count=false,$additional_where='', $order_by="id_riwayat DESC")
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
        if (!empty($pencarian)) {            
        	$where.=' AND (kode_barang LIKE '.$this->db->escape($pencarian).' OR nama_aset LIKE '.$this->db->escape($pencarian).')';
        }
        $id_aset = $this->input->post('id_aset',TRUE);
        if (!empty($id_aset)) {            
            $where.=' AND id_aset = '.$this->db->escape($id_aset).'';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'aset';
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
}

/* End of file Malamat.php */
/* Location: ./application/models/Malamat.php */