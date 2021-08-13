<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mjadwal extends CI_Model {


    public function jadwal_tagihan($params,$custom_select='',$count=false,$additional_where='', $order_by="id_pinjaman DESC")
    {
        $id_kolektor = AUTHORIZATION::get_id_kolektor();
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);   
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
        $hari_kerja = $this->function_lib->get_one('hari_kerja','owner','id_owner="'.$id_owner.'"');
        $tgl=$this->input->post('tgl',TRUE);        
        if (trim($tgl)!="") {
            $jml_libur = 0;
            // jika $tgl =  senin tambahkan $jml libur tergantung hari kerja
            // jika $tgl = sabtu tambahkan $tgl tergantung hari kerja
            // dapatkan jumlah hari
            // dapatkan $jml_libur:jumlah hari libur sebelum $tgl tanpa ada interval
            for ($i=1; $i <= 30; $i++) {
                $isLibur = false;
                $cek = $this->function_lib->get_one('tgl_hari_libur','hari_libur','(id_owner="'.$id_owner.'" OR id_owner IS NULL) AND tgl_hari_libur="'.date("Y-m-d", strtotime($tgl."-".$i." days")).'"');
                if ($hari_kerja == 5) {
                    if (date("w", strtotime(date("Y-m-d", strtotime($tgl."-".$i." days")))) == "6" AND date("Y-m-d", strtotime($cek))!= date("Y-m-d", strtotime($tgl."-".$i." days"))) {
                        // sabtu dan tidak termasuk tgl merah
                        $isLibur = true;
                        // $jml_libur++;
                    }else if(date("w", strtotime(date("Y-m-d", strtotime($tgl."-".$i." days")))) == "0" AND date("Y-m-d", strtotime($cek))!= date("Y-m-d", strtotime($tgl."-".$i." days"))){
                        // minggu dan tidak termasuk tgl merah
                        $isLibur = true;
                        // $jml_libur++;
                    }
                }else if($hari_kerja == 6){
                    if(date("w", strtotime(date("Y-m-d", strtotime($tgl."-".$i." days")))) == "0" AND date("Y-m-d", strtotime($cek))!= date("Y-m-d", strtotime($tgl."-".$i." days"))){
                        // minggu dan tidak termasuk tgl merah
                        $isLibur = true;
                        // $jml_libur++;
                    }
                }
                // jika tgl cek libur dan is libur = true
                if (!empty($cek) OR $isLibur) {
                    $jml_libur++;
                }else{
                    break;
                }
            }
            
            // $where .=' AND date(tgl_pinjaman)='.$this->db->escape(date("Y-m-d",strtotime($tgl))).'';
            $whereLibur = "";
            if ($jml_libur > 0) {
                $whereLibur = " ( ";
            }
            
            $where .=' AND '.$whereLibur.$this->db->escape(date("Y-m-d",strtotime($tgl))).' >= date(tgl_pinjaman) AND status_pinjaman = "aktif" AND 
            (
                (periode_angsuran = "harian"  AND  '.$this->db->escape(date("Y-m-d",strtotime($tgl))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 DAY))) OR 
                (periode_angsuran = "mingguan"  AND dayofweek(tgl_pinjaman)=dayofweek('.$this->db->escape(date("Y-m-d",strtotime($tgl))).') AND  '.$this->db->escape(date("Y-m-d",strtotime($tgl))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 WEEK))) OR
                (periode_angsuran = "bulanan" AND '.$this->db->escape(date("Y-m-01",strtotime($tgl))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 MONTH))) OR
                (periode_angsuran = "tahunan" AND month(tgl_pinjaman) = '.$this->db->escape(date("n",strtotime($tgl))).' AND  '.$this->db->escape(date("Y-m-01",strtotime($tgl))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 YEAR))) 
            ) ';
            // set confition untuk tagihan tgl sebelumnya
            if ($jml_libur > 0) {
                // $where .=" (";
                for ($i=1; $i <= $jml_libur ; $i++) { 
                    $tglLibur = date("Y-m-d", strtotime($tgl." -".$i."days"));
                    $where .= ' OR ('.$this->db->escape(date("Y-m-d",strtotime($tglLibur))).' >= date(tgl_pinjaman) AND status_pinjaman = "aktif" AND 
                    (
                        (periode_angsuran = "harian"  AND  '.$this->db->escape(date("Y-m-d",strtotime($tglLibur))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 DAY))) OR 
                        (periode_angsuran = "mingguan"  AND dayofweek(tgl_pinjaman)=dayofweek('.$this->db->escape(date("Y-m-d",strtotime($tglLibur))).') AND  '.$this->db->escape(date("Y-m-d",strtotime($tglLibur))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 WEEK))) OR
                        (periode_angsuran = "bulanan" AND '.$this->db->escape(date("Y-m-01",strtotime($tglLibur))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 MONTH))) OR
                        (periode_angsuran = "tahunan" AND month(tgl_pinjaman) = '.$this->db->escape(date("n",strtotime($tglLibur))).' AND  '.$this->db->escape(date("Y-m-01",strtotime($tglLibur))).' <= date(DATE_ADD(tgl_pinjaman, INTERVAL lama_angsuran-1 YEAR))) 
                    ) )';
                }
                
            }
            if ($jml_libur > 0) {
                $where .=" )";
            }
            // setting hari libur dan hari kerja
            $where .= ' AND date("'.date("Y-m-d", strtotime($tgl)).'") NOT IN (SELECT date(tgl_hari_libur) as tgl_pinjaman FROM hari_libur WHERE id_owner IS NULL OR id_owner='.$this->db->escape($id_owner).')';
            if ($hari_kerja == "5") {
                $where .= ' AND DAYOFWEEK("'.$tgl.'")!=7 AND DAYOFWEEK("'.$tgl.'")!=1';
            }else if($hari_kerja == "6"){
                $where .= ' AND DAYOFWEEK("'.$tgl.'")!=1';
            }
            // $where .= ' OR '
            // jika ada pinjaman yg tertagih hari libur () ubah tagihan jadi tgl berikutnya
            // cek apakah hari sebelumnya hari libur
            // cek apakah hari sebelumnya bukan hari_kerja
            // 5 hari kerja = dayofweek($tgl) != 7,1
            // 6 hari kerja = dayofweek($tgl) != 7

            // dapatkan jumlah libur(bukan hari kerja dan bukan hari libur) di hari sebelumnya 
            
        }        
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'pinjaman';
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
    function hitung_hari($day, $startdate, $enddate, $counter) {
        if($startdate >= $enddate) {
            return $counter-1;  // A hack to make this function return the correct number of days.
        } else {
            return daycount($day, strtotime("next ".$day, $startdate), $enddate, ++$counter);
        }
    }     

}

/* End of file Mjadwal.php */
/* Location: ./application/models/Mjadwal.php */