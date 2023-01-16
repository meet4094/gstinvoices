<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("model_product");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function addProduct()
    {
        $iRes = $this->validate->addProduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_product->addProduct($iRes['data']);
        response($iProfile);
    }

    function updateProduct()
    {
        $iRes = $this->validate->updateProduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_product->updateProduct($iRes['data']);
        response($iProfile);
    }

    function deleteProduct()
    {
        $iRes = $this->validate->deleteProduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_product->deleteProduct($iRes['data']);
        response($iResult);
    }

    function getProduct()
    {
        $iRes = $this->validate->getProduct($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_product->getProduct($iRes['data']);
        response($iProfile);
    }
}
