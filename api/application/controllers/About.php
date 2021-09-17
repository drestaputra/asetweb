<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

// use namespace
use Restserver\Libraries\REST_Controller;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
class About extends REST_Controller {
	
	function __construct($config = 'rest') {
        parent::__construct($config);        
        $this->load->database();
        $this->load->model('Mabout');        
    }
    public function index_get()
    {         
     	$data=$this->Mabout->get_about();     	
     	$this->response($data);     
    }
}

/* End of file About.php */
/* Location: ./application/controllers/android/About.php */