<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Shopper extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("model_shopper");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function addShopper()
    {
        $iRes = $this->validate->addShopper($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_shopper->addShopper($iRes['data']);
        response($iProfile);
    }

    function updateShopper()
    {
        $iRes = $this->validate->updateShopper($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_shopper->updateShopper($iRes['data']);
        response($iProfile);
    }

    function deleteShopper()
    {
        $iRes = $this->validate->deleteShopper($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iResult = $this->model_shopper->deleteShopper($iRes['data']);
        response($iResult);
    }

    function getShopper()
    {
        $iRes = $this->validate->getShopper($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->model_shopper->getShopper($iRes['data']);
        response($iProfile);
    }
}
