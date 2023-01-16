<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit("<h1 align='center'>Access Denied!</h1>");
    }

    function index() { 
        redirect('/admin/login', 'refresh');
    	//_e("<h1 align='center'>Access Denied!</h1>");
        // $this->load->view('welcome');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
