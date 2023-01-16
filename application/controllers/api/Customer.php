<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customer extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("model_customer");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function addCustomer()
    {
        $iRes = $this->validate->addCustomer($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_customer->addCustomer($iRes['data']);
        response($iProfile);
    }

    function updateCustomer()
    {
        $iRes = $this->validate->updateCustomer($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_customer->updateCustomer($iRes['data']);
        response($iProfile);
    }

    function deleteCustomer()
    {
        $iRes = $this->validate->deleteCustomer($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_customer->deleteCustomer($iRes['data']);
        response($iResult);
    }

    function getCustomer()
    {
        $iRes = $this->validate->getCustomer($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_customer->getCustomer($iRes['data']);
        response($iProfile);
    }
}
