<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mylib { 

    private $CI;

    public function __construct() {
        $this->CI = & get_instance();
    }

    function index() {
        _e("<h1 align='center'>Access Denied!</h1>");
    }

    function set_global_setting() {
        $this->CI->db->where(array("autoload" => "yes"));
        $iQuery = $this->CI->db->get("setting");
        foreach ($iQuery->result() as $iRaw) {
            $this->CI->settings[$iRaw->setting_name] = $iRaw->setting_value;
        }
    }

    function save_global_setting($settings = array()) {
        foreach ($settings as $key => $val) {
            $this->CI->db->where(array("setting_name" => $key));
            $this->CI->db->update("setting", array("setting_value" => $val));
            $this->CI->db->limit(1, 0);
        }
        return success_res("settings_updated");
    }

    function get_global_settings($settings = array()) {
        if (empty($settings))
            return $this->CI->settings;
        $iData = array();
        foreach ($settings as $key) {
            $iData[$key] = "";
            $this->CI->db->where(array("setting_name" => $key));
            $iQuery = $this->CI->db->get("setting", 1, 0);
            $iRaw = (array) $iQuery->row();
            if (!empty($iRaw)) {
                $iData[$key] = $iRaw['setting_value'];
            }
        }
        return $iData;
    }

    function get_all_global_settings() {
        $iData = array();
        $iQuery = $this->CI->db->get("setting");
        $allSettings = $iQuery->result_array();
        for ($i = 0; $i < count($allSettings); $i++) {
            $iData[$allSettings[$i]['setting_name']] = $allSettings[$i]['setting_value'];
        }
        return $iData;
    }

    function get_activity($activityName, $ip, $userType, $iLimit = 0, $iWhere = "") {
        if ($iWhere == "") {
            $iWhere = array('and' => array('activity_name' => $activityName, 'ip' => $ip, 'user_type' => $userType, 'is_ignore' => 0));
            return $this->CI->model_api->get("activity_log", array(), $iWhere, array(), array("on_date DESC"), 0, $iLimit);
        } else {
            $iQuery = "SELECT * FROM activity_log WHERE activity_name = '{$activityName}' AND ip = '{$ip}' AND {$iWhere} LIMIT 0, {$iLimit}";
            $iResult = $this->CI->model_api->execute_query($iQuery);
            return $iResult;
        }
    }

    function create_activity($activityName, $userType) {
        $iData = array('activity_name' => $activityName, 'ip' => ip(), 'user_type' => $userType, 'is_ignore' => 0);
        $this->CI->db->insert("activity_log", $iData);
    }

    function send_forgot_password_email($userType, $userDetail) {
        $iSent = FALSE;
        if ($userType == "user") {
            $this->CI->load->model("model_user");
            $token = $this->CI->model_user->getNewForgotPasswordToken();
            $detail = array("forgot_password_token" => $token);
            $userDetail['token'] = $token;
            $this->CI->model_user->addMeta($detail, $userDetail['uid']);
            $iBody = $this->CI->load->view('template/email/UserResetPassword', $userDetail, true);
        }
        if ($userType == "admin") {
            $token = random_str(15);
            $userDetail['token'] = $token;
            $this->save_global_setting(array("admin_forgotpassword_token" => $token));
            $iBody = $this->CI->load->view('template/email/AdminResetPassword', $userDetail, true);
        }
        $iSubject = $this->CI->lang->line("mail_forgotpassword");
        $iSent = send_email($userDetail['email'], $iSubject, $iBody);
        return $iSent;
    }
}