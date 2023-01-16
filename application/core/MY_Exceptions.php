<?php

/**
 * MY_Log Class
 *
 * This library extends the native Log library.
 * It adds the function to have the log messages being emailed when they have
 * been outputted to the log file.
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @category    Exception
 */
class MY_Exceptions extends CI_Exceptions {

    function __construct() {
        parent::__construct();
    }

    /**
     * 401 Unauthorized
     *
     * @access  private
     * @param   string  the page
     * @param   bool    log error yes/no
     * @return  string
     */
    function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
        $result = parent::show_error($heading, $message, $template = 'error_general', $status_code);

        if (ENVIRONMENT === 'production' && SEND_ERROR_MAIL) {
             
            $header = "Reply-To: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n"
                    . "Return-Path: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n"
                    . "From: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n"
                    . "Organization: " . PLATFORM_NAME . "\r\n"
                    . "Content-Type: text/html\r\n";
            
            $msg = $result . "Post = http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?" . http_build_query($_POST);
            if($status_code != 404 || $status_code != 302)
                $sent = mail(ERR_TO_EMAIL, $heading, $msg, $header);
        }
        return $result;
    }

    function log_exception($severity, $message, $filepath, $line) {        
        if (ENVIRONMENT === 'production' && SEND_ERROR_MAIL) {

            $header = "Reply-To: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "Return-Path: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "From: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "Organization: " . PLATFORM_NAME . "\r\n";
            $header .= "Content-Type: text/html\r\n";
            $sent = mail(ERR_TO_EMAIL, 'A PHP Error was encountered', $message . ' on '.$filepath. ' at line: '.$line . "Post = http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?" . http_build_query($_POST) , $header);
        }

        parent::log_exception($severity, $message, $filepath, $line);
    }

    function show_php_error($severity, $message, $filepath, $line) {
        $severity = (!isset($this->levels[$severity])) ? $severity : $this->levels[$severity];

        $filepath = str_replace("\\", "/", $filepath);

        // For safety reasons we do not show the full file path
        if (FALSE !== strpos($filepath, '/')) {
            $x = explode('/', $filepath);
            $filepath = $x[count($x) - 2] . '/' . end($x);
        }

        if (ob_get_level() > $this->ob_level + 1) {
            ob_end_flush();
        }
        ob_start();
        include(APPPATH . 'views/errors/html/error_php.php');
        $buffer = ob_get_contents();
        ob_end_clean();
        
        if (ENVIRONMENT === 'production' && SEND_ERROR_MAIL) {

            $header = "Reply-To: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "Return-Path: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "From: " . PLATFORM_NAME . " <" . ERR_FROM_EMAIL . ">\r\n";
            $header .= "Organization: " . PLATFORM_NAME . "\r\n";
            $header .= "Content-Type: text/html\r\n";
            if($buffer)
                $sent = mail(ERR_TO_EMAIL, 'A PHP Error was encountered', $buffer . "Post = http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?" . http_build_query($_POST) , $header);
        }
        
        echo $buffer;
    }

}

/* End of file MY_Log.php */
/* Location: ./application/core/MY_Log.php */