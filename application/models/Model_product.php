<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_product extends CI_Model
{
    public static $defaultFields = array();

    public function __construct()
    {
        parent::__construct();
        self::$defaultFields = array(
            "product_name" => array("type" => "string", "default" => "", "protected" => 0),
            "product_code" => array("type" => "string", "default" => "", "protected" => 0),
            "user_id" => array("type" => "integer", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
    }

    function productDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM product_detailes WHERE id = {$Id}";
        $iUser = $this->model_api->execute_query($iQuery);
        if (!isset($iUser['data'][0]['id']))
            return error_res("invalid_data");
        $iUser = $iUser['data'][0];
        return $iUser;
    }

    function addProduct($param)
    {
        $userId = user_id();

        $defaultFields = get_default_fields(self::$defaultFields);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $iStatus = $this->model_api->add('product_detailes', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $Id = $iStatus['data']['id'];
        $param['id'] = $Id;

        $iUser = $this->model_api->get('product_detailes', array("id,product_name,product_code,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->productDetailById($Id);
        $iStatus = success_res("product_details_created");
        return $iStatus;
    }

    function updateProduct($param)
    {
        $productId = isset($param['product_id']) ? $param['product_id'] : '';
        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);

        $iUpdate = $this->model_api->update('product_detailes', $updateFields, array("and" => array("id" => $productId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->productDetailById($param['product_id']);
        $iData = success_res("product_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deleteProduct($param)
    {
        $iStatus = $this->model_api->delete('product_detailes', array("and" => array("id" => $param['product_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("product_delete_successfully");
    }

    function getProduct($param)
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

        $iQuery = "SELECT id,user_id,product_name,product_code,created_date,modified_date,is_del FROM product_detailes WHERE product_detailes.user_id = {$userId} AND product_detailes.is_del = 0 ";
        $iproductsDetails = $this->model_api->execute_query($iQuery);

        if (!isset($iproductsDetails['statuscode']) || $iproductsDetails['statuscode'] != 1)
            return error_res("invalid_data");

        $iRes = success_res("success");
        $iRes = $iproductsDetails;
        return $iRes;
    }
}
