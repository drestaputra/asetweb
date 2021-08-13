<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mpinjaman extends CI_Model {


    public function data_pinjaman($params,$custom_select='',$count=false,$additional_where='', $order_by="id_pinjaman DESC")
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
            $where.=' AND (id_pinjaman LIKE '.$this->db->escape($pencarian).' OR id_nasabah IN (SELECT id_nasabah from nasabah WHERE username LIKE "%'.$this->db->escape_str($pencarian).'%"))';
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
    function get_all_id_pinjaman($id_kolektor){
        // SELECT id_pinjaman,id_nasabah, nama_nasabah, jumlah_perangsuran
        $this->db->where('pinjaman.id_kolektor', $id_kolektor);
        $this->db->where('status_pinjaman', "aktif");
        $this->db->select('id_pinjaman,pinjaman.id_nasabah, nasabah.nama_nasabah, jumlah_perangsuran');
        $this->db->join('nasabah', 'pinjaman.id_nasabah = nasabah.id_nasabah', 'left');
        $query = $this->db->get('pinjaman');
        $data = $query->result_array();
        return $data;
    }
    function bayar_angsuran($id_kolektor) {    
        $status = 500;
        $msg = "";
        $id_pinjaman = $this->input->post('id_pinjaman',TRUE);
        $jumlah_pembayaran_angsuran = $this->input->post('jumlah_pembayaran',TRUE);
        $jumlah_simpanan = $this->input->post('jumlah_simpanan',true);
        $jumlah_simpanan = (isset($jumlah_simpanan) AND !empty($jumlah_simpanan)) ? floatval($jumlah_simpanan) : 0;
        $keterangan = $this->input->post('keterangan',TRUE);

        $jumlah_pembayaran_angsuran = floatval($jumlah_pembayaran_angsuran) + $jumlah_simpanan;
        $angsuran_ke = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman='.$this->db->escape($id_pinjaman).'');
        $jumlah_pembayaran_sementara = $this->function_lib->get_one('sum(jumlah_riwayat_pembayaran)','riwayat_pinjaman','id_pinjaman='.$this->security->sanitize_filename($id_pinjaman).' ORDER BY angsuran_ke DESC');
        $jumlah_pembayaran_sementara = floatval($jumlah_pembayaran_sementara)+floatval($jumlah_pembayaran_angsuran);
        $angsuran_ke = floatval($angsuran_ke+1);
        // validasi cek pinjaman aktif atau tidak, data pinjaman tidak ditemukan atau angsuran telah lunas
        $cek_id_pinjaman = $this->function_lib->get_one('id_pinjaman','pinjaman','status_pinjaman!="lunas" AND id_pinjaman='.$this->db->escape($id_pinjaman).' AND id_kolektor='.$this->db->escape($id_kolektor).'');
        // jika ada yg ambil dari simpanan, kurangi data simpanan DAN tambah riwayat simpanan
        $id_nasabah = $this->function_lib->get_one('id_nasabah','pinjaman','id_pinjaman='.$this->db->escape($cek_id_pinjaman).'');
        $id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_nasabah='.$this->db->escape($id_nasabah).' AND status_simpanan="aktif"');
        if ($jumlah_simpanan!=0) {
            $jumlah_simpanan_tersimpan = $this->function_lib->get_one('jumlah_simpanan','simpanan','id_simpanan='.$this->db->escape($id_simpanan).'');
            if ($jumlah_simpanan>floatval($jumlah_simpanan_tersimpan)) {
                return array("status"=>500,"msg"=>"Jumlah simpanan yang diambil tidak cukup");
            }
        }
        if (!empty($cek_id_pinjaman)) {
            $status = 200;
            $msg = "Pembayaran Angsuran Berhasil...";
            $columnInsert = array(
                "id_pinjaman" => $this->security->sanitize_filename($id_pinjaman),
                "angsuran_ke" => $angsuran_ke,
                "jumlah_riwayat_pembayaran" => $this->security->sanitize_filename($jumlah_pembayaran_angsuran),
                "input_oleh" => "kolektor",
                "input_oleh_id" => $id_kolektor,
                "tgl_riwayat_pinjaman" => date("Y-m-d H:i:s"),
                "keterangan_riwayat" => $this->security->sanitize_filename($keterangan)
            );
            $this->db->insert('riwayat_pinjaman', $columnInsert);        
            // insert table pinjaman
            $columnUpdate = array(
                "tgl_terakhir_angsuran" => date("Y-m-d H:i:s"),
                "angsuran_ke" => $angsuran_ke,
                "jumlah_terbayar" => $jumlah_pembayaran_sementara,
                "last_update" => date("Y-m-d H:i:s")
            );
            $this->db->where('id_pinjaman', $this->security->sanitize_filename($id_pinjaman));
            $this->db->update('pinjaman', $columnUpdate);
            
            if (!empty($jumlah_simpanan) AND floatval($jumlah_simpanan)!=0) {                
                if (!empty($id_simpanan)) {
                    $jumlah_simpanan_float = floatval($jumlah_simpanan);
                    $this->db->set('jumlah_simpanan','jumlah_simpanan-'.$jumlah_simpanan_float);
                    $this->db->where('id_simpanan', $id_simpanan);
                    $this->db->update('simpanan');

                    // tambah riwayat simpanan
                    $columnInsert = array(
                        "id_simpanan" => $id_simpanan,
                        "jumlah_riwayat_simpanan" => "-".$jumlah_simpanan_float,
                        "tipe_riwayat" => "angsuran",
                        "input_oleh" => "kolektor",
                        "input_oleh_id" => $id_kolektor,
                        "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
                        "keterangan_riwayat" => "Digunakan untuk membayar angsuran pada ID Pinjaman : ". $id_pinjaman
                    );
                    $this->db->insert('riwayat_simpanan', $columnInsert);
                }
            }
        }else{
            $status = 500;
            $msg = "Data Pinjaman tidak ditemukan atau STATUS PINJAMAN tersebut telah LUNAS";
        }
        return array("status"=>$status,"msg"=>$msg);
    }
    function get_all_id_nasabah_for_pinjaman($id_kolektor){                     
        $this->db->where('id_kolektor', $id_kolektor,TRUE);
        $this->db->where('status', "aktif");                
        $this->db->select('id_nasabah,username,nama_nasabah');
        $query = $this->db->get('nasabah');
        $data = $query->result_array();
        return $data;
    }
    function validasi_tambah_pinjaman($id_kolektor){
        $id_owner = $this->function_lib->get_id_owner($id_kolektor);
        $id_nasabah = $this->input->post('id_nasabah', TRUE);
        $jumlah_pinjaman = $this->input->post('jumlah_pinjaman', TRUE);
        $lama_angsuran = $this->input->post('lama_angsuran', TRUE);
        $periode_angsuran = $this->input->post('periode_angsuran', TRUE);
        $status=200;
        $msg="";
        // angsuran ,periode ,lama angsuran :,tgl pinjaman :,bunga :,jumlah_diterima :,biaya admin :,biaya simpanan : 
        $data = array("angsuran"=>"","periode"=>$periode_angsuran,"lama_angsuran"=>$lama_angsuran,"tgl_pinjaman"=>date("d F Y H:i"),"bunga_pinjaman"=>"","jumlah_diterima"=>"","biaya_administrasi"=>"","biaya_simpanan"=>"");
        $this->load->library('form_validation');      
        $this->form_validation->set_rules('id_nasabah', 'Nasabah', 'trim|required',
             array(
                'required'      => '%s masih kosong',                            
            )
        );    
        $this->form_validation->set_rules('jumlah_pinjaman', 'Jumlah Pinjaman', 'trim|required|numeric|min_length[1]|max_length[18]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                        
        $this->form_validation->set_rules('periode_angsuran', 'Periode Angsuran', 'trim|required',
             array(
                'required'      => '%s masih kosong',                
            )
        );                
        $this->form_validation->set_rules('lama_angsuran', 'Lama Angsuran', 'trim|required|numeric|min_length[1]|max_length[11]',
             array(
                'required'      => '%s masih kosong',                
            )
        );                
        if ($this->form_validation->run() == TRUE) {
            // cek rasio modal dan pinjaman
            $total_modal_usaha = $this->function_lib->get_one('sum(jumlah_modal_usaha)','modal_usaha','status_modal_usaha="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $total_modal_simpanan = $this->function_lib->get_one('sum(jumlah_simpanan)','simpanan','status_simpanan="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $total_modal = floatval($total_modal_usaha) + floatval($total_modal_simpanan);
            $total_pinjaman = $this->function_lib->get_one('sum(jumlah_pinjaman)','pinjaman','status_pinjaman="aktif" AND id_owner="'.$this->db->escape_str($id_owner).'"');
            $ratio_modal_pinjaman = floatval($total_pinjaman) / floatval($total_modal);
            $ratio_modal_config_owner = (float) $this->function_lib->get_one('ratio_modal','owner','id_owner='.$this->db->escape($id_owner).'');

           if (($ratio_modal_pinjaman) > floatval($ratio_modal_config_owner/100)) {
                //  jika ratio modal lebih tinggi dari pada config maka errorkan
                $status = 500;
                $msg = "Maaf saat ini modal koperasi tidak mencukupi, silahkan coba beberapa hari lagi";
                 $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_nasabah='.$this->db->escape($id_nasabah).' AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga'); 
                $biaya = $this->function_lib->get_row('owner','id_owner='.$this->db->escape($id_owner).'');
                $biaya_administrasi = isset($biaya['biaya_administrasi']) ? $biaya['biaya_administrasi'] : "";
                $data['biaya_administrasi'] = floatval($biaya_administrasi)*$jumlah_pinjaman/100;
                $biaya_simpanan = isset($biaya['biaya_simpanan']) ? $biaya['biaya_simpanan'] : "";
                $data['biaya_simpanan'] = floatval($biaya_simpanan)*$jumlah_pinjaman/100;
                $data['bunga_pinjaman'] = isset($biaya['bunga_pinjaman']) ? $biaya['bunga_pinjaman'] : "";            
                $bunga_pinjaman_uang = floatval($data['bunga_pinjaman'])*$jumlah_pinjaman/100;
                $data['jumlah_diterima'] = floatval($jumlah_pinjaman)-(floatval($data['biaya_simpanan'])+floatval($data['biaya_administrasi']));
                $jumlah_pinjaman_setelah_bunga = floatval($bunga_pinjaman_uang)+floatval($jumlah_pinjaman);
                $data['angsuran'] = floatval($jumlah_pinjaman_setelah_bunga)/floatval($lama_angsuran);

                $data['biaya_simpanan'] = "Rp. ".number_format(floatval($data['biaya_simpanan']),'2',',','.');
                $data['bunga_pinjaman'] = number_format(floatval($data['bunga_pinjaman']),'2',',','.')." %";
                $data['biaya_administrasi'] = "Rp. ".number_format(floatval($data['biaya_administrasi']),'2',',','.');
                $data['jumlah_diterima'] = "Rp. ".number_format(floatval($data['jumlah_diterima']),'2',',','.');
                $data['angsuran'] = "Rp. ".number_format(floatval($data['angsuran']),'2',',','.');
           }else{
                $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_nasabah='.$this->db->escape($id_nasabah).' AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga ORDER BY id_pinjaman DESC'); 
                $id_pinjaman_belum_lunas = $this->function_lib->get_one('id_pinjaman','pinjaman','id_nasabah='.$this->db->escape($id_nasabah).' AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga ORDER BY id_pinjaman DESC'); 
                if (floatval($jumlah_pinjaman) < $jumlah_kurang) {
                    $status = 500;
                    $msg = "Jumlah pinjaman harus lebih besar dari kekurangan pinjaman yang belum lunas. Anda mempunyai pinjaman belum lunas dengan data sbb :";
                    $jumlah_pinjaman = 
                    $pinjaman_belum_lunas = $this->function_lib->get_row('pinjaman','id_pinjaman="'.$id_pinjaman_belum_lunas.'"');
                    $data['lama_angsuran'] = isset($pinjaman_belum_lunas['lama_angsuran']) ? $pinjaman_belum_lunas['lama_angsuran'] : "";
                    $data['periode'] = isset($pinjaman_belum_lunas['periode_angsuran']) ? $pinjaman_belum_lunas['periode_angsuran'] : "";
                    $data['tgl_pinjaman'] = isset($pinjaman_belum_lunas['tgl_pinjaman']) ? date("d F Y H:i",strtotime($pinjaman_belum_lunas['tgl_pinjaman'])) : "";
                    $data['bunga_pinjaman'] = isset($pinjaman_belum_lunas['persentase_bunga']) ? $pinjaman_belum_lunas['persentase_bunga'] : "";
                    $data['angsuran'] = isset($pinjaman_belum_lunas['jumlah_perangsuran']) ? "Rp .".number_format($pinjaman_belum_lunas['jumlah_perangsuran'],2,',','.') : "";
                    $data['jumlah_diterima'] = isset($pinjaman_belum_lunas['jumlah_diterima']) ? "Rp .".number_format($pinjaman_belum_lunas['jumlah_diterima'],2,',','.') : "";
                    $data['biaya_administrasi'] = isset($pinjaman_belum_lunas['persentase_biaya_simpanan']) ? "Rp .".number_format((floatval($pinjaman_belum_lunas['persentase_biaya_simpanan'])*floatval($pinjaman_belum_lunas['jumlah_pinjaman'])/100),2,',','.') : "";
                    $data['biaya_simpanan'] = isset($pinjaman_belum_lunas['persentase_biaya_simpanan']) ? "Rp .".number_format((floatval($pinjaman_belum_lunas['persentase_biaya_simpanan'])*floatval($pinjaman_belum_lunas['jumlah_pinjaman'])/100),2,',','.') : "";
                }else{
                    $status=200;
                    $msg="Dengan menekan tombol konfirmasi, Anda telah menyetujui untuk mengajukan pinjaman kepada nasabah dengan data pinjaman sbb : ";
                     $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_nasabah='.$this->db->escape($id_nasabah).' AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga'); 
                    $biaya = $this->function_lib->get_row('owner','id_owner='.$this->db->escape($id_owner).'');
                    $biaya_administrasi = isset($biaya['biaya_administrasi']) ? $biaya['biaya_administrasi'] : "";
                    $data['biaya_administrasi'] = floatval($biaya_administrasi)*$jumlah_pinjaman/100;
                    $biaya_simpanan = isset($biaya['biaya_simpanan']) ? $biaya['biaya_simpanan'] : "";
                    $data['biaya_simpanan'] = floatval($biaya_simpanan)*$jumlah_pinjaman/100;
                    $data['bunga_pinjaman'] = isset($biaya['bunga_pinjaman']) ? $biaya['bunga_pinjaman'] : "";            
                    $bunga_pinjaman_uang = floatval($data['bunga_pinjaman'])*$jumlah_pinjaman/100;
                    $data['jumlah_diterima'] = floatval($jumlah_pinjaman)-(floatval($data['biaya_simpanan'])+floatval($data['biaya_administrasi']));
                    $jumlah_pinjaman_setelah_bunga = floatval($bunga_pinjaman_uang)+floatval($jumlah_pinjaman);
                    $data['angsuran'] = floatval($jumlah_pinjaman_setelah_bunga)/floatval($lama_angsuran);

                    $data['biaya_simpanan'] = "Rp. ".number_format(floatval($data['biaya_simpanan']),'2',',','.');
                    $data['bunga_pinjaman'] = number_format(floatval($data['bunga_pinjaman']),'2',',','.')." %";
                    $data['biaya_administrasi'] = "Rp. ".number_format(floatval($data['biaya_administrasi']),'2',',','.');
                    $data['jumlah_diterima'] = "Rp. ".number_format(floatval($data['jumlah_diterima']),'2',',','.');
                    $data['angsuran'] = "Rp. ".number_format(floatval($data['angsuran']),'2',',','.');
                }
            }

        } else {
            $status=500;
            $msg=validation_errors(' ',' ');
        }
        return array("status"=>$status,"msg"=>$msg,"data"=>$data);
    }
    public function tambah_pinjaman($id_kolektor){
        $validasi = $this->validasi_tambah_pinjaman($id_kolektor);
        $status = isset($validasi['status']) ? $validasi['status'] : 500;
        $msg = isset($validasi['msg']) ? $validasi['msg'] : "";
        if ($status == 200) {
            $tgl_sekarang = date("Y-m-d H:i:s");
            $id_owner = $this->function_lib->get_id_owner($id_kolektor);
            $id_nasabah = $this->input->post('id_nasabah', TRUE);
            $jumlah_pinjaman = $this->input->post('jumlah_pinjaman', TRUE);
            $lama_angsuran = $this->input->post('lama_angsuran', TRUE);
            $periode_angsuran = $this->input->post('periode_angsuran', TRUE);
            // ambil data pinjaman lama yg belum lunas
            $id_pinjaman = (float) $this->function_lib->get_one('id_pinjaman','pinjaman','id_nasabah="'.$id_nasabah.'" AND jumlah_terbayar<jumlah_pinjaman_setelah_bunga');  
            // ambil kekurangan angsurannya
            $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_pinjaman="'.$id_pinjaman.'"'); 
            $jumlah_diterima = (float) ($jumlah_pinjaman - $jumlah_kurang);
            $columnInsert = array(
                "id_nasabah" => $this->security->sanitize_filename($id_nasabah),
                "id_owner" => $this->security->sanitize_filename($id_owner),
                "id_kolektor" => $this->security->sanitize_filename($id_kolektor),
                "jumlah_pinjaman" => $this->security->sanitize_filename($jumlah_pinjaman),
                "lama_angsuran" => $this->security->sanitize_filename($lama_angsuran),
                "periode_angsuran" => $this->security->sanitize_filename($periode_angsuran),
                "jumlah_diterima" => $this->security->sanitize_filename($jumlah_diterima),
            );
            $insert_pinjaman = $this->db->insert('pinjaman', $columnInsert);
            $insert_id = $this->db->insert_id();
            // jika ada data pinjaman lama yg belum lunas tambah riwayat angsuran untuk melunasi pinjaman lama
            if (!empty($id_pinjaman)) {
                $jumlah_angsuran_awal = $this->function_lib->get_one('count(id_riwayat)','riwayat_pinjaman','id_pinjaman="'.$id_pinjaman.'"');
                $jumlah_angsuran = $jumlah_angsuran_awal+1;
                // tambahkan riwayat angsuran yg diambil dari potongan pinjaman baru
                $jumlah_kurang = (float) $this->function_lib->get_one('jumlah_pinjaman_setelah_bunga-jumlah_terbayar','pinjaman','id_pinjaman="'.$id_pinjaman.'"');                                    
                $columnInsert = array(
                    "id_pinjaman" => $id_pinjaman,
                    "angsuran_ke" => $jumlah_angsuran_awal+1,
                    "jumlah_riwayat_pembayaran" => $jumlah_kurang,
                    "input_oleh" => "kolektor",
                    "input_oleh_id" => $id_kolektor,
                    "tgl_riwayat_pinjaman" => $tgl_sekarang,
                    "keterangan_riwayat" => "Angsuran diambil dari potongan pinjaman baru dengan ID Pinjaman : ".$insert_id." Sejumlah : Rp.". number_format($jumlah_pinjaman,"2",",",".")
                );
                $this->db->insert('riwayat_pinjaman', $columnInsert);
                // update status dan data di pinjaman lama
                $columnUpdate = array(                
                    "status_pinjaman" => "lunas",
                    "angsuran_ke" => (int) $jumlah_angsuran,
                    "last_update" => $tgl_sekarang,       
                    "tgl_terakhir_angsuran" => $tgl_sekarang,
                );
                $this->db->set('jumlah_terbayar', 'jumlah_pinjaman_setelah_bunga', false);
                $this->db->where('id_pinjaman', $id_pinjaman);
                $this->db->update('pinjaman', $columnUpdate);  
            }
            $bunga_pinjaman = $this->function_lib->get_one('bunga_pinjaman','owner','id_owner IN (SELECT id_owner FROM pinjaman WHERE id_pinjaman ="'.$insert_id.'")');
            $id_nasabah = $this->function_lib->get_one('id_nasabah','pinjaman','id_pinjaman="'.$insert_id.'"');
            $id_owner = $this->function_lib->get_one('id_owner','pinjaman','id_pinjaman="'.$insert_id.'"');
            $id_kolektor = $this->function_lib->get_one('id_kolektor','pinjaman','id_pinjaman="'.$insert_id.'"');
            $biaya_simpanan = (float) $this->function_lib->get_one('biaya_simpanan','owner','id_owner="'.$id_owner.'"');
            $biaya_administrasi = (float) $this->function_lib->get_one('biaya_administrasi','owner','id_owner="'.$id_owner.'"');
            $this->db->query('UPDATE `pinjaman` SET 
                `jumlah_pinjaman_setelah_bunga` = ((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`, 
                `persentase_bunga` = "'.$bunga_pinjaman.'",
                `jumlah_perangsuran` = (((`jumlah_pinjaman`*'.$bunga_pinjaman.')/100)+`jumlah_pinjaman`)/`lama_angsuran`,
                `tgl_pinjaman` = "'.date("Y-m-d H:i:s").'",
                `persentase_biaya_simpanan` = "'.$biaya_simpanan.'",
                `persentase_biaya_admin` = "'.$biaya_administrasi.'",
                `input_oleh` = "kolektor",
                `input_oleh_id` = "'.$id_kolektor.'",
                `last_update` = "'.date("Y-m-d H:i:s").'" WHERE `id_pinjaman` = '.$insert_id.'
                ');        
            // update simpanan milik nasabah
            // cek apakah nasabah punya data simpanan aktif        
            $cek_id_simpanan = $this->function_lib->get_one('id_simpanan','simpanan','id_nasabah="'.$id_nasabah.'"');
                    
             $jumlah_pinjaman = $this->function_lib->get_one('jumlah_pinjaman','pinjaman','id_pinjaman="'.$insert_id.'"');
            $jumlah_pinjaman = floatval($jumlah_pinjaman);
            $jumlah_simpanan_tambahan = (float) ($jumlah_pinjaman*$biaya_simpanan)/100;

            if (!empty($cek_id_simpanan)) {
                // jika nasabah ada data simpanan, maka update jumlahnya ditambah dengan total pinjaman* bunga simpanan            
               $this->db->query('UPDATE `simpanan` SET 
                `jumlah_simpanan` = `jumlah_simpanan`+'.$jumlah_simpanan_tambahan.',
                `last_update` = "'.date("Y-m-d H:i:s").'" ,
                `update_oleh_id` = "kolektor",
                `update_oleh` = "'.$id_kolektor.'"
                WHERE id_simpanan = '.$cek_id_simpanan.'
                ');   
               // insert log riwayat simpanan dengan log type biaya_simpanan
               $columnInsertLog = array(
                "id_simpanan" => $cek_id_simpanan,
                "jumlah_riwayat_simpanan" => $jumlah_simpanan_tambahan,
                "tipe_riwayat" => "biaya_pinjaman",
                "input_oleh" => "kolektor",
                "input_oleh_id" => $id_kolektor,
                "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
                "keterangan_riwayat" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
               );
               $this->db->insert('riwayat_simpanan', $columnInsertLog);
            }else{
                // jika nasabah belum ada data simpanan, maka insert data simpanan, dan insert riwayat simpanan
                $columnInsert = array(
                    "id_nasabah" => $id_nasabah,
                    "id_owner" => $id_owner,
                    "id_kolektor" => $id_kolektor,
                    "jumlah_simpanan" => $jumlah_simpanan_tambahan,
                    "tgl_simpanan" => date("Y-m-d H:i:s"),                
                    "status_simpanan" => "aktif",                
                    "input_oleh" => "kolektor",                
                    "input_oleh_id" => $id_kolektor,                
                    "note" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
                );
                $this->db->insert('simpanan', $columnInsert);
                $id_simpanan = $this->db->insert_id();
                $columnInsertLog = array(
                    "id_simpanan" => $id_simpanan,
                    "jumlah_riwayat_simpanan" => $jumlah_simpanan_tambahan,
                    "tipe_riwayat" => "biaya_pinjaman",
                    "input_oleh" => "kolektor",
                    "input_oleh_id" => $id_kolektor,
                    "tgl_riwayat_simpanan" => date("Y-m-d H:i:s"),
                    "keterangan_riwayat" => "Biaya pinjaman dari koperasi sejumlah ".$jumlah_pinjaman
                );
                $this->db->insert('riwayat_simpanan', $columnInsertLog);

            }  
            $msg = "Pinjaman baru berhasil masuk";

        }
        return array("status"=>$status,"msg"=>$msg);      
    }

}

/* End of file Mpinjaman.php */
/* Location: ./application/models/Mpinjaman.php */