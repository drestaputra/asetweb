<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mriwayat_simpanan extends CI_Model {


    public function data_riwayat_simpanan($params,$custom_select='',$count=false,$additional_where='', $order_by="id_riwayat_simpanan DESC")
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
            
        	$where.=' AND id_simpanan LIKE '.$this->db->escape($pencarian).'';
        }
                
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'riwayat_simpanan';
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

/* End of file Mriwayat_simpanan.php */
/* Location: ./application/models/Mriwayat_simpanan.php */