<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Purchase_invoices_payment extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view()
    {
        $shopperLists = $this->model_api->get("shopper", array("id,trade_name,is_del"), array("and" => array("is_del" => 0)));
        $res['shopperLists'] = $shopperLists['data'];
        $this->load->view("admin/purchase_invoices_payment_view", $res);
    }

    function add($id)
    {
        $iQry = "SELECT purchase_invoices.id,purchase_invoices.user_id,purchase_invoices.shopper_id,purchase_invoices.invoices_no,purchase_invoices.invoices_total,shopper.trade_name as shoppername FROM purchase_invoices JOIN shopper ON (shopper.id = purchase_invoices.shopper_id) WHERE purchase_invoices.id = {$id}";
        $invoicesLists = $this->model_api->execute_query($iQry);

        $purchase = "SELECT SUM(transfer_amount) as transfer_amount FROM purchase_invoices_payments WHERE invoices_id = {$id}";
        $purchaseinvoices = $this->model_api->execute_query($purchase);

        if (empty($purchaseinvoices['data'][0]['transfer_amount'])) {
            $transferamount = '0';
        } else {
            $transferamount = $purchaseinvoices['data'][0]['transfer_amount'];
        }

        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $invoicesLists['data'][0]['user_id'];
            $inserted_fields['invoices_id'] = $id;
            $inserted_fields['shopper_id'] = $invoicesLists['data'][0]['shopper_id'];
            $inserted_fields['invoices_no'] = $invoicesLists['data'][0]['invoices_no'];
            $inserted_fields['payment_mode'] = $this->param['payment_mode'];
            $inserted_fields['payment_date'] = $this->param['payment_date'];
            $inserted_fields['invoices_total'] = $invoicesLists['data'][0]['invoices_total'];
            $inserted_fields['amount'] = $this->param['amount'];
            $inserted_fields['cheque_number'] = $this->param['cheque_number'];
            $inserted_fields['bank_detail'] = $this->param['bank_detail'];
            $inserted_fields['transaction_detail'] = $this->param['transaction_detail'];
            $inserted_fields['transfer_amount'] = $transferamount + $this->param['amount'];

            if ($invoicesLists['data'][0]['invoices_total'] == $this->param['amount']) {
                $inserted_fields['status'] = '1';
            } else if ($invoicesLists['data'][0]['invoices_total'] == ($transferamount + $this->param['amount'])) {
                $inserted_fields['status'] = '1';
            } else {
                $inserted_fields['status'] = '0';
            }

            $status = $this->model_api->add("purchase_invoices_payments", $inserted_fields);
            $this->model_api->update("purchase_invoices", array("payment_status" => $inserted_fields['status']), array("and" => array("id" => $id)));
            if (!isset($status['data']['id']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $res['invoicesLists'] = $invoicesLists['data'][0];
            $res['transferamount'] = $transferamount;
            $this->load->view("admin/purchase_invoices_payment_add", $res);
        } else {
            $res['invoicesLists'] = $invoicesLists['data'][0];
            $res['transferamount'] = $transferamount;
            $this->load->view("admin/purchase_invoices_payment_add", $res);
        }
    }

    function edit($id)
    {
        $iQry = "SELECT purchase_invoices_payments.id,purchase_invoices_payments.invoices_id,purchase_invoices_payments.user_id,purchase_invoices_payments.shopper_id,purchase_invoices_payments.invoices_no,purchase_invoices_payments.invoices_total,purchase_invoices_payments.payment_mode,purchase_invoices_payments.payment_date,purchase_invoices_payments.amount,purchase_invoices_payments.transfer_amount,purchase_invoices_payments.cheque_number,purchase_invoices_payments.bank_detail,purchase_invoices_payments.transaction_detail,shopper.trade_name as shoppername FROM purchase_invoices_payments JOIN shopper ON (shopper.id = purchase_invoices_payments.shopper_id) WHERE purchase_invoices_payments.id = {$id}";
        $invoicesLists = $this->model_api->execute_query($iQry);

        if (isset($this->param['submit'])) {
            $inserted_fields['payment_mode'] = $this->param['payment_mode'];
            $inserted_fields['payment_date'] = $this->param['payment_date'];
            $inserted_fields['amount'] = $this->param['amount'];
            $inserted_fields['cheque_number'] = $this->param['cheque_number'];
            $inserted_fields['bank_detail'] = $this->param['bank_detail'];
            $inserted_fields['transaction_detail'] = $this->param['transaction_detail'];
            $inserted_fields['transfer_amount'] = $invoicesLists['data'][0]['transfer_amount'] - $invoicesLists['data'][0]['amount'] + $this->param['amount'];

            if ($invoicesLists['data'][0]['invoices_total'] == $this->param['amount']) {
                $inserted_fields['status'] = '1';
            } else if ($invoicesLists['data'][0]['invoices_total'] == ($invoicesLists['data'][0]['transfer_amount'] + $this->param['amount'])) {
                $inserted_fields['status'] = '1';
            } else {
                $inserted_fields['status'] = '0';
            }

            $status = $this->model_api->update("purchase_invoices_payments", $inserted_fields, array("and" => array("id" => $id)));
            $this->model_api->update("purchase_invoices", array("payment_status" => $inserted_fields['status']), array("and" => array("id" => $invoicesLists['data'][0]['invoices_id'])));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }

            $res['invoicesLists'] = $invoicesLists['data'][0];
            $this->load->view("admin/purchase_invoices_payment_edit", $res);
        } else {
            $res['invoicesLists'] = $invoicesLists['data'][0];
            $this->load->view("admin/purchase_invoices_payment_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM purchase_invoices_payments WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/purchase_invoices_payment/view");
    }

    function purchaseinvoicepayment_List()
    {
        $iWhere = " WHERE purchase_invoices_payments.id IS NOT NULL";
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            $fromdate = json_encode($this->param['inv_date_from']);
            $fromto = json_encode($this->param['inv_date_to']);

            if (isset($this->param['shopper_id']) && $this->param['shopper_id'] != '')
                $iWhere .= " AND purchase_invoices_payments.shopper_id LIKE '%" . $this->param['shopper_id'] . "%'";

            if (isset($this->param['invoices_no']) && $this->param['invoices_no'] != '')
                $iWhere .= " AND purchase_invoices_payments.invoices_no LIKE '%" . $this->param['invoices_no'] . "%'";

            if (isset($this->param['payment_mode']) && $this->param['payment_mode'] != '')
                $iWhere .= " AND purchase_invoices_payments.payment_mode = " . $this->param['payment_mode'];

            if (isset($this->param['cheque_number']) && $this->param['cheque_number'] != '')
                $iWhere .= " AND purchase_invoices_payments.cheque_number LIKE '%" . $this->param['cheque_number'] . "%'";

            if (isset($this->param['bank_detail']) && $this->param['bank_detail'] != '')
                $iWhere .= " AND purchase_invoices_payments.bank_detail LIKE '%" . $this->param['bank_detail'] . "%'";

            if (isset($this->param['transaction_detail']) && $this->param['transaction_detail'] != '')
                $iWhere .= " AND purchase_invoices_payments.transaction_detail LIKE '%" . $this->param['transaction_detail'] . "%'";

            if (isset($this->param['inv_date_from']) && $this->param['inv_date_from'] != '')
                $iWhere = " WHERE purchase_invoices_payments.payment_date BETWEEN " . $fromdate;

            if (isset($this->param['inv_date_to']) && $this->param['inv_date_to'] != '')
                $iWhere .= " AND " . $fromto;

            if (isset($this->param['status']) && $this->param['status'] != '')
                $iWhere .= " AND purchase_invoices_payments.status = " . $this->param['status'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(id) cnt FROM purchase_invoices_payments" . $iWhere);
        $iTotalRecords = isset($iResCount['data'][0]['cnt']) ? $iResCount['data'][0]['cnt'] : 0;
        $iDisplayLength = intval($this->param['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($this->param['start']);
        $sEcho = intval($this->param['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $modeList = array(
            array("" => ""),
            array("info" => "Transfer"),
            array("danger" => "Cash"),
            array("success" => "Cheque")
        );

        $iLimit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
        $iQry = "SELECT purchase_invoices_payments.id,purchase_invoices_payments.shopper_id,purchase_invoices_payments.invoices_no,purchase_invoices_payments.payment_mode,purchase_invoices_payments.payment_date,purchase_invoices_payments.invoices_total,purchase_invoices_payments.transfer_amount,purchase_invoices_payments.amount,purchase_invoices_payments.cheque_number,purchase_invoices_payments.bank_detail,purchase_invoices_payments.transaction_detail,purchase_invoices_payments.status,shopper.trade_name as shoppername FROM purchase_invoices_payments JOIN shopper ON (shopper.id = purchase_invoices_payments.shopper_id) " . $iWhere . " ORDER BY purchase_invoices_payments.id DESC " . $iLimit;
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iModes = $modeList[$iUser['payment_mode']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['shoppername'],
                $iUser['invoices_no'],
                '<span class="label label-sm label-' . (key($iModes)) . '">' . (current($iModes)) . '</span>',
                $iUser['payment_date'],
                $iUser['invoices_total'],
                $iUser['transfer_amount'],
                $iUser['amount'],
                $iUser['cheque_number'],
                $iUser['bank_detail'],
                $iUser['transaction_detail'],
                '<a href="' . BASE_URL . "admin/purchase_invoices_payment/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a><a href="' . BASE_URL . "admin/purchase_invoices_payment/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-edit"></i> Delete</a>'
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
