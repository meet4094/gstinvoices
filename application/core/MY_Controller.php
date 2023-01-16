<?php

class MY_Controller extends CI_Controller
{

    public $start_time = 0;
    public $param = array();
    public $is_login = false;
    public $is_admin = false;
    public $is_admin_url = false;

    function __construct()
    {
        parent::__construct();
        $this->start_time = microtime();
        $this->start_time = explode(' ', $this->start_time);
        $this->start_time = $this->start_time[1] + $this->start_time[0];
        $this->mylib->set_global_setting();
        if (is_array($this->input->post()))
            $this->param = $this->input->post();
        $this->validate_admin_login();
    }

    function validate_admin_login()
    {
        if (strpos($this->input->server('REDIRECT_URL'), "/admin/") !== false) {
            $this->is_admin_url = TRUE;
            if ($this->is_admin_login()) {
                $this->is_admin = TRUE;
                $this->is_login = TRUE;
                $this->update_admin_auth_cookie();
            } else {
                $bypass_url = $this->config->item("admin_login_bypass_url");
                for ($i = 0; $i < count($bypass_url); $i++) {
                    if (preg_match("[" . $bypass_url[$i] . "]", $this->input->server('REDIRECT_URL')))
                        return;
                }
                redirect("admin/login");
            }
            $this->config->set_item("ln", "en"); // by default english
        }
    }

    public function is_admin_login()
    {
        $cook = get_cookie("auth");
        if ($cook == "")
            return FALSE;
        $data = json_decode($cook, TRUE);
        if ($data['user_type'] == "admin")
            return TRUE;
        return FALSE;
    }

    public function update_admin_auth_cookie()
    {
        $cook = get_cookie("auth");
        if ($cook == "")
            return FALSE;
        $data = json_decode($cook, TRUE);
        if ($data['user_type'] == "admin") {
            $cookie = array(
                'name' => 'auth',
                'value' => json_encode($data),
                'expire' => ADMIN_LOGIN_SESSION_TIME,
            );
            $this->input->set_cookie($cookie);
        }
    }
}
