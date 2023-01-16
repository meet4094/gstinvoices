<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_common extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function deleteDir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != "." && $object != "..") { 
                    if (is_dir($dir."/".$object))
                        $this->deleteDir($dir."/".$object);
                    else
                        unlink($dir."/".$object); 
                } 
            }
            rmdir($dir); 
        } 
    }

    function userExist($uId) {
        $iUser = $this->model_api->get("sh_users", array("COUNT(uid) AS cnt"), array("and" => array("uid" => $uId)));
        if (!isset($iUser['data'][0]['cnt']) || $iUser['data'][0]['cnt'] != 0) 
            return TRUE;
        return FALSE;
    }
}