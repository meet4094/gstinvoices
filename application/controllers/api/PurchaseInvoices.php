<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PurchaseInvoices extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("Model_purchaseInvoices");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function addPurchaseInvoices()
    {
        $iRes = $this->validate->addPurchaseInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_purchaseInvoices->addPurchaseInvoices($iRes['data']);
        response($iProfile);
    }

    function updatePurchaseInvoices()
    {
        $iRes = $this->validate->updatePurchaseInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_purchaseInvoices->updatePurchaseInvoices($iRes['data']);
        response($iProfile);
    }

    function deletePurchaseInvoices()
    {
        $iRes = $this->validate->deletePurchaseInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->Model_purchaseInvoices->deletePurchaseInvoices($iRes['data']);
        response($iResult);
    }

    function deletePurchaseInvoicesproduct()
    {
        $iRes = $this->validate->deletePurchaseInvoicesproduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->Model_purchaseInvoices->deletePurchaseInvoicesproduct($iRes['data']);
        response($iResult);
    }

    function getPurchaseInvoices()
    {
        $iRes = $this->validate->getPurchaseInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_purchaseInvoices->getPurchaseInvoices($iRes['data']);
        response($iProfile);
    }
}
