<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sell_invoice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view($id = 0)
    {
        $CustomerLists = $this->model_api->get("customer", array("id,trade_name"), array("and" => array("is_del" => 0)));

        $res['id'] = $id;
        $res['CustomerLists'] = $CustomerLists['data'];
        $this->load->view("admin/sell_invoice_view", $res);
    }

    function add()
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $customers = $this->model_api->get("customer", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['customer_id'] = $this->param['customer_id'];
            $inserted_fields['invoices_date'] = $this->param['invoices_date'];
            $inserted_fields['invoices_no'] = $this->param['invoices_no'];
            $inserted_fields['gst'] = $this->param['gst'];
            $inserted_fields['sub_total'] = $this->param['sub_total'];
            $inserted_fields['product_sgst'] = $this->param['product_sgst'];
            $inserted_fields['product_cgst'] = $this->param['product_cgst'];
            $inserted_fields['round_off'] = $this->param['round_off'];
            $inserted_fields['invoices_total'] = $this->param['invoices_total'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $status = $this->model_api->add("sell_invoices", $inserted_fields);

            $Id = $status['data']['id'];

            foreach ($this->param['product_id'] as $key => $productid) {
                $inserted_field['challan_id'] = $Id;
                $inserted_field['product_id'] = $productid;
                $inserted_field['product_quantity'] = $this->param['product_quantity'][$key];
                $inserted_field['product_rate'] = $this->param['product_rate'][$key];
                $inserted_field['total_amount'] = $this->param['total_amount'][$key];
                $status = $this->model_api->add("sell_product_detailes", $inserted_field);
                if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                    $res = error_res();
                } else {
                    $res = success_res();
                }
            }

            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/sell_invoices_add", $res);
        } else {
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/sell_invoices_add", $res);
        }
    }

    function edit($id)
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $customers = $this->model_api->get("customer", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['customer_id'] = $this->param['customer_id'];
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
            $status = $this->model_api->update("sell_invoices", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }

            $sellinvoicesproducts = $this->model_api->get("sell_product_detailes", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));
            foreach ($sellinvoicesproducts['data'] as $skey => $sellchallanproduct) {
                $sellinvoicesproductId = $sellchallanproduct['id'];
                foreach ($this->param['product_id'] as $key => $productid) {
                    $inserted_field['product_id'] = $productid;
                    $inserted_field['product_quantity'] = $this->param['product_quantity'][$key];
                    $inserted_field['product_rate'] = $this->param['product_rate'][$key];
                    $inserted_field['total_amount'] = $this->param['total_amount'][$key];
                    $status = $this->model_api->update("sell_product_detailes", $inserted_field, array("and" => array("id" => $sellinvoicesproductId)));
                    if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                        $res = error_res();
                    } else {
                        $res = success_res();
                    }
                }
            }

            $edit_sell_invoices = $this->model_api->get("sell_invoices", array("id,user_id,customer_id,invoices_date,invoices_no,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("sell_product_detailes", array("id,sell_invoice_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,"), array("and" => array("sell_invoice_id" => $id)));

            $res['edit_sell_invoices'] = $edit_sell_invoices['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $this->load->view("admin/sell_invoices_edit", $res);
        } else {
            $edit_sell_invoices = $this->model_api->get("sell_invoices", array("id,user_id,customer_id,invoices_date,invoices_no,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("sell_product_detailes", array("id,sell_invoice_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,"), array("and" => array("sell_invoice_id" => $id)));

            $res['edit_sell_invoices'] = $edit_sell_invoices['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $this->load->view("admin/sell_invoices_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM sell_invoices WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/sell_invoices/view");
    }

    function sellinvoice_list($id = 0)
    {
        $iWhere = " WHERE sell_invoices.id IS NOT NULL";
        if ($id != 0) {
            $iWhere .= " AND sell_invoices.customer_id = '$id'";
        }
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['invoices_date']) && $this->param['invoices_date'] != '')
                $iWhere .= " AND sell_invoices.invoices_date = " . $this->param['invoices_date'];
            if (isset($this->param['customer_id']) && $this->param['customer_id'] != '')
                $iWhere .= " AND sell_invoices.customer_id = " . $this->param['customer_id'];
            if (isset($this->param['payment_status']) && $this->param['payment_status'] != '')
                $iWhere .= " AND sell_invoices.payment_status = " . $this->param['payment_status'];
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND sell_invoices.is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(sell_invoices.id) cnt FROM sell_invoices" . $iWhere);
        $iTotalRecords = isset($iResCount['data'][0]['cnt']) ? $iResCount['data'][0]['cnt'] : 0;
        $iDisplayLength = intval($this->param['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($this->param['start']);
        $sEcho = intval($this->param['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $paymentList = array(
            array("danger" => "Panding"),
            array("success" => "Completed")
        );
        $statusList = array(
            array("success" => "Enable"),
            array("danger" => "Disable")
        );

        $iLimit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
        $iQry = "SELECT sell_invoices.id,sell_invoices.invoices_date,sell_invoices.invoices_no,sell_invoices.invoices_total,sell_invoices.payment_status,sell_invoices.created_date,sell_invoices.is_del,customer.trade_name as customername FROM sell_invoices JOIN customer ON (customer.id = sell_invoices.customer_id) {$iWhere} ORDER BY sell_invoices.id DESC {$iLimit}";
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $PaList = $paymentList[$iUser['payment_status']];
            $iStatus = $statusList[$iUser['is_del']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['customername'],
                $iUser['invoices_date'],
                $iUser['invoices_no'],
                $iUser['invoices_total'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($PaList)) . '">' . (current($PaList)) . '</span>',
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/sell_invoice/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a> <a href="' . BASE_URL . "admin/sell_invoice/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
                '<a href="' . BASE_URL . "admin/Sell_invoices_payment/add/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue">Payment</a> <a href="' . BASE_URL . "admin/sell_invoicespdf/Invoices_pdf/" . $iUser['id'] . '" class="btn btn-sm btn-outline yellow"   ><i class="fa fa-file-pdf-o"></i> Pdf</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
