<?php
if (!function_exists('error_res')) {
    function error_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "query_fail" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 0, 'msg' => $msg);
    }
}
if (!function_exists('success_res')) {
    function success_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "query_ok" : $msg;
        $msg = "success_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 1, 'msg' => $msg);
    }
}
if (!function_exists('info_res')) {
    function info_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "warning" : $msg;
        $msg = "warn_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 2, 'msg' => $msg);
    }
}
if (!function_exists('sql_error_res')) {
    function sql_error_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "sql_error" : $msg;
        $msg = "db_error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 3, 'msg' => $msg);
    }
}
if (!function_exists('parameter_error_res')) {
    function parameter_error_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "parameter_missing" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 4, 'msg' => $msg);
    }
}
if (!function_exists('no_action_res')) {
    function no_action_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "action_not_found" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 5, 'msg' => $msg);
    }
}
if (!function_exists('spamer_res')) {
    function spamer_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "too_much_attempt" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 6, 'msg' => $msg);
    }
}
if (!function_exists('req_token_missing_res')) {
    function req_token_missing_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "request_token_invalid" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 7, 'msg' => $msg);
    }
}
if (!function_exists('auth_token_missing_res')) {
    function auth_token_missing_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "auth_token_invalid" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 8, 'msg' => $msg);
    }
}
if (!function_exists('new_request_token_res')) {
    function new_request_token_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "new_request_token" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 9, 'msg' => $msg);
    }
}
if (!function_exists('captcha_error_res')) {
    function captcha_error_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "captcha" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 10, 'msg' => $msg);
    }
}
if (!function_exists('validation_error_res')) {
    function validation_error_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "validation_error" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 11, 'msg' => $msg);
    }
}
if (!function_exists('pop_up_res')) {
    function pop_up_res($msg = "", $args = array())
    {
        $CI = &get_instance();
        $msg = $msg == "" ? "email_not_verified" : $msg;
        $msg = "error_" . $msg;
        $converted = $CI->lang->line($msg);
        $msg = $converted == "" ? $msg : $converted;
        $msg = vsprintf($msg, $args);
        return array('statuscode' => 12, 'msg' => $msg);
    }
}
function _e($data)
{
    $CI = &get_instance();
    $CI->output->_display();
    if (is_array($data) || is_object($data)) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        echo $data;
    }
}
function send_email($to, $subject, $body, $from = "", $from_name = "")
{
    if (is_development())
        return true;
    $CI = &get_instance();
    $from = $from == "" ? SUPPORT_EMAIL : $from;
    $from_name = $from_name == "" ? PLATFORM_NAME : $from_name;
    $header = "Reply-To: " . $from_name . " <" . $from . ">\r\n";
    $header .= "Return-Path: " . $from_name . " <" . $from . ">\r\n";
    $header .= "From: " . $from_name . " <" . $from . ">\r\n";
    $header .= "Organization: " . PLATFORM_NAME . "\r\n";
    $header .= "Content-Type: text/html\r\n";
    $sent = mail($to, $subject, $body, $header, "-f " . $from);
    return $sent;
}
if (!function_exists('is_assoc')) {
    function is_assoc($arr)
    {
        if (is_array($arr)) {
            return array_keys($arr) !== range(0, count($arr) - 1);
        }
        return FALSE;
    }
}
function is_production()
{
    if (ENVIRONMENT == "production") {
        return TRUE;
    }
    return FALSE;
}
function is_development()
{
    if (ENVIRONMENT == "development") {
        return TRUE;
    }
    return FALSE;
}
function is_testing()
{
    if (ENVIRONMENT == "testing") {
        return TRUE;
    }
    return FALSE;
}
function ip()
{
    $CI = &get_instance();
    $ip = $CI->input->server("REMOTE_ADDR");
    return $ip;
}
function _url($str)
{
    return parse_url($str);
}
function _email($str)
{
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}
function _gst($str)
{
    return filter_var($str, FILTER_VALIDATE_GST);
}
function response($data)
{
    $CI = &get_instance();
    if (is_array($data)) {
        $CI->output->set_content_type('application/json');
        if (isset($_POST['monitor']) || isset($_GET['monitor']))
            $data['execution_time'] = get_execution_time();
        $res = json_encode($data);
    } else {
        $res = $data;
    }
    print_rlog($res, 'response');
    $CI->output->_display($res);
    exit;
}
function get_execution_time()
{
    $CI = &get_instance();
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $CI->start_time), 4);
    return $total_time;
}
function get_default_fields($defult_fields)
{
    $new_default = array();
    foreach ($defult_fields as $key => $val) {
        $new_default[$key] = $defult_fields[$key]['default'];
    }
    return $new_default;
}
function get_default_meta_fields($defult_fields)
{
    $new_default = array();
    foreach ($defult_fields as $val) {
        $new_default[$val['meta_key']] = array("autoload" => $val['autoload'], "value" => $val['default']);
    }
    return $new_default;
}
function check_and_create_directory($dir_path)
{
    if (!file_exists($dir_path)) {
        mkdir($dir_path, 0777);
    }
    return TRUE;
}
if (!function_exists('random_str')) {
    function random_str($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
if (!function_exists('get_unique_code')) {
    function get_unique_code($tbl, $col, $length = 15)
    {
        $CI = &get_instance();
        $CI->load->model("model_api");
        static $loop = 0;
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token .= $alphabet[$n];
        }
        $where = array("and" => array($col => $token, $col . ' !=' => ''));
        $res = $CI->model_api->get($tbl, array($col), $where, array(), array(), 0, 1);
        if (!empty($res['data']) && $loop < 100) {
            $loop++;
            $token = get_unique_code($tbl, $col);
        }
        return $token;
    }
}
if (!function_exists('get_unique_number')) {
    function get_unique_number($tbl, $col, $length = 15, $iszeo = true)
    {
        $CI = &get_instance();
        $CI->load->model("model_api");
        static $loop = 0;
        $alphabet = "0123456789";
        if (!$iszeo) {
            $alphabet = "123456789";
        }
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token .= $alphabet[$n];
        }
        $where = array("and" => array($col => $token, $col . ' !=' => ''));
        $res = $CI->model_api->get($tbl, array($col), $where, array(), array(), 0, 1);
        if (!empty($res['data']) && $loop < 100) {
            $loop++;
            $token = $this->get_unique_number($tbl, $col);
        }
        return $token;
    }
}
if (!function_exists('get_unique_alphabet')) {
    function get_unique_alphabet($tbl, $col, $length = 15)
    {
        $CI = &get_instance();
        $CI->load->model("model_api");
        static $loop = 0;
        $alphabet = "abcdefghijklmnopqrstuwxyz0123456789";
        $token = "";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $token .= $alphabet[$n];
        }
        $where = array("and" => array($col => $token, $col . ' !=' => ''));
        $res = $CI->model_api->get($tbl, array($col), $where, array(), array(), 0, 1);
        if (!empty($res['data']) && $loop < 100) {
            $loop++;
            $token = $this->get_unique_alphabet($tbl, $col);
        }
        return $token;
    }
}
function filter_data_to_api($default_fields, $actual_data)
{
    foreach ($default_fields as $key => $val) {
        if (isset($actual_data[$key])) {
            if ($val['protected'] == 1) {
                unset($actual_data[$key]);
            }
        }
    }
    return $actual_data;
}
function user_detail()
{
    $CI = &get_instance();
    $user = $CI->config->item("user_detail");
    return $user;
}
function user_id()
{
    $CI = &get_instance();
    $user = $CI->config->item("user_detail");
    return isset($user['uid']) ? $user['uid'] : 0;
}
if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        $arh = array();
        $rx_http = '/\AHTTP_/';
        foreach ($_SERVER as $key => $val) {
            if (preg_match($rx_http, $key)) {
                $arh_key = preg_replace($rx_http, '', $key);
                $rx_matches = array();
                // do some nasty string manipulations to restore the original letter case
                // this should work in most cases
                $rx_matches = explode('_', $arh_key);
                if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                    foreach ($rx_matches as $ak_key => $ak_val)
                        $rx_matches[$ak_key] = ucfirst($ak_val);
                    $arh_key = implode('-', $rx_matches);
                }
                $arh[$arh_key] = $val;
            }
        }
        return ($arh);
    }
}
function print_rlog($log = '', $type = '', $message = '')
{
    $log_dir = "my_logs";
    $log_file = $type . '_' . date("d-m-y-H") . '.txt';
    $log_path = $log_dir . '/' . $log_file;
    if (!file_exists($log_dir))
        mkdir($log_dir, 0777, true) or die('enable to print log');
    $content = "";
    $content .= date("h:i:s") . "\n======================================================================================================================================\n";
    $content .= "URL      : http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]\n";
    $content .= "Execute(Time)      : " . get_execution_time() . "\n";
    $content .= "HEADER   : ";
    if (function_exists("getallheaders"))
        foreach (getallheaders() as $name => $value)
            $content .= "$name: $value\t || \t";
    $content .= "\n";
    if ($_POST)
        $content .= "POST     : " . http_build_query($_POST) . "\n";
    if ($_GET)
        $content .= "GET      : " . http_build_query($_GET) . "\n";
    if ($_FILES)
        foreach ($_FILES as $key => $_FILE) {
            $content .= "File     : " . $key . " : " . urldecode(http_build_query($_FILE)) . "\n";
        }
    if ($message)
        $content .= "Message : " . $message . "\n";
    if ($log)
        $content .= "Responce : " . $log . "\n";
    $content .= "\n";
    file_put_contents($log_path, $content, FILE_APPEND) or die('enable to print log');
}
