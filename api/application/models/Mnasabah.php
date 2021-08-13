<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mnasabah extends CI_Model {


    public function data_nasabah($params,$custom_select='',$count=false,$additional_where='', $order_by="nama_nasabah ASC")
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
        $username=$this->input->post('username',TRUE);        
        if (trim($pencarian)!="") {
            $where.=' AND (nama_nasabah like "%'.$this->db->escape_str($pencarian).'%" OR nama_usaha like "%'.$this->db->escape_str($pencarian).'%" OR username like "%'.$this->db->escape_str($pencarian).'%")';
        }
        if (trim($username)!="") {
            $where.=' AND username like "%'.$this->db->escape_str($username).'%"';
        }
        $no_nasabah = $this->input->post('no_nasabah',TRUE);
        if (trim($no_nasabah)!="") {
            $where .= ' AND no_nasabah = "'.$no_nasabah.'"';
        }        
        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = 'nasabah';
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
    // SELECT * FROM (SELECT id_riwayat, id_pinjaman as id_transaksi, jumlah_riwayat_pembayaran as jumlah_riwayat, tgl_riwayat_pinjaman as tgl_riwayat, keterangan_riwayat, 'pinjaman' as tipe_transaksi FROM riwayat_pinjaman WHERE id_pinjaman IN (SELECT id_pinjaman FROM pinjaman where id_nasabah=1) UNION SELECT id_riwayat_simpanan as id_riwayat, id_simpanan as id_transaksi, jumlah_riwayat_simpanan as jumlah_riwayat, tgl_riwayat_simpanan as tgl_riwayat, keterangan_riwayat, 'simpanan' as tipe_riwayat FROM riwayat_simpanan WHERE id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE id_nasabah=1)) as t ORDER BY t.tgl_riwayat

    public function riwayat_nasabah($params,$custom_select='',$count=false,$additional_where='', $order_by="tgl_riwayat ASC", $id_kolektor)
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
            $where.=' AND (id_transaksi like "%'.$this->db->escape_str($pencarian).'%")';
        }

        if(isset($_POST["sort"]["type"]) && isset($_POST["sort"]["field"]) && ($_POST["sort"]["type"]!="" && $_POST["sort"]["field"]!="")){
            $params["order_by"]=$_POST["sort"]["field"].' '.$_POST["sort"]["type"];
        }

        $where.=$additional_where;
        $params['table'] = "(SELECT id_riwayat, id_pinjaman as id_transaksi, jumlah_riwayat_pembayaran as jumlah_riwayat, tgl_riwayat_pinjaman as tgl_riwayat, keterangan_riwayat, 'pinjaman' as tipe_transaksi FROM riwayat_pinjaman WHERE id_pinjaman IN (SELECT id_pinjaman FROM pinjaman where id_kolektor=".$this->db->escape($id_kolektor).") UNION SELECT id_riwayat_simpanan as id_riwayat, id_simpanan as id_transaksi, jumlah_riwayat_simpanan as jumlah_riwayat, tgl_riwayat_simpanan as tgl_riwayat, keterangan_riwayat, 'simpanan' as tipe_riwayat FROM riwayat_simpanan WHERE id_simpanan IN (SELECT id_simpanan FROM simpanan WHERE  id_kolektor=".$this->db->escape($id_kolektor).")) as t";
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
    public function daftar_nasabah($id_kolektor){
        $validasi = $this->validasi("daftar");
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {
            $post = $this->input->post();
            $id_owner = $this->function_lib->get_id_owner($id_kolektor);
            $kode_koperasi = $this->function_lib->get_one('kode_koperasi','owner','id_owner='.$this->db->escape($id_owner).'');
            $post['id_owner'] = $id_owner;
            $post['status'] = "aktif";
            $post['id_kolektor'] = $id_kolektor;
            $post['tgl_bergabung'] = date("Y-m-d H:i:s");
            $this->db->insert('nasabah', $post, TRUE);
            $id_nasabah_last_insert = $this->db->insert_id();
            $columnUpdate = array(
                "no_nasabah"=>$kode_koperasi.$id_nasabah_last_insert
            );
            $this->db->where('id_nasabah', $id_nasabah_last_insert);
            $this->db->update('nasabah', $columnUpdate);
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function edit_nasabah($id_kolektor){
        $validasi = $this->validasi("edit");
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {
            $email = $this->input->post('email',true);
            $id_nasabah = $this->input->post('id_nasabah',true);
            $nama_nasabah = $this->input->post('nama_nasabah',true);
            $nama_ibu_kandung = $this->input->post('nama_ibu_kandung',true);
            $no_hp = $this->input->post('no_hp',true);
            $jenis_kelamin = $this->input->post('jenis_kelamin',true);
            $tempat_lahir = $this->input->post('tempat_lahir',true);
            $tanggal_lahir = $this->input->post('tanggal_lahir',true);
            $alamat_rumah = $this->input->post('alamat_rumah',true);
            $provinsi = $this->input->post('provinsi',true);
            $kabupaten = $this->input->post('kabupaten',true);
            $kecamatan = $this->input->post('kecamatan',true);
            $kelurahan = $this->input->post('kelurahan',true);
            $warga_negara = $this->input->post('warga_negara',true);
            $pekerjaan = $this->input->post('pekerjaan',true);
            $alamat_tempat_kerja = $this->input->post('alamat_tempat_kerja',true);
            $nama_usaha = $this->input->post('nama_usaha',true);
            $agama = $this->input->post('agama',true);
            $golongan_darah = $this->input->post('golongan_darah',true);
            $hobi = $this->input->post('hobi',true);
            $makanan_kesukaan = $this->input->post('makanan_kesukaan',true);            
            $columnUpdate = array(
                "email" => $email,
                "nama_nasabah" => $nama_nasabah,
                "nama_ibu_kandung" => $nama_ibu_kandung,
                "no_hp" => $no_hp,
                "jenis_kelamin" => $jenis_kelamin,
                "tempat_lahir" => $tempat_lahir,
                "tanggal_lahir" => date("Y-m-d",strtotime($tanggal_lahir)),
                "alamat_rumah" => $alamat_rumah,
                "provinsi" => $provinsi,
                "kabupaten" => $kabupaten,
                "kecamatan" => $kecamatan,
                "kelurahan" => $kelurahan,
                "warga_negara" => $warga_negara,
                "pekerjaan" => $pekerjaan,
                "alamat_tempat_kerja" => $alamat_tempat_kerja,
                "nama_usaha" => $nama_usaha,
                "agama" => $agama,
                "golongan_darah" => $golongan_darah,
                "hobi" => $hobi,
                "makanan_kesukaan" => $makanan_kesukaan
            );
            $this->db->where('id_nasabah', $id_nasabah);
            $this->db->update('nasabah', $columnUpdate);            
        }
        return array("status"=>$status,"msg"=>$msg);
    }
   
    function validasi($action="daftar"){
        $status=200;
        $msg="";
        $this->load->library('form_validation');      
        if ($action == "daftar") {            
            $this->form_validation->set_rules('username', 'Username', 'trim|required|is_unique[nasabah.username]',
                 array(
                    'required'      => '%s masih kosong',                
                    'is_unique'      => '%s sudah terpakai silahkan pilih yang lain',                
                )
            );   
        }
        $this->form_validation->set_rules('nama_nasabah', 'Nama nasabah', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );    
         $this->form_validation->set_rules('email', 'Email nasabah', 'trim|required|min_length[1]|max_length[250]',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        $this->form_validation->set_rules('no_hp', 'No. HP', 'trim|required|numeric|min_length[10]|max_length[15]',
             array(
                'required'      => '%s masih kosong',                
            )
        ); 
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin',  'required');      
        $this->form_validation->set_rules('tempat_lahir', 'Tempat lahir', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );   
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal lahir', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );     
        $this->form_validation->set_rules('alamat_rumah', 'Alamat rumah', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        ); 
        $this->form_validation->set_rules('alamat_tempat_kerja', 'Alamat tempat kerja', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        ); 
        $this->form_validation->set_rules('nama_usaha', 'Nama usaha', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );      
        if ($this->form_validation->run() == TRUE) {
            $status=200;
            $msg="Pendaftaran nasabah berhasil dilakukan";
            $email = $this->input->post('email',true);
            $username = $this->input->post('username',true);
            $id_nasabah = $this->input->post('id_nasabah',true);
            if ($action == "edit") {                
                $msg="Data nasabah berhasil diubah";
                $cek_id_nasabah = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah='.$this->db->escape($id_nasabah).'');
                $cek_email = $this->function_lib->get_one('email','nasabah','email='.$this->db->escape($email).' AND username!='.$this->db->escape($username).'');
                if (!empty($cek_email)) {
                    $status = 500;
                    $msg = "Email sudah digunakan silahkan pilih email lain";
                }
                if (empty($cek_id_nasabah)) {
                    $status = 500;
                    $msg = "Data nasabah tidak ditemukan";
                }
            }
        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function simpan(){
        $unique_time = time();
        $config['upload_path']          = 'assets/image_nasabah';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = FALSE;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('gambar_nasabah', FALSE)) {        
            $id_kategori_nasabah=$this->input->post('id_kategori_nasabah');            
            $judul_nasabah=$this->input->post('judul_nasabah');            
            $deskripsi_nasabah=$this->input->post('deskripsi_nasabah');            
            $insertData = array(
                "id_kategori_nasabah" => $id_kategori_nasabah,
                "judul_nasabah" => $judul_nasabah,
                "deskripsi_nasabah" => $deskripsi_nasabah,                                
            );

            $status = 200;
            $msg = "Berhasil menambahkan nasabah";
            $post=$this->input->post();
            $this->db->insert('nasabah', $insertData);   
        }
        else
        {
            $config['image_library'] = 'gd2';
            $config['source_image'] = 'assets/image_nasabah/'.$this->upload->data('file_name');
            $config['create_thumb'] = TRUE;            
            $config['thumb_marker'] = '-' . $unique_time;        
            $config['maintain_ratio'] = TRUE;            
            $config['width'] = 500;
            $config['heigt'] = 500;
            $this->upload->overwrite = true;
            $this->load->library('image_lib');            
            $this->image_lib->clear();
            $this->image_lib->initialize($config);
            $this->image_lib->resize();
            $id_kategori_nasabah=$this->input->post('id_kategori_nasabah');            
            $judul_nasabah=$this->input->post('judul_nasabah');            
            $deskripsi_nasabah=$this->input->post('deskripsi_nasabah');            

            $data = $this->upload->data(); 
            $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

            $insertData = array(
                "id_kategori_nasabah" => $id_kategori_nasabah,
                "judul_nasabah" => $judul_nasabah,
                "deskripsi_nasabah" => $deskripsi_nasabah,                
                "gambar_nasabah" => $thumbnail,
            );

            $status = 200;
            $msg = "Berhasil menambahkan nasabah";
            $post=$this->input->post();
            $this->db->insert('nasabah', $insertData);      
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function edit($id_nasabah){
        $id_kategori_nasabah=$this->input->post('id_kategori_nasabah');            
        $judul_nasabah=$this->input->post('judul_nasabah',TRUE);            
        $deskripsi_nasabah=$this->input->post('deskripsi_nasabah');         
        $unique_time = time();
        $config['upload_path']          = 'assets/image_nasabah';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 1000;        
        $config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $upload = $this->upload->do_upload('gambar_nasabah');        
        if ($upload) {        
                $gambar_nasabah=$this->function_lib->get_one('gambar_nasabah','nasabah','id_nasabah="'.$id_nasabah.'"');
                if (file_exists(FCPATH.'assets/image_nasabah/'.$gambar_nasabah) && $gambar_nasabah != "") {
                    unlink(FCPATH.'assets/image_nasabah/'.$gambar_nasabah);                    
                }       
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'assets/image_nasabah/'.$this->upload->data('file_name');
                $config['create_thumb'] = TRUE;            
                $config['thumb_marker'] = '-' . $unique_time;        
                $config['maintain_ratio'] = TRUE;            
                $config['width'] = 500;
                $config['heigt'] = 500;
                $config['overwrite'] = TRUE;
                $this->load->library('image_lib');            
                $this->image_lib->clear();
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                

                $data = $this->upload->data(); 
                $thumbnail = $data['raw_name'].'-'.$unique_time.$data['file_ext']; 

                $updateData = array(
                    "id_kategori_nasabah" => $id_kategori_nasabah,
                    "judul_nasabah" => $judul_nasabah,
                    "deskripsi_nasabah" => $deskripsi_nasabah,                
                    "gambar_nasabah" => $thumbnail,
                );
                
                $status = 200;
                $msg = "Berhasil mengubah Kategori";
                $this->db->where('id_nasabah', $id_nasabah);                
                $this->db->update('nasabah', $updateData); 
            
        }else{
            // tidak ada perubahan upload file            
            $status = 200;
            $msg = "Berhasil mengubah Nasabah";
            $updateData = array(
               "id_kategori_nasabah" => $id_kategori_nasabah,
                "judul_nasabah" => $judul_nasabah,
                "deskripsi_nasabah" => $deskripsi_nasabah,   
            );
            $this->db->where('id_nasabah', $id_nasabah);
            $post=$this->input->post();
            $this->db->update('nasabah', $updateData);             
        }
       return array("status"=>$status,"msg"=>$msg); 
    }      
    function hapus($id_nasabah){
        $gambar_nasabah=$this->function_lib->get_one('gambar_nasabah','nasabah','id_nasabah="'.$id_nasabah.'"');
        
        if (file_exists(FCPATH.'assets/image_nasabah/'.$gambar_nasabah) && $gambar_nasabah != "") {
            unlink(FCPATH.'assets/image_nasabah/'.$gambar_kategori_nasabah);
        }                
        $this->db->where('id_nasabah', $id_nasabah);
        $this->db->delete('nasabah');
    }
     public function tambahView(){
        $status=500;
        $msg="";        
        $id_nasabah=$this->input->post('id_nasabah');
        $cek=$this->function_lib->get_one('id_nasabah','nasabah','id_nasabah="'.$id_nasabah.'"');
        if (trim($cek)!="") {
            $this->db->query('UPDATE `nasabah` SET `jumlah_view` = `jumlah_view`+1 WHERE `id_nasabah` = '.$id_nasabah.'');            
            $status=200;
            $msg="OK";
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    public function upload_foto(){        
        $config['upload_path']          = 'assets/foto_nasabah';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 5000;        
        $config['overwrite'] = TRUE;
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $id_nasabah = $this->input->post('id_nasabah',TRUE);
        $id_nasabah = $this->security->sanitize_filename($id_nasabah);
        $upload = $this->upload->do_upload('foto_nasabah');      
        if ($upload) {
            $cek = $this->function_lib->get_one('id_nasabah','nasabah','id_nasabah='.$this->db->escape($id_nasabah).'');
            if (!empty($cek)) {                
                $nama_file = $this->upload->data('file_name');
                $this->db->set('foto_nasabah',$nama_file);
                $this->db->where('id_nasabah', $id_nasabah);
                $this->db->update('nasabah');
                $data['status'] = 200;
                $data['msg'] = "Sukses Upload Gambar";
            }else{
                $data['status'] = 500;
                $data['msg'] = "Data nasabah tidak ditemukan";
            }
        }else{
            $data['status'] = 500;
            $data['msg'] = "gagal Upload Gambar";
        }
        return $data;
    }

}

/* End of file Mnasabah.php */
/* Location: ./application/models/Mnasabah.php */