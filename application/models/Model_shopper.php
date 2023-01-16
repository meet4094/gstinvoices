<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_shopper extends CI_Model
{
    public static $defaultFields = array();

    public function __construct()
    {
        parent::__construct();
        self::$defaultFields = array(
            "trade_name" => array("type" => "string", "default" => "", "protected" => 0),
            "dealer_name" => array("type" => "string", "default" => "", "protected" => 0),
            "mobile_number" => array("type" => "string", "default" => "", "protected" => 0),
            "address" => array("type" => "string", "default" => "", "protected" => 0),
            "gst_number" => array("type" => "string", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
    }

    function shopperDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM shopper WHERE id = {$Id}";
        $iUser = $this->model_api->execute_query($iQuery);
        if (!isset($iUser['data'][0]['id']))
            return error_res("invalid_data");
        $iUser = $iUser['data'][0];
        return $iUser;
    }

    function addShopper($param)
    {

        $userId = user_id();

        $defaultFields = get_default_fields(self::$defaultFields);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $iStatus = $this->model_api->add('shopper', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $Id = $iStatus['data']['id'];
        $param['id'] = $Id;

        $iUser = $this->model_api->get('shopper', array("id,trade_name,dealer_name,mobile_number,address,gst_number,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->shopperDetailById($Id);
        $iStatus = success_res("shopper_details_created");
        return $iStatus;
    }

    function updateShopper($param)
    {
        $shopperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';
        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);

        $iUpdate = $this->model_api->update('shopper', $updateFields, array("and" => array("id" => $shopperId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->shopperDetailById($param['shopper_id']);
        $iData = success_res("shopper_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deleteShopper($param)
    {
        $iStatus = $this->model_api->delete('shopper', array("and" => array("id" => $param['shopper_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("shopper_delete_successfully");
    }

    function getShopper($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';


        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND name LIKE '%$searchKey%'";

        $userId = user_id();

        $iQuery = "SELECT id,user_id,trade_name,dealer_name,mobile_number,address,gst_number,created_date,modified_date,is_del FROM shopper WHERE shopper.user_id = {$userId} AND shopper.is_del = 0 ";
        $iShoppersDetails = $this->model_api->execute_query($iQuery);

        if (!isset($iShoppersDetails['statuscode']) || $iShoppersDetails['statuscode'] != 1)
            return error_res("invalid_data");

        $iRes = success_res("success");
        $iRes = $iShoppersDetails;
        return $iRes;
    }
}
