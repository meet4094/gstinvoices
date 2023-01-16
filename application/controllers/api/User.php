<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("model_user");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function getSettings()
    {
        $getSettings = array("request_token", "support_email", "latest_apk_version_name", "latest_apk_version_code", "apk_file_url", "whats_new_on_latest_apk", "update_skipable", "ads_enable", "ads_network", "admob_open_id", "admob_banner_ads_id", "admob_interstitial_ads_id", "admob_native_ads_placement_id", "ads_clicks", "adx_open_id", "adx_banner_ads_id", "adx_interstitial_ads_id", "adx_native_ads_placement_id", "fcm_url", "fcm_key", "privacy_policy_url", "share_app_link");
        $iSetting = $this->mylib->get_global_settings($getSettings);

        $iResult = success_res();
        $iResult['data'] = $iSetting;
        response($iResult);
    }

    function signUp()
    {
        $iRes = $this->validate->signUp($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_user->signUp($iRes['data']);
        response($iProfile);
    }

    function createProfile()
    {
        $iRes = $this->validate->createProfile($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_user->createProfile($iRes['data']);
        response($iProfile);
    }

    function forgotPassword()
    {
        $iRes = $this->validate->forgotPassword($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->forgotPassword($iRes['data']);
        response($iResult);
    }

    function resetPassword()
    {
        $iRes = $this->validate->resetPassword($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->resetPassword($iRes['data']);
        response($iResult);
    }

    function resendActivationMail()
    {
        $iRes = $this->validate->resendActivationMail($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->resendActivationMail($iRes['data']);
        response($iResult);
    }

    function logIn()
    {
        $iRes = $this->validate->logIn($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iLogin = $this->model_user->logIn($iRes['data']);
        response($iLogin);
    }

    function verifyOtp()
    {
        $iRes = $this->validate->verifyOtp($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iLogin = $this->model_user->verifyOtp($iRes['data']);
        response($iLogin);
    }

    function resendOtp()
    {
        $iRes = $this->validate->resendOtp($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iLogin = $this->model_user->resendOtp($iRes['data']);
        response($iLogin);
    }

    function changePassword()
    {
        $iRes = $this->validate->changePassword($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->changePassword($iRes['data']);
        response($iResult);
    }

    function changeEmail()
    {
        $iRes = $this->validate->changeEmail($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->changeEmail($iRes['data']);
        response($iResult);
    }

    function deactivateAccount()
    {
        $uId = user_id();
        $iResult = $this->model_user->deactivateAccount($uId);
        response($iResult);
    }

    function logOut()
    {
        $token = $this->input->get_request_header("Auth-token");
        $iResult = $this->model_user->logOut($token);
        response($iResult);
    }

    function editProfile()
    {
        $uId = user_id();
        $iRes = $this->validate->editProfile($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);

        $iEdited = $this->model_user->editProfile($iRes['data'], $uId);

        response($iEdited);
    }

    function getProfile()
    {
        $iRes = $this->validate->getProfile($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_user->getProfile($iRes['data']);
        response($iProfile);
    }

    function getDetail()
    {
        $uId = user_id();
        $iProfile = $this->model_user->getDetail($uId, $this->param);
        response($iProfile);
    }

    function getOtherUserDetail()
    {
        $iRes = $this->validate->getOtherUserDetail($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_user->getOtherUserDetail($iRes['data']);
        response($iProfile);
    }

    function lastSeenUpdate()
    {
        $iRes = $this->validate->lastSeenUpdate($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->lastSeenUpdate($iRes['data']);
        response($iResult);
    }

    function contactUs()
    {
        $iRes = $this->validate->contactUs($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_user->contactUs($iRes['data']);
        response($iResult);
    }
}
