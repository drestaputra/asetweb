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
        // private String nama_aset,kode_barang, tahun_perolehan, alamat, nomor_sertifikat;
        $nama_aset = $this->input->post('nama_aset',TRUE);
        if (!empty($nama_aset)) {            
            $where.=' AND nama_aset like "%'.$this->db->escape_str($nama_aset).'%"';
        }
        $kode_barang = $this->input->post('kode_barang',TRUE);
        if (!empty($kode_barang)) {            
            $where.=' AND kode_barang like "%'.$this->db->escape_str($kode_barang).'%"';
        }
        $tahun_perolehan = $this->input->post('tahun_perolehan',TRUE);
        if (!empty($tahun_perolehan)) {            
            $where.=' AND tahun_perolehan like "%'.$this->db->escape_str($tahun_perolehan).'%"';
        }
        $alamat = $this->input->post('alamat',TRUE);
        if (!empty($alamat)) {            
            $where.=' AND alamat like "%'.$this->db->escape_str($alamat).'%"';
        }
        $nomor_sertifikat = $this->input->post('nomor_sertifikat',TRUE);
        if (!empty($nomor_sertifikat)) {            
            $where.=' AND nomor_sertifikat like "%'.$this->db->escape_str($nomor_sertifikat).'%"';
        }
        $jenis_hak = $this->input->post('jenis_hak',TRUE);
        if (!empty($jenis_hak)) {            
            if (strtolower($jenis_hak) != "pilih") {
            $where.=' AND jenis_hak like "%'.$this->db->escape_str($jenis_hak).'%"';
            }
        }
        $luas_tanah = $this->input->post('luas_tanah',TRUE);
        if (!empty($luas_tanah)) {            
            if (strtolower($luas_tanah) != "pilih") {
                $luasTanahArr = explode("-", $luas_tanah);
                if (sizeof($luasTanahArr) == 2 ) {
                    $luasBawah = (isset($luasTanahArr[0])) ? intval($luasTanahArr[0]) : "0";
                    $luasAtas = (isset($luasTanahArr[1])) ? intval($luasTanahArr[1]) : "0";
                    $where.=' AND luas_tanah >= '.$this->db->escape($luasBawah).' AND luas_tanah <= '.$this->db->escape($luasAtas).'';
                }else if(sizeof($luasTanahArr == 1)){
                    $where.=' AND luas_tanah > '.$this->db->escape($luas_tanah).' ';
                }
            }
        }
        $pencarian = $this->input->post('pencarian',TRUE);
        if (!empty($pencarian)) {            
        	$where.=' AND (kode_barang LIKE "%'.$this->db->escape_str($pencarian).'%" OR nama_aset LIKE "%'.$this->db->escape_str($pencarian).'%")';
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