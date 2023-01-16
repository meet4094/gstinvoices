<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_Payment extends CI_Model
{
    public static $selldefaultFields = array();
    public static $purchasedefaultFields = array();

    public function __construct()
    {
        parent::__construct();
        self::$selldefaultFields = array(
            "user_id" => array("integer" => "integer", "default" => "", "protected" => 0),
            "customer_id" => array("integer" => "integer", "default" => "", "protected" => 0),
            "invoices_id" => array("integer" => "integer", "default" => "", "protected" => 0),
            "invoices_no" => array("type" => "string", "default" => "", "protected" => 0),
            "payment_mode" => array("type" => "integer", "default" => "", "protected" => 1),
            "payment_date" => array("type" => "string", "default" => "", "protected" => 0),
            "invoices_total" => array("type" => "string", "default" => "", "protected" => 0),
            "transfer_amount" => array("type" => "string", "default" => "", "protected" => 0),
            "amount" => array("type" => "integer", "default" => "", "protected" => 0),
            "cheque_number" => array("type" => "string", "default" => "", "protected" => 0),
            "bank_detail" => array("type" => "string", "default" => "", "protected" => 0),
            "transaction_detail" => array("type" => "string", "default" => "", "protected" => 0),
            "status" => array("type" => "integer", "default" => "", "protected" => 1),
        );
        self::$purchasedefaultFields = array(
            "shopper_id" => array("integer" => "integer", "default" => "", "protected" => 0),
            "invoices_id" => array("integer" => "integer", "default" => "", "protected" => 0),
            "invoices_no" => array("type" => "string", "default" => "", "protected" => 0),
            "payment_mode" => array("type" => "integer", "default" => "", "protected" => 1),
            "payment_date" => array("type" => "string", "default" => "", "protected" => 0),
            "amount" => array("type" => "integer", "default" => "", "protected" => 0),
            "cheque_number" => array("type" => "string", "default" => "", "protected" => 0),
            "bank_detail" => array("type" => "string", "default" => "", "protected" => 0),
            "transaction_detail" => array("type" => "string", "default" => "", "protected" => 0),
            "status" => array("type" => "integer", "default" => "", "protected" => 1),
        );
    }

    function SellInvoicesDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM sell_invoices_payments WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");

        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function PurchaseInvoicesDetailById($Id = 0)
    {
        $iQuery = "SELECT * FROM purchase_invoices_payments WHERE id = {$Id}";
        $detailes = $this->model_api->execute_query($iQuery);
        if (!isset($detailes['data'][0]['id']))
            return error_res("invalid_data");

        $detailes = $detailes['data'][0];
        return $detailes;
    }

    function addSellInvoicePayment($param)
    {
        $userId = user_id();
        $customerId = isset($param['customer_id']) ? $param['customer_id'] : '';
        $invoicenumber = isset($param['invoices_no']) ? $param['invoices_no'] : '';
        $invoicestotal = isset($param['invoices_total']) ? $param['invoices_total'] : '';
        $transferamount = isset($param['transfer_amount']) ? $param['transfer_amount'] : '';
        $amount = isset($param['amount']) ? $param['amount'] : '';

        $icustomer = $this->model_api->get("customer", array('id'), array("and" => array("id" => $customerId)));
        if (!isset($icustomer['data'][0]['id']) || $icustomer['statuscode'] != 1)
            return error_res("customer_id_invalide");

        $iQuery = "SELECT id,customer_id,invoices_no,CONCAT(sell_invoices.prefix,'',sell_invoices.invoices_no) AS invoicesno FROM sell_invoices WHERE customer_id = '{$customerId}' HAVING invoicesno = '{$invoicenumber}'";
        $iSellinvoices = $this->model_api->execute_query($iQuery);
        if (!isset($iSellinvoices['data'][0]['id']) || $iSellinvoices['statuscode'] != 1)
            return error_res("invoices_no_invalide");
        $sellid = $iSellinvoices['data'][0]['id'];

        if ($invoicestotal < $amount) {
            return error_res("amount_invalide");
        } else if ($invoicestotal == $transferamount) {
            return error_res("payment_complete_invalide");
        } else if ($invoicestotal < $amount + $transferamount) {
            return error_res("amount_invalide");
        }

        $selldefaultFields = get_default_fields(self::$selldefaultFields);
        $iArrayDiff = array_diff_key($selldefaultFields, $param);
        $insertedFields = array_intersect_key($param, $selldefaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $insertedFields['invoices_id'] = $sellid;
        $insertedFields['invoices_total'] = $invoicestotal;
        $insertedFields['transfer_amount'] = $amount;

        if ($invoicestotal == $amount) {
            $insertedFields['status'] = '1';
        } else if ($invoicestotal == $transferamount + $amount) {
            $insertedFields['status'] = '1';
        } else {
            $insertedFields['status'] = '0';
        }

        $iStatus = $this->model_api->add('sell_invoices_payments', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $this->model_api->update("sell_invoices", array("payment_status" => $insertedFields['status']), array("and" => array("id" => $sellid)));

        $iStatus = success_res("sell_invoices_payment_created");
        return $iStatus;
    }

    function updateSellInvoicePayment($param)
    {
        $SellInvoicesPaymentId = isset($param['sell_invoices_payment_id']) ? $param['sell_invoices_payment_id'] : '';
        $invoicestotal = isset($param['invoices_total']) ? $param['invoices_total'] : '';
        $transferamounts = isset($param['transfer_amount']) ? $param['transfer_amount'] : '';
        $amount = isset($param['amount']) ? $param['amount'] : '';

        $iSellInvoices = $this->model_api->get('sell_invoices_payments', array(""), array("and" => array("id" => $SellInvoicesPaymentId)));
        if (!isset($iSellInvoices['data'][0]['id']) || $iSellInvoices['statuscode'] != 1)
            return error_res('sell_invoices_payment_id_invalide');
        $invoicesId = $iSellInvoices['data'][0]['invoices_id'];
        $invoicesamount = $iSellInvoices['data'][0]['amount'];

        $transferamount = $transferamounts - $invoicesamount;
        if ($invoicestotal < $amount) {
            return error_res("amount_invalide");
        } else if ($invoicestotal == $transferamounts) {
            return error_res("payment_complete_invalide");
        } else if ($invoicestotal < $amount + $transferamount) {
            return error_res("amount_invalide");
        }

        $selldefaultFields = get_default_fields(self::$selldefaultFields);
        $updateFields = array_intersect_key($param, $selldefaultFields);
        $updateFields['invoices_total'] = $invoicestotal;
        $updateFields['transfer_amount'] = $amount;

        if ($invoicestotal == $amount) {
            $updateFields['status'] = '1';
        } else if ($invoicestotal == $transferamount + $amount) {
            $updateFields['status'] = '1';
        } else {
            $updateFields['status'] = '0';
        }
        $iUpdate = $this->model_api->update('sell_invoices_payments', $updateFields, array("and" => array("id" => $SellInvoicesPaymentId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $this->model_api->update("sell_invoices", array("payment_status" => $updateFields['status']), array("and" => array("id" => $invoicesId)));

        $iUser = $this->SellInvoicesDetailById($SellInvoicesPaymentId);
        $iData = success_res("sell_invoices_payment_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deleteSellInvoicePayment($param)
    {
        $iQuery = "SELECT invoices_id FROM sell_invoices_payments WHERE id = {$param['sell_invoices_payment_id']}";
        $iSellInvoicesPaymentList = $this->model_api->execute_query($iQuery);
        if (empty($iSellInvoicesPaymentList['data'])){
            return error_res('sell_invoices_payment_id_invalide');
        }
            $invoicesId = $iSellInvoicesPaymentList['data'][0]['invoices_id'];

        $this->model_api->update("sell_invoices", array("payment_status" => '0'), array("and" => array("id" => $invoicesId)));

        $iStatus = $this->model_api->delete('sell_invoices_payments', array("and" => array("id" => $param['sell_invoices_payment_id'])));

        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("sell_invoices_payment_delete");
    }

    function getSellInvoicePayment($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;

        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";

        $userId = user_id();
        $customerID = isset($param['customer_id']) ? $param['customer_id'] : '';

        if ($customerID == "") {
            $iQuery = "SELECT sell_invoices.id,sell_invoices.user_id,sell_invoices.customer_id,customer.trade_name as customername,CONCAT(sell_invoices.prefix,'',sell_invoices.invoices_no) AS invoicesno,sell_invoices.invoices_total,sell_invoices.payment_status FROM sell_invoices JOIN customer ON (sell_invoices.customer_id = customer.id) WHERE sell_invoices.user_id = '{$userId}' ORDER BY sell_invoices.id DESC {$iLimit}";
            $iSellInvoicesDetails = $this->model_api->execute_query($iQuery);
            foreach ($iSellInvoicesDetails['data'] as $key => $iSellInvoicesDetail) {
                $invoicesNo = $iSellInvoicesDetail['invoicesno'];
                $customerId = $iSellInvoicesDetail['customer_id'];

                $iQuery = "SELECT id,customer_id,invoices_no,amount,payment_mode,payment_date,cheque_number,bank_detail,transaction_detail FROM sell_invoices_payments WHERE customer_id = '{$customerId}' AND invoices_no = '{$invoicesNo}'";
                $iPaymentDetails = $this->model_api->execute_query($iQuery);

                $iQuerys = "SELECT SUM(transfer_amount) as transfer_amount FROM sell_invoices_payments WHERE customer_id = '{$customerId}' AND invoices_no = '{$invoicesNo}'";
                $transferAmount = $this->model_api->execute_query($iQuerys);

                if (empty($transferAmount['data'][0]['transfer_amount'])) {
                    $Tramount = '0';
                } else {
                    $Tramount = $transferAmount['data'][0]['transfer_amount'];
                }
                $iSellInvoicesDetails['data'][$key]['payment_detailes'] = $iPaymentDetails['data'];
                $iSellInvoicesDetails['data'][$key]['transfer_amount'] = $Tramount;
            }
        } else {
            $iQuery = "SELECT sell_invoices.id,sell_invoices.user_id,sell_invoices.customer_id,customer.trade_name as customername,CONCAT(sell_invoices.prefix,'',sell_invoices.invoices_no) AS invoicesno,sell_invoices.invoices_total FROM sell_invoices JOIN customer ON (customer.id = sell_invoices.customer_id) WHERE sell_invoices.user_id = '{$userId}' AND sell_invoices.customer_id = '{$customerID}' ORDER BY sell_invoices.id DESC {$iLimit}";
            $iSellInvoicesDetails = $this->model_api->execute_query($iQuery);

            foreach ($iSellInvoicesDetails['data'] as $key => $iSellInvoicesDetail) {
                $sell = "SELECT SUM(transfer_amount)as transferamount FROM sell_invoices_payments WHERE sell_invoices_payments.customer_id = '{$iSellInvoicesDetail['customer_id']}' AND sell_invoices_payments.invoices_no = '{$iSellInvoicesDetail['invoicesno']}'";
                $sellinvoices = $this->model_api->execute_query($sell);

                foreach ($sellinvoices['data'] as $keys => $sellinvoice) {
                    $transferamount = $sellinvoice['transferamount'];
                    if ($transferamount === null) {
                        $transferamount = 0;
                        $iSellInvoicesDetails['data'][$key]['transferamount'] = $transferamount;
                    } else {
                        $iSellInvoicesDetails['data'][$key]['transferamount'] = $transferamount;
                    }
                }
            }

            if (!isset($iSellInvoicesDetails['statuscode']) || $iSellInvoicesDetails['statuscode'] != 1)
                return error_res("invalid_data");
        }
        $iRes = success_res("success");
        $iRes = $iSellInvoicesDetails;
        return $iRes;
    }

    function addPurchaseInvoicePayment($param)
    {
        $userId = user_id();
        $shopperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';
        $invoicenumber = isset($param['invoices_no']) ? $param['invoices_no'] : '';
        $invoicestotal = isset($param['invoices_total']) ? $param['invoices_total'] : '';
        $transferamount = isset($param['transfer_amount']) ? $param['transfer_amount'] : '';
        $amount = isset($param['amount']) ? $param['amount'] : '';

        $ishopper = $this->model_api->get("shopper", array('id'), array("and" => array("id" => $shopperId)));
        if (!isset($ishopper['data'][0]['id']) || $ishopper['statuscode'] != 1)
            return error_res("shopper_id_invalide");

        $iQuery = "SELECT id,shopper_id,invoices_no FROM purchase_invoices WHERE shopper_id = {$shopperId} AND invoices_no = {$invoicenumber}";
        $iPurchaseinvoices = $this->model_api->execute_query($iQuery);
        if (!isset($iPurchaseinvoices['data'][0]['id']) || $iPurchaseinvoices['statuscode'] != 1)
            return error_res("invoices_no_invalide");
        $purchaseid = $iPurchaseinvoices['data'][0]['id'];

        if ($invoicestotal < $amount) {
            return error_res("amount_invalide");
        } else if ($invoicestotal == $transferamount) {
            return error_res("payment_complete_invalide");
        } else if ($invoicestotal < $amount + $transferamount) {
            return error_res("amount_invalide");
        }

        $purchasedefaultFields = get_default_fields(self::$purchasedefaultFields);
        $iArrayDiff = array_diff_key($purchasedefaultFields, $param);
        $insertedFields = array_intersect_key($param, $purchasedefaultFields);
        $insertedFields = array_merge($insertedFields, $iArrayDiff);
        $insertedFields['user_id'] = $userId;
        $insertedFields['invoices_id'] = $purchaseid;
        $insertedFields['invoices_total'] = $invoicestotal;
        $insertedFields['transfer_amount'] = $amount;

        if ($invoicestotal == $amount) {
            $insertedFields['status'] = '1';
        } else if ($invoicestotal == $transferamount + $amount) {
            $insertedFields['status'] = '1';
        } else {
            $insertedFields['status'] = '0';
        }

        $iStatus = $this->model_api->add('purchase_invoices_payments', $insertedFields);
        if (!isset($iStatus['data']['id']) || $iStatus['statuscode'] != 1)
            return $iStatus;

        $this->model_api->update("purchase_invoices", array("payment_status" => $insertedFields['status']), array("and" => array("id" => $purchaseid)));

        $iStatus = success_res("purchase_invoices_payment_created");
        return $iStatus;
    }

    function updatePurchaseInvoicePayment($param)
    {
        $PurchaseInvoicesPaymentId = isset($param['purchase_invoices_payment_id']) ? $param['purchase_invoices_payment_id'] : '';
        $invoicestotal = isset($param['invoices_total']) ? $param['invoices_total'] : '';
        $transferamounts = isset($param['transfer_amount']) ? $param['transfer_amount'] : '';
        $amount = isset($param['amount']) ? $param['amount'] : '';

        $iPurchaseInvoices = $this->model_api->get('purchase_invoices_payments', array(""), array("and" => array("id" => $PurchaseInvoicesPaymentId)));
        if (!isset($iPurchaseInvoices['data'][0]['id']) || $iPurchaseInvoices['statuscode'] != 1)
            return error_res('purchase_invoices_payment_id_invalide');
        $invoicesId = $iPurchaseInvoices['data'][0]['invoices_id'];
        $invoicesamount = $iPurchaseInvoices['data'][0]['amount'];

        $transferamount = $transferamounts - $invoicesamount;
        if ($invoicestotal < $amount) {
            return error_res("amount_invalide");
        } else if ($invoicestotal == $transferamounts) {
            return error_res("payment_complete_invalide");
        } else if ($invoicestotal < $amount + $transferamount) {
            return error_res("amount_invalide");
        }

        $purchasedefaultFields = get_default_fields(self::$purchasedefaultFields);
        $updateFields = array_intersect_key($param, $purchasedefaultFields);
        $updateFields['invoices_total'] = $invoicestotal;
        $updateFields['transfer_amount'] = $amount;

        if ($invoicestotal == $amount) {
            $updateFields['status'] = '1';
        } else if ($invoicestotal == $transferamount + $amount) {
            $updateFields['status'] = '1';
        } else {
            $updateFields['status'] = '0';
        }

        $iUpdate = $this->model_api->update('purchase_invoices_payments', $updateFields, array("and" => array("id" => $PurchaseInvoicesPaymentId)));
        if (!isset($iUpdate['statuscode']) || $iUpdate['statuscode'] != 1)
            return error_res("invalid_data");

        $this->model_api->update("purchase_invoices", array("payment_status" => $updateFields['status']), array("and" => array("id" => $invoicesId)));

        $iUser = $this->PurchaseInvoicesDetailById($PurchaseInvoicesPaymentId);
        $iData = success_res("purchase_invoices_updated");
        $iData['data'] = $iUser;
        return $iData;
    }

    function deletePurchaseInvoicePayment($param)
    {
        $iQuery = "SELECT invoices_id FROM purchase_invoices_payments WHERE id = {$param['purchase_invoices_payment_id']}";
        $iPurchaseInvoicesPaymentList = $this->model_api->execute_query($iQuery);
        if (empty($iPurchaseInvoicesPaymentList['data'])){
            return error_res('purchase_invoices_payment_id_invalide');
        }
        $invoicesId = $iPurchaseInvoicesPaymentList['data'][0]['invoices_id'];

        $this->model_api->update("purchase_invoices", array("payment_status" => '0'), array("and" => array("id" => $invoicesId)));

        $iStatus = $this->model_api->delete('purchase_invoices_payments', array("and" => array("id" => $param['purchase_invoices_payment_id'])));
        if ($iStatus['statuscode'] != '1')
            return $iStatus;
        return success_res("purchase_invoices_payment_delete");
    }

    function getPurchaseInvoicePayment($param)
    {
        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;

        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";

        $userId = user_id();
        $shopperId = isset($param['shopper_id']) ? $param['shopper_id'] : '';

        if ($shopperId == "") {
            $iQuery = "SELECT purchase_invoices.id,purchase_invoices.user_id,purchase_invoices.shopper_id,shopper.trade_name as shoppername,purchase_invoices.invoices_no,purchase_invoices.invoices_total,purchase_invoices.payment_status FROM purchase_invoices JOIN shopper ON (purchase_invoices.shopper_id = shopper.id) WHERE purchase_invoices.user_id = '{$userId}' ORDER BY purchase_invoices.id DESC {$iLimit}";
            $iPurchaseInvoicesDetails = $this->model_api->execute_query($iQuery);
            foreach ($iPurchaseInvoicesDetails['data'] as $key => $iPurchaseInvoicesDetail) {
                $shopperId = $iPurchaseInvoicesDetail['shopper_id'];
                $invoicesNo = $iPurchaseInvoicesDetail['invoices_no'];

                $iQuery = "SELECT id,shopper_id,invoices_no,amount,payment_mode,payment_date,cheque_number,bank_detail,transaction_detail FROM purchase_invoices_payments WHERE shopper_id = {$shopperId} AND invoices_no = {$invoicesNo}";
                $iPaymentDetails = $this->model_api->execute_query($iQuery);

                $iQuerys = "SELECT SUM(transfer_amount) as transfer_amount FROM purchase_invoices_payments WHERE shopper_id = '{$shopperId}' AND invoices_no = '{$invoicesNo}'";
                $transferAmount = $this->model_api->execute_query($iQuerys);

                if (empty($transferAmount['data'][0]['transfer_amount'])) {
                    $Tramount = '0';
                } else {
                    $Tramount = $transferAmount['data'][0]['transfer_amount'];
                }
                $iPurchaseInvoicesDetails['data'][$key]['payment_detailes'] = $iPaymentDetails['data'];
                $iPurchaseInvoicesDetails['data'][$key]['transfer_amount'] = $Tramount;
            }
        } else {
            $iQuery = "SELECT purchase_invoices.id,purchase_invoices.user_id,purchase_invoices.shopper_id,shopper.trade_name as shoppername,purchase_invoices.invoices_no,purchase_invoices.invoices_total FROM purchase_invoices JOIN shopper ON (shopper.id = purchase_invoices.shopper_id) WHERE purchase_invoices.user_id = '{$userId}' AND purchase_invoices.shopper_id = '{$shopperId}' ORDER BY purchase_invoices.id DESC {$iLimit}";
            $iPurchaseInvoicesDetails = $this->model_api->execute_query($iQuery);

            foreach ($iPurchaseInvoicesDetails['data'] as $key => $iPurchaseInvoicesDetail) {
                $sell = "SELECT SUM(transfer_amount) as transferamount FROM purchase_invoices_payments WHERE purchase_invoices_payments.shopper_id = '{$iPurchaseInvoicesDetail['shopper_id']}' AND purchase_invoices_payments.invoices_no = '{$iPurchaseInvoicesDetail['invoices_no']}'";
                $purchaseinvoices = $this->model_api->execute_query($sell);

                foreach ($purchaseinvoices['data'] as $keys => $purchaseinvoice) {
                    $transferamount = $purchaseinvoice['transferamount'];
                    if ($transferamount === null) {
                        $transferamount = 0;
                        $iPurchaseInvoicesDetails['data'][$key]['transferamount'] = $transferamount;
                    } else {
                        $iPurchaseInvoicesDetails['data'][$key]['transferamount'] = $transferamount;
                    }
                }
            }

            if (!isset($iPurchaseInvoicesDetails['statuscode']) || $iPurchaseInvoicesDetails['statuscode'] != 1)
                return error_res("invalid_data");
        }
        $iRes = success_res("success");
        $iRes = $iPurchaseInvoicesDetails;
        return $iRes;
    }
}
