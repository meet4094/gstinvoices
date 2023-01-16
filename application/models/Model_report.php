<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model_report extends CI_Model
{

    function getStockReport($param)
    {
        $userId = user_id();

        $iStart = isset($param['start']) ? $param['start'] : 0;
        $iLen = isset($param['len']) ? $param['len'] : 10;
        $searchKey = isset($param['search_key']) ? $param['search_key'] : '';

        $iLimit = '';
        if ($iStart != '-1')
            $iLimit = "LIMIT {$iStart},{$iLen}";
        $iWhere = " WHERE is_del = 0";

        if ($searchKey != '')
            $iWhere .= " AND product_name LIKE '%$searchKey%'";

        $productdetailes = "SELECT id,product_name FROM product_detailes {$iWhere} AND user_id = $userId ORDER BY product_detailes.id DESC {$iLimit}";
        $iProductdetailes = $this->model_api->execute_query($productdetailes);

        foreach ($iProductdetailes['data'] as $key => $iProductdetaile) {
            $productId = $iProductdetaile['id'];

            $purchaseinvoices = "SELECT SUM(product_quantity) as productqty FROM purchase_product_detailes WHERE product_id = $productId";
            $iPurchaseinvoices = $this->model_api->execute_query($purchaseinvoices);
            foreach ($iPurchaseinvoices['data'] as $keys => $iProductdetaile) {
                if ($iProductdetaile['productqty'] == null) {
                    $buyproductqty = 0;
                } else {
                    $buyproductqty = $iProductdetaile['productqty'];
                }
                $iProductdetailes['data'][$key]['buyqty'] = $buyproductqty;
            }
            $sellinvoices = "SELECT SUM(product_quantity) as productqty FROM sell_product_detailes WHERE product_id = $productId";
            $iSellinvoices = $this->model_api->execute_query($sellinvoices);
            foreach ($iSellinvoices['data'] as $keys => $iProductdetaile) {
                if ($iProductdetaile['productqty'] == null) {
                    $sellproductqty = 0;
                } else {
                    $sellproductqty = $iProductdetaile['productqty'];
                }
                $iProductdetailes['data'][$key]['sellqty'] = $sellproductqty;
            }
            $iProductdetailes['data'][$key]['remain'] = $buyproductqty - $sellproductqty;
        }

        $iRes = success_res("success");
        $iRes = $iProductdetailes;
        return $iRes;
    }

    function getProfitReport($param)
    {
        $userId = user_id();
        $iBuyQry = "SELECT invoices_date,SUM(purchase_invoices.product_sgst + purchase_invoices.product_cgst) as buygst, SUM(purchase_invoices.invoices_total) as buyamount, MONTH(purchase_invoices.invoices_date) as buymonth,YEAR(purchase_invoices.invoices_date) as buyyear FROM purchase_invoices WHERE purchase_invoices.user_id = $userId GROUP BY buymonth,buyyear ORDER BY buymonth DESC, buyyear DESC";
        $iPurchaseinvoices = $this->model_api->execute_query($iBuyQry);

        $iSellQry = "SELECT invoices_date,SUM(sell_invoices.product_sgst + sell_invoices.product_cgst) as sellgst, SUM(sell_invoices.invoices_total) as sellamount, MONTH(sell_invoices.invoices_date) as sellmonth,YEAR(sell_invoices.invoices_date) as sellyear FROM sell_invoices WHERE user_id = $userId GROUP BY sellmonth,sellyear ORDER BY sellmonth DESC, sellyear DESC";
        $iSellinvoices = $this->model_api->execute_query($iSellQry);

        $Data = array();
        foreach ($iPurchaseinvoices['data'] as $key => $iPurchaseinvoice) {
            foreach ($iSellinvoices['data'] as $keys => $iSellinvoice) {
                $buydate = date("m-Y", strtotime($iPurchaseinvoice['invoices_date']));
                $selldate = date("m-Y", strtotime($iSellinvoice['invoices_date']));
                if ($buydate == $selldate) {
                    $Data[$key]['month'] = $buydate;
                    $Data[$key]['buygst'] = $iPurchaseinvoice['buygst'];
                    $Data[$key]['buyamount'] = $iPurchaseinvoice['buyamount'];
                    $Data[$key]['sellgst'] = $iSellinvoice['sellgst'];
                    $Data[$key]['sellamount'] = $iSellinvoice['sellamount'];
                    $Data[$key]['differentgst'] = $iPurchaseinvoice['buygst'] - $iSellinvoice['sellgst'];
                    $Data[$key]['differentamount'] = $iPurchaseinvoice['buyamount'] - $iSellinvoice['sellamount'];
                }
            }
        }
        $iRes = success_res("success");
        $iRes['data'] = $Data;
        return $iRes;
    }
}
