<?php

class Model_token extends CI_Model
{

    public $by_pass_auth_url = array(
        'api\/token\/getRequestToken',
        'api\/user\/getSettings',
        'api\/user\/signUp',
        'api\/user\/logIn',
        'api\/user\/verifyOtp',
        'api\/user\/resendOtp',
    );

    public $do_not_check_list = array(
        'api\/token\/getRequestToken',
        'api\/user\/getSettings',
        'api\/user\/resetPassword',
    );

    public function __construct()
    {
        parent::__construct();
    }

    function check_request_token($rtoken = "")
    {
        if ($rtoken == "") {
            return FALSE;
        } else {
            $latest_token = $this->getRequestToken();
            if ($latest_token == $rtoken)
                return TRUE;
        }
        return FALSE;
    }

    function getRequestToken()
    {
        return $this->settings["request_token"];
    }

    function check_auth_token($auth_token)
    {
        if ($auth_token == "")
            return FALSE;
        if (!$this->is_valid_auth($auth_token))
            return FALSE;
        return TRUE;
    }

    function is_valid_auth($auth_token)
    {
        $this->db->limit(1, 0);
        $res = $this->db->get_where("sh_users_device", array('auth_token' => $this->db->escape_str($auth_token), 'is_del' => '0'));
        if ($res->num_rows() == 0)
        return FALSE;
        $device_detail = (array) $res->row();
        
        $qry = $this->db->get_where("sh_users", array('uid' => $device_detail['user_id'], 'is_del' => '0'));
        $user_detail = (array) $qry->row();
        $meta_qry = $this->db->get_where("sh_users_meta", array('user_id' => $user_detail['uid'], "autoload" => 1));
        $meta = array();
        foreach ($meta_qry->result_array() as $row) {
            $meta[$row['user_meta_key']] = $row['user_meta_value'];
        }
        $user_detail = array_merge($meta, $user_detail);
        $this->config->set_item("user_detail", $user_detail);
        $this->config->set_item("is_login", true);
        return $qry->num_rows() == 0 ? FALSE : TRUE;
    }

    function request_token_require($url)
    {
        for ($i = 0; $i < count($this->do_not_check_list); $i++) {
            if (preg_match("[" . $this->do_not_check_list[$i] . "]", $url))
                return FALSE;
        }
        for ($i = 0; $i < count($this->by_pass_auth_url); $i++) {
            if (preg_match("[" . $this->by_pass_auth_url[$i] . "]", $url)) {
                return TRUE;
            }
        }
        return FALSE;
    }

    function auth_token_require($url)
    {
        for ($i = 0; $i < count($this->by_pass_auth_url); $i++) {
            if (preg_match("[" . $this->by_pass_auth_url[$i] . "]", $url))
                return FALSE;
        }
        if (@preg_match("/api/", $url) != FALSE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
