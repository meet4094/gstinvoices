<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Purchase_invoice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view($id = 0)
    {
        $ShopperLists = $this->model_api->get("shopper", array("id,trade_name"), array("and" => array("is_del" => 0)));

        $res['id'] = $id;
        $res['ShopperLists'] = $ShopperLists['data'];
        $this->load->view("admin/purchase_invoice_view", $res);
    }
    function Moredetaile($id)
    {
        $PurchaseInvoices = $this->model_api->get("purchase_invoices", array("id,user_id,shopper_id,invoices_date,invoices_no,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,created_date,is_del"), array("and" => array("id" => $id)));
        $ShoperId = $PurchaseInvoices['data'][0]['shopper_id'];
        $UserId = $PurchaseInvoices['data'][0]['user_id'];
        $ShopperLists = $this->model_api->get("shopper", array("id,trade_name,mobile_number,address,gst_number"), array("and" => array("id" => $ShoperId)));
        $UserLists = $this->model_api->get("sh_users", array("uid,trade_name,mobile_number,address,gst_number"), array("and" => array("uid" => $UserId)));
        $PurchaseInvoicesProducts = $this->model_api->get("purchase_product_detailes", array("id,purchase_invoices_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,created_date,is_del"), array("and" => array("purchase_invoices_id" => $id)));

        foreach ($PurchaseInvoicesProducts['data'] as $key => $PurchaseInvoicesProduct) {
            $productId = $PurchaseInvoicesProduct['product_id'];
        }
        $ProductLists = $this->model_api->get("product_detailes", array("id,product_name,product_code,created_date,is_del"), array("and" => array("id" => $productId)));

        $res['id'] = $id;
        $res['ShopperLists'] = $ShopperLists['data'][0];
        $res['UserLists'] = $UserLists['data'][0];
        $res['PurchaseInvoices'] = $PurchaseInvoices['data'][0];
        $res['PurchaseInvoicesProducts'] = $PurchaseInvoicesProducts['data'][0];
        $res['ProductLists'] = $ProductLists['data'];
        $this->load->view("admin/purchase_invoice_moredetaile", $res);
    }

    function add()
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $shoppers = $this->model_api->get("shopper", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            // $inserted_fields['user_id'] = $this->param['user_id'];
            // $inserted_fields['shopper_id'] = $this->param['shopper_id'];
            // $inserted_fields['invoices_date'] = $this->param['invoices_date'];
            // $inserted_fields['invoices_no'] = $this->param['invoices_no'];
            // $inserted_fields['gst'] = $this->param['gst'];
            // $inserted_fields['sub_total'] = $this->param['sub_total'];
            // $inserted_fields['product_sgst'] = $this->param['product_sgst'];
            // $inserted_fields['product_cgst'] = $this->param['product_cgst'];
            // $inserted_fields['round_off'] = $this->param['round_off'];
            // $inserted_fields['invoices_total'] = $this->param['invoices_total'];
            // $inserted_fields['is_del'] = $this->param['is_del'];
            // $status = $this->model_api->add("purchase_invoices", $inserted_fields);

            // $Id = $status['data']['id'];

            foreach ($this->param['product_id'] as $key => $productid) {
                $inserted_field['product_id'] = $productid;
                // $status = $this->model_api->add("purchase_product_detailes", $inserted_field);
                // $inserted_field['purchase_invoices_id'] = $Id;
                // if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                //     $res = error_res();
                // } else {
                //     $res = success_res();
                // }
            }
            print_r($inserted_field);
            // foreach ($this->param['product_hsn_sac'] as $key => $producthsnsac) {
            //     foreach ($this->param['product_quantity'] as $key => $productquantity) {
            //         foreach ($this->param['product_rate'] as $key => $productrate) {
            //             foreach ($this->param['total_amount'] as $key => $totalamount) {
            //                 $inserted_field['product_hsn_sac'] = $producthsnsac;
            //                 $inserted_field['product_quantity'] = $productquantity;
            //                 $inserted_field['product_rate'] = $productrate;
            //                 $inserted_field['total_amount'] = $totalamount;
            //             }
            //         }
            //     }
            // }

            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/purchase_invoices_add", $res);
        } else {
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/purchase_invoices_add", $res);
        }
    }

    function edit($id)
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $shoppers = $this->model_api->get("shopper", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['shopper_id'] = $this->param['shopper_id'];
            $inserted_fields['invoices_date'] = $this->param['invoices_date'];
            $inserted_fields['invoices_no'] = $this->param['invoices_no'];
            $inserted_fields['gst'] = $this->param['gst'];
            $inserted_fields['sub_total'] = $this->param['sub_total'];
            $inserted_fields['product_sgst'] = $this->param['product_sgst'];
            $inserted_fields['product_cgst'] = $this->param['product_cgst'];
            $inserted_fields['round_off'] = $this->param['round_off'];
            $inserted_fields['invoices_total'] = $this->param['invoices_total'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $inserted_fields['modified_date'] = date("Y-m-d H:i:s");
            $status = $this->model_api->update("purchase_invoices", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $edit_purchase_invoices = $this->model_api->get("purchase_invoices", array("id,user_id,shopper_id,invoices_date,invoices_no,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("purchase_product_detailes", array("id,purchase_invoices_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,"), array("and" => array("purchase_invoices_id" => $id)));

            $res['edit_purchase_invoices'] = $edit_purchase_invoices['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $this->load->view("admin/purchase_invoices_edit", $res);
        } else {
            $edit_purchase_invoices = $this->model_api->get("purchase_invoices", array("id,user_id,shopper_id,invoices_date,invoices_no,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("purchase_product_detailes", array("id,purchase_invoices_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,"), array("and" => array("purchase_invoices_id" => $id)));

            $res['edit_purchase_invoices'] = $edit_purchase_invoices['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $this->load->view("admin/purchase_invoices_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM purchase_invoices WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/purchase_invoices/view");
    }

    function purchaseinvoice_list($id = 0)
    {
        $iWhere = " WHERE purchase_invoices.id IS NOT NULL";
        if ($id != 0) {
            $iWhere .= " AND purchase_invoices.shopper_id = '$id'";
        }
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['invoices_date']) && $this->param['invoices_date'] != '')
                $iWhere .= " AND purchase_invoices.invoices_date LIKE '%" . $this->param['purchase_invoices.invoices_date'] . "%'";
            if (isset($this->param['invoices_no']) && $this->param['invoices_no'] != '')
                $iWhere .= " AND purchase_invoices.invoices_no LIKE '%" . $this->param['purchase_invoices.invoices_no'] . "%'";
            if (isset($this->param['shopper_id']) && $this->param['shopper_id'] != '')
                $iWhere .= " AND purchase_invoices.shopper_id LIKE '%" . $this->param['purchase_invoices.shopper_id'] . "%'";
            if (isset($this->param['payment_status']) && $this->param['payment_status'] != '')
                $iWhere .= " AND purchase_invoices.payment_status = " . $this->param['payment_status'];
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND purchase_invoices.is_del = " . $this->param['purchase_invoices.is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(purchase_invoices.id) cnt FROM purchase_invoices" . $iWhere);
        $iTotalRecords = isset($iResCount['data'][0]['cnt']) ? $iResCount['data'][0]['cnt'] : 0;
        $iDisplayLength = intval($this->param['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($this->param['start']);
        $sEcho = intval($this->param['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $statusList = array(
            array("success" => "Enable"),
            array("danger" => "Disable")
        );
        $paymentList = array(
            array("danger" => "Panding"),
            array("success" => "Completed")
        );

        $iLimit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
        $iQry = "SELECT purchase_invoices.id,purchase_invoices.invoices_date,purchase_invoices.invoices_no,purchase_invoices.shopper_id,purchase_invoices.invoices_total,purchase_invoices.created_date,purchase_invoices.payment_status,purchase_invoices.is_del,shopper.trade_name as shoppername FROM purchase_invoices JOIN shopper ON (purchase_invoices.shopper_id = shopper.id) {$iWhere} ORDER BY purchase_invoices.id DESC {$iLimit}";
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $PaList = $paymentList[$iUser['payment_status']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['shoppername'],
                $iUser['invoices_date'],
                $iUser['invoices_no'],
                $iUser['invoices_total'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($PaList)) . '">' . (current($PaList)) . '</span>',
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/Purchase_invoice/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a> <a href="' . BASE_URL . "admin/Purchase_invoice/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
                '<a href="' . BASE_URL . "admin/purchase_invoices_payment/add/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"></i> Payment</a> <a href="' . BASE_URL . "admin/Purchase_invoice/Moredetaile/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-eye"></i> Invoice View</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
