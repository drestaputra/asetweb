<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmahasiswa extends CI_Model {


    public function data_mahasiswa($params,$custom_select='',$count=false,$additional_where='', $order_by="id_mahasiswa DESC")
    {
        
        $where_detail=' ';
        $where=" ";
        $pencarian=htmlentities($this->input->post('pencarian',TRUE));        
        if (trim($pencarian)!="") {
            $where.=' AND nama_mahasiswa like "%'.$pencarian.'%" OR username like "%'.$pencarian.'%" OR nim like "%'.$pencarian.'%"';
        }

        if($count==false)
        {
            $params['order_by'] =$order_by;
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'mahasiswa';
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
    public function data_peringkat_mahasiswa($params,$custom_select='',$count=false,$additional_where='', $order_by="jumlah_nilai DESC")
    {
        
        $where_detail=' ';
        $where=" ";
        $pencarian=htmlentities($this->input->post('pencarian',TRUE));        
        if (trim($pencarian)!="") {
            $where.=' AND id_mahasiswa in (SELECT id_mahasiswa from mahasiswa where nama_mahasiswa like "%'.$pencarian.'%" OR username like "%'.$pencarian.'%" OR nim like "%'.$pencarian.'%")';
        }

        if($count==false)
        {
            $params['order_by'] =$order_by;
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $this->db->query('SET @i=0;');        
        $where.=$additional_where;
        $params['table'] = '(
        SELECT @i:=@i+1 AS ranking,id_mahasiswa,jumlah_nilai FROM  (SELECT id_mahasiswa, (sum(nilai_komponen_1)+sum(nilai_komponen_2)+sum(nilai_komponen_3)+sum(nilai_komponen_4)+sum(nilai_komponen_5)+sum(nilai_komponen_6)+sum(nilai_komponen_7)+sum(nilai_komponen_8)+sum(nilai_komponen_9)) as jumlah_nilai from penilaian_tutorial GROUP BY id_mahasiswa ORDER BY jumlah_nilai DESC) as T) as B';
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
    public function riwayat_tutorial($params,$custom_select='',$count=false,$additional_where='', $order_by="waktu_selesai DESC")
    {
        //filter default
        $columnFilterDefault=array(
            'start_date'=>'waktu_mulai',
            'end_date'=>'waktu_selesai'
        );
        $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);
        $where_detail=' ';
        $where=' AND tutorial_is_active="0" AND penilaian_tutorial.id_mahasiswa="'.$id_mahasiswa.'"';
        if(!empty($columnFilterDefault)) //data grid
        {
            foreach($columnFilterDefault AS $columnName=>$value)
            {
                if (isset($_POST[$columnName]) && trim($_POST[$columnName])!='' && $columnName=='start_date'){
                    $where.= " AND ".$value." >= ".$this->db->escape(date('Y-m-d',strtotime($_POST[$columnName])).' 00:00:00')." ";
                } else if (isset($_POST[$columnName]) && trim($_POST[$columnName])!='' && $columnName=='end_date'){
                    $where.= " AND ".$value." <= ".$this->db->escape(date('Y-m-d',strtotime($_POST[$columnName])).' 23:59:59')." ";
                } else if (isset($_POST[$columnName]) && trim($_POST[$columnName])!=''){
                    $where.= " AND ".$value." LIKE ".$this->db->escape('%'.$_POST[$columnName].'%')." ";
                }
            }
        }

        if($count==false)
        {
            $params['order_by'] =$order_by;
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'tutorial';
        // $params['select'] = '*';
        $params['select'] = 'tutorial.*,blok.nama_blok,blok.jumlah_tempat_duduk,topik.nama_topik,topik.skenario_topik,topik.kunci_jawaban_topik,dosen.nama_dosen,ruangan.nama_ruangan';
        $params['join'] = "
        LEFT JOIN blok on blok.id_blok=tutorial.id_blok
        LEFT JOIN topik on topik.id_topik=tutorial.id_topik
        LEFT JOIN dosen on dosen.id_dosen=tutorial.id_dosen
        LEFT JOIN ruangan on tutorial.id_ruangan=ruangan.id_ruangan
        LEFT JOIN penilaian_tutorial on tutorial.id_tutorial=penilaian_tutorial.id_tutorial
        ";

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

    // tutorial active di mahasiswa
    public function tutorial_active($params,$custom_select='',$count=false,$additional_where='', $order_by="waktu_selesai DESC")
    {
        //filter default
        $columnFilterDefault=array(
            'start_date'=>'waktu_mulai',
            'end_date'=>'waktu_selesai'
        );
        $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);
        $where_detail=' ';
        $where=' AND tutorial_is_active="1" AND penilaian_tutorial.id_mahasiswa="'.$id_mahasiswa.'"';
        if(!empty($columnFilterDefault)) //data grid
        {
            foreach($columnFilterDefault AS $columnName=>$value)
            {
                if (isset($_POST[$columnName]) && trim($_POST[$columnName])!='' && $columnName=='start_date'){
                    $where.= " AND ".$value." >= ".$this->db->escape(date('Y-m-d',strtotime($_POST[$columnName])).' 00:00:00')." ";
                } else if (isset($_POST[$columnName]) && trim($_POST[$columnName])!='' && $columnName=='end_date'){
                    $where.= " AND ".$value." <= ".$this->db->escape(date('Y-m-d',strtotime($_POST[$columnName])).' 23:59:59')." ";
                } else if (isset($_POST[$columnName]) && trim($_POST[$columnName])!=''){
                    $where.= " AND ".$value." LIKE ".$this->db->escape('%'.$_POST[$columnName].'%')." ";
                }
            }
        }

        if($count==false)
        {
            $params['order_by'] =$order_by;
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'tutorial';
        // $params['select'] = '*';
        $params['select'] = 'tutorial.*,blok.nama_blok,blok.jumlah_tempat_duduk,topik.nama_topik,topik.skenario_topik,topik.kunci_jawaban_topik,dosen.nama_dosen,ruangan.nama_ruangan';
        $params['join'] = "
        LEFT JOIN blok on blok.id_blok=tutorial.id_blok
        LEFT JOIN topik on topik.id_topik=tutorial.id_topik
        LEFT JOIN dosen on dosen.id_dosen=tutorial.id_dosen
        LEFT JOIN ruangan on tutorial.id_ruangan=ruangan.id_ruangan
        LEFT JOIN penilaian_tutorial on tutorial.id_tutorial=penilaian_tutorial.id_tutorial
        ";

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

    public function join_tutorial(){
        $kode_tutorial=$this->input->post('kode_tutorial');
        $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);
        $id_tutorial=$this->function_lib->get_one('id_tutorial','tutorial','kode_tutorial="'.$kode_tutorial.'"');
        if (isset($id_tutorial) AND trim($id_tutorial)!="") {
            $status=200;
            $msg="Berhasil bergabung ke tutorial";
            // cek apakah id_mahasiswa sudah ada atau belum
            $cek_id_mahasiswa=$this->function_lib->get_one('id_mahasiswa','penilaian_tutorial','id_tutorial="'.$id_tutorial.'" AND id_mahasiswa="'.$id_mahasiswa.'"');
            $cek_tempat_duduk_mahasiswa=$this->function_lib->get_one('tempat_duduk','penilaian_tutorial','id_tutorial="'.$id_tutorial.'" AND id_mahasiswa="'.$id_mahasiswa.'"');
            if (isset($cek_id_mahasiswa) AND trim($cek_id_mahasiswa)=="") {

                // id_mahasiswa belum ada (mahasiswa belum terdaftar di tutorial)
                // diarahkan untuk memilih tempat duduk
                $column_penilaian_tutorial=array(
                    "id_tutorial"=>$id_tutorial,
                    "waktu_penilaian"=>date('Y-m-d H:i:s'),
                    "id_mahasiswa"=>$id_mahasiswa
                );
                $this->db->insert('penilaian_tutorial', $column_penilaian_tutorial);    
                $status=200;                
                $msg="Berhasil bergabung ke kelas tutorial, Silahkan pilih tempat Duduk";                                    
            }else{
                // id_mahasiswa sudah ada(sudah terdaftar di tutorial)
                // diarahkan untuk hanya melihat tempat duduk atau pilih tempat
                $column_update_penilaian_tutorial=array(                    
                    "waktu_penilaian_update"=>date('Y-m-d H:i:s'),                    
                );
                $this->db->where('id_tutorial', $cek_id_mahasiswa);
                $this->db->update('penilaian_tutorial', $column_update_penilaian_tutorial);    
                $status=300;
                $msg="Anda sudah terdaftar";                
            }            
        }else{
            $status=500;
            $msg="Tutorial tidak ditemukan";
        }
        return array("status"=>$status,"msg"=>$msg,"data"=>$id_tutorial);
    }
    // pilih tempat duduk
    public function pilih_tempat_duduk(){
        $tempat_duduk=$this->input->post('tempat_duduk');    
        $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);
        $id_tutorial=$this->input->post('id_tutorial',TRUE);
        $status=500;
        $msg="";
        $cek_id_tutorial=$this->function_lib->get_one('id_tutorial','penilaian_tutorial','id_tutorial="'.$id_tutorial.'"');
        if (isset($cek_id_tutorial) AND trim($cek_id_tutorial)!="") {            
            $cek_tempat_duduk_mahasiswa=$this->function_lib->get_one('tempat_duduk','penilaian_tutorial','id_tutorial="'.$id_tutorial.'" AND tempat_duduk="'.$tempat_duduk.'"');        
            if ($cek_tempat_duduk_mahasiswa!="") {
                // tempat duduk sudah dipake
                $status=400;
                $msg="Tempat duduk sudah ditempati silahkan pilih yang lain";
            }else{
                $status=200;
                $msg="Berhasil Memilih";
                $update_column=array(
                    "tempat_duduk"=>$tempat_duduk
                );
                $where='id_tutorial="'.$id_tutorial.'" AND id_mahasiswa="'.$id_mahasiswa.'"';
                $this->db->where($where);
                $this->db->update('penilaian_tutorial', $update_column);
            }
        }else{
            $status=500;
            $msg="Tutorial tidak ditemukan";
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    
    public function profil(){
        $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);
        $this->db->where('id_mahasiswa', $id_mahasiswa);
        $query=$this->db->get('mahasiswa');
        $data=$query->row_array();        
        return array("status"=>200,"msg"=>"","data"=>$data);
    }
    function validasi_udpate_profil(){
        $status=200;
        $msg="";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_mahasiswa', 'Mahasiswa', 'required',
             array(                
                'required'     => 'Data Mahasiswa tidak ditemukan.'
            )
        );
        $this->form_validation->set_rules('nim', 'NIP', 'max_length[100]');
        $this->form_validation->set_rules('email', 'Email', 'max_length[100]',
            array(                
                'is_unique'     => 'Maksimal karakter email 100.'
            )
        );
        $this->form_validation->set_rules('no_hp', 'Nomor HP', 'max_length[20]',
            array(                
                'max_length'    => '%s maksimal 20 karakter',                
            )
        );
        $this->form_validation->set_rules('nama_mahasiswa', 'Nama Mahasiswa', 'max_length[250]',
            array(                
                'max_length'    => '%s maksimal 250 karakter',                
            )
        );              
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Berhasil";
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function update_profil(){
        $status=500;
        $msg="";
        $validasi=$this->validasi_udpate_profil();
        if ($validasi['status']==200) {
            $id_mahasiswa=$this->input->post('id_mahasiswa');
            $cek_id=$this->function_lib->get_one('id_mahasiswa','mahasiswa','id_mahasiswa="'.$id_mahasiswa.'"');
            if (trim($cek_id)=="") {
                $status=500;
                $msg="Data Mahasiswa tidak ditemukan";
            }else{
                $update=array();
                $nim=$this->input->post('nim');
                $email=$this->input->post('email');
                $no_hp=$this->input->post('no_hp');
                $nama_mahasiswa=$this->input->post('nama_mahasiswa');
                if (trim($nim)!="") {
                    $update['nim']=$nim;                    
                }
                if (trim($email)!="") {
                    $update['email']=$email;                    
                }
                if (trim($no_hp)!="") {
                    $update['no_hp']=$no_hp;                    
                }
                if (trim($nama_mahasiswa)!="") {
                    $update['nama_mahasiswa']=$nama_mahasiswa;                    
                }
                $this->db->where('id_mahasiswa', $id_mahasiswa);
                $this->db->update('mahasiswa', $update);
                $status=200;
                $msg="Berhasil mengubah profil";
            }

        }else{
            $status=$validasi['status'];
            $msg=$validasi['msg'];
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function statistik(){
        $response['ranking']=$this->get_ranking();
        $response['tutorial']=$this->get_jumlah_tutorial();
        $response['blok']=$this->get_jumlah_topik();
        return array("status"=>200,"msg"=>"","data"=>$response);
    }
    public function get_ranking($id_mahasiswa_p=""){
        if (trim($id_mahasiswa_p)!="") {
            $id_mahasiswa=$id_mahasiswa_p;
        }else{            
            $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);        
        }
        $this->db->query('SET @i=0;');
        $query=$this->db->query('
        SELECT * from (
        SELECT @i:=@i+1 AS ranking,id_mahasiswa,jumlah_nilai FROM  (SELECT id_mahasiswa, (sum(nilai_komponen_1)+sum(nilai_komponen_2)+sum(nilai_komponen_3)+sum(nilai_komponen_4)+sum(nilai_komponen_5)+sum(nilai_komponen_6)+sum(nilai_komponen_7)+sum(nilai_komponen_8)+sum(nilai_komponen_9)) as jumlah_nilai from penilaian_tutorial GROUP BY id_mahasiswa ORDER BY jumlah_nilai DESC) as T) as B where id_mahasiswa="'.$id_mahasiswa.'"');
        $data=$query->row_array();
        return $data['ranking'];
    }
    public function get_jumlah_tutorial($id_mahasiswa_p=""){
        if (trim($id_mahasiswa_p)!="") {
            $id_mahasiswa=$id_mahasiswa_p;
        }else{            
            $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);                    
        }
        $jml=$this->function_lib->get_one('count(id_tutorial)','penilaian_tutorial','id_mahasiswa="'.$id_mahasiswa.'"');
        $jml=(trim($jml)!="")?$jml:"0";
        return $jml;
    }
    public function get_jumlah_topik($id_mahasiswa_p=""){
        if (trim($id_mahasiswa_p)!="") {
            $id_mahasiswa=$id_mahasiswa_p;
        }else{            
            $id_mahasiswa=$this->input->post('id_mahasiswa',TRUE);                    
        }
        
        $where='id_topik in (SELECT id_topik from tutorial where id_tutorial in (SELECT id_tutorial FROM penilaian_tutorial where id_mahasiswa="'.$id_mahasiswa.'"))';
        $jml=$this->function_lib->get_one('count(id_topik)','topik',$where);
        $jml=(trim($jml)!="")?$jml:"0";
        return $jml;
    }


}

/* End of file Mmahasiswa.php */
/* Location: ./application/models/Mmahasiswa.php */