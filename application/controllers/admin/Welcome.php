<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    function index()
    {
        echo "<center>Welcome to Admin Panel.</center>";
    }

    function dashboard()
    {
        $iQry = "SELECT COUNT(uid) cnt FROM sh_users";
        $iUserCount = $this->model_api->execute_query($iQry);
        $iDashboard['iUserCount'] = isset($iUserCount['data'][0]['cnt']) ? $iUserCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT COUNT(id) AS cnt FROM product_detailes";
        $iProductCount = $this->model_api->execute_query($iQry);
        $iDashboard['iproductCount'] = isset($iProductCount['data'][0]['cnt']) ? $iProductCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT COUNT(id) AS cnt FROM shopper";
        $iShopperCount = $this->model_api->execute_query($iQry);
        $iDashboard['iShoppersCount'] = isset($iShopperCount['data'][0]['cnt']) ? $iShopperCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT COUNT(id) AS cnt FROM customer";
        $iCustomerCount = $this->model_api->execute_query($iQry);
        $iDashboard['iCustomerCount'] = isset($iCustomerCount['data'][0]['cnt']) ? $iCustomerCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT COUNT(id) AS cnt FROM purchase_invoices";
        $iPurchaseInvoicesCount = $this->model_api->execute_query($iQry);
        $iDashboard['iPurchaseInvoicesCount'] = isset($iPurchaseInvoicesCount['data'][0]['cnt']) ? $iPurchaseInvoicesCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT COUNT(id) AS cnt FROM sell_invoices";
        $iSellInvoicesCount = $this->model_api->execute_query($iQry);
        $iDashboard['iSellInvoicesCount'] = isset($iSellInvoicesCount['data'][0]['cnt']) ? $iSellInvoicesCount['data'][0]['cnt'] : 0;

        $iQry = "SELECT Year(created_date) AS year, Month(created_date) AS month, DAY(created_date) AS day, count(created_date) AS user FROM sh_users GROUP BY DATE(created_date) ORDER BY DATE(created_date) DESC LIMIT 30";
        $iUserRegState = $this->model_api->execute_query($iQry);
        $iDashboard['iUserRegState'] = isset($iUserRegState['data']) ? array_reverse($iUserRegState['data']) : new stdclass();

        $this->load->view('admin/dashboard', $iDashboard);
    }
}
