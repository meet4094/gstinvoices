<?php

class Validate
{

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    function login_admin($param)
    {
        $param = array_map('trim', $param);
        if (!isset($param['email']) || empty($param['email']) || !_email($param['email']))
            return parameter_error_res("invalid_email");
        if (!isset($param['password']) || empty($param['password']))
            return parameter_error_res("password_missing");
        if (strlen($param['password']) < PASSWORD_MIN_LENGTH || strlen($param['password']) > PASSWORD_MAX_LENGTH)
            return parameter_error_res("password_min_max_length_violate", array(PASSWORD_MIN_LENGTH, PASSWORD_MAX_LENGTH));
        $interval_seconds = $this->CI->settings['unlock_login_interval'] * 3600; // hours to seconds
        $unlock_time = time() - $interval_seconds;
        $where = "is_ignore = 0 AND on_date > {$unlock_time}";
        $data = $this->CI->mylib->get_activity("login_try", ip(), "admin", $this->CI->settings['no_of_login_attampt'], $where);
        if (isset($data['data'][0]) && $data['statuscode'] == 1 && !empty($data['data'][0])) {
            if ($this->CI->settings['no_of_login_attampt'] < count($data['data']) + 1) {
                return spamer_res("too_much_attempt", array($this->CI->settings['unlock_login_interval']));
            }
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function forgot_password_admin($param)
    {
        $param = array_map('trim', $param);
        if (!isset($param['email']) || empty($param['email']) || !_email($param['email']))
            return parameter_error_res("invalid_email");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    // User Module
    function signUp($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("type", $keys) || !in_array($param['type'], array(1, 2, 3)))
            return parameter_error_res("invalid_type_of_login_signup");
        if (in_array("email", $keys) && empty($param['email']))
            return parameter_error_res("email_missing");
        if (in_array("email", $keys) && !_email($param['email']))
            return parameter_error_res("invalid_email");
        $type = isset($param['type']) ? $param['type'] : 0;
        if ($type == 1) { // SigUp With Email & Password
            if (!in_array("email", $keys) || empty($param['email']))
                return parameter_error_res("email_missing");
            if ($this->CI->model_user->emailExist($param['email']))
                return parameter_error_res("email_exist");
            if (!in_array("password", $keys) || empty($param['password']))
                return parameter_error_res("password_missing");
            if (strlen($param['password']) < PASSWORD_MIN_LENGTH)
                return parameter_error_res("password_min_max_length_violate", array(PASSWORD_MIN_LENGTH));
        }
        if ($type == 2) { // SigUp With Facebook
            if (!in_array("facebook_id", $keys) || empty($param['facebook_id']))
                return parameter_error_res("thirdparty_id_missing");
            // if (in_array("facebook_id", $keys) && $this->CI->model_user->facebookIdExist($param['facebook_id']))
            //     return parameter_error_res("thirdparty_id_exist");
        }
        if ($type == 3) { // SigUp With Google
            if (!in_array("google_id", $keys) || empty($param['google_id']))
                return parameter_error_res("thirdparty_id_missing");
            // if (in_array("google_id", $keys) && $this->CI->model_user->googleIdExist($param['google_id']))
            //     return parameter_error_res("thirdparty_id_exist");
        }
        if (in_array("first_name", $keys) && empty($param['first_name']))
            return parameter_error_res("first_name_missing");
        if (in_array("last_name", $keys) && empty($param['last_name']))
            return parameter_error_res("last_name_missing");
        if (in_array("invite_by", $keys) && !empty($param['invite_by']))
            $param['invite_by'] = $this->CI->model_user->inviteExist($param['invite_by']);

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function createProfile($param)
    {
        $uId = user_id();
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("email", $keys) && empty($param['email']))
            return parameter_error_res("email_missing");
        if (in_array("email", $keys) && !_email($param['email']))
            return parameter_error_res("invalid_email");
        if (in_array("email", $keys) && $this->CI->model_user->emailByUidExist($param['email'], $uId))
            return parameter_error_res("email_exist");
        if (in_array("mobile_number", $keys) && empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] != 0)
            return parameter_error_res("avatar_missing");
        if (isset($_FILES['avatar']['error']) && $_FILES['avatar']['error'] == 0) {
            $ext = strtolower(substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], ".") + 1));
            $allowed_uploads_extensions = $this->CI->config->item("allow_image_upload_extensions");
            if (!in_array($ext, $allowed_uploads_extensions))
                return parameter_error_res("invalid_image_extension");
        }
        if (in_array("invite_by", $keys) && !empty($param['invite_by']))
            $param['invite_by'] = $this->CI->model_user->inviteExist($param['invite_by']);

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function forgotPassword($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("email", $keys) || empty($param['email']))
            return parameter_error_res("email_missing");
        if (!_email($param['email']))
            return parameter_error_res("invalid_email");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function resetPassword($param)
    {
        if (!isset($param['secret_token']) || empty($param['secret_token']))
            return parameter_error_res("invalid_page");
        if (!isset($param["new_password"]) || empty($param['new_password']))
            return parameter_error_res("password_missing");
        if (strlen($param['new_password']) < PASSWORD_MIN_LENGTH)
            return parameter_error_res("password_min_max_length_violate", array(PASSWORD_MIN_LENGTH));

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function resendActivationMail($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("email", $keys) || empty($param['email']))
            return parameter_error_res("email_missing");
        if (!_email($param['email']))
            return parameter_error_res("invalid_email");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function logIn($param)
    {
        $param = array_map('trim', $param);
        if (!isset($param['mobile_number']) || empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (strlen($param['mobile_number']) < NUMBER_MIN_LENGTH || strlen($param['mobile_number']) > NUMBER_MAX_LENGTH)
            return parameter_error_res("mobile_number_min_max_length_violate", array(NUMBER_MIN_LENGTH, NUMBER_MAX_LENGTH));
        if (isset($data['data'][0]) && $data['statuscode'] == 1 && !empty($data['data'][0])) {
            if ($this->CI->settings['no_of_login_attampt'] < count($data['data']) + 1) {
                return spamer_res("too_much_attempt", array($this->CI->settings['unlock_login_interval']));
            }
        }
        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    function verifyOtp($param)
    {
        $param = array_map('trim', $param);
        if (!isset($param['mobile_number']) || empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (strlen($param['mobile_number']) < NUMBER_MIN_LENGTH || strlen($param['mobile_number']) > NUMBER_MAX_LENGTH)
            return parameter_error_res("mobile_number_min_max_length_violate", array(NUMBER_MIN_LENGTH, NUMBER_MAX_LENGTH));
        if (!isset($param['otp']) || empty($param['otp']))
            return parameter_error_res("otp_missing");
        if (strlen($param['otp']) < OTP_MIN_LENGTH || strlen($param['otp']) > OTP_MAX_LENGTH)
            return parameter_error_res("otp_min_max_length_violate", array(OTP_MIN_LENGTH, OTP_MAX_LENGTH));
        if (isset($data['data'][0]) && $data['statuscode'] == 1 && !empty($data['data'][0])) {
            if ($this->CI->settings['no_of_login_attampt'] < count($data['data']) + 1) {
                return spamer_res("too_much_attempt", array($this->CI->settings['unlock_login_interval']));
            }
        }
        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function resendOtp($param)
    {
        $param = array_map('trim', $param);
        if (!isset($param['mobile_number']) || empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (strlen($param['mobile_number']) < NUMBER_MIN_LENGTH || strlen($param['mobile_number']) > NUMBER_MAX_LENGTH)
            return parameter_error_res("mobile_number_min_max_length_violate", array(NUMBER_MIN_LENGTH, NUMBER_MAX_LENGTH));

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }



    function changeEmail($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("new_email", $keys) || empty($param['new_email']))
            return parameter_error_res("email_missing");
        if (!_email($param['new_email']))
            return parameter_error_res("invalid_email");
        if ($this->CI->model_user->emailExist($param['new_email']))
            return parameter_error_res("email_exist");
        if ($this->CI->model_user->emailChangeExist($param['new_email']))
            return parameter_error_res("email_exist");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function changePassword($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("password", $keys) || empty($param['password']))
            return parameter_error_res("current_password_missing");
        if (strlen($param['password']) < PASSWORD_MIN_LENGTH)
            return parameter_error_res("current_password_min_max_length_violate", array(PASSWORD_MIN_LENGTH));
        if (!in_array("new_password", $keys) || empty($param['new_password']))
            return parameter_error_res("new_password_missing");
        if (strlen($param['new_password']) < PASSWORD_MIN_LENGTH)
            return parameter_error_res("new_password_min_max_length_violate", array(PASSWORD_MIN_LENGTH));
        if ($param["password"] == $param['new_password'])
            return parameter_error_res("same_password");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function editProfile($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        $uId = user_id();
        unset($param['mobile_number']);
        if (!in_array("trade_name", $keys) || empty($param['trade_name']))
            return parameter_error_res("trade_name_missing");
        if (!in_array("leagal_name", $keys) || empty($param['leagal_name']))
            return parameter_error_res("leagal_name_missing");
        if (!in_array("gst_number", $keys) || empty($param['gst_number']))
            return parameter_error_res("gst_number_missing");
        if (!in_array("address", $keys) || empty($param['address']))
            return parameter_error_res("address_missing");
        if (in_array("gst_number", $keys) && $this->CI->model_user->emailByUidExist($param['gst_number'], $uId))
            return parameter_error_res("gst_number_all_ready_exist");
        if (!in_array("prefix", $keys) || empty($param['prefix']))
            return parameter_error_res("prefix_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getProfile($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getOtherUserDetail($param)
    {
        $uId = $param['user_id'] = user_id();
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("ouid", $keys) || empty($param['ouid']) || !is_numeric($param['ouid']) || $uId === $param['ouid'])
            return parameter_error_res("invalid_ouid");
        if (!$this->CI->model_common->userExist($param['ouid']))
            return parameter_error_res("invalid_ouid");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function lastSeenUpdate($param)
    {
        $uId = $param['user_id'] = user_id();
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (isset($param['ouid'])) {
            if (empty($param['ouid']) || !is_numeric($param['ouid']) || ($uId == $param['ouid']))
                return parameter_error_res("invalid_ouid");
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function contactUs($param)
    {
        $uId = $param['user_id'] = user_id();
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("name", $keys) || empty($param['name']))
            return parameter_error_res("name_missing");
        if (!in_array("email", $keys) || empty($param['email']) || !_email($param['email']))
            return parameter_error_res("email_missing");
        if (!in_array("subject", $keys) || empty($param['subject']))
            return parameter_error_res("subject_missing");
        if (!in_array("description", $keys) || empty($param['description']))
            return parameter_error_res("description_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    //---------------------------------------------------------------------------//
    function addCustomer($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("leagel_name", $keys) && empty($param['leagel_name']))
            return parameter_error_res("leagel_name_missing");
        if (!in_array("trade_name", $keys) && empty($param['trade_name']))
            return parameter_error_res("trade_name_missing");
        if (!in_array("dealer_name", $keys) && empty($param['dealer_name']))
            return parameter_error_res("dealer_name_missing");
        if (!in_array("mobile_number", $keys) && empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (!in_array("address", $keys) && empty($param['address']))
            return parameter_error_res("address_missing");
        if (!in_array("place_of_supply", $keys) && empty($param['place_of_supply']))
            return parameter_error_res("place_of_supply_missing");
        if (!in_array("gst_number", $keys) && empty($param['gst_number']))
            return parameter_error_res("gst_number_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateCustomer($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("leagel_name", $keys) && empty($param['leagel_name']))
            return parameter_error_res("leagel_name_missing");
        if (!in_array("trade_name", $keys) && empty($param['trade_name']))
            return parameter_error_res("trade_name_missing");
        if (!in_array("dealer_name", $keys) && empty($param['dealer_name']))
            return parameter_error_res("dealer_name_missing");
        if (!in_array("mobile_number", $keys) && empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (!in_array("address", $keys) && empty($param['address']))
            return parameter_error_res("address_missing");
        if (!in_array("place_of_supply", $keys) && empty($param['place_of_supply']))
            return parameter_error_res("place_of_supply_missing");
        if (!in_array("gst_number", $keys) && empty($param['gst_number']))
            return parameter_error_res("gst_number_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteCustomer($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getCustomer($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    //---------------------------------------------------------------------------//
    function addShopper($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("dealer_name", $keys) && empty($param['dealer_name']))
            return parameter_error_res("dealer_name_missing");
        if (!in_array("trade_name", $keys) && empty($param['trade_name']))
            return parameter_error_res("trade_name_missing");
        if (!in_array("mobile_number", $keys) && empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (!in_array("address", $keys) && empty($param['address']))
            return parameter_error_res("address_missing");
        if (!in_array("gst_number", $keys) && empty($param['gst_number']))
            return parameter_error_res("gst_number_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateShopper($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("dealer_name", $keys) && empty($param['dealer_name']))
            return parameter_error_res("dealer_name_missing");
        if (!in_array("trade_name", $keys) && empty($param['trade_name']))
            return parameter_error_res("trade_name_missing");
        if (!in_array("mobile_number", $keys) && empty($param['mobile_number']))
            return parameter_error_res("mobile_number_missing");
        if (!in_array("address", $keys) && empty($param['address']))
            return parameter_error_res("address_missing");
        if (!in_array("gst_number", $keys) && empty($param['gst_number']))
            return parameter_error_res("gst_number_missing");
        if (!in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteShopper($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getShopper($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    //---------------------------------------------------------------------------//
    function addProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("product_name", $keys) && empty($param['product_name']))
            return parameter_error_res("product_name_missing");
        if (!in_array("product_code", $keys) && empty($param['product_code']))
            return parameter_error_res("product_code_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("product_name", $keys) && empty($param['product_name']))
            return parameter_error_res("product_name_missing");
        if (!in_array("product_code", $keys) && empty($param['product_code']))
            return parameter_error_res("product_code_missing");
        if (!in_array("product_id", $keys) && empty($param['product_id']))
            return parameter_error_res("_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("product_id", $keys) && empty($param['product_id']))
            return parameter_error_res("product_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    //---------------------------------------------------------------------------//
    function addSellChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("challan_date", $keys) && empty($param['challan_date']))
            return parameter_error_res("challan_date_missing");

        if (!in_array("product", $keys) && empty($param['productid,quantity,rate,total']))
            return parameter_error_res("product_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $key => $product) {
            if (!isset($product['productid']) && empty($param['productid']))
                return parameter_error_res("product_id_missing");
            if (!isset($product['quantity']) && empty($param['quantity']))
                return parameter_error_res("product_quantity_missing");
            if (!isset($product['rate']) && empty($param['rate']))
                return parameter_error_res("product_rate_missing");
            if (!isset($product['total']) && empty($param['total']))
                return parameter_error_res("total_missing");
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateSellChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_id", $keys) && empty($param['challan_id']))
            return parameter_error_res("challan_id_missing");
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("challan_date", $keys) && empty($param['challan_date']))
            return parameter_error_res("challan_date_missing");

        if (!in_array("product", $keys) && empty($param['id,productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (in_array("delete_id", $keys) && empty($param['delete_id']))
            return parameter_error_res("delete_id_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $valkey => $val) {
            foreach ($arr as $key => $product) {
                if ($arr[$valkey]['id'] === "") {
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                } else {
                    if (!isset($product['id']) && empty($param['id']))
                        return parameter_error_res("id_missing");
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                }
            }
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteSellChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_id", $keys) && empty($param['challan_id']))
            return parameter_error_res("challan_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteSellChallanproduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_invoice_product_id", $keys) && empty($param['challan_invoice_product_id']))
            return parameter_error_res("challan_invoice_product_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getSellChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function addPurchaseChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("challan_no", $keys) && empty($param['challan_no']))
            return parameter_error_res("challan_no_missing");
        if (!in_array("challan_date", $keys) && empty($param['challan_date']))
            return parameter_error_res("challan_date_missing");

        if (!in_array("product", $keys) && empty($param['productid,quantity,rate,total']))
            return parameter_error_res("product_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $key => $product) {
            if (!isset($product['productid']) && empty($param['productid']))
                return parameter_error_res("product_id_missing");
            if (!isset($product['quantity']) && empty($param['quantity']))
                return parameter_error_res("product_quantity_missing");
            if (!isset($product['rate']) && empty($param['rate']))
                return parameter_error_res("product_rate_missing");
            if (!isset($product['total']) && empty($param['total']))
                return parameter_error_res("total_missing");
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updatePurchaseChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_id", $keys) && empty($param['challan_id']))
            return parameter_error_res("challan_id_missing");
        if (!in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("challan_date", $keys) && empty($param['challan_date']))
            return parameter_error_res("challan_date_missing");

        if (!in_array("product", $keys) && empty($param['id,productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (in_array("delete_id", $keys) && empty($param['delete_id']))
            return parameter_error_res("delete_id_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $valkey => $val) {
            foreach ($arr as $key => $product) {
                if ($arr[$valkey]['id'] === "") {
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                } else {
                    if (!isset($product['id']) && empty($param['id']))
                        return parameter_error_res("id_missing");
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                }
            }
        }

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deletePurchaseChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_id", $keys) && empty($param['challan_id']))
            return parameter_error_res("challan_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deletePurchaseChallanproduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("challan_invoice_product_id", $keys) && empty($param['challan_invoice_product_id']))
            return parameter_error_res("challan_invoice_product_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getPurchaseChallan($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    //---------------------------------------------------------------------------//
    function addPurchaseInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);

        if (!in_array("invoices_date", $keys) || empty($param['invoices_date']))
            return parameter_error_res("invoices_date_missing");
        if (!in_array("invoices_no", $keys) || empty($param['invoices_no']))
            return parameter_error_res("invoices_no_missing");
        if (!in_array("shopper_id", $keys) || empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("gst", $keys) || empty($param['gst']))
            return parameter_error_res("gst_missing");
        if (!in_array("product", $keys) && empty($param['productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $key => $product) {
            if (!isset($product['productid']) && empty($param['productid']))
                return parameter_error_res("product_id_missing");
            if (!isset($product['quantity']) && empty($param['quantity']))
                return parameter_error_res("product_quantity_missing");
            if (!isset($product['rate']) && empty($param['rate']))
                return parameter_error_res("product_rate_missing");
            if (!isset($product['total']) && empty($param['total']))
                return parameter_error_res("total_missing");
        }
        if (!in_array("sub_total", $keys) || empty($param['sub_total']))
            return parameter_error_res("sub_total_missing");
        if (!in_array("product_sgst", $keys) || empty($param['product_sgst']))
            return parameter_error_res("product_sgst_missing");
        if (!in_array("product_cgst", $keys) || empty($param['product_cgst']))
            return parameter_error_res("product_cgst_missing");
        if (!in_array("round_off", $keys) && empty($param['round_off']))
            return parameter_error_res("round_off_missing");

        if (!in_array("invoices_total", $keys) || empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");


        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updatePurchaseInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("purchase_invoices_id", $keys) && empty($param['purchase_invoices_id']))
            return parameter_error_res("purchase_invoices_id_missing");
        if (!in_array("invoices_date", $keys) && empty($param['invoices_date']))
            return parameter_error_res("invoices_date_missing");
        if (!in_array("invoices_no", $keys) && empty($param['invoices_no']))
            return parameter_error_res("invoices_no_missing");
        if (!in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("gst", $keys) && empty($param['gst']))
            return parameter_error_res("gst_missing");
        if (!in_array("product", $keys) && empty($param['id,productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");
        if (in_array("delete_id", $keys) && empty($param['delete_id']))
            return parameter_error_res("delete_id_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $valkey => $val) {
            foreach ($arr as $key => $product) {
                if ($arr[$valkey]['id'] === "") {
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                } else {
                    if (!isset($product['id']) && empty($param['id']))
                        return parameter_error_res("id_missing");
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                }
            }
        }
        if (!in_array("sub_total", $keys) && empty($param['sub_total']))
            return parameter_error_res("sub_total_missing");
        if (!in_array("product_sgst", $keys) && empty($param['product_sgst']))
            return parameter_error_res("product_sgst_missing");
        if (!in_array("product_cgst", $keys) && empty($param['product_cgst']))
            return parameter_error_res("product_cgst_missing");
        if (!in_array("round_off", $keys) && empty($param['round_off']))
            return parameter_error_res("round_off_missing");
        if (!in_array("invoices_total", $keys) && empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deletePurchaseInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("purchase_invoices_id", $keys) && empty($param['purchase_invoices_id']))
            return parameter_error_res("purchase_invoices_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deletePurchaseInvoicesProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("purchase_invoice_product_id", $keys) && empty($param['purchase_invoice_product_id']))
            return parameter_error_res("purchase_invoice_product_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getPurchaseInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    //---------------------------------------------------------------------------//
    function addSellInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("invoices_date", $keys) && empty($param['invoices_date']))
            return parameter_error_res("invoices_date_missing");
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("gst", $keys) && empty($param['gst']))
            return parameter_error_res("gst_missing");
        if (!in_array("product", $keys) && empty($param['productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $key => $product) {
            if (!isset($product['productid']) && empty($param['productid']))
                return parameter_error_res("product_id_missing");
            if (!isset($product['quantity']) && empty($param['quantity']))
                return parameter_error_res("product_quantity_missing");
            if (!isset($product['rate']) && empty($param['rate']))
                return parameter_error_res("product_rate_missing");
            if (!isset($product['total']) && empty($param['total']))
                return parameter_error_res("total_missing");
        }
        if (!in_array("sub_total", $keys) && empty($param['sub_total']))
            return parameter_error_res("sub_total_missing");
        if (!in_array("product_sgst", $keys) && empty($param['product_sgst']))
            return parameter_error_res("product_sgst_missing");
        if (!in_array("product_cgst", $keys) && empty($param['product_cgst']))
            return parameter_error_res("product_cgst_missing");
        if (!in_array("round_off", $keys) && empty($param['round_off']))
            return parameter_error_res("round_off_missing");

        if (!in_array("invoices_total", $keys) && empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");


        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateSellInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("sell_invoices_id", $keys) && empty($param['sell_invoices_id']))
            return parameter_error_res("sell_invoices_id_missing");
        if (!in_array("invoices_date", $keys) && empty($param['invoices_date']))
            return parameter_error_res("invoices_date_missing");
        if (!in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("gst", $keys) && empty($param['gst']))
            return parameter_error_res("gst_missing");
        if (!in_array("product", $keys) && empty($param['id,productid,quantity,rate,total']))
            return parameter_error_res("product_missing");
        if (!in_array("is_del", $keys) && empty($param['is_del']))
            return parameter_error_res("is_del_missing");
        if (in_array("delete_id", $keys) && empty($param['delete_id']))
            return parameter_error_res("delete_id_missing");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $valkey => $val) {
            foreach ($arr as $key => $product) {
                if ($arr[$valkey]['id'] === "") {
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                } else {
                    if (!isset($product['id']) && empty($param['id']))
                        return parameter_error_res("id_missing");
                    if (!isset($product['productid']) && empty($param['productid']))
                        return parameter_error_res("product_id_missing");
                    if (!isset($product['quantity']) && empty($param['quantity']))
                        return parameter_error_res("product_quantity_missing");
                    if (!isset($product['rate']) && empty($param['rate']))
                        return parameter_error_res("product_rate_missing");
                    if (!isset($product['total']) && empty($param['total']))
                        return parameter_error_res("total_missing");
                }
            }
        }
        if (!in_array("sub_total", $keys) && empty($param['sub_total']))
            return parameter_error_res("sub_total_missing");
        if (!in_array("product_sgst", $keys) && empty($param['product_sgst']))
            return parameter_error_res("product_sgst_missing");
        if (!in_array("product_cgst", $keys) && empty($param['product_cgst']))
            return parameter_error_res("product_cgst_missing");
        if (!in_array("round_off", $keys) && empty($param['round_off']))
            return parameter_error_res("round_off_missing");
        if (!in_array("invoices_total", $keys) && empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteSellInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("sell_invoices_id", $keys) && empty($param['sell_invoices_id']))
            return parameter_error_res("sell_invoices_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteSellInvoicesProduct($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("sell_invoice_product_id", $keys) && empty($param['sell_invoice_product_id']))
            return parameter_error_res("sell_invoice_product_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getSellInvoices($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    // PAYMENT//

    function addSellInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("customer_id", $keys) || empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");
        if (!in_array("invoices_no", $keys) || empty($param['invoices_no']))
            return parameter_error_res("invoices_no_missing");
        if (!in_array("payment_mode", $keys) || empty($param['payment_mode']))
            return parameter_error_res("payment_mode_missing");
        if (!in_array("payment_date", $keys) || empty($param['payment_date']))
            return parameter_error_res("payment_date_missing");
        if (!in_array("invoices_total", $keys) || empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");
        if (!in_array("transfer_amount", $keys) && empty($param['transfer_amount']))
            return parameter_error_res("transfer_amount_missing");
        if (!in_array("amount", $keys) || empty($param['amount']))
            return parameter_error_res("amount_missing");
        if (in_array("cheque_number", $keys) && empty($param['cheque_number']))
            return parameter_error_res("cheque_number_missing");
        if (in_array("bank_detail", $keys) && empty($param['bank_detail']))
            return parameter_error_res("bank_detail_missing");
        if (in_array("transaction_detail", $keys) && empty($param['transaction_detail']))
            return parameter_error_res("transaction_detail_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updateSellInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("sell_invoices_payment_id", $keys) || empty($param['sell_invoices_payment_id']))
            return parameter_error_res("sell_invoices_payment_id_missing");
        if (!in_array("payment_mode", $keys) || empty($param['payment_mode']))
            return parameter_error_res("payment_mode_missing");
        if (!in_array("payment_date", $keys) || empty($param['payment_date']))
            return parameter_error_res("payment_date_missing");
        if (!in_array("invoices_total", $keys) || empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");
        if (!in_array("transfer_amount", $keys) && empty($param['transfer_amount']))
            return parameter_error_res("transfer_amount_missing");
        if (!in_array("amount", $keys) || empty($param['amount']))
            return parameter_error_res("amount_missing");
        if (in_array("cheque_number", $keys) && empty($param['cheque_number']))
            return parameter_error_res("cheque_number_missing");
        if (in_array("bank_detail", $keys) && empty($param['bank_detail']))
            return parameter_error_res("bank_detail_missing");
        if (in_array("transaction_detail", $keys) && empty($param['transaction_detail']))
            return parameter_error_res("transaction_detail_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deleteSellInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("sell_invoices_payment_id", $keys) || empty($param['sell_invoices_payment_id']))
            return parameter_error_res("sell_invoices_payment_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getSellInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;
        if (in_array("customer_id", $keys) && empty($param['customer_id']))
            return parameter_error_res("customer_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function addPurchaseInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("shopper_id", $keys) || empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");
        if (!in_array("invoices_no", $keys) || empty($param['invoices_no']))
            return parameter_error_res("invoices_no_missing");
        if (!in_array("payment_mode", $keys) || empty($param['payment_mode']))
            return parameter_error_res("payment_mode_missing");
        if (!in_array("payment_date", $keys) || empty($param['payment_date']))
            return parameter_error_res("payment_date_missing");
        if (!in_array("invoices_total", $keys) || empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");
        if (!in_array("transfer_amount", $keys) && empty($param['transfer_amount']))
            return parameter_error_res("transfer_amount_missing");
        if (!in_array("amount", $keys) || empty($param['amount']))
            return parameter_error_res("amount_missing");
        if (in_array("cheque_number", $keys) && empty($param['cheque_number']))
            return parameter_error_res("cheque_number_missing");
        if (in_array("bank_detail", $keys) && empty($param['bank_detail']))
            return parameter_error_res("bank_detail_missing");
        if (in_array("transaction_detail", $keys) && empty($param['transaction_detail']))
            return parameter_error_res("transaction_detail_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function updatePurchaseInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("purchase_invoices_payment_id", $keys) || empty($param['purchase_invoices_payment_id']))
            return parameter_error_res("purchase_invoices_payment_id_missing");
        if (!in_array("payment_mode", $keys) || empty($param['payment_mode']))
            return parameter_error_res("payment_mode_missing");
        if (!in_array("payment_date", $keys) || empty($param['payment_date']))
            return parameter_error_res("payment_date_missing");
        if (!in_array("invoices_total", $keys) || empty($param['invoices_total']))
            return parameter_error_res("invoices_total_missing");
        if (!in_array("transfer_amount", $keys) && empty($param['transfer_amount']))
            return parameter_error_res("transfer_amount_missing");
        if (!in_array("amount", $keys) || empty($param['amount']))
            return parameter_error_res("amount_missing");
        if (in_array("cheque_number", $keys) && empty($param['cheque_number']))
            return parameter_error_res("cheque_number_missing");
        if (in_array("bank_detail", $keys) && empty($param['bank_detail']))
            return parameter_error_res("bank_detail_missing");
        if (in_array("transaction_detail", $keys) && empty($param['transaction_detail']))
            return parameter_error_res("transaction_detail_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function deletePurchaseInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (!in_array("purchase_invoices_payment_id", $keys) || empty($param['purchase_invoices_payment_id']))
            return parameter_error_res("purchase_invoices_payment_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getPurchaseInvoicePayment($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;
        if (in_array("shopper_id", $keys) && empty($param['shopper_id']))
            return parameter_error_res("shopper_id_missing");

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }

    function getStockReport($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
    function getProfitReport($param)
    {
        $param = array_map('trim', $param);
        $keys = array_keys($param);
        if (in_array("search_key", $keys) && empty($param['search_key']))
            return parameter_error_res("search_key_missing");
        if (!in_array("start", $keys) || empty($param['start']))
            $param['start'] = 0;
        if (!in_array("len", $keys) || empty($param['len']))
            $param['len'] = 10;

        $iRes = success_res("validation_ok");
        $iRes['data'] = $param;
        return $iRes;
    }
}
