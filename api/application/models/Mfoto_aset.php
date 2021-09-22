<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mfoto_aset extends CI_Model {

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
        	$where.=' AND (kode_barang LIKE '.$this->db->escape($pencarian).' OR nama_foto_aset LIKE '.$this->db->escape($pencarian).')';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'foto_aset';
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
    public function getFotoAset($id_aset){
    	$dataFoto = array();
    	$cekIdAset = $this->function_lib->get_one('id_aset','aset','id_aset='.$this->db->escape($id_aset).'');
    	if (!empty($cekIdAset)) {
            $this->db->select('id_foto_aset,id_aset,CONCAT("'.base_url('assets/foto_aset/').'", foto_aset) as foto_aset,status_foto,created_datetime');
    		$this->db->where('id_aset', $id_aset);
    		$this->db->order_by('id_foto_aset', 'desc');
    		$query = $this->db->get('foto_aset');
    		$dataFoto = $query->result_array();
    	}
    	return $dataFoto;
    }
}

/* End of file Malamat.php */
/* Location: ./application/models/Malamat.php */