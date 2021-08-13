<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mriwayat_pinjaman extends CI_Model {


    public function data_riwayat_pinjaman($params,$custom_select='',$count=false,$additional_where='', $order_by="id_riwayat DESC")
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
        	$where.=' AND (id_pinjaman LIKE '.$this->db->escape($pencarian).' OR id_pinjaman IN (SELECT id_pinjaman FROM pinjaman where id_nasabah IN (SELECT id_nasabah FROM nasabah WHERE username='.$this->db->escape($pencarian).')))';
        }
                
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'riwayat_pinjaman';
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
    public function summary_pinjaman_hari_ini($id_kolektor){
        // (id_pinjaman LIKE '.$this->db->escape($pencarian).' OR id_nasabah IN (SELECT id_nasabah FROM nasabah where username='.$this->db->escape($pencarian).'))
        // jumlah pengangsur, total angsuran, terbesar, terkecil
        $pencarian = $this->input->post('pencarian',true);
        if (!empty($pencarian)) {
            $where = ' AND (id_pinjaman LIKE '.$this->db->escape($pencarian).' OR id_pinjaman IN (SELECT id_pinjaman FROM pinjaman where id_nasabah IN (SELECT id_nasabah FROM nasabah WHERE username='.$this->db->escape($pencarian).')))';
        }else{
            $where =" AND 1";
        }
        $jumlah_pengangsur = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman IN(SELECT id_pinjaman FROM pinjaman where id_kolektor='.$this->db->escape($id_kolektor).') AND date(tgl_riwayat_pinjaman)= '.$this->db->escape(date("Y-m-d")).''.$where);
        $total_angsuran = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman IN(SELECT id_pinjaman FROM pinjaman where id_kolektor='.$this->db->escape($id_kolektor).') AND date(tgl_riwayat_pinjaman)= '.$this->db->escape(date("Y-m-d")).''.$where);
        $terbesar = $this->function_lib->get_one('max(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman IN(SELECT id_pinjaman FROM pinjaman where id_kolektor='.$this->db->escape($id_kolektor).') AND date(tgl_riwayat_pinjaman)= '.$this->db->escape(date("Y-m-d")).''.$where);
        $terkecil = $this->function_lib->get_one('min(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman IN(SELECT id_pinjaman FROM pinjaman where id_kolektor='.$this->db->escape($id_kolektor).') AND date(tgl_riwayat_pinjaman)= '.$this->db->escape(date("Y-m-d")).''.$where);
        $response = array(
            "jumlah_pengangsur" => !empty($jumlah_pengangsur) ? number_format($jumlah_pengangsur,0,',','.') : "0",
            "total_angsuran" => !empty($total_angsuran) ? "Rp. ".number_format($total_angsuran,2,',','.') : "0",
            "terbesar" => !empty($terbesar) ? "Rp. ".number_format($terbesar,2,',','.') : "0",
            "terkecil" => !empty($terkecil) ? "Rp. ".number_format($terkecil,2,',','.') : "0"
        );
        return $response;
    }
    
}

/* End of file Mriwayat_pinjaman.php */
/* Location: ./application/models/Mriwayat_pinjaman.php */