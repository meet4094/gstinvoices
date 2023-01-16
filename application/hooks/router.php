<?php
class Router
{

    private $available_lang = array("en", "de", "gu");
    private $available_response_format = array("json", "xml", "rss", "atom");
    private static $current_lang = "en";
    private static $response_format = "json";
    private static $is_api = FALSE;
    private static $path_info = "";
    private static $last_segment = "";

    public function __construct()
    {
        if (!defined("ROUTE_ACCESS"))
            define("ROUTE_ACCESS", 1);
    }

    function pre_system()
    {
        $request = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : (isset($_SERVER['ORIG_PATH_INFO']) ? $_SERVER['ORIG_PATH_INFO'] : (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : ""));
        $parts = explode('.', $request);
        self::$response_format = $parts[sizeof($parts) - 1];
        if (in_array(self::$response_format, $this->available_response_format)) {
            $request = substr($request, 0, (strlen($request) - strlen(self::$response_format) - 1));
        } elseif (preg_match("[/api]", $request) && count($parts) > 1 && self::$response_format != "") {
            $res = array("statuscode" => 0, "msg" => "Invalid response format");
            echo json_encode($res);
            exit;
        }

        $_SERVER['REQUEST_URI'] = $request;
        $_SERVER['PATH_INFO'] = $_SERVER['REQUEST_URI'];
        $_SERVER['ORIG_PATH_INFO'] = $_SERVER['REQUEST_URI'];
    }

    public function set_device_identity()
    {
        $CI = &get_instance();
        $device_type = "other";
        $os = "other";
        self::$is_api = FALSE;

        $CI->load->library("mobile_detect");

        if ($CI->input->is_ajax_request())
            self::$is_api = TRUE;
        if (($CI->mobile_detect->isMobile() || $CI->mobile_detect->isTablet())) {
            $device_type = "mobile";
            $os = "other";
            if ($CI->mobile_detect->isAndroidOS()) {
                $os = "android";
            } elseif ($CI->mobile_detect->isiOS()) {
                $os = "iphone";
            }
        }
        if (!self::$is_api) {
            $user_agent = $CI->mobile_detect->getUserAgent();
            if (strpos(strtolower($user_agent), "videostatus") !== FALSE && strpos(strtolower($user_agent), "android") !== FALSE) {
                $os = "android";
                $device_type = "mobile";
                self::$is_api = TRUE;
            } elseif (strpos(strtolower($user_agent), "videostatus") !== FALSE && strpos(strtolower($user_agent), "iphone") !== FALSE) {
                $os = "iphone";
                $device_type = "mobile";
                self::$is_api = TRUE;
            }
        }
        $CI->config->set_item('response_format', self::$response_format);
        $language = isset(apache_request_headers()['ln']) ? apache_request_headers()['ln'] : 'en';
        if ($language) {
            if (!isset($language) || $language == "" || !in_array($language, $this->available_lang)) {
                $res = array("statuscode" => 0, "msg" => "Invalid Language selection");
                echo json_encode($res);
                exit;
            }
            self::$current_lang = $language;
        }
        $CI->lang->load(self::$current_lang, self::$current_lang);
        $CI->config->set_item('ln', self::$current_lang);
        $CI->config->set_item('device_type', $device_type); // mobile,tablete,other
        $CI->config->set_item('os', $os); // android,iphone,other
        $CI->is_login = self::$is_api;
        $CI->config->set_item('is_api', self::$is_api); // true,false
    }

    function check_site_status()
    {
        $CI = &get_instance();
        if (isset($CI->is_admin_url) && $CI->is_admin_url != '') {
            if (@$CI->settings['site_status'] == "offline") {
                if ($CI->config->item("is_api"))
                    response(info_res("site_offline"));
                else
                    show_error("We are offline.");
                exit;
            }
        }
    }

    function check_url_permission()
    {
        $uri = $_SERVER['PATH_INFO'];
        $uri = trim($uri, "/");

        self::$path_info = $uri;
        self::$path_info = trim(self::$path_info, "/");

        $CI = &get_instance();
        $CI->load->model("model_token");


        if ($CI->model_token->request_token_require(self::$path_info)) {
            $rtoken = $CI->input->get_request_header("Request-token");
            $valid = $CI->model_token->check_request_token($rtoken);
            if (!$valid) {
                response(req_token_missing_res("request_token_invalid"));
            }
        } else if ($CI->model_token->auth_token_require(self::$path_info)) {
            $auth_token = $CI->input->get_request_header("Auth-token");
            $valid = $CI->model_token->check_auth_token($auth_token);
            if (!$valid)
                response(auth_token_missing_res("auth_token_invalid"));
        } else {
        }
        return TRUE;
    }
}
