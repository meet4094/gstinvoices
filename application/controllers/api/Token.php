<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Token extends MY_Controller {

    function __construct() {
        if (!defined('ROUTE_ACCESS'))
            exit("<h1 align='center'>Access Denied!</h1>");
        parent::__construct();        
    }

    function index() {
        exit("<h1 align='center'>Access Denied!</h1>");
    }

    function getRequestToken() {
        $iRes = new_request_token_res("new_request_token");
        $iRes['data'] = array("token" => $this->model_token->getRequestToken());
        response($iRes);
    }
}
