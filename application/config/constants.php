<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',                            'rb');
define('FOPEN_READ_WRITE',                        'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',        'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',    'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',                    'ab');
define('FOPEN_READ_WRITE_CREATE',                'a+b');
define('FOPEN_WRITE_CREATE_STRICT',                'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',        'x+b');

$PROTOCOL = $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
$HOST = strpos($_SERVER['HTTP_HOST'], "localhost") !== FALSE ? $_SERVER['HTTP_HOST'] . "/gstinvoice" : "{$_SERVER['HTTP_HOST']}/gstinvoice";
define('BASE_URL', $PROTOCOL . "://{$HOST}/");

define('WEB_URL', $PROTOCOL . "://{$HOST}/");
define('PLATFORM_NAME', 'Gst Invoice');
define('DS', DIRECTORY_SEPARATOR);
define('INFO_EMAIL', 'arya.infotech3360@gmail.com');
define('SUPPORT_EMAIL', 'arya.infotech3360@gmail.com');

define('SEND_ERROR_MAIL', FALSE);
define('ERR_FROM_EMAIL', 'arya.infotech3360@gmail.com');
define('ERR_TO_EMAIL', 'arya.infotech3360@gmail.com');

define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_MAX_LENGTH', 16);
define('ADMIN_LOGIN_SESSION_TIME', 2592000); // 1 month

define('NUMBER_MIN_LENGTH', 10);
define('NUMBER_MAX_LENGTH', 10);

define('OTP_MIN_LENGTH', 4);
define('OTP_MAX_LENGTH', 4);

define('BASE_DIR_PATH', realpath(FCPATH));
define('IMAGE_DIR_PATH', realpath(BASE_DIR_PATH . "/images"));

define('USER_AVATAR_PATH', realpath(IMAGE_DIR_PATH . "/users"));
define('USER_AVATAR_URL', BASE_URL . "images/users");

define('SLIDER_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/slider"));
define('SLIDER_IMAGE_URL', BASE_URL . "images/slider");

define('FESTIVALCATEGORY_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/festivalcategory"));
define('FESTIVALCATEGORY_IMAGE_URL', BASE_URL . "images/festivalcategory");

define('FESTIVALIMAGE_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/festivalimage"));
define('FESTIVALIMAGE_IMAGE_URL', BASE_URL . "images/festivalimage");

define('FESTIVALVIDEO_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/festivalvideo"));
define('FESTIVALVIDEO_IMAGE_URL', BASE_URL . "images/festivalvideo");

define('BUSINESSCATEGORY_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/businesscategory"));
define('BUSINESSCATEGORY_IMAGE_URL', BASE_URL . "images/businesscategory");

define('BUSINESSIMAGE_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/businessimage"));
define('BUSINESSIMAGE_IMAGE_URL', BASE_URL . "images/businessimage");

define('BUSINESSVIDEO_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/businessvideo"));
define('BUSINESSVIDEO_IMAGE_URL', BASE_URL . "images/businessvideo");

define('GREETINGCATEGORY_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/greetingcategory"));
define('GREETINGCATEGORY_IMAGE_URL', BASE_URL . "images/greetingcategory");

define('GREETINGIMAGE_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/greetingimage"));
define('GREETINGIMAGE_IMAGE_URL', BASE_URL . "images/greetingimage");

define('GREETINGVIDEO_IMAGE_PATH', realpath(IMAGE_DIR_PATH . "/greetingvideo"));
define('GREETINGVIDEO_IMAGE_URL', BASE_URL . "images/greetingvideo");

define('DATABASE_BACKUP_PATH', realpath(IMAGE_DIR_PATH . "/databases"));
define('DATABASE_BACKUP_URL', BASE_URL . "images/databases");


/* End of file constants.php */
/* Location: ./application/config/constants.php */
