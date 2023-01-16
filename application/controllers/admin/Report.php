<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view()
    {
        $this->load->view("admin/report_view");
    }

    function overall_profit()
    {
        $ibuyAmount = "SELECT invoices_date,MONTH(purchase_invoices.invoices_date) as month, YEAR(purchase_invoices.invoices_date) as year, SUM(purchase_invoices.invoices_total) as buyamount, SUM(purchase_invoices.product_sgst + purchase_invoices.product_cgst) as buygst FROM purchase_invoices  GROUP BY month,year ORDER BY year DESC, month ASC";
        $iPurchaseinvoices = $this->model_api->execute_query($ibuyAmount);

        $isellAmount = "SELECT invoices_date,MONTH(sell_invoices.invoices_date) as month, YEAR(sell_invoices.invoices_date) as year, SUM(sell_invoices.invoices_total) as sellamount, SUM(sell_invoices.product_sgst + sell_invoices.product_cgst) as sellgst FROM sell_invoices GROUP BY month,year ORDER BY year DESC, month ASC";
        $iSellinvoices = $this->model_api->execute_query($isellAmount);

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

        $res['allprofits'] = $Data;
        $this->load->view("admin/overall_profit_view", $res);
    }

    function overall_stocks()
    {
        $iQuery = "SELECT id,product_name FROM product_detailes";
        $iProducts = $this->model_api->execute_query($iQuery);

        foreach ($iProducts['data'] as $key => $iProduct) {
            $productId = $iProduct['id'];

            $buyQty = "SELECT id,product_id, SUM(product_quantity) as buyqty FROM purchase_product_detailes WHERE product_id = {$productId}";
            $buyproductQtys = $this->model_api->execute_query($buyQty);

            $Qty = "SELECT id,product_id, SUM(product_quantity) as sellqty FROM sell_product_detailes WHERE product_id = {$productId}";
            $sellproductQtys = $this->model_api->execute_query($Qty);

            foreach ($buyproductQtys['data'] as $Qkey => $buyproductQty) {
            }
            foreach ($sellproductQtys['data'] as $Qkey => $sellproductQty) {
            }
            $iProducts['data'][$key]['buyproductQty'] = $buyproductQty['buyqty'];
            $iProducts['data'][$key]['sellproductQty'] = $sellproductQty['sellqty'];
            $iProducts['data'][$key]['remain'] = $buyproductQty['buyqty'] - $sellproductQty['sellqty'];
        }

        $res['produts'] = $iProducts['data'];
        $this->load->view("admin/overall_stocks_view", $res);
    }

    function stock_detail($date)
    {
        $ibuyinvoices = "SELECT purchase_product_detailes.product_id,product_detailes.product_name as productname,SUM(purchase_product_detailes.product_quantity) as produtqty FROM purchase_invoices JOIN purchase_product_detailes JOIN product_detailes ON (product_detailes.id = purchase_product_detailes.product_id) WHERE date_format(purchase_invoices.invoices_date, '%m-%Y' ) = '{$date}' AND purchase_invoices.id = purchase_product_detailes.purchase_invoices_id GROUP BY purchase_product_detailes.product_id";
        $iBuyInvoices = $this->model_api->execute_query($ibuyinvoices);

        $isellinvoices = "SELECT sell_product_detailes.product_id,product_detailes.product_name as productname,SUM(sell_product_detailes.product_quantity) as produtqty FROM sell_invoices JOIN sell_product_detailes JOIN product_detailes ON (product_detailes.id = sell_product_detailes.product_id) WHERE date_format(sell_invoices.invoices_date, '%m-%Y' ) = '{$date}' AND sell_invoices.id = sell_product_detailes.sell_invoice_id GROUP BY sell_product_detailes.product_id";
        $iSellInvoices = $this->model_api->execute_query($isellinvoices);

        $Data = array();
        foreach ($iBuyInvoices['data'] as $key => $iBuyInvoice) {
            foreach ($iSellInvoices['data'] as $keys => $iSellInvoice) {
                if ($iBuyInvoice['product_id'] == $iSellInvoice['product_id']) {
                    $Data[$key]['productid'] = $iBuyInvoice['product_id'];
                    $Data[$key]['productname'] = $iBuyInvoice['productname'];
                    $Data[$key]['buyqty'] = $iBuyInvoice['produtqty'];
                    $Data[$key]['sellqty'] = $iSellInvoice['produtqty'];
                    $Data[$key]['remain'] = $iBuyInvoice['produtqty'] - $iSellInvoice['produtqty'];
                }
            }
        }

        $res['produtdetails'] = $Data;
        $this->load->view("admin/stock_detail", $res);
    }

    function report_detail($date)
    {
        $ibuyAmount = "SELECT purchase_invoices.id,purchase_invoices.invoices_no,purchase_invoices.invoices_total,purchase_invoices.invoices_date,shopper.trade_name as tradename, SUM(purchase_invoices.invoices_total) as total, SUM(purchase_invoices.gst) as totalgst FROM purchase_invoices JOIN shopper ON (purchase_invoices.shopper_id = shopper.id) WHERE date_format(purchase_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $ibuys = $this->model_api->execute_query($ibuyAmount);

        foreach ($ibuys['data'] as $key => $ibuy) {
            $buyId = $ibuy['id'];
            $ibuyBank = "SELECT invoices_id,payment_date,cheque_number,amount, SUM(purchase_invoices_payments.amount) as total FROM purchase_invoices_payments WHERE invoices_id = '{$buyId}'";
            $buybanks = $this->model_api->execute_query($ibuyBank);
            foreach ($buybanks['data'] as $key => $buypaymentdetailes) {
                $ibuys['data'][$key]['buypayment'] = $buypaymentdetailes;
            }
        }

        $isellAmount = "SELECT sell_invoices.id,sell_invoices.invoices_no,sell_invoices.gst,sell_invoices.invoices_total,sell_invoices.invoices_date,customer.trade_name as tradename, SUM(sell_invoices.invoices_total) as total, SUM(sell_invoices.gst) as totalgst FROM sell_invoices JOIN customer ON (sell_invoices.customer_id = customer.id) WHERE date_format(sell_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $isells = $this->model_api->execute_query($isellAmount);

        foreach ($isells['data'] as $key => $isell) {
            $sellId = $isell['id'];
            $isellBank = "SELECT invoices_id,payment_date,cheque_number,amount, SUM(sell_invoices_payments.amount) as total FROM sell_invoices_payments WHERE invoices_id = '{$sellId}'";
            $sellbanks = $this->model_api->execute_query($isellBank);
            foreach ($sellbanks['data'] as $key => $sellpaymentdetailes) {
                $isells['data'][$key]['sellpayment'] = $sellpaymentdetailes;
            }
        }

        $res['buys'] = $ibuys['data'];
        $res['sells'] = $isells['data'];
        $this->load->view("admin/report_detail", $res);
    }

    function purchase_detail($date)
    {
        $ibuyAmount = "SELECT purchase_invoices.invoices_no,purchase_invoices.invoices_date,purchase_invoices.sub_total,purchase_invoices.gst,purchase_invoices.invoices_total,shopper.trade_name as tradename FROM purchase_invoices JOIN shopper ON (purchase_invoices.shopper_id = shopper.id) WHERE date_format(purchase_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $iBuys = $this->model_api->execute_query($ibuyAmount);

        $iAmount = "SELECT purchase_invoices.id, SUM(purchase_invoices.gst) as gst, SUM(purchase_invoices.sub_total) as subtotal, SUM(purchase_invoices.invoices_total) as total FROM purchase_invoices WHERE date_format(purchase_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $iTotal = $this->model_api->execute_query($iAmount);

        $res['buyinvoices'] = $iBuys['data'];
        $res['total'] = $iTotal['data'];
        $this->load->view("admin/purchase_detail_view.php", $res);
    }

    function sell_detail($date)
    {
        $isellAmount = "SELECT sell_invoices.invoices_no,sell_invoices.invoices_date,sell_invoices.sub_total,sell_invoices.gst,sell_invoices.invoices_total,customer.trade_name as tradename FROM sell_invoices JOIN customer ON (sell_invoices.customer_id = customer.id) WHERE date_format(sell_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $isells = $this->model_api->execute_query($isellAmount);

        $iAmount = "SELECT sell_invoices.id, SUM(sell_invoices.gst) as gst, SUM(sell_invoices.sub_total) as subtotal, SUM(sell_invoices.invoices_total) as total FROM sell_invoices WHERE date_format(sell_invoices.invoices_date, '%m-%Y' ) = '{$date}'";
        $iTotal = $this->model_api->execute_query($iAmount);

        $res['sellinvoices'] = $isells['data'];
        $res['total'] = $iTotal['data'];
        $this->load->view("admin/sell_detail_view", $res);
    }

    function report_list()
    {
        $iWhere = " WHERE id IS NOT NULL";

        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['month']) && $this->param['month'] != '')
                $iWhere .= " AND purchase_invoices.month LIKE '%" . $this->param['month'] . "%'";
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(MONTH(purchase_invoices.invoices_date)) cnt FROM purchase_invoices" . $iWhere);
        $iTotalRecords = isset($iResCount['data'][0]['cnt']) ? $iResCount['data'][0]['cnt'] : 0;
        $iDisplayLength = intval($this->param['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($this->param['start']);
        $sEcho = intval($this->param['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $ibuyAmount = "SELECT invoices_date,SUM(purchase_invoices.invoices_total) as buyamount, SUM(purchase_invoices.product_sgst + purchase_invoices.product_cgst) as buygst, MONTHNAME(purchase_invoices.invoices_date) as monthname, MONTH(purchase_invoices.invoices_date) as month, YEAR(purchase_invoices.invoices_date) as year FROM purchase_invoices GROUP BY month,year ORDER BY year DESC, month DESC";
        $iPurchaseinvoices = $this->model_api->execute_query($ibuyAmount);

        $isellAmount = "SELECT invoices_date,SUM(sell_invoices.invoices_total) as sellamount, SUM(sell_invoices.product_sgst + sell_invoices.product_cgst) as sellgst, MONTHNAME(sell_invoices.invoices_date) as monthname, MONTH(sell_invoices.invoices_date) as month, YEAR(sell_invoices.invoices_date) as year FROM sell_invoices GROUP BY month,year ORDER BY year DESC, month DESC";
        $iSellinvoices = $this->model_api->execute_query($isellAmount);

        foreach ($iPurchaseinvoices['data'] as $key => $iPurchaseinvoice) {
            foreach ($iSellinvoices['data'] as $keys => $iSellinvoice) {
                $date = date("M-Y", strtotime($iPurchaseinvoice['invoices_date']));
                $buydate = date("m-Y", strtotime($iPurchaseinvoice['invoices_date']));
                $selldate = date("m-Y", strtotime($iSellinvoice['invoices_date']));
                for ($i = 1; $i <= $key; $i++) {
                }
                if ($buydate == $selldate) {
                    $records["data"][] = array(
                        $i,
                        $date,
                        $iPurchaseinvoice['buygst'],
                        $iPurchaseinvoice['buyamount'] . ' ' . '<a href="' . BASE_URL . "admin/report/purchase_detail/" . ($buydate) . '" target="_blank" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Invoice</a>',
                        $iSellinvoice['sellgst'],
                        $iSellinvoice['sellamount'] . ' ' . '<a href="' . BASE_URL . "admin/report/sell_detail/" . ($buydate) . '" target="_blank" class="btn btn-sm btn-outline yellow"><i class="fa fa-eye"></i> Invoice</a>',
                        $iPurchaseinvoice['buygst'] - $iSellinvoice['sellgst'],
                        $iPurchaseinvoice['buyamount'] - $iSellinvoice['sellamount'],
                        '<a href="' . BASE_URL . "admin/report/stock_detail/" . ($buydate) . '" target="_blank" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Stock</a> <a href="' . BASE_URL . "admin/report/report_detail/" . ($buydate) . '" target="_blank" class="btn btn-sm btn-outline red"><i class="fa fa-eye"></i> Report</a>',
                    );
                }
            }
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
