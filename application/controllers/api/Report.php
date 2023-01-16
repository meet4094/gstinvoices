<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!defined('ROUTE_ACCESS'))
            exit('No Access');
        $this->load->model("Model_report");
    }

    function index()
    {
        _e("<h1><center>Access Denied!!</center></h1>");
    }

    function getStockReport()
    {
        $iRes = $this->validate->getStockReport($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_report->getStockReport($iRes['data']);
        response($iProfile);
    }
    function getProfitReport()
    {
        $iRes = $this->validate->getProfitReport($this->param);
        if ($iRes['statuscode'] != 1)
            response($iRes);
        $iProfile = $this->Model_report->getProfitReport($iRes['data']);
        response($iProfile);
    }
}
