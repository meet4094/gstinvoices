<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services extends MY_Controller {

    function __construct() {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit("<h1 align='center'>Access Denied!</h1>");
    }

    function index() {
        _e("<h1 align='center'>Access Denied!</h1>");
    }

    function user($action, $token = array()) {
        $this->load->model("model_user");
        switch ($action) {
            case "activate":
                $this->model_user->displayAccountActivated($token);
                break;
            case "fpassword":
                $this->model_user->displayResetPassword($token);
                break;
            case "changeemail":
                $this->model_user->displayChangeEmail($token);
                break;
            default :
                break;
        }
    }
    
    function admin($action, $token = array()) {
        switch ($action) {
            case "fpassword":
                $iValid = $this->validate_forgot_password_token($token);
                $iStatus = error_res("invalid_page");
                if ($iValid) {
                    $iStatus = success_res("valid_page");
                    $iStatus['info_message'] = $this->session->flashdata("info_message");
                    $iStatus['error_message'] = $this->session->flashdata("error_message");
                    $iStatus['token'] = $token;
                }
                $this->load->view('admin/reset_password', $iStatus);
                break;
            case "reset_password":
                $iSettings = array("admin_forgotpassword_token" => random_str(20), "admin_password" => md5($this->param['new_password']));
                $this->mylib->save_global_setting($iSettings);
                $iRes = success_res('password_changed');
                $this->session->set_flashdata("info_message", $iRes['msg']);
                redirect("/admin/login");
                break;
            default :
                break;
        }
    }

    function validate_forgot_password_token($token) {
        $dbToken = $this->mylib->get_global_settings(array("admin_forgotpassword_token"));
        if ($dbToken['admin_forgotpassword_token'] == $token)
            return TRUE;
        return FALSE;
    }
    
}
