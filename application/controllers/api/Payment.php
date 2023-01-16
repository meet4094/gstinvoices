<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Payment extends MY_Controller

{



    function __construct()

    {

        parent::__construct();

        if (!defined('ROUTE_ACCESS'))

            exit('No Access');

        $this->load->model("Model_payment");
    }



    function index()

    {

        _e("<h1><center>Access Denied!!</center></h1>");
    }



    function addSellInvoicePayment()

    {
        $iRes = $this->validate->addSellInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->addSellInvoicePayment($iRes['data']);

        response($iProfile);
    }



    function updateSellInvoicePayment()

    {

        $iRes = $this->validate->updateSellInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->updateSellInvoicePayment($iRes['data']);

        response($iProfile);
    }



    function deleteSellInvoicePayment()

    {

        $iRes = $this->validate->deleteSellInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->Model_payment->deleteSellInvoicePayment($iRes['data']);

        response($iResult);
    }



    function getSellInvoicePayment()

    {

        $iRes = $this->validate->getSellInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->getSellInvoicePayment($iRes['data']);

        response($iProfile);
    }

    function addPurchaseInvoicePayment()

    {
        $iRes = $this->validate->addPurchaseInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->addPurchaseInvoicePayment($iRes['data']);

        response($iProfile);
    }



    function updatePurchaseInvoicePayment()

    {

        $iRes = $this->validate->updatePurchaseInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->updatePurchaseInvoicePayment($iRes['data']);

        response($iProfile);
    }



    function deletePurchaseInvoicePayment()

    {

        $iRes = $this->validate->deletePurchaseInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->Model_payment->deletePurchaseInvoicePayment($iRes['data']);

        response($iResult);
    }



    function getPurchaseInvoicePayment()

    {

        $iRes = $this->validate->getPurchaseInvoicePayment($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->Model_payment->getPurchaseInvoicePayment($iRes['data']);

        response($iProfile);
    }
}
