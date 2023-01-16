<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }
    
    function index() { 
        if($this->is_admin_login()) 
            redirect("/admin/welcome/dashboard");
        if(empty($this->param)) {
            $iData = array(
                'info_message' => $this->session->flashdata("info_message"),
                'error_message' => $this->session->flashdata("error_message"),
            );
            $this->load->view("admin/login", $iData);
        } else {
            $isValid = $this->validate->login_admin($this->param);
            if($isValid['statuscode'] != 1) {
                $this->mylib->create_activity("login_try","admin");
                $this->session->set_flashdata('error_message', $isValid['msg']);
                redirect("/admin/login");
            }
            $iSettings = $this->mylib->get_global_settings(array("admin_email","admin_password"));
            if($iSettings['admin_email'] == $this->param['email'] && $iSettings['admin_password'] == md5($this->param['password'])) {
               $isValid = success_res("login");
            } else {
                $isValid = error_res("wrong_email_password");
                $this->mylib->create_activity("login_try","admin");
                $this->session->set_flashdata('error_message', $isValid['msg']);
                redirect("/admin/login");
            }
            $cookie = array('name' => 'auth', 'value' => '{"user_type" : "admin"}', 'expire' => ADMIN_LOGIN_SESSION_TIME);
            $this->input->set_cookie($cookie); 
            redirect("/admin/welcome/dashboard");
        }
    }
}