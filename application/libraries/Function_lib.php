<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Function_lib {

    var $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->database();
    }
    
    public function findAll($where=1,$table='',$orderBy='')
    {
        $sql='SELECT * FROM '.$table.'
              WHERE '.$where.' 
              ';
        if(trim($orderBy)!='')
        {
            $sql.=' ORDER BY '.$orderBy;
        }

        $exec=$this->CI->db->query($sql);
        return $exec->result_array();
    }
    public function findAllCustom($where=1,$table='',$select='*',$orderBy='')
    {
        $sql='SELECT '.$select.' FROM '.$table.'
              WHERE '.$where.' 
              ';
        if(trim($orderBy)!='')
        {
            $sql.=' ORDER BY '.$orderBy;
        }

        $exec=$this->CI->db->query($sql);
        return $exec->result_array();
    }
 
    function db_query_execution($params, $count = false) {
        extract($this->db_query_params($params, $count));
        $offset=($count==false)?$offset:'';
        $sql = "
            SELECT $parent_select  
            FROM 
            (
                SELECT $select 
                FROM $table 
                $join 
                $where_detail 
                $group_by_detail
            ) result 
            $where 
            $group_by 
            $sort
            $limit
            $offset
        ";
        // echo $sql;
        //exit;
        $query = $this->CI->db->query($sql);
        
        if($count) {
            $row = $query->row();
            return isset($row->row_count)?$row->row_count:0;
        } else {
            return $query;
        }
    }
    /**
     * db_query_params
     * parameter query
     * @param array $params
     * @param bool $count
     */
    function db_query_params($params, $count = false) {
        $arr_condition = array();
        
        $arr_condition['parent_select'] = "*";
        if(isset($params['parent_select'])) {
            $arr_condition['parent_select'] = $params['parent_select'];
        }
        if($count) {
            $arr_condition['parent_select'] = "COUNT(*) AS row_count";
        }
        
        $arr_condition['table'] = "";
        if(isset($params['table'])) {
            $arr_condition['table'] = $params['table'];
        }
        
        $arr_condition['select'] = "*";
        if(isset($params['select'])) {
            $arr_condition['select'] = $params['select'];
        }
        
        $arr_condition['join'] = "";
        if(isset($params['join'])) {
            $arr_condition['join'] = $params['join'];
        }
        
        $arr_condition['where_detail'] = " WHERE 1 ";
        if(isset ($params['where_detail'])) {
            $arr_condition['where_detail'] .= "AND " . $params['where_detail'];
        }
        
        $arr_condition['group_by_detail'] = "";
        if(isset ($params['group_by_detail'])) {
            $arr_condition['group_by_detail'] = "GROUP BY " . $params['group_by_detail'];
        }
        $arr_condition['limit'] = "";
        if(isset ($params['limit'])) {
            $arr_condition['limit'] = "LIMIT " . $params['limit'];
        }
        $arr_condition['offset'] = "";
        if(isset ($params['offset'])) {
            $arr_condition['offset'] = "OFFSET " . $params['offset'];
        }
        
        $arr_condition['where'] = " WHERE 1 ";
        if(isset($params['query']) && $params['query'] != false && $params['query'] != '') {
            $arr_condition['where'] .= "AND " . $params['qtype'] . " LIKE '%" . mysql_real_escape_string($params['query']) . "%' ";
        } elseif(isset($params['optionused']) && $params['optionused'] == 'true') {
            $arr_condition['where'] .= "AND " . $params['qtype'] . " = '" . $params['option'] . "' ";
        } elseif((isset($params['date_start']) && $params['date_start'] != false) && (isset($params['date_end'])) && $params['date_end'] != false) {
            $arr_condition['where'] .= "AND DATE(" . $params['qtype'] . ") BETWEEN '" . mysql_real_escape_string($params['date_start']) . "' AND '" . mysql_real_escape_string($params['date_end']) . "' ";
        } elseif((isset($params['num_start']) && $params['num_start'] != false) && (isset($params['num_end'])) && $params['num_end'] != false) {
            $arr_condition['where'] .= "AND " . $params['qtype'] . " BETWEEN '" . mysql_real_escape_string($params['num_start']) . "' AND '" . mysql_real_escape_string($params['num_end']) . "' ";
        }
        
        if(isset($params['where'])) {
            $arr_condition['where'] .= "AND " . $params['where'];
        }
        
        $arr_condition['group_by'] = "";
        if(isset ($params['group_by'])) {
            $arr_condition['group_by'] = "GROUP BY " . $params['group_by'];
        }
        
        $arr_condition['sort'] = "";
        if(isset($params['order_by']) && $count == false) {
            $arr_condition['sort'] = "ORDER BY " . $params['order_by'];
        } elseif(isset($params['sortname']) && isset($params['sortorder']) && $count == false) {
            $arr_condition['sort'] = "ORDER BY " . $params['sortname'] . " " . $params['sortorder'];
        }
        //$arr_condition['limit'] = "";
        if(isset($params['perPage']) && ($params['perPage']>=0) && $count == false) {
            //$offset = (($params['start']) * $params['length']);
            $offset = ($params['page']*$params['perPage']);
            $arr_condition['limit'] = "LIMIT $offset, " . $params['perPage'];
        }
        
        return $arr_condition;
    }
    
    /**
     * fungsi standar untuk get 1 value
     * @param string $field nama kolom
     * @param string $table nama table
     * @param string $where parameter where
     */
    function get_one($field='',$table='',$where='')
    {
        $result = $this->CI->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where." LIMIT 1");
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row[$field];
        }
        else return "";
    }

    /**
     * fungsi standar untuk get 1 baris data     
     * @param string $table nama table
     * @param string $where parameter where
     */
    function get_row($table='',$where='')
    {
        $result = $this->CI->db->query("SELECT * FROM ".$table." WHERE ".$where." LIMIT 1");
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row;
        }
        else return null;
    }  
    /**
     * fungsi standar untuk get 1 baris data     
     * @param string $table nama table
     * @param string $where parameter where
     */
    function get_row_select_by($select = '*' ,$table='',$where='')
    {
        $result = $this->CI->db->query("SELECT ".$select." FROM ".$table." WHERE ".$where." LIMIT 1");
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row;
        }
        else return null;
    }  

    

    function random_num($size) {
    $alpha_key = '';
    $keys = range('A', 'Z');
    
    for ($i = 0; $i < 2; $i++) {
        $alpha_key .= $keys[array_rand($keys)];
    }
    
    $length = $size - 2;
    
    $key = '';
    $keys = range(0, 9);
    
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    
    return $alpha_key . $key;
}
    /**
     * fungsi standar untuk get 1 baris data     
     * @param string $table nama table
     * @param string $where parameter where
     */
    function get_one_multi($field=array(),$table='',$where='')
    {
      $kolom=" ";
      foreach ($field as $key => $value) {        
        if ($key<count($field)-1) {          
        $kolom.= "".$value."".',';
        }else{          
        $kolom.= "".$value."";
        }
        
      }
        $result = $this->CI->db->query("SELECT ".$kolom." FROM ".$table." WHERE ".$where." LIMIT 1");
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row;
        }
        else return "";
    }    

    /**
     * fungsi standar untuk get 1 baris
     * @param string $field
     * @param string $table
     * @param string $where
     * @param string $orderby
     */
    function get_one_by($field='',$table='',$where='', $orderby='')
    {
        $result = $this->CI->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where." ORDER BY ".$orderby." LIMIT 1");
   
        if($result->num_rows() > 0)
        {
            $row = $result->row_array();
            return $row;
        }
        else return "";
    }

    /**
    * dapatkan tanggal maintenance
    * jika offline maka ambil dari json, sebaliknya dari config
    * get profile_id, service_id, online_prefix, maintenance_date
    */
    public function get_fungsional_app()
    {
        $response=array();
        $response['profile_id']=$this->get_config_value('owner_profile_id');
        $response['service_id']=$this->get_config_value('owner_service_id');
        $response['online_prefix']=$this->get_config_value('online_prefix');
        $response['online_prefix_center']=$this->get_config_value('online_prefix_center');
        $response['maintenance_date']=$this->get_config_value('maintenance_date');
        $response['package']=$this->get_config_value('package');
        $response['services_branch_name']=$this->get_config_value('services_branch_name');
        $response['license']=$this->get_config_value('license');
        $response['activation_expired']=$this->get_config_value('activation_expired');
        $response['branch_code']=$this->get_config_value('branch_code');
        $response['app_name']=$this->get_config_value('config_name');
        return $response;
    }


    /**
     * fungsi standar untuk get 1 baris
     * @param string $field
     * @param string $table
     * @param string $where
     * @param string $orderby
     */
    function get_all($field='',$table='',$where='', $orderby='')
    {
        $result = $this->CI->db->query("SELECT ".$field." FROM ".$table." WHERE ".$where." ORDER BY ".$orderby."");
   
        if($result->num_rows() > 0)
        {
            $row = $result->result_array();
            return $row;
        }
        else return "";
    }

    public static function getLeftMenu(){
        $lib=new function_lib;
        if (!empty($lib->CI->session->userdata('super_admin'))) {
            $viewMenu = $lib->CI->load->view('super_admin/left_bar', null, FALSE);
        }else if (!empty($lib->CI->session->userdata('admin'))) {
            $viewMenu = $lib->CI->load->view('admin/left_bar', null, FALSE);        
        }else if (!empty($lib->CI->session->userdata('koordinator'))) {
            $viewMenu = $lib->CI->load->view('koordinator/left_bar', null, FALSE);        
        }else if (!empty($lib->CI->session->userdata('pengurus_barang'))) {
            $viewMenu = $lib->CI->load->view('pengurus_barang/left_bar', null, FALSE);        
        }
        return $viewMenu;
    }
    public static function getHeader(){
        $lib=new function_lib;
        if (!empty($lib->CI->session->userdata('super_admin'))) {
            $viewMenu = $lib->CI->load->view('super_admin/header', null, FALSE);
        }else if (!empty($lib->CI->session->userdata('admin'))) {
            $viewMenu = $lib->CI->load->view('admin/header', null, FALSE);        
        }else if (!empty($lib->CI->session->userdata('koordinator'))) {
            $viewMenu = $lib->CI->load->view('koordinator/header', null, FALSE);        
        }else if (!empty($lib->CI->session->userdata('pengurus_barang'))) {
            $viewMenu = $lib->CI->load->view('pengurus_barang/header', null, FALSE);        
        }
        return $viewMenu;
    }
    /*jumlah hari yg digunakan untuk perhitungan jatuh tempo di table owner.tgl_jatuh_tempo_pembayaran_sistem*/
    public function periode_jatuh_tempo_pendaftaran(){
        // $hari = 90;
        $hari = $this->get_config_value("masa_jatuh_tempo_pembayaran_sistem");
        return $hari;
    }
    /*level user : super_admin, admin, owner, kasir,kolektor, user*/
    public function cek_auth($allowed = array()){     
        foreach ($allowed as $key => $value) {
            if (!empty($this->CI->session->userdata($value))) {            
                return false;
            }
        }
        redirect(base_url().(isset($allowed[0]) ? $allowed[0] : "owner").'/login?status=500&msg='.base64_encode("Fitur dibatasi untuk pengguna ini"));
        exit();
        
    }
    
    /**
     * fungsi get insert id
     * dari penyimpanan database
     */
    function insert_id()
    {
        $query = $this->CI->db->query('SELECT LAST_INSERT_ID()');
        $row = $query->row_array();
        return $row['LAST_INSERT_ID()'];
    }

    function random_alphanumeric($jumlah=17){
      return substr(sha1(rand()), 0, $jumlah);
    }

    public static function convert_month_name($monthNumber,$lang="id_ID"){
      setlocale (LC_TIME, $lang);                  
      $monthName = strftime('%B', mktime(0, 0, 0, $monthNumber));
      return $monthName;
    }
    public static function convert_date($date,$format="H:i d M Y",$lang="id_ID"){
      setlocale (LC_TIME, $lang);                  
      $tanggal=date($format,strtotime($date));
      return $tanggal;
    }
    public static function response_notif($status,$message)
    {      
        if ($status==200) {            
            $html='<div class="alert alert-success">'.base64_decode($message).'</div>';
        }else{            
            $html='<div class="alert alert-danger">'.base64_decode($message).'</div>';
        }
      return $html;
    }
    /**
    dapatkan value config
    @param string $config_index
    */
    public static function get_config_value($config_index)
    {
        $lib=new function_lib;
        $value=$lib->get_one('configuration_value','site_configuration','configuration_index="'.$config_index.'"');
        return ($value!='')?$value:'';
    }
    public function get_user_level(){
        $user = array();
        $sess = array();
        
        if (!empty($this->CI->session->userdata('super_admin'))) {
            $sess = $this->CI->session->userdata('super_admin');
            $user = array(
                "level"=>"super_admin",
                "username"=>isset($sess['username']) ? $sess['username'] : "",
                "id_user"=>isset($sess['id_super_admin']) ? $sess['id_super_admin'] : "",
            );
        }else if (!empty($this->CI->session->userdata('admin'))) {
            $sess = $this->CI->session->userdata('admin');            
            $user = array(
                "level"=>"admin",
                "username"=>isset($sess['username']) ? $sess['username'] : "",
                "id_user"=>isset($sess['id_admin']) ? $sess['id_admin'] : "",
            );
        }else if (!empty($this->CI->session->userdata('koordinator'))) {
            $sess = $this->CI->session->userdata('koordinator');            
            $user = array(
                "level"=>"koordinator",
                "username"=>isset($sess['username_koordinator']) ? $sess['username_koordinator'] : "",
                "id_user"=>isset($sess['id_koordinator']) ? $sess['id_koordinator'] : "",
            );
        }else if (!empty($this->CI->session->userdata('pengurus_barang'))) {
            $sess = $this->CI->session->userdata('pengurus_barang');            
            $user = array(
                "level"=>"pengurus_barang",
                "username"=>isset($sess['username_pengurus_barang']) ? $sess['username_pengurus_barang'] : "",
                "id_user"=>isset($sess['id_pengurus_barang']) ? $sess['id_pengurus_barang'] : "",
            );
        }
        return $user;
    }
     function remove_string_and_substr2($string){
        return substr(preg_replace('/[^0-9]/', '', $string), 0, -2);
    }
    // remove except alphanumeric and comma
    function remove_forbid_chars($string){
        return preg_replace('/[^ 0-9,a-zA-Z]/', '', $string);
    }

}