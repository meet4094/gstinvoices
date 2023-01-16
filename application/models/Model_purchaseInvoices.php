<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_purchaseInvoices extends CI_Model
{
    public static $defaultFields = array();

    public function __construct()
    {
        parent::__construct();
        self::$defaultFields = array(
            "invoices_date" => array("type" => "string", "default" => "", "protected" => 0),
            "invoices_no" => array("type" => "string", "default" => "", "protected" => 0),
            "shopper_id" => array("type" => "string", "default" => "", "protected" => 0),
            "gst" => array("type" => "string", "default" => "", "protected" => 0),
            "sub_total" => array("type" => "string", "default" => "", "protected" => 0),
            "product_sgst" => array("type" => "string", "default" => "", "protected" => 0),
            "product_cgst" => array("type" => "string", "default" => "", "protected" => 0),
            "round_off" => array("type" => "string", "default" => "", "protected" => 0),
            "invoices_total" => array("type" => "string", "default" => "", "protected" => 0),
            "payment_status" => array("type" => "integer", "default" => 0, "protected" => 1),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
    }

    function addPurchaseInvoicesProduct($Id, $product)
    {
        $productId = $product['productid'];
        $iProduct = $this->model_api->get('product_detailes', array("id,product_code,is_del"), array("and" => array("id" => $productId)));
        $code = $iProduct['data'][0]['product_code'];

        $inserted_fields['purchase_invoices_id'] = $Id;
        $inserted_fields['product_hsn_sac'] = $code;
        $inserted_fields['product_id'] = $product['productid'];
        $inserted_fields['product_quantity'] = $product['quantity'];
        $inserted_fields['product_rate'] = $product['rate'];
        $inserted_fields['total_amount'] = $product['total'];
        $this->model_api->add('purchase_product_detailes', $inserted_fields);

        $iRes = success_res("success");
        return $iRes;
    }

    function updatePurchaseInvoicesProduct($product)
    {
        $productId = $product['productid'];
        $iProduct = $this->model_api->get('product_detailes', array("id,product_code,is_del"), array("and" => array("id" => $productId)));
        $code = $iProduct['data'][0]['product_code'];

        $Id = $product['id'];
        $productName = $product['productid'];
        $productQuantity = $product['quantity'];
        $productRate = $product['rate'];
        $totalAmount = $product['total'];
        $this->model_api->update('purchase_product_detailes', array('product_id' => $productName, 'product_hsn_sac' => $code, 'product_quantity' => $productQuantity, 'product_rate' => $productRate, 'total_amount' => $totalAmount), array("and" => array("id" => $Id)));


        $iRes = success_res("success");
        return $iRes;
    }

    function PurchaseInvoicesDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM purchase_invoices WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");
        foreach ($detailes['data'] as $key => $detaile) {
            $purchaseInvoicesId = $detaile['id'];

            $iQuery = "SELECT id,purchase_invoices_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount FROM purchase_product_detailes WHERE purchase_invoices_id = {$purchaseInvoicesId} AND is_del = 0";
            $iProductDetails = $this->model_api->execute_query($iQuery);
            $detailes['data'][$key]['product'] = $iProductDetails['data'];
        }
        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function addPurchaseInvoices($param)
    {
        $userId = user_id();
        $shoperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';
        $gst = isset($param['gst']) ? $param['gst'] : '';

        $ishopper = $this->model_api->get("shopper", array('id,is_del'), array("and" => array("id" => $shoperId)));
        if (!isset($ishopper['data'][0]['id']) || $ishopper['statuscode'] != 1)
            return error_res("shopper_id_invalide");

        $ishopper = $this->model_api->get("gst_percentage", array('id,gst,is_del'), array("and" => array("gst" => $gst)));
        if (!isset($ishopper['data'][0]['id']) || $ishopper['statuscode'] != 1)
            return error_res("gst_percentage_invalide");

        $defaultFields = get_default_fields(self::$defaultFields);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $iStatus = $this->model_api->add('purchase_invoices', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $Id = $iStatus['data']['id'];
        $param['id'] = $Id;

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $product) {
            $productId = $product['productid'];
            $iProduct = $this->model_api->get('product_detailes', array("id,is_del"), array("and" => array("id" => $productId)));
            if (isset($iProduct['data'][0]['id'])) {
                $this->addPurchaseInvoicesProduct($Id, $product);
            } else {
                return error_res('product_id_invalide');
            }
        }
        $iUser = $this->model_api->get('purchase_invoices', array("id,invoices_date,invoices_no,shopper_id,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->PurchaseInvoicesDetailById($Id);
        $iStatus = success_res("purchase_invoices_details_created");
        return $iStatus;
    }

    function updatePurchaseInvoices($param)
    {
        $purchaseInvoicesId = isset($param['purchase_invoices_id']) ? $param['purchase_invoices_id'] : '';
        $shopperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';

        $iSellId = $this->model_api->get('purchase_invoices', array("id,is_del"), array("and" => array("id" => $purchaseInvoicesId)));
        $iShopper = $this->model_api->get("shopper", array('id'), array("and" => array("id" => $shopperId)));

        if (!isset($iSellId['data'][0]['id']) || $iSellId['statuscode'] != 1)
            return error_res('purchase_invoices_id_invalide');
        if (!isset($iShopper['data'][0]['id']) || $iShopper['statuscode'] != 1)
            return error_res('shopper_id_invalide');

        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);

        $iUpdate = $this->model_api->update('purchase_invoices', $updateFields, array("and" => array("id" => $purchaseInvoicesId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $product) {
            $productpurchaseId = $product['id'];
            $productId = $product['productid'];
            if ($productpurchaseId == "") {
                $this->addPurchaseInvoicesProduct($purchaseInvoicesId, $product);
            } else {
                $iProductPurchaseId = $this->model_api->get('purchase_product_detailes', array("id,is_del"), array("and" => array("id" => $productpurchaseId)));
                if (!isset($iProductPurchaseId['data'][0]['id']) || $iProductPurchaseId['statuscode'] != 1)
                    return error_res('id_invalide');
            }
            $iProduct = $this->model_api->get('product_detailes', array("id,is_del"), array("and" => array("id" => $productId)));
            if (!isset($iProduct['data'][0]['id']) || $iProduct['statuscode'] != 1)
                return error_res('product_id_invalide');
            $this->updatePurchaseInvoicesProduct($product);
        }

        $deleteproductids = $param['delete_id'];
        $deleteidarr = json_decode($deleteproductids, true);
        foreach ($deleteidarr as $key => $deletechallanid) {
            $id = $deletechallanid;
            $iStatus = $this->model_api->delete('purchase_product_detailes', array("and" => array("id" => $id, "is_del" => '0')));
            if ($iStatus['statuscode'] != '1')
                return $iStatus;
        }

        $iUser = $this->PurchaseInvoicesDetailById($purchaseInvoicesId);
        $iData = success_res("purchase_invoices_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deletePurchaseInvoices($param)
    {
        $iStatus = $this->model_api->delete('purchase_invoices', array("and" => array("id" => $param['purchase_invoices_id'], "is_del" => '0')));
        $iStatus = $this->model_api->delete('purchase_product_detailes', array("and" => array("purchase_invoices_id" => $param['purchase_invoices_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("purchase_invoices_delete_successfully");
    }

    function deletePurchaseInvoicesproduct($param)
    {
        $iStatus = $this->model_api->delete('purchase_product_detailes', array("and" => array("id" => $param['purchase_invoice_product_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("purchase_invoices_product_delete_successfully");
    }

    function getPurchaseInvoices($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';

        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE purchase_invoices.is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND name LIKE '%$searchKey%'";

        $userId = user_id();

        $iQuery = "SELECT purchase_invoices.id,purchase_invoices.user_id,purchase_invoices.invoices_date,purchase_invoices.invoices_no,purchase_invoices.shopper_id,purchase_invoices.gst,purchase_invoices.sub_total,purchase_invoices.product_sgst,purchase_invoices.product_cgst,purchase_invoices.round_off,purchase_invoices.invoices_total,purchase_invoices.payment_status,purchase_invoices.created_date,purchase_invoices.modified_date,purchase_invoices.is_del,shopper.trade_name as shopper_name FROM purchase_invoices JOIN shopper ON (purchase_invoices.shopper_id = shopper.id) WHERE purchase_invoices.user_id = {$userId} AND purchase_invoices.is_del = 0 ";
        $ipurchaseInvoicesDetails = $this->model_api->execute_query($iQuery);

        if (!isset($ipurchaseInvoicesDetails['statuscode']) || $ipurchaseInvoicesDetails['statuscode'] != 1)
            return error_res("invalid_data");

        if (isset($ipurchaseInvoicesDetails['data']) && count($ipurchaseInvoicesDetails['data']) > 0) {
            foreach ($ipurchaseInvoicesDetails['data'] as $petkey => $ipurchaseInvoicesDetail) {

                $purchaseInvoicesId = $ipurchaseInvoicesDetail['id'];

                $iQuery = "SELECT purchase_product_detailes.id,purchase_product_detailes.purchase_invoices_id,purchase_product_detailes.product_id,purchase_product_detailes.product_hsn_sac,purchase_product_detailes.product_quantity,purchase_product_detailes.product_rate,purchase_product_detailes.total_amount,product_detailes.product_name as product_name FROM purchase_product_detailes JOIN product_detailes ON (purchase_product_detailes.product_id = product_detailes.id) WHERE purchase_product_detailes.purchase_invoices_id = {$purchaseInvoicesId} AND purchase_product_detailes.is_del = 0";
                $ipurchaseProductDetails = $this->model_api->execute_query($iQuery);

                $ipurchaseInvoicesDetails['data'][$petkey]['product'] = $ipurchaseProductDetails['data'];
            }
        }
        $iRes = success_res("success");
        $iRes = $ipurchaseInvoicesDetails;
        return $iRes;
    }
}
