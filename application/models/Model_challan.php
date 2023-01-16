<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_challan extends CI_Model
{
    public static $defaultFieldssell = array();
    public static $defaultFieldspurchase = array();

    public function __construct()
    {
        parent::__construct();
        self::$defaultFieldssell = array(
            "customer_id" => array("type" => "string", "default" => "", "protected" => 0),
            "prefix" => array("type" => "string", "default" => "", "protected" => 0),
            "challan_no" => array("type" => "string", "default" => "", "protected" => 0),
            "challan_date" => array("type" => "string", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
        self::$defaultFieldspurchase = array(
            "shopper_id" => array("type" => "string", "default" => "", "protected" => 0),
            "challan_no" => array("type" => "string", "default" => "", "protected" => 0),
            "challan_date" => array("type" => "string", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
    }

    function addSellChallanproduct($Id, $product)
    {
        $inserted_fields['challan_id'] = $Id;
        $inserted_fields['product_id'] = $product['productid'];
        $inserted_fields['product_quantity'] = $product['quantity'];
        $inserted_fields['product_rate'] = $product['rate'];
        $inserted_fields['total_amount'] = $product['total'];
        $this->model_api->add('sell_challan_product', $inserted_fields);

        $iRes = success_res("success");
        return $iRes;
    }

    function updateSellChallanproduct($product)
    {
        $Id = $product['id'];
        $productName = $product['productid'];
        $productQuantity = $product['quantity'];
        $productRate = $product['rate'];
        $totalAmount = $product['total'];
        $this->model_api->update('sell_challan_product', array('product_id' => $productName, 'product_quantity' => $productQuantity, 'product_rate' => $productRate, 'total_amount' => $totalAmount), array("and" => array("id" => $Id)));
        $iRes = success_res("success");
        return $iRes;
    }

    function sellchallanDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM sell_challan WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");
        foreach ($detailes['data'] as $key => $detaile) {
            $challanId = $detaile['id'];

            $iQuery = "SELECT id,challan_id,product_id,product_quantity,product_rate,total_amount FROM sell_challan_product WHERE challan_id = {$challanId} AND is_del = 0";
            $iProductDetails = $this->model_api->execute_query($iQuery);
            $detailes['data'][$key]['product'] = $iProductDetails['data'];
        }
        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function addPurchaseChallanproduct($Id, $product)
    {
        $inserted_fields['challan_id'] = $Id;
        $inserted_fields['product_id'] = $product['productid'];
        $inserted_fields['product_quantity'] = $product['quantity'];
        $inserted_fields['product_rate'] = $product['rate'];
        $inserted_fields['total_amount'] = $product['total'];
        $this->model_api->add('purchase_challan_product', $inserted_fields);

        $iRes = success_res("success");
        return $iRes;
    }

    function updatePurchaseChallanproduct($product)
    {
        $Id = $product['id'];
        $productName = $product['productid'];
        $productQuantity = $product['quantity'];
        $productRate = $product['rate'];
        $totalAmount = $product['total'];
        $this->model_api->update('purchase_challan_product', array('product_id' => $productName, 'product_quantity' => $productQuantity, 'product_rate' => $productRate, 'total_amount' => $totalAmount), array("and" => array("id" => $Id)));
        $iRes = success_res("success");
        return $iRes;
    }

    function purchasechallanDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM purchase_challan WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");
        foreach ($detailes['data'] as $key => $detaile) {
            $challanId = $detaile['id'];

            $iQuery = "SELECT id,challan_id,product_id,product_quantity,product_rate,total_amount FROM purchase_challan_product WHERE challan_id = {$challanId} AND is_del = 0";
            $iProductDetails = $this->model_api->execute_query($iQuery);
            $detailes['data'][$key]['product'] = $iProductDetails['data'];
        }
        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function addSellChallan($param)
    {
        $userId = user_id();
        $userdetailes = user_detail();
        $customerId = isset($param['customer_id']) ? $param['customer_id'] : '';
        $invoices_date = isset($param['invoices_date']) ? $param['invoices_date'] : '';

        $iQry = "SELECT id,user_id,challan_no,challan_date FROM sell_challan WHERE user_id = {$userId} ORDER BY id DESC LIMIT 1";
        $iUsers = $this->model_api->execute_query($iQry);
        // $invoiceDate = date("Y-m-d", strtotime($invoices_date));
        // $currentDate = date("Y-m-d", strtotime(date('Y') . '-03-31'));
        // $fastDate = date("Y-m-d", strtotime(date('Y') . '-01-01'));

        $isNewFinancialYear = false;
        if (empty($iUsers['data'])) {
            $isNewFinancialYear = true;
        }
        // else {
        //     if ($currentDate < $invoiceDate) {
        //         $isNewFinancialYear = true;
        //     } else if (($currentDate > $invoiceDate) && ($fastDate <= $invoiceDate)) {
        //         $isNewFinancialYear = false;
        //     } else if ($currentDate > $invoiceDate) {
        //         $isNewFinancialYear = false;
        //     }
        // }

        if ($isNewFinancialYear == true) {
            $invoicesno = '1';
        } else {
            $invoicesno = $iUsers['data'][0]['challan_no'] + 1;
        }

        $icustomer = $this->model_api->get("customer", array('id'), array("and" => array("id" => $customerId)));
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("customer_id_invalide");

        $defaultFieldssell = get_default_fields(self::$defaultFieldssell);
        $iArrayDiff = array_diff_key($defaultFieldssell, $param);
        $insertedFields = array_intersect_key($param, $defaultFieldssell);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $insertedFields['prefix'] = $userdetailes['prefix'];
        $insertedFields['challan_no'] = $invoicesno;
        $iStatus = $this->model_api->add('sell_challan', $insertedFields);
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

                $this->addSellChallanproduct($Id, $product);
            } else {
                return error_res('product_id_invalide');
            }
        }

        $iUser = $this->model_api->get('sell_challan', array("id,customer_id,challan_date,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->sellchallanDetailById($Id);
        $iStatus = success_res("challan_details_created");
        return $iStatus;
    }

    function updateSellChallan($param)
    {
        $challanId = isset($param['challan_id']) ? $param['challan_id'] : '';
        $customerId = isset($param['customer_id']) ? $param['customer_id'] : '';

        $ichallan = $this->model_api->get("sell_challan", array('id'), array("and" => array("id" => $challanId)));
        $icustomer = $this->model_api->get("customer", array('id'), array("and" => array("id" => $customerId)));

        if (!isset($ichallan['data'][0]['id']) || $ichallan['statuscode'] != 1)
            return error_res("challan_id_invalide");
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("customer_id_invalide");

        $defaultFieldssell = get_default_fields(self::$defaultFieldssell);
        $updateFields = array_intersect_key($param, $defaultFieldssell);
        $iUpdate = $this->model_api->update('sell_challan', $updateFields, array("and" => array("id" => $challanId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $product) {
            $Id = $product['id'];
            $productId = $product['productid'];

            if ($Id == "") {
                $this->addSellChallanproduct($challanId, $product);
            } else {
                $iProduct = $this->model_api->get('sell_challan_product', array("id,is_del"), array("and" => array("id" => $Id)));
                if (!isset($iProduct['data'][0]['id']) || $icustomer['statuscode'] != 1)
                    return error_res('id_invalide');
            }
            $iProductId = $this->model_api->get('product_detailes', array("id,is_del"), array("and" => array("id" => $productId)));
            if (!isset($iProductId['data'][0]['id']) || $icustomer['statuscode'] != 1)
                return error_res('product_id_invalide');
            $this->updateSellChallanproduct($product);
        }

        $deleteproductids = $param['delete_id'];
        $deleteidarr = json_decode($deleteproductids, true);
        foreach ($deleteidarr as $key => $deletechallanid) {
            $id = $deletechallanid;
            $iStatus = $this->model_api->delete('sell_challan_product', array("and" => array("id" => $id, "is_del" => '0')));
            if ($iStatus['statuscode'] != '1')
                return $iStatus;
        }

        $iUser = $this->sellchallanDetailById($challanId);
        $iData = success_res("challan_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deleteSellChallan($param)
    {
        $iStatus = $this->model_api->delete('sell_challan', array("and" => array("id" => $param['challan_id'], "is_del" => '0')));
        $iStatus = $this->model_api->delete('sell_challan_product', array("and" => array("challan_id" => $param['challan_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("challan_delete_successfully");
    }

    function deleteSellChallanproduct($param)
    {
        $iStatus = $this->model_api->delete('sell_challan_product', array("and" => array("id" => $param['challan_invoice_product_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("challan_invoices_product_delete_successfully");
    }

    function getSellChallan($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';


        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE sell_challan.is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND sell_challan.name LIKE '%$searchKey%'";

        $userId = user_id();

        $iQuery = "SELECT sell_challan.id,sell_challan.user_id,sell_challan.customer_id,sell_challan.challan_date,CONCAT(sell_challan.prefix,'',sell_challan.challan_no) AS challan_no,sell_challan.created_date,sell_challan.modified_date,sell_challan.is_del,customer.trade_name as customer_name FROM sell_challan JOIN customer ON (sell_challan.customer_id = customer.id) WHERE sell_challan.user_id = {$userId} ORDER BY sell_challan.id DESC " . $iLimit;
        $isellchallansDetails = $this->model_api->execute_query($iQuery);

        if (!isset($isellchallansDetails['statuscode']) || $isellchallansDetails['statuscode'] != 1)
            return error_res("invalid_data");

        if (isset($isellchallansDetails['data']) && count($isellchallansDetails['data']) > 0) {
            foreach ($isellchallansDetails['data'] as $petkey => $ichallansDetail) {
                $challanId = $ichallansDetail['id'];

                $iQuery = "SELECT sell_challan_product.id,sell_challan_product.challan_id,sell_challan_product.product_id,sell_challan_product.product_quantity,sell_challan_product.product_rate,sell_challan_product.total_amount,product_detailes.product_name as product_name FROM sell_challan_product JOIN product_detailes ON (sell_challan_product.product_id = product_detailes.id) WHERE sell_challan_product.challan_id = {$challanId} AND sell_challan_product.is_del = 0";
                $ichallanProductDetails = $this->model_api->execute_query($iQuery);
                $isellchallansDetails['data'][$petkey]['product'] = $ichallanProductDetails['data'];
            }
        }
        $iRes = success_res("success");
        $iRes = $isellchallansDetails;
        return $iRes;
    }

    //Purchse Challan

    function addPurchaseChallan($param)
    {
        $userId = user_id();
        $shopperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';

        $icustomer = $this->model_api->get("shopper", array('id'), array("and" => array("id" => $shopperId)));
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("shopper_id_invalide");

        $defaultFieldspurchase = get_default_fields(self::$defaultFieldspurchase);
        $iArrayDiff = array_diff_key($defaultFieldspurchase, $param);
        $insertedFields = array_intersect_key($param, $defaultFieldspurchase);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $iStatus = $this->model_api->add('purchase_challan', $insertedFields);
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

                $this->addPurchaseChallanproduct($Id, $product);
            } else {
                return error_res('product_id_invalide');
            }
        }

        $iUser = $this->model_api->get('purchase_challan', array("id,shopper_id,challan_date,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->purchasechallanDetailById($Id);
        $iStatus = success_res("challan_details_created");
        return $iStatus;
    }

    function updatePurchaseChallan($param)
    {
        $challanId = isset($param['challan_id']) ? $param['challan_id'] : '';
        $customerId = isset($param['shopper_id']) ? $param['shopper_id'] : '';

        $ichallan = $this->model_api->get("purchase_challan", array('id'), array("and" => array("id" => $challanId)));
        $icustomer = $this->model_api->get("shopper", array('id'), array("and" => array("id" => $customerId)));

        if (!isset($ichallan['data'][0]['id']) || $ichallan['statuscode'] != 1)
            return error_res("challan_id_invalide");
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("shopper_id_invalide");

        $defaultFieldspurchase = get_default_fields(self::$defaultFieldspurchase);
        $updateFields = array_intersect_key($param, $defaultFieldspurchase);
        $iUpdate = $this->model_api->update('purchase_challan', $updateFields, array("and" => array("id" => $challanId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $product) {
            $Id = $product['id'];
            $productId = $product['productid'];
            if ($Id == "") {
                $this->addPurchaseChallanproduct($challanId, $product);
            } else {
                $iProduct = $this->model_api->get('purchase_challan_product', array("id,is_del"), array("and" => array("id" => $Id)));
                if (!isset($iProduct['data'][0]['id']) || $icustomer['statuscode'] != 1)
                    return error_res('id_invalide');
            }
            $iProductId = $this->model_api->get('product_detailes', array("id,is_del"), array("and" => array("id" => $productId)));
            if (!isset($iProductId['data'][0]['id']) || $icustomer['statuscode'] != 1)
                return error_res('product_id_invalide');
            $this->updatePurchaseChallanproduct($product);
        }

        $deleteproductids = $param['delete_id'];
        $deleteidarr = json_decode($deleteproductids, true);
        foreach ($deleteidarr as $key => $deletechallanid) {
            $id = $deletechallanid;
            $iStatus = $this->model_api->delete('purchase_challan_product', array("and" => array("id" => $id, "is_del" => '0')));
            if ($iStatus['statuscode'] != '1')
                return $iStatus;
        }

        $iUser = $this->purchasechallanDetailById($challanId);
        $iData = success_res("challan_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deletePurchaseChallan($param)
    {
        $iStatus = $this->model_api->delete('purchase_challan', array("and" => array("id" => $param['challan_id'], "is_del" => '0')));
        $iStatus = $this->model_api->delete('purchase_challan_product', array("and" => array("challan_id" => $param['challan_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("challan_delete_successfully");
    }

    function deletePurchaseChallanproduct($param)
    {
        $iStatus = $this->model_api->delete('purchase_challan_product', array("and" => array("id" => $param['challan_invoice_product_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("challan_invoices_product_delete_successfully");
    }

    function getPurchaseChallan($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';


        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE purchase_challan.is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND purchase_challan.name LIKE '%$searchKey%'";

        $userId = user_id();

        $iQuery = "SELECT purchase_challan.id,purchase_challan.user_id,purchase_challan.shopper_id,purchase_challan.challan_no,purchase_challan.challan_date,purchase_challan.created_date,purchase_challan.modified_date,purchase_challan.is_del,shopper.trade_name as shopper_name  FROM purchase_challan JOIN shopper ON (purchase_challan.shopper_id = shopper.id) WHERE purchase_challan.user_id = {$userId} ORDER BY purchase_challan.id DESC " . $iLimit;
        $ichallansDetails = $this->model_api->execute_query($iQuery);

        if (!isset($ichallansDetails['statuscode']) || $ichallansDetails['statuscode'] != 1)
            return error_res("invalid_data");

        if (isset($ichallansDetails['data']) && count($ichallansDetails['data']) > 0) {
            foreach ($ichallansDetails['data'] as $petkey => $ichallansDetail) {
                $challanId = $ichallansDetail['id'];

                $iQuery = "SELECT purchase_challan_product.id,purchase_challan_product.challan_id,purchase_challan_product.product_id,purchase_challan_product.product_quantity,purchase_challan_product.product_rate,purchase_challan_product.total_amount,product_detailes.product_name as product_name FROM purchase_challan_product JOIN product_detailes ON (purchase_challan_product.product_id = product_detailes.id) WHERE purchase_challan_product.challan_id = {$challanId} AND purchase_challan_product.is_del = 0";
                $ichallanProductDetails = $this->model_api->execute_query($iQuery);
                $ichallansDetails['data'][$petkey]['product'] = $ichallanProductDetails['data'];
            }
        }
        $iRes = success_res("success");
        $iRes = $ichallansDetails;
        return $iRes;
    }
}
