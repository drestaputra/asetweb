<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mberkas extends CI_Model {


    public function data_berkas($params,$custom_select='',$count=false,$additional_where='', $order_by="id_berkas DESC")
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
            $where.=' AND nama_berkas like "%'.$this->db->escape_str($pencarian).'%"';
        }
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'berkas';
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

/* End of file Mberkas.php */
/* Location: ./application/models/Mberkas.php */