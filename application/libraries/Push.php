<?php

class Push {

    public $fcm_url = "";
    public $fcm_key = "";

    public function __construct() {
        $getSettings = array("fcm_url","fcm_key");
        $iSetting = $this->mylib->get_global_settings($getSettings);
        $this->fcm_url = $iSetting['fcm_url'];
        $this->fcm_key = $iSetting['fcm_key'];

    }

    public function fcm($deviceToken = array(), $badge = 0, $ntype = 0, $meta = array(), $sound = '') {
        if (!isset($badge) || !isset($ntype) || empty($meta) || !isset($sound))
            return error_res("Push data not properly configured!");
        try {
            if (is_production()) {
                $registrationIDs = $deviceToken;
                if (!is_array($deviceToken))
                    $registrationIDs = array($deviceToken);

                $headers = array(
                    'Authorization: key=' . $this->fcm_key,
                    'Content-Type: application/json'
                );

                if(count($registrationIDs) > 1000) {
                    $registrationIDs = array_chunk($registrationIDs, 1000);
                    foreach ($registrationIDs as $registrationID) {
                        $fields = array(
                            'registration_ids' => $registrationID,
                            'data' => $meta,
                        );

                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $this->fcm_url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        $result = curl_exec($ch);
                        print_push_rlog('Android', json_encode($fields), $result);
                        curl_close($ch);

                        $curl_result = json_decode($result, true);
                        $errorRegistrationIDs = [];
                        $successRegistrationIDs = [];
                        foreach ($curl_result['results'] as $key => $res) {
                            if (isset($res['error'])) {
                                $errorRegistrationIDs[] = $registrationID[$key];
                            } else {
                                $successRegistrationIDs[] = $registrationID[$key];
                            }
                        }
                        if (count($errorRegistrationIDs) > 0) {
                            $errorRegistrationIDs = "'".implode ("','", $errorRegistrationIDs)."'";
                        }
                        if (count($successRegistrationIDs) > 0) {
                            $successRegistrationIDs = "'".implode ("','", $successRegistrationIDs)."'";
                        }
                    }
                } else {
                    $fields = array(
                        'registration_ids' => $registrationIDs,
                        'data' => $meta,
                    );                    

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $this->fcm_url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    $result = curl_exec($ch);
                    print_push_rlog('Android', json_encode($fields), $result);
                    curl_close($ch);

                    $curl_result = json_decode($result, true);
                    $errorRegistrationIDs = [];
                    $successRegistrationIDs = [];
                    foreach ($curl_result['results'] as $key => $res) {
                        if (isset($res['error'])) {
                            $errorRegistrationIDs[] = $registrationIDs[$key];
                        } else {
                            $successRegistrationIDs[] = $registrationIDs[$key];
                        }
                    }
                    if (count($errorRegistrationIDs) > 0) {
                        $errorRegistrationIDs = "'".implode ("','", $errorRegistrationIDs)."'";
                    }
                    if (count($successRegistrationIDs) > 0) {
                        $successRegistrationIDs = "'".implode ("','", $successRegistrationIDs)."'";
                    }
                }
            }
        } catch (Exception $e) {
            print_push_rlog('Android', json_encode($fields), json_encode($e->getMessage()));
        }
        return success_res("success");
    }
}

?>
