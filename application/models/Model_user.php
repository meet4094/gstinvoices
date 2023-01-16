<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_user extends CI_Model
{

    public static $defaultFields = array();
    public static $defaultMeta = array();
    public static $defaultDeviceMeta = array();
    public static $defaultContactUs = array();
    public static $schema = "videostatus";

    public function __construct()
    {
        parent::__construct();
        self::$defaultFields = array(
            "google_id" => array("type" => "string", "default" => "", "protected" => 0),
            "facebook_id" => array("type" => "string", "default" => "", "protected" => 0),
            "otp" => array("type" => "string", "default" => "", "protected" => 0),
            "trade_name" => array("type" => "string", "default" => "", "protected" => 0),
            "leagal_name" => array("type" => "string", "default" => "", "protected" => 0),
            "prefix" => array("type" => "string", "default" => "", "protected" => 0),
            "email" => array("type" => "string", "default" => "", "protected" => 0),
            "address" => array("type" => "string", "default" => "", "protected" => 0),
            "gst_number" => array("type" => "string", "default" => "", "protected" => 0),
            // "avatar" => array("type" => "string", "default" => "", "protected" => 0),
            "mobile_number" => array("type" => "string", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_create_profile" => array("type" => "integer", "default" => 1, "protected" => 0),
            "is_active" => array("type" => "integer", "default" => 0, "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );

        self::$defaultMeta = array(
            "password" => array("type" => "md5", "default" => "", "autoload" => 0, "protected" => 1),
            "forgot_password_token" => array("type" => "string", "default" => "", "autoload" => 0, "protected" => 1),
            "user_activation_token" => array("type" => "string", "default" => "", "autoload" => 0, "protected" => 1),
            "user_change_email_token" => array("type" => "string", "default" => "", "autoload" => 0, "protected" => 1),
            "last_login" => array("type" => "integer", "default" => "", "autoload" => 1, "protected" => 1),
            "last_login_ip" => array("type" => "integer", "default" => "", "autoload" => 0, "protected" => 1),
            "last_login_device" => array("type" => "string", "default" => "iphone", "autoload" => 0, "protected" => 1),
            "last_action" => array("type" => "string", "default" => time(), "autoload" => 1, "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );

        self::$defaultDeviceMeta = array(
            "user_id" => array("type" => "integer", "default" => "", "protected" => 1),
            "device_id" => array("type" => "string", "default" => "", "protected" => 1),
            "auth_token" => array("type" => "string", "default" => "", "protected" => 0),
            "device_type" => array("type" => "string", "default" => "iphone", "protected" => 1),
            "device_token" => array("type" => "string", "default" => "", "protected" => 1),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );

        self::$defaultContactUs = array(
            "user_id" => array("type" => "integer", "default" => "", "protected" => 1),
            "name" => array("type" => "string", "default" => "", "protected" => 1),
            "email" => array("type" => "string", "default" => "", "protected" => 0),
            "subject" => array("type" => "string", "default" => "iphone", "protected" => 1),
            "description" => array("type" => "string", "default" => "", "protected" => 1),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
        );
    }

    function inviteExist($inviteCode)
    {
        $iRes = $this->model_api->get('sh_users', array("uid"), array("and" => array("invite_code" => $inviteCode)));
        return isset($iRes['data'][0]['uid']) ? $iRes['data'][0]['uid'] : 0;
    }

    function emailExist($EmailId)
    {
        $iRes = $this->model_api->get('sh_users', array("COUNT(*) AS cnt"), array("and" => array("email" => $EmailId)));
        if (!isset($iRes['data'][0]['cnt']) || $iRes['data'][0]['cnt'] != 0)
            return TRUE;
        return FALSE;
    }

    function emailByUidExist($emailId, $uId)
    {
        $iQuery = "SELECT COUNT(uid) AS cnt FROM sh_users WHERE gst_number LIKE '{$emailId}' AND uid != {$uId}";
        $iRes = $this->model_api->execute_query($iQuery);
        if (!isset($iRes['data'][0]['cnt']) || $iRes['data'][0]['cnt'] != 0)
            return TRUE;
        return FALSE;
    }

    function emailChangeExist($EmailId)
    {
        $iRes = $this->model_api->get('sh_users', array("COUNT(uid) AS cnt"), array("and" => array("change_email" => $EmailId)));
        if (!isset($iRes['data'][0]['cnt']) || $iRes['data'][0]['cnt'] != 0)
            return TRUE;
        return FALSE;
    }

    function facebookIdExist($facebookId)
    {
        $iRes = $this->model_api->get("sh_users", array("COUNT(uid) AS cnt"), array("and" => array("facebook_id" => $facebookId)));
        if (!isset($iRes['data'][0]['cnt']) || $iRes['data'][0]['cnt'] != 0)
            return TRUE;
        return FALSE;
    }

    function googleIdExist($googleId)
    {
        $iRes = $this->model_api->get("sh_users", array("COUNT(uid) AS cnt"), array("and" => array("google_id" => $googleId)));
        if (!isset($iRes['data'][0]['cnt']) || $iRes['data'][0]['cnt'] != 0)
            return TRUE;
        return FALSE;
    }

    function addMeta($meTas, $uId)
    {
        $defaultFields = get_default_fields(self::$defaultMeta);
        $insertedFields = array_intersect_key($meTas, $defaultFields);
        $iMetaData = array();
        foreach ($insertedFields as $key => $metaVal) {
            $key = trim($this->db->escape_str($key));
            $metaVal = trim($this->db->escape_str($metaVal));
            if (self::$defaultMeta[$key]['type'] == "md5")
                $metaVal = md5($metaVal);
            $iExist = $this->metaKeyExist($key, $uId);
            if ($iExist != 0) {
                $this->updateMeta($key, $metaVal, $uId);
            } else {
                array_push($iMetaData, array('user_id' => $uId, 'user_meta_key' => $key, 'user_meta_value' => $metaVal, "autoload" => self::$defaultMeta[$key]['autoload']));
            }
        }
        if (!empty($iMetaData))
            $this->model_api->add('sh_users_meta', $iMetaData, TRUE);
        return success_res("success");
    }

    function metaKeyExist($metaKey, $uId)
    {
        $iRes = $this->model_api->get('sh_users_meta', array(), array("and" => array("user_id" => $uId, "user_meta_key" => $metaKey, "is_del" => '0')));
        return isset($iRes['data'][0]['umid']) ? $iRes['data'][0]['umid'] : 0;
    }

    function updateMeta($key, $newVal, $uId)
    {
        $iRes = $this->model_api->update('sh_users_meta', array("user_meta_value" => $newVal), array("and" => array("user_meta_key" => $key, "user_id" => $uId, "is_del" => '0')), 1);
        return $iRes;
    }

    function getMeta($uId = 0)
    {
        $userMeta = $this->model_api->get('sh_users_meta', array(), array("and" => array("user_id" => $uId, "is_del" => '0')));
        $userMeta = isset($userMeta['data'][0]) ? $userMeta['data'] : array();
        $iMeta = array();
        for ($i = 0; $i < count($userMeta); $i++) {
            $iMeta[$userMeta[$i]['user_meta_key']] = $userMeta[$i]['user_meta_value'];
        }
        $defaultFields = get_default_fields(self::$defaultMeta);
        $iArrayDiff = array_diff_key($defaultFields, $iMeta);
        $iAllMetas = array_intersect_key($iMeta, $defaultFields);
        $iAllMetas = array_merge($iAllMetas, $iArrayDiff);
        return $iAllMetas;
    }

    function userDeviceDetail($userDeviceDetail)
    {
        $this->model_api->delete('sh_users_device', array("and" => array("user_id" => $userDeviceDetail['user_id']))); // Single Login -- Delete old Auth-Token.
        $loginId = $this->validateDeviceToken($userDeviceDetail['device_token'], $userDeviceDetail['device_id']);
        if ($loginId == 0) {
            $userDeviceDetail['created_date'] = date("Y-m-d H:i:s");
            $iStatus = $this->model_api->add('sh_users_device', $userDeviceDetail);
            if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                return $iStatus;
        } else {
            $userDeviceDetail['modified_date'] = date("Y-m-d H:i:s");
            $iStatus = $this->model_api->update('sh_users_device', $userDeviceDetail, array("and" => array("login_id" => $loginId)));
            if ($iStatus['statuscode'] != 1)
                return $iStatus;
        }
        return $iStatus;
    }

    function validateDeviceToken($deviceToken = '', $deviceId = '')
    {
        if (!empty($deviceToken)) {
            $deviceData = $this->model_api->get('sh_users_device', array("login_id"), array("or" => array("device_token" => $deviceToken, "device_id" => $deviceId)));
            return isset($deviceData['data'][0]['login_id']) ? $deviceData['data'][0]['login_id'] : 0;
        } else {
            return 0;
        }
    }

    function getNewAuthToken()
    {
        $newToken = random_str(15);
        $iToken = $this->model_api->get('sh_users_device', array("COUNT(*) cnt"), array("and" => array("auth_token" => $newToken, "is_del" => '0')));
        if (isset($iToken['data'][0]['cnt']) && $iToken['data'][0]['cnt'] > 0)
            return $this->getNewAuthToken();
        return $newToken;
    }

    function getNewForgotPasswordToken()
    {
        $newToken = random_str(15);
        $token = $this->model_api->get("sh_users_meta", array("COUNT(*) cnt"), array("and" => array("user_meta_key" => "forgot_password_token", "user_meta_value" => $newToken, "is_del" => '0')));
        if (isset($token['data'][0]['cnt']) && $token['data'][0]['cnt'] > 0)
            return $this->getNewForgotPasswordToken();
        return $newToken;
    }

    function getNewActivationToken()
    {
        $newToken = random_str(15);
        $token = $this->model_api->get("sh_users_meta", array("COUNT(*) cnt"), array("and" => array("user_meta_key" => "user_activation_token", "user_meta_value" => $newToken, "is_del" => '0')));
        if (isset($token['data'][0]['cnt']) && $token['data'][0]['cnt'] > 0)
            return $this->getNewActivationToken();
        return $newToken;
    }

    function getNewChangeEmailToken()
    {
        $newToken = random_str(15);
        $token = $this->model_api->get("sh_users_meta", array("COUNT(*) cnt"), array("and" => array("user_meta_key" => "user_change_email_token", "user_meta_value" => $newToken, "is_del" => '0')));
        if (isset($token['data'][0]['cnt']) && $token['data'][0]['cnt'] > 0)
            return $this->getNewChangeEmailToken();
        return $newToken;
    }

    function validateForgotPasswordToken($token)
    {
        $iUserInfo = $this->model_api->get("sh_users_meta", array("user_id"), array("and" => array("user_meta_key" => "forgot_password_token", "user_meta_value" => $token, "is_del" => '0')));
        return isset($iUserInfo['data'][0]['user_id']) ? $iUserInfo['data'][0]['user_id'] : 0;
    }

    function validateUserActivationToken($token)
    {
        $iUserInfo = $this->model_api->get("sh_users_meta", array("user_id"), array("and" => array("user_meta_key" => "user_activation_token", "user_meta_value" => $token, "is_del" => '0')));
        return isset($iUserInfo['data'][0]['user_id']) ? $iUserInfo['data'][0]['user_id'] : 0;
    }

    function validatechangeEmailToken($token)
    {
        $iUserInfo = $this->model_api->get("sh_users_meta", array("user_id"), array("and" => array("user_meta_key" => "user_change_email_token", "user_meta_value" => $token, "is_del" => '0')));
        return isset($iUserInfo['data'][0]['user_id']) ? $iUserInfo['data'][0]['user_id'] : 0;
    }

    function changeAccountStatus($iStatus, $uId)
    {
        $iStatus = array("is_active" => $iStatus);
        return $this->editProfile($iStatus, $uId);
    }

    function changeEmail($param)
    {
        $uId = isset($param['user_id']) ? $param['user_id'] : user_id();
        $userDetail = user_detail();
        $emailId = isset($param['email']) ? $param['email'] : $userDetail['email'];
        $nemailId = isset($param['new_email']) ? $param['new_email'] : '';

        $iUser = $this->model_api->get("sh_users", array("uid,email"), array("and" => array("uid" => $uId)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1 || !isset($iUser['data'][0]['uid']))
            return error_res("invalid_data");
        $oemailId = isset($iUser['data'][0]['email']) ? $iUser['data'][0]['email'] : '';
        if (empty($oemailId) || $oemailId != $emailId)
            return error_res('wrong_current_email');

        $iStatus = $this->model_api->update("sh_users", array("change_email" => $nemailId), array("and" => array("uid" => $uId)));
        if (!isset($iUser['statuscode']) || $iStatus['statuscode'] != '1')
            return $iStatus;

        $this->sendChangeEmailTokenMail($uId, $nemailId);
        $iUserInfo = $this->getDetail($uId, $param);
        $iRes = success_res("success");
        $iRes['data'] = $iUserInfo['data'];
        return $iRes;
    }

    function sendChangeEmailTokenMail($uId, $uEmail)
    {
        $iSent = FALSE;
        $token = $this->getNewChangeEmailToken();
        $iDetail = array("user_change_email_token" => $token);
        $this->addMeta($iDetail, $uId);
        $userDetail['token'] = $token;
        $iBody = $this->load->view('template/email/UserChangeEmail', $userDetail, true);
        $iSubject = $this->lang->line("account_change_email_subject");
        $iSent = send_email($uEmail, $iSubject, $iBody);
        return $iSent;
    }

    function deactivateAccount($uId)
    {
        $iStatus = array("is_active_profile" => 0, "last_seen" => time());
        $this->editProfile($iStatus, $uId);
        $this->model_api->delete('sh_users_device', array("and" => array("user_id" => $uId)));
        $iRes = success_res("profile_deactivated");
        return $iRes;
    }

    function displayAccountActivated($token)
    {
        $iStatus = error_res("invalid_page");
        $iStatus['code'] = 0;
        $uId = $this->validateUserActivationToken($token);
        if ($uId != 0) {
            $this->changeAccountStatus(1, $uId);
            $this->addMeta(array("user_activation_token" => ""), $uId);
            $iStatus = success_res("valid_page");
            $iStatus['code'] = 1;
        }

        $iStatus['token'] = $token;
        $this->load->view('template/viewActiveAccount', $iStatus);
    }

    function displayChangeEmail($token)
    {
        $iStatus = error_res("invalid_page");
        $iStatus['code'] = 0;
        $uId = $this->validatechangeEmailToken($token);
        if ($uId != 0) {
            $iUser = $this->model_api->get("sh_users", array("change_email"), array("and" => array("uid" => $uId)));
            $emailId = isset($iUser['data'][0]['change_email']) ? $iUser['data'][0]['change_email'] : '';
            if (!empty($emailId))
                $this->model_api->update("sh_users", array("email" => $emailId, "change_email" => ''), array("and" => array("uid" => $uId)));
            $this->addMeta(array("user_change_email_token" => ""), $uId);
            $iStatus = success_res("valid_page");
            $iStatus['code'] = 1;
        }

        $iStatus['token'] = $token;
        $this->load->view('template/viewChangeEmail', $iStatus);
    }

    function displayResetPassword($token)
    {
        $iStatus = error_res("invalid_page");
        $iStatus['code'] = 0;
        $uId = $this->validateForgotPasswordToken($token);
        if ($uId != 0) {
            $iStatus = success_res("valid_page");
            $iStatus['code'] = 1;
        }

        $iStatus['token'] = $token;
        $this->load->view('template/viewResetPassword', $iStatus);
    }

    function resetPassword($param)
    {
        $uId = $this->validateForgotPasswordToken($param['secret_token']);
        if ($uId == 0)
            return error_res("invalid_page");
        $this->addMeta(array("password" => $param['new_password'], "forgot_password_token" => ""), $uId);
        $iRes = success_res("password_resetted");
        response($iRes);
    }

    function forgotPassword($param)
    {
        $emailId = isset($param['email']) ? $param['email'] : '';
        $iUserInfo = $this->model_api->get("sh_users", array("uid,email"), array("and" => array("email" => $emailId)));
        if (!isset($iUserInfo['data'][0]))
            return error_res("email_not_registered");
        $iUserData = $iUserInfo['data'][0];
        $iSent = $this->mylib->send_forgot_password_email("user", $iUserData);
        $iRes = $iSent ? success_res("forgot_password_email_sent") : error_res("unknown");
        response($iRes);
    }

    function resendActivationMail($param)
    {
        $emailId = isset($param['email']) ? $param['email'] : 0;
        $iUserInfo = $this->model_api->get("sh_users", array("uid,email"), array("and" => array("email" => $emailId)));
        if (!isset($iUserInfo['data'][0]['uid']))
            return error_res("email_not_registered");
        $iUser = $iUserInfo['data'][0];
        $this->sendAccountActivationMail($iUser['uid'], $iUser['email']);
        $iRes = success_res("activation_email_sent");
        return $iRes;
    }

    function sendAccountActivationMail($uId, $uEmail)
    {
        $iSent = FALSE;
        $token = $this->getNewActivationToken();
        $iDetail = array("user_activation_token" => $token);
        $this->addMeta($iDetail, $uId);
        $userDetail['token'] = $token;
        $iBody = $this->load->view('template/email/UserWelcomeEmail', $userDetail, true);
        $iSubject = $this->lang->line("account_activation_subject");
        $iSent = send_email($uEmail, $iSubject, $iBody);
        return $iSent;
    }

    function signUp($param)
    {
        $type = isset($param['type']) ? $param['type'] : 0;
        $defaultFields = get_default_fields(self::$defaultFields);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['invite_code'] = get_unique_number('sh_users', 'invite_code', 6, false);
        if ($type == 2 || $type == 3) {
            $insertedFields['is_active'] = 1;
            $insertedFields['is_first_login'] = 0;
        }

        if ($type == 2) { // SigUp With Facebook
            $facebookId = isset($param['facebook_id']) ? $param['facebook_id'] : '';
            $iUser = $this->model_api->get('sh_users', array("uid"), array("and" => array("facebook_id" => $facebookId)));
            if (!isset($iUser['data'][0]['uid'])) {
                $emailId = isset($param['email']) ? $param['email'] : '';
                if (!empty($emailId)) {
                    $iUser = $this->model_api->get("sh_users", array("uid"), array("and" => array("email" => $emailId)));
                    if (isset($iUser['data'][0]['uid'])) {
                        $uId = $iUser['data'][0]['uid'];
                        $iStatus = $this->model_api->update("sh_users", array("facebook_id" => $facebookId), array("and" => array("uid" => $uId)));
                        if ($iStatus['statuscode'] != '1')
                            return $iStatus;
                    } else {
                        $iStatus = $this->model_api->add('sh_users', $insertedFields);
                        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                            return $iStatus;
                        $uId = $iStatus['data']['id'];
                    }
                } else {
                    $iStatus = $this->model_api->add('sh_users', $insertedFields);
                    if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                        return $iStatus;
                    $uId = $iStatus['data']['id'];
                }
            } else {
                $uId = $iUser['data'][0]['uid'];
            }
        } elseif ($type == 3) { // SigUp With Google
            $googleId = isset($param['google_id']) ? $param['google_id'] : '';
            $iUser = $this->model_api->get('sh_users', array("uid"), array("and" => array("google_id" => $googleId)));
            if (!isset($iUser['data'][0]['uid'])) {
                $emailId = isset($param['email']) ? $param['email'] : '';
                if (!empty($emailId)) {
                    $iUser = $this->model_api->get("sh_users", array("uid"), array("and" => array("email" => $emailId)));
                    if (isset($iUser['data'][0]['uid'])) {
                        $uId = $iUser['data'][0]['uid'];
                        $iStatus = $this->model_api->update("sh_users", array("google_id" => $googleId), array("and" => array("uid" => $uId)));
                        if ($iStatus['statuscode'] != '1')
                            return $iStatus;
                    } else {
                        $iStatus = $this->model_api->add('sh_users', $insertedFields);
                        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                            return $iStatus;
                        $uId = $iStatus['data']['id'];
                    }
                } else {
                    $iStatus = $this->model_api->add('sh_users', $insertedFields);
                    if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                        return $iStatus;
                    $uId = $iStatus['data']['id'];
                }
            } else {
                $uId = $iUser['data'][0]['uid'];
            }
        } else { // SigUp with Email-Password
            $iStatus = $this->model_api->add('sh_users', $insertedFields);
            if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
                return $iStatus;
            $uId = $iStatus['data']['id'];
        }
        $param['uid'] = $uId;

        if ($type == 2 || $type == 3) {
            $iUser = $this->model_api->get('sh_users', array("uid,last_seen,is_active_profile,is_del"), array("and" => array("uid" => $uId)));
            if ($iUser['data'][0]['is_del'] == 1)
                return error_res("suspended_account");

            if ($iUser['data'][0]['is_active_profile'] == 0) {
                $lastSeen = $iUser['data'][0]['last_seen'];
                $currentSeen = time();
                $days = ($currentSeen - $lastSeen) / 86400;
                if ($days > 14) {
                    return error_res("deactivated_account_exceeded");
                } else {
                    $this->model_api->update("sh_users", array("is_active_profile" => 1, "last_seen" => time()), array("and" => array("uid" => $uId)));
                }
            }

            $iUserInfo = $this->userDetailById($uId);
            $deviceId = isset($param['device_id']) ? $param['device_id'] : "";
            $deviceToken = isset($param['device_token']) ? $param['device_token'] : "";
            $iUserMeta = array("last_login" => time(), "last_login_ip" => ip(), "last_action" => time(), 'last_login_device' => $deviceToken);
            $this->addMeta($iUserMeta, $iUserInfo['uid']);
            $iUserInfo = array_merge($iUserInfo, $iUserMeta);
            $newToken = $this->getNewAuthToken();
            $iUserInfo['auth_token'] = $newToken;
            $iDeviceDetail = array("user_id" => $iUserInfo['uid'], "auth_token" => $newToken, "device_type" => $this->config->item("os"), "device_token" => $deviceToken, "device_id" => $deviceId);
            $iUpdateStatus = $this->userDeviceDetail($iDeviceDetail);
            if ($iUpdateStatus['statuscode'] != 1)
                return $iUpdateStatus;

            $iRes = success_res("login");
            $iUserInfo = array_merge($iUserInfo, $iDeviceDetail);
            $defaultFields = array_merge(self::$defaultFields, self::$defaultMeta, self::$defaultDeviceMeta);
            $filteredUser = filter_data_to_api($defaultFields, $iUserInfo);
            $filteredUser['is_first_login'] = '1';
            $iRes['data'] = $filteredUser;
            return $iRes;
        } else {
            $param['last_action'] = time();
            $this->addMeta($param, $uId);
            $this->sendAccountActivationMail($uId, $insertedFields['email']);

            $iUser = $this->model_api->get('sh_users', array("uid,email,last_seen,is_first_login,is_active,is_active_profile,is_del"), array("and" => array("uid" => $uId)));
            if ($iUser['data'][0]['is_del'] == 1)
                return error_res("suspended_account");

            if ($iUser['data'][0]['is_active'] == 0) {
                $iRes = pop_up_res("verify_account");
                $iRes['data']['end_point'] = "user/resendActivationMail";
                $iRes['data']['title'] = "Resend Account Activation Email.";
                $iRes['data']['fields'] = array("email" => array("type" => "text", "label" => "Email", "placeholder" => "Email Address", "value" => $iUser['data'][0]['email']));
                return $iRes;
            }

            $iStatus = success_res("profile_created");
            return $iStatus;
        }
    }

    function createProfile($param)
    {
        $uId = user_id();
        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);
        $updateFields['is_create_profile'] = 0;

        $iStatus = $this->model_api->update('sh_users', $updateFields,  array("and" => array("uid" => $uId)));
        if (!isset($iStatus['statuscode']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] == 0)
            $this->uploadAvatar($uId, $_FILES);

        $iUserInfo = $this->getDetail($uId, $param);
        $iStatus = success_res("profile_created");
        $iStatus['data'] = $iUserInfo['data'];
        return $iStatus;
    }

    function uploadAvatar($uId, $_FILEOBJ, $iRemoveOld = 0)
    {
        $mInfo = $this->model_api->get('sh_users', array("avatar"), array("and" => array("uid" => $uId)));
        $iExt = strtolower(substr($_FILEOBJ['avatar']['name'], strrpos($_FILEOBJ['avatar']['name'], ".") + 1));
        $fileName = md5($uId . time()) . "." . $iExt;
        check_and_create_directory(USER_AVATAR_PATH . DS . $uId);
        move_uploaded_file($_FILEOBJ['avatar']['tmp_name'], USER_AVATAR_PATH . DS . $uId . DS . $fileName);
        $this->model_api->update('sh_users', array("avatar" => $fileName), array("and" => array("uid" => $uId)));
        if (isset($mInfo['data'][0]['avatar']) && !empty($mInfo['data'][0]['avatar']))
            file_exists(USER_AVATAR_PATH . DS . $uId . DS . $mInfo['data'][0]['avatar']) ? unlink(USER_AVATAR_PATH . DS . $uId . DS . $mInfo['data'][0]['avatar']) : "";
        $iRes = success_res("success");
        $iRes['data']['avatar'] = $fileName;
        return $iRes;
    }

    function logIn($param)
    {
        $mobileNumber = isset($param['mobile_number']) ? $param['mobile_number'] : '';
        $Otpnumber = rand(1000, 9999);
        $iUser = $this->model_api->get("sh_users", array("uid,mobile_number,otp"), array("and" => array("mobile_number" => $mobileNumber)));

        if (empty($iUser['data'])) {
            $iStatus = $this->model_api->add('sh_users', array("otp" => $Otpnumber, "mobile_number" => $mobileNumber));
            if ($iStatus['statuscode'] != 1)
                return $iStatus;
            $uId = $iStatus['data']['id'];
            $userDeviceDetail = array("uid" => $uId, "mobile_number" => $mobileNumber, "otp" => $Otpnumber);
        } else {
            $iStatus = $this->model_api->update('sh_users', array("otp" => $Otpnumber), array("and" => array("mobile_number" => $mobileNumber)));
            if ($iStatus['statuscode'] != 1)
                return $iStatus;

            $iUser = $this->model_api->get('sh_users', array("uid,mobile_number,otp,is_del"), array("and" => array("mobile_number" => $mobileNumber)));
            $uId = $iUser['data'][0]['uid'];
            if ($iUser['data'][0]['is_del'] == 1)
                return error_res("suspended_account");
            $userDeviceDetail = array("uid" => $uId, "mobile_number" => $mobileNumber, "otp" => $Otpnumber);
        }

        $iRes = success_res("verifyOtp");
        $iRes['data'] = $userDeviceDetail;
        return $iRes;
    }

    function verifyOtp($param)
    {
        $mobileNumber = isset($param['mobile_number']) ? $param['mobile_number'] : '';
        $mobileOtp = isset($param['otp']) ? $param['otp'] : '';

        $iUser = $this->model_api->get("sh_users", array("uid,mobile_number,otp"), array("and" => array("mobile_number" => $mobileNumber)));

        if ($iUser['data'][0]['otp'] == $mobileOtp) {

            $uId = $iUser['data'][0]['uid'];
            $iUser = $this->model_api->get('sh_users', array("uid,trade_name,leagal_name,prefix,email,mobile_number,address,created_date,modified_date,is_del"), array("and" => array("uid" => $uId)));
            if ($iUser['data'][0]['is_del'] == 1)
                return error_res("suspended_account");

            $iUser = $this->userDetailById($iUser['data'][0]['uid']);
            $deviceId = isset($param['device_id']) ? $param['device_id'] : "";
            $deviceToken = isset($param['device_token']) ? $param['device_token'] : "";
            $iMeta = array("last_login" => time(), "last_login_ip" => ip(), "last_action" => time(), 'last_login_device' => $deviceToken);
            $this->addMeta($iMeta, $iUser['uid']);
            $newToken = $this->getNewAuthToken();

            if ($mobileNumber == 1234567890) {
                $newToken = 'VZ4tAsOg0SrDep9';
            }
            $iUser = array_merge($iUser);
            $userDeviceDetail = array("user_id" => $iUser['uid'], "auth_token" => $newToken, "device_type" => $this->config->item("os"), "device_token" => $deviceToken, "device_id" => $deviceId);
            $iUpdateStatus = $this->userDeviceDetail($userDeviceDetail);
            if ($iUpdateStatus['statuscode'] != 1)
                return $iUpdateStatus;
        } else {
            if ($iUser['data'][0]['otp'])
                return error_res('wrong_otp');
        }

        $iRes = success_res("login");
        $iUser = array_merge($iUser, $userDeviceDetail);
        $defaultFields = array_merge(self::$defaultFields, self::$defaultMeta, self::$defaultDeviceMeta);
        $filteredUser = filter_data_to_api($defaultFields, $iUser);
        $iRes['data'] = $filteredUser;
        return $iRes;
    }

    function resendOtp($param)
    {
        $mobileNumber = isset($param['mobile_number']) ? $param['mobile_number'] : '';
        $Otpnumber = rand(1000, 9999);
        $iUser = $this->model_api->get("sh_users", array("uid,mobile_number,otp"), array("and" => array("mobile_number" => $mobileNumber)));

        if (isset($iUser['data'][0]['uid'])) {
            $iStatus = $this->model_api->update('sh_users', array("otp" => $Otpnumber), array("and" => array("mobile_number" => $mobileNumber)));
            if ($iStatus['statuscode'] != 1)
                return $iStatus;
        }

        $iRes = success_res("resendOtp");
        $userDeviceDetail = array("mobile_number" => $mobileNumber, "otp" => $Otpnumber);
        $iRes['data'] = $userDeviceDetail;
        return $iRes;
    }

    function getProfile()
    {
        $userId = user_id();
        $iUsers = $this->userDetailById($userId);

        foreach ($iUsers as $key => $iUser) {
            $iCustomer = $this->model_api->get("customer", array("id,user_id,trade_name"), array("and" => array("user_id" => $userId)));

            $iShopper = $this->model_api->get("shopper", array("id,user_id,trade_name"), array("and" => array("user_id" => $userId)));

            $iGstpercentage = $this->model_api->get("gst_percentage", array("id,gst"), array("and" => array("is_del" => '0')));

            $iProduct = $this->model_api->get("product_detailes", array("id,user_id,product_name,product_code"), array("and" => array("user_id" => $userId)));
            $iUsers['customer'] = $iCustomer['data'];
            $iUsers['shopper'] = $iShopper['data'];
            $iUsers['gstPercentage'] = $iGstpercentage['data'];
            $iUsers['product'] = $iProduct['data'];
        }

        $iRes = success_res("success");
        $iRes['data'] = $iUsers;
        return $iRes;
    }

    function logOut($iAuthToken)
    {
        $iStatus = $this->model_api->delete('sh_users_device', array("and" => array("auth_token" => $iAuthToken, "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("logout");
    }

    function changePassword($param)
    {
        $uId = user_id();
        $iMeta = $this->getMeta($uId);
        if (empty($iMeta) || $iMeta['password'] != md5($param['password']))
            return error_res('wrong_current_password');

        $this->addMeta(array("password" => $param['new_password']), $uId);
        $iRes = success_res("password_changed");
        return $iRes;
    }

    function editProfile($param, $uId)
    {
        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);
        $updateFields['is_create_profile'] = 1;
        if (!empty($updateFields))
            $this->model_api->update('sh_users', $updateFields,  array("and" => array("uid" => $uId)));
        $iData = $this->model_api->get("sh_users", array("uid,trade_name,leagal_name,prefix,gst_number,mobile_number,address,is_create_profile"), array("and" => array("uid" => $uId)));

        $iRes = success_res("profile_updated");
        $iRes['data'] = $iData['data'][0];
        return $iRes;
    }

    function userDetailById($uId = 0)
    {
        $iQuery = "SELECT uid,trade_name,leagal_name,prefix,gst_number,mobile_number,address,is_create_profile FROM sh_users WHERE uid = {$uId}";
        $iUser = $this->model_api->execute_query($iQuery);
        if (!isset($iUser['data'][0]['uid']))
            return error_res("invalid_data");
        $iUser = $iUser['data'][0];
        if (isset($iUser['avatar']) && !empty($iUser['avatar']))
            $iUser['avatar'] = USER_AVATAR_URL . DS . $uId . DS . $iUser['avatar'];

        return $iUser;
    }

    function getDetail($uId, $param)
    {
        $iUser = $this->userDetailById($uId);
        $filteredUser = filter_data_to_api(self::$defaultFields, $iUser);

        $extras['privacy_policy'] = BASE_URL . 'privacy-policy.html';
        $extras['terms_conditions'] = BASE_URL . 'terms-conditions.html';
        $extras['help_faqs'] = BASE_URL . 'help-faqs.html';
        $extras['email_id'] = 'alpll.1328@gmail.com';
        $extras['google_banner_id_data'] = 'ca-app-pub-2425812586453881/9721005793';
        $extras['google_interstitial_id_data'] = 'ca-app-pub-2425812586453881/3171430386';
        $extras['google_native_banner_id_data'] = '';
        $extras['google_native_advance_id_data'] = '';
        $extras['facebook_native_banner_id_data'] = '2784662788221606_3119849658036249';
        $extras['facebook_ad'] = 0;
        $extras['facebook_app_check'] = 0;

        $deviceId = isset($param['device_id']) ? $param['device_id'] : "";
        $deviceToken = isset($param['device_token']) ? $param['device_token'] : "";
        $deviceType = $this->config->item("os");
        $iUpdateDeviceFields = array('device_id' => $deviceId, 'device_type' => $deviceType, 'device_token' => $deviceToken, "modified_date" => date("Y-m-d H:i:s"));
        $this->model_api->update('sh_users_device', $iUpdateDeviceFields,  array("and" => array("user_id" => $uId)));

        $iRes = success_res("got_profile");
        $iRes['data'] = $filteredUser;
        $iRes['extra'] = $extras;
        return $iRes;
    }

    function getOtherUserDetail($param)
    {
        $uId = isset($param['user_id']) ? $param['user_id'] : user_id();
        $otheruId = isset($param['ouid']) ? $param['ouid'] : 0;
        $iQuery = "SELECT uid,email,avatar,first_name,last_name,mobile_number,last_seen FROM sh_users WHERE uid = {$otheruId}";
        $iUser = $this->model_api->execute_query($iQuery);
        if (!isset($iUser['data'][0]['uid']))
            return parameter_error_res("invalid_ouid");
        $iUser = $iUser['data'][0];
        if (isset($iUser['avatar']) && !empty($iUser['avatar']))
            $iUser['avatar'] = USER_AVATAR_URL . DS . $otheruId . DS . $iUser['avatar'];
        $filteredUser = filter_data_to_api(self::$defaultFields, $iUser);

        $iRes = success_res("got_profile");
        $iRes['data'] = $filteredUser;
        return $iRes;
    }

    function lastSeenUpdate($param)
    {
        $updateFields['last_seen'] = time();
        if (isset($param['ouid'])) {
            $iWhereToID = array("and" => array("uid" => $param['ouid']));
            $this->model_api->update("sh_users", $updateFields, $iWhereToID, 1);
        }
        $iWhereUserID = array("and" => array("uid" => $param['user_id']));
        $this->model_api->update("sh_users", $updateFields, $iWhereUserID, 1);

        $iRes = success_res("profile_updated");
        return $iRes;
    }

    function contactUs($param)
    {
        $uId = isset($param['user_id']) ? $param['user_id'] : user_id();
        $defaultFields = get_default_fields(self::$defaultContactUs);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);

        $iStatus = $this->model_api->add('sh_contact_us', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $iQuery = "SELECT uid FROM sh_users WHERE uid = {$uId}";
        $iUserInfo = $this->model_api->execute_query($iQuery);

        $iSettings = $this->mylib->get_global_settings(array("support_email"));
        $supportEmail = $iSettings['support_email'];

        $subject = $param['subject'];
        $mData['name'] = $param['name'];
        $mData['email'] = $param['email'];
        $mData['description'] = $param['description'];
        $body = $this->load->view('template/email/contactUsEmail', $mData, true);
        $iSent = send_email($supportEmail, $subject, $body);

        $iRes = success_res("success");
        return $iRes;
    }
}
