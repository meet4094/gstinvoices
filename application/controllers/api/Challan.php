<?php

if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Challan extends MY_Controller

{



    function __construct()

    {

        parent::__construct();

        if (!defined('ROUTE_ACCESS'))

            exit('No Access');

        $this->load->model("model_challan");
    }



    function index()

    {

        _e("<h1><center>Access Denied!!</center></h1>");
    }


    //SELL CAHALLAN

    function addSellChallan()

    {

        $iRes = $this->validate->addSellChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->addSellChallan($iRes['data']);

        response($iProfile);
    }



    function updateSellChallan()

    {

        $iRes = $this->validate->updateSellChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->updateSellChallan($iRes['data']);

        response($iProfile);
    }



    function deleteSellChallan()

    {

        $iRes = $this->validate->deleteSellChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->model_challan->deleteSellChallan($iRes['data']);

        response($iResult);
    }



    function deleteSellChallanproduct()

    {

        $iRes = $this->validate->deleteSellChallanproduct($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->model_challan->deleteSellChallanproduct($iRes['data']);

        response($iResult);
    }



    function getSellChallan()

    {

        $iRes = $this->validate->getSellChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->getSellChallan($iRes['data']);

        response($iProfile);
    }

    //PURCHASE CAHALLAN

    function addPurchaseChallan()

    {

        $iRes = $this->validate->addPurchaseChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->addPurchaseChallan($iRes['data']);

        response($iProfile);
    }



    function updatePurchaseChallan()

    {

        $iRes = $this->validate->updatePurchaseChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->updatePurchaseChallan($iRes['data']);

        response($iProfile);
    }



    function deletePurchaseChallan()

    {

        $iRes = $this->validate->deletePurchaseChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->model_challan->deletePurchaseChallan($iRes['data']);

        response($iResult);
    }



    function deletePurchaseChallanproduct()

    {

        $iRes = $this->validate->deletePurchaseChallanproduct($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iResult = $this->model_challan->deletePurchaseChallanproduct($iRes['data']);

        response($iResult);
    }



    function getPurchaseChallan()

    {

        $iRes = $this->validate->getPurchaseChallan($this->param);

        if ($iRes['statuscode'] != 1)

            response($iRes);

        $iProfile = $this->model_challan->getPurchaseChallan($iRes['data']);

        response($iProfile);
    }
}
