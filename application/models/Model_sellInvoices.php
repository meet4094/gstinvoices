<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_sellInvoices extends CI_Model
{
    public static $defaultFields = array();

    public function __construct()
    {
        parent::__construct();
        self::$defaultFields = array(
            "invoices_date" => array("type" => "string", "default" => "", "protected" => 0),
            "prefix" => array("type" => "string", "default" => "", "protected" => 0),
            "invoices_no" => array("type" => "string", "default" => "", "protected" => 0),
            "gst" => array("type" => "string", "default" => "", "protected" => 0),
            "customer_id" => array("type" => "string", "default" => "", "protected" => 0),
            "sub_total" => array("type" => "string", "default" => "", "protected" => 0),
            "product_sgst" => array("type" => "string", "default" => "", "protected" => 0),
            "product_cgst" => array("type" => "string", "default" => "", "protected" => 0),
            "round_off" => array("type" => "string", "default" => "", "protected" => 0),
            "invoices_total" => array("type" => "string", "default" => "", "protected" => 0),
            "created_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "modified_date" => array("type" => "datetime", "default" => date("Y-m-d H:i:s"), "protected" => 1),
            "is_del" => array("type" => "integer", "default" => 0, "protected" => 1),
        );
    }

    function addSellInvoicesProduct($Id, $product)
    {
        $productId = $product['productid'];
        $iProduct = $this->model_api->get('product_detailes', array("id,product_code,is_del"), array("and" => array("id" => $productId)));
        $code = $iProduct['data'][0]['product_code'];

        $inserted_fields['sell_invoice_id'] = $Id;
        $inserted_fields['product_hsn_sac'] = $code;
        $inserted_fields['product_id'] = $product['productid'];
        $inserted_fields['product_quantity'] = $product['quantity'];
        $inserted_fields['product_rate'] = $product['rate'];
        $inserted_fields['total_amount'] = $product['total'];
        $this->model_api->add('sell_product_detailes', $inserted_fields);

        $iRes = success_res("success");
        return $iRes;
    }

    function updateSellInvoicesProduct($product)
    {
        $productId = $product['productid'];
        $iProduct = $this->model_api->get('product_detailes', array("id,product_code,is_del"), array("and" => array("id" => $productId)));
        $code = $iProduct['data'][0]['product_code'];

        $Id = $product['id'];
        $productName = $product['productid'];
        $productQuantity = $product['quantity'];
        $productRate = $product['rate'];
        $totalAmount = $product['total'];
        $this->model_api->update('sell_product_detailes', array('product_id' => $productName, 'product_hsn_sac' => $code, 'product_quantity' => $productQuantity, 'product_rate' => $productRate, 'total_amount' => $totalAmount), array("and" => array("id" => $Id)));

        $iRes = success_res("success");
        return $iRes;
    }

    function SellInvoicesDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM sell_invoices WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");
        foreach ($detailes['data'] as $key => $detaile) {
            $SellInvoicesId = $detaile['id'];

            $iQuery = "SELECT id,sell_invoice_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount FROM sell_product_detailes WHERE sell_invoice_id = {$SellInvoicesId} AND is_del = 0";
            $iProductDetails = $this->model_api->execute_query($iQuery);
            $detailes['data'][$key]['product'] = $iProductDetails['data'];
        }
        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function addSellInvoices($param)
    {
        $userId = user_id();
        $userdetailes = user_detail();
        $customerId = isset($param['customer_id']) ? $param['customer_id'] : '';
        $invoices_date = isset($param['invoices_date']) ? $param['invoices_date'] : '';

        $iQry = "SELECT id,user_id,invoices_no,invoices_date FROM sell_invoices WHERE user_id = {$userId} ORDER BY id DESC LIMIT 1";
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
            $invoicesno = $iUsers['data'][0]['invoices_no'] + 1;
        }

        $icustomer = $this->model_api->get("customer", array('id'), array("and" => array("id" => $customerId)));
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("customer_id_invalide");

        $defaultFields = get_default_fields(self::$defaultFields);
        $iArrayDiff = array_diff_key($defaultFields, $param);
        $insertedFields = array_intersect_key($param, $defaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $insertedFields['prefix'] = $userdetailes['prefix'];
        $insertedFields['invoices_no'] = $invoicesno;
        $iStatus = $this->model_api->add('sell_invoices', $insertedFields);
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
                $this->addSellInvoicesProduct($Id, $product);
            } else {
                return error_res('product_id_invalide');
            }
        }

        $iUser = $this->model_api->get('sell_invoices', array("id,invoices_date,invoices_no,customer_id,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,created_date,modified_date,is_del"), array("and" => array("id" => $Id)));
        if (!isset($iUser['statuscode']) || $iUser['statuscode'] != 1)
            return error_res("invalid_data");

        $iUser = $this->SellInvoicesDetailById($Id);
        $iStatus = success_res("sell_invoices_details_created");
        return $iStatus;
    }

    function updateSellInvoices($param)
    {
        $SellInvoicesId = isset($param['sell_invoices_id']) ? $param['sell_invoices_id'] : '';
        $customerId = isset($param['customer_id']) ? $param['customer_id'] : '';

        $iSellId = $this->model_api->get('sell_invoices', array("id,is_del"), array("and" => array("id" => $SellInvoicesId)));
        $iCustomer = $this->model_api->get("customer", array('id'), array("and" => array("id" => $customerId)));

        if (!isset($iSellId['data'][0]['id']) || $iSellId['statuscode'] != 1)
            return error_res('sell_invoices_id_invalide');
        if (!isset($iCustomer['data'][0]['id']) || $iCustomer['statuscode'] != 1)
            return error_res("customer_id_invalide");

        $defaultFields = get_default_fields(self::$defaultFields);
        $updateFields = array_intersect_key($param, $defaultFields);
        $iUpdate = $this->model_api->update('sell_invoices', $updateFields, array("and" => array("id" => $SellInvoicesId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $products = $param['product'];
        $arr = json_decode($products, true);

        foreach ($arr as $product) {
            $productSellId = $product['id'];
            $productId = $product['productid'];
            if ($productSellId == "") {
                $this->addSellInvoicesProduct($SellInvoicesId, $product);
            } else {
                $iProductSellId = $this->model_api->get('sell_product_detailes', array("id,is_del"), array("and" => array("id" => $productSellId)));
                if (!isset($iProductSellId['data'][0]['id']) || $iProductSellId['statuscode'] != 1)
                    return error_res('id_invalide');
            }
            $iProduct = $this->model_api->get('product_detailes', array("id,is_del"), array("and" => array("id" => $productId)));
            if (!isset($iProduct['data'][0]['id']) || $iProduct['statuscode'] != 1)
                return error_res('product_id_invalide');
            $this->updateSellInvoicesProduct($product);
        }

        $deleteproductids = $param['delete_id'];
        $deleteidarr = json_decode($deleteproductids, true);
        foreach ($deleteidarr as $key => $deletechallanid) {
            $id = $deletechallanid;
            $iStatus = $this->model_api->delete('sell_product_detailes', array("and" => array("id" => $id, "is_del" => '0')));
            if ($iStatus['statuscode'] != '1')
                return $iStatus;
        }

        $iUser = $this->SellInvoicesDetailById($SellInvoicesId);
        $iData = success_res("sell_invoices_updated");
        $iData['data'] = $iUser;

        return $iData;
    }

    function deleteSellInvoices($param)
    {
        $iStatus = $this->model_api->delete('sell_invoices', array("and" => array("id" => $param['sell_invoices_id'], "is_del" => '0')));
        $iStatus = $this->model_api->delete('sell_product_detailes', array("and" => array("sell_invoice_id" => $param['sell_invoices_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("sell_invoices_delete_successfully");
    }

    function deleteSellInvoicesproduct($param)
    {
        $iStatus = $this->model_api->delete('sell_product_detailes', array("and" => array("id" => $param['sell_invoice_product_id'], "is_del" => '0')));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("sell_invoices_product_delete_successfully");
    }

    function getSellInvoices($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';


        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE sell_invoices.is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND sell_invoices.name LIKE '%$searchKey%'";

        $userId = user_id();

        $iQuery = "SELECT sell_invoices.id,sell_invoices.user_id,sell_invoices.invoices_date,CONCAT(sell_invoices.prefix,'',sell_invoices.invoices_no) AS invoices_no,sell_invoices.customer_id,customer.trade_name as customer_name,sell_invoices.gst,sell_invoices.sub_total,sell_invoices.product_sgst,sell_invoices.product_cgst,sell_invoices.round_off,sell_invoices.invoices_total,sell_invoices.payment_status,sell_invoices.created_date,sell_invoices.modified_date,sell_invoices.is_del FROM sell_invoices JOIN customer ON (sell_invoices.customer_id = customer.id) " . $iWhere . " AND sell_invoices.user_id = {$userId} ORDER BY sell_invoices.id DESC " . $iLimit;
        $iSellInvoicesDetails = $this->model_api->execute_query($iQuery);

        if (!isset($iSellInvoicesDetails['statuscode']) || $iSellInvoicesDetails['statuscode'] != 1)
            return error_res("invalid_data");

        if (isset($iSellInvoicesDetails['data']) && count($iSellInvoicesDetails['data']) > 0) {
            foreach ($iSellInvoicesDetails['data'] as $petkey => $iSellInvoicesDetail) {
                $SellInvoicesId = $iSellInvoicesDetail['id'];

                $iQuery = "SELECT sell_product_detailes.id,sell_product_detailes.sell_invoice_id,sell_product_detailes.product_id,sell_product_detailes.product_hsn_sac,sell_product_detailes.product_quantity,sell_product_detailes.product_rate,sell_product_detailes.total_amount,product_detailes.product_name as product_name FROM sell_product_detailes JOIN product_detailes ON (sell_product_detailes.product_id = product_detailes.id) WHERE sell_invoice_id = {$SellInvoicesId} AND sell_product_detailes.is_del = 0";
                $iProductDetails = $this->model_api->execute_query($iQuery);
                $iSellInvoicesDetails['data'][$petkey]['product'] = $iProductDetails['data'];
                $iSellInvoicesDetails['data'][$petkey]['sell_invoices_pdf'] = 'http://localhost/gstinvoice/admin/sell_invoicespdf/Invoices_pdf/' . $iSellInvoicesDetail['id'];
            }
        }
        $iRes = success_res("success");
        $iRes = $iSellInvoicesDetails;
        return $iRes;
    }
}
