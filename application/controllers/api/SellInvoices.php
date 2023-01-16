<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SellInvoices extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("Model_sellInvoices");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function addSellInvoices()
    {
        $iRes = $this->validate->addSellInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_sellInvoices->addSellInvoices($iRes['data']);
        response($iProfile);
    }

    function updateSellInvoices()
    {
        $iRes = $this->validate->updateSellInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_sellInvoices->updateSellInvoices($iRes['data']);
        response($iProfile);
    }

    function deleteSellInvoices()
    {
        $iRes = $this->validate->deleteSellInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->Model_sellInvoices->deleteSellInvoices($iRes['data']);
        response($iResult);
    }

    function deleteSellInvoicesproduct()
    {
        $iRes = $this->validate->deleteSellInvoicesproduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->Model_sellInvoices->deleteSellInvoicesproduct($iRes['data']);
        response($iResult);
    }

    function getSellInvoices()
    {
        $iRes = $this->validate->getSellInvoices($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_sellInvoices->getSellInvoices($iRes['data']);
        response($iProfile);
    }
}
