<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pengurus_barang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('grocery_CRUD','ajax_grocery_crud'));   
        
    }
    public function login()
    {
        $this->load->model('Mpengurus_barang');
        if (!empty($this->session->userdata('pengurus_barang'))) {
            redirect(base_url('pengurus_barang/dashboard'));
        }
        $this->load->view('pengurus_barang/login');
        if ($this->input->post()) {         
            $response=$this->Mpengurus_barang->cekLogin();          
            if ($response['status']==200) {
                redirect(base_url('pengurus_barang/dashboard'));
            }else{
                redirect(base_url().'pengurus_barang/login?status='.$response['status'].'&msg='.base64_encode($response['msg']).'');
            }
        }
    }
    public function lupass(){
        header("Content-type: Application/json");
        $this->load->model('Mpengurus_barang');
        $status = 500;
        $msg = "";
        if (!empty($this->session->userdata('pengurus_barang'))) {
            $status = 500;
            $msg = "Anda sudah login";
        }       
        if (!empty($this->input->post('email'))) {          
            $lupass=$this->Mpengurus_barang->lupass();          
            $status = isset($lupass['status']) ? $lupass['status'] : 500;
            $msg = isset($lupass['msg']) ? $lupass['msg'] : "";         
        }
        $response = array(
            "status" => $status,
            "msg" => $msg,
        );
        echo(json_encode($response));
    }
    public function logout(){
        $this->session->sess_destroy('pengurus_barang');
        redirect(base_url('pengurus_barang/login'));
    }
    public function profil(){
        $this->load->model('Mpengurus_barang');
        if (empty($this->session->userdata('pengurus_barang'))) {
            redirect(base_url('pengurus_barang/login'));
            exit();
        }
        $idpengurus_barang = $this->session->userdata('pengurus_barang')['id_pengurus_barang'];                
        if ($this->input->post('edit')) {
            $response = $this->Mpengurus_barang->editProfil();
            if (!empty($response)) {
                $status = $response['status'];
                $msg = $response['msg'];
                redirect('user/pengurus_barang/profil?status='.$status.'&msg='.base64_encode($msg).'');
            }else{
                redirect('user/pengurus_barang/profil');
            }
        }else if($this->input->post('change_password')){
            $response = $this->Mpengurus_barang->changePassword($idpengurus_barang);
            if (!empty($response)) {
                $status = $response['status'];
                $msg = $response['msg'];
                redirect('user/pengurus_barang/profil?status='.$status.'&msg='.base64_encode($msg).'');
            }else{
                redirect('user/pengurus_barang/profil');
            }
        }
        $data['profil'] = $this->function_lib->get_row('pengurus_barang','id_pengurus_barang="'.$idpengurus_barang.'"');
        $this->load->view('user/pengurus_barang/profil',$data,FALSE);
    }
    /*report data pengurus_barang, hanya boleh diakses {super} pengurus_barang*/
    public function getData(){
        if (empty($this->session->userdata('pengurus_barang'))) {
            redirect('pengurus_barang/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh pengurus_barang"));
        }
        $this->load->model('Mpengurus_barang');
        $data = $this->Mpengurus_barang->getData();
        $query = $data['query'];
        $total = $data['total'];
        header("Content-type: application/json");
        $_POST['rp'] = isset($_POST['rp'])?$_POST['rp']:20;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $json_data = array('page' => $page, 'total' => $total, 'rows' => array());
        $prev_trx = '';
        $no = 0 + ($_POST['rp'] * ($page - 1));
        foreach ($query->result() as $row) {

            foreach($row AS $variable=>$value)
            {
                ${$variable}=$value;
            }
            $no++;

            $actions='<a class="btn btn-xs btn-primary" href="'.base_url().'user/pengurus_barang/edit/'.$id_pengurus_barang.'" title="Edit"><i class="fa fa-pencil"></i></a>'.' '.'<button class="btn btn-xs btn-danger" onclick="delete_pengurus_barang('.$id_pengurus_barang.');return false;" title="Hapus"><i class="fa fa-trash"></i></button>';                        

            $entry = array('id' => $id_pengurus_barang,
                'cell' => array(
                    'actions' =>  $actions,
                    'no' =>  $no,                    
                    'username' =>(trim($username)!="")?$username:"",                    
                    'email' =>(trim($email)!="")?$email:"",                                        
                    'status' =>(trim($status)!="")?$status:"",                                                            
                ),
            );
            $json_data['rows'][] = $entry;
        }
        echo json_encode($json_data);
    }
    public function index() {
        $this->function_lib->cek_auth(array("super_admin","admin"));
        $crud = new Ajax_grocery_CRUD();
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";

        $crud->set_theme('adminlte');
        $crud->set_table('pengurus_barang');
        $crud->set_subject('Data Pengurus Barang');
        $crud->set_language('indonesian');
        $crud->set_relation('id_opd_pengurus_barang','opd','label_opd');
        $crud->columns('id_opd_pengurus_barang', 'Ubah Password','username_pengurus_barang','email_pengurus_barang','nama_pengurus_barang','no_hp_pengurus_barang','alamat_pengurus_barang','status_pengurus_barang');                 
        
        $crud->order_by('id_pengurus_barang','DESC');
        $action = $this->uri->segment(4,0);
        
        $crud->display_as('id_opd_pengurus_barang','OPD')
             ->display_as('nama_pengurus_barang','Nama')
             ->display_as('username_pengurus_barang','Username')
             ->display_as('email_pengurus_barang','Email')
             ->display_as('no_hp_pengurus_barang','No HP')             
             ->display_as('alamat_pengurus_barang','Alamat')             
             ->display_as('status_pengurus_barang','STATUS') ;                                      

        $crud->unset_texteditor(array('alamat_pengurus_barang','full_text'));
        $crud->change_field_type('password_pengurus_barang', 'password');
        $crud->unique_fields(['username_pengurus_barang','email_pengurus_barang']);        

        $crud->callback_column('Ubah Password', array($this, 'link_ubah_password'));        
        $crud->required_fields('id_opd_pengurus_barang','nama_pengurus_barang','username_pengurus_barang','password_pengurus_barang','email_pengurus_barang','status_pengurus_barang');
        $crud->callback_after_insert(array($this, 'cpass'));
        $crud->unset_edit_fields('password_pengurus_barang');
        $crud->unset_add_fields('status_pengurus_barang');
        $data = $crud->render();

        
 
        $this->load->view('user/pengurus_barang/index', $data, FALSE);
    }
    public function link_ubah_password($value, $row){
        $this->function_lib->cek_auth(array("super_admin","admin"));
        return '<a href="'.base_url("user/pengurus_barang/ubah_password/".$row->id_pengurus_barang).'" class="btn btn-info btn-sm"><i class="fa fa-key"></i></a>';
    }
    public function ubah_password($id_pengurus_barang){
        $this->function_lib->cek_auth(array("super_admin","admin"));
        $user_sess = $this->function_lib->get_user_level();
        $level = isset($user_sess['level']) ? $user_sess['level'] : "";
        $id_user = isset($user_sess['id_user']) ? $user_sess['id_user'] : "";
        $id_pengurus_barang = $this->function_lib->get_one('id_pengurus_barang','pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');
        if (empty($id_pengurus_barang) AND ($level!="owner" OR $level!="kasir")) {
            redirect(base_url().'user/pengurus_barang/index/');
            exit();
        }else{
            $data['id_pengurus_barang'] = $id_pengurus_barang;
            $this->load->view('user/pengurus_barang/ubah_password', $data, FALSE);
        }
        
    }
    public function cpass($post_array,$primary_key){
        $this->function_lib->cek_auth(array("super_admin","admin"));
        $hash = hash('sha512',$post_array['password_pengurus_barang'] . config_item('encryption_key'));
        $this->db->set("password_pengurus_barang",$hash);
        $this->db->where('id_pengurus_barang', $primary_key);
        $this->db->update('pengurus_barang');
     
        return true;
    }
    public function change_password($id_pengurus_barang){
        $this->function_lib->cek_auth(array('admin','super_admin'));
        if($this->input->post('change_password')){
            $this->load->model('Mpengurus_barang');
            $validasiChangePassword = $this->Mpengurus_barang->changePassword($id_pengurus_barang); 
            header('Content-Type: application/json');                       
            $status = isset($validasiChangePassword['status']) ? $validasiChangePassword['status'] : 500;
            $msg = isset($validasiChangePassword['msg']) ? $validasiChangePassword['msg'] : 500;
            $error = isset($validasiChangePassword['error']) ? $validasiChangePassword['error'] : array();
            echo json_encode(array("status"=>$status,"msg"=>$msg,"error"=>$error));
        }
    }
    
    public function delete($id_pengurus_barang){
        $this->function_lib->cek_auth(array('super_admin'));
        $this->load->model('Mpengurus_barang');
        $status = 500;
        $msg = "Gagal";
        if (empty($this->session->userdata('pengurus_barang'))) {
            echo json_encode(array("status"=>$status,"msg"=>"Akses ditolak"));
        }
        header("Content-type:application/json");
        $cek = $this->function_lib->get_one('id_pengurus_barang','pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');
        if (trim($cek)!="") {       
            $response = $this->Mpengurus_barang->delete($id_pengurus_barang);
            $status = $response['status'];
            $msg = $response['msg'];
        }else{
            $status = 500;
            $msg = "Data tidak ditemukan";
        }

        echo json_encode(array("status"=>$status,"msg"=>$msg));
    }
    public function edit($id_pengurus_barang){
        if (empty($this->session->userdata('pengurus_barang'))) {
            redirect('pengurus_barang/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh pengurus_barang"));
        }
        $this->load->model('Mpengurus_barang');
        if ($this->input->post('edit')) {
            $cek = $this->function_lib->get_one('id_pengurus_barang','pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');
            if (trim($cek)!="") {       
                $response = $this->Mpengurus_barang->edit($id_pengurus_barang);
                $status = $response['status'];
                $msg = $response['msg'];
                
            }else{
                $status = 500;
                $msg = "Data tidak ditemukan";
            }       
            redirect(base_url().'user/pengurus_barang?status='.$status.'&msg='.base64_encode($msg));
        }
        $data['pengurus_barang'] = $this->function_lib->get_row('pengurus_barang','id_pengurus_barang="'.$id_pengurus_barang.'"');
        $this->load->view('user/pengurus_barang/edit', $data, FALSE);
    }
    public function tambah(){
        if (empty($this->session->userdata('pengurus_barang'))) {
            redirect('pengurus_barang/login?status=500?msg='.base64_encode("fitur hanya bisa diakses oleh pengurus_barang"));
        }
        $this->load->model('Mpengurus_barang');
        if ($this->input->post('tambah')) {
            $validasi = $this->Mpengurus_barang->validasi();
            if (trim($validasi['status'])==200) {       
                $response = $this->Mpengurus_barang->tambah();
                $status = $response['status'];
                $msg = $response['msg'];
                redirect(base_url().'user/pengurus_barang?status='.$status.'&msg='.base64_encode($msg));
            }else{
                $status = 500;
                $msg = $validasi['msg'];
                redirect(base_url().'user/pengurus_barang/tambah?status='.$status.'&msg='.base64_encode($msg));
            }       
        }       
        $data=array();
        $this->load->view('user/pengurus_barang/tambah', $data, FALSE);
    }
    
}

/* End of file pengurus_barang.php */
/* Location: ./application/controllers/user/pengurus_barang.php */