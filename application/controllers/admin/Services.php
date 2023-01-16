<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function logout()
    {
        $cookie = array('name' => 'auth', 'value'  => '', 'expire' => '');
        $this->input->set_cookie($cookie);
        redirect(BASE_URL . "admin/login");
    }

    function forgot_password()
    {
        if (isset($this->param['email'])) {
            $email = $this->param['email'];
            $isAdmin = $this->mylib->get_global_settings(array("admin_email"));
            $userDetail = array("email" => $this->param['email']);
            $iSent = $this->mylib->send_forgot_password_email("admin", $userDetail);
            if ($iSent) {
                $this->session->set_flashdata('info_message', "A link to reset the password will be sent to " . $email);
            } else {
                $this->session->set_flashdata('error_message', "An error occured, Please, try again.");
            }
        } else {
            $this->session->set_flashdata('error_message', "Please enter valid email id.");
        }
        redirect("/admin/login");
    }
}
