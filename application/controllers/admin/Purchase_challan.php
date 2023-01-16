<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_challan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view($id = 0)
    {

        $res['id'] = $id;
        $this->load->view("admin/purchase_challan_view", $res);
    }

    function add()
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $shoppers = $this->model_api->get("shopper", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            // $inserted_fields['user_id'] = $this->param['user_id'];
            // $inserted_fields['shopper_id'] = $this->param['shopper_id'];
            // $inserted_fields['challan_date'] = $this->param['challan_date'];
            // $inserted_fields['is_del'] = $this->param['is_del'];
            // $status = $this->model_api->add("purchase_challan", $inserted_fields);

            // $Id = $status['data']['id'];

            foreach ($this->param['product_id'] as $key => $productid) {
                $inserted_field['product_id'] = $productid;
                $status = $this->model_api->add("purchase_challan_product", $inserted_field);
                // $inserted_field['purchase_challan_id'] = $Id;
                // if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                //     $res = error_res();
                // } else {
                //     $res = success_res();
                // }
            }
            //     foreach ($this->param['product_quantity'] as $key => $productquantity) {
            //         foreach ($this->param['product_rate'] as $key => $productrate) {
            //             foreach ($this->param['total_amount'] as $key => $totalamount) {
            //                 $inserted_field['product_quantity'] = $productquantity;
            //                 $inserted_field['product_rate'] = $productrate;
            //                 $inserted_field['total_amount'] = $totalamount;
            //             }
            //         }
            // }

            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/purchase_challan_add", $res);
        } else {
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/purchase_challan_add", $res);
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
            $inserted_fields['challan_date'] = $this->param['challan_date'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $inserted_fields['modified_date'] = date("Y-m-d H:i:s");

            $status = $this->model_api->update("purchase_challan", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $edit_purchase_challan = $this->model_api->get("purchase_challan", array("id,user_id,shopper_id,challan_date,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("purchase_challan_product", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));

            $res['edit_purchase_challan'] = $edit_purchase_challan['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $this->load->view("admin/purchase_challan_edit", $res);
        } else {
            $edit_purchase_challan = $this->model_api->get("purchase_challan", array("id,user_id,shopper_id,challan_date,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("purchase_challan_product", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));

            $res['edit_purchase_challan'] = $edit_purchase_challan['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['shoppers'] = $shoppers['data'];
            $this->load->view("admin/purchase_challan_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM purchase_challan WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/purchase_challan/view");
    }

    function purchaseChallan_list($id = 0)
    {
        $iWhere = " WHERE purchase_challan.id IS NOT NULL";
        if ($id != 0) {
            $iWhere .= " AND purchase_challan.shopper_id = '$id'";
        }
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['challan_date']) && $this->param['challan_date'] != '')
                $iWhere .= " AND purchase_challan.challan_date LIKE '%" . $this->param['challan_date'] . "%'";
            if (isset($this->param['shopper_id']) && $this->param['shopper_id'] != '')
                $iWhere .= " AND purchase_challan.shopper_id LIKE '%" . $this->param['shopper_id'] . "%'";
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND purchase_challan.is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(purchase_challan.id) cnt FROM purchase_challan" . $iWhere);
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

        $iLimit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
        $iQry = "SELECT purchase_challan.id,purchase_challan.challan_date,purchase_challan.shopper_id,purchase_challan.created_date,purchase_challan.is_del,shopper.trade_name as shoppername FROM purchase_challan JOIN shopper ON (shopper.id = purchase_challan.shopper_id) {$iWhere} ORDER BY purchase_challan.id DESC {$iLimit}";
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['challan_date'],
                $iUser['shoppername'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/purchase_challan/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a><a href="' . BASE_URL . "admin/purchase_challan/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
                '<a href="' . BASE_URL . "admin/purchase_challan/view/" . $iUser['id'] . '" class="btn btn-sm btn-outline yellow"><i class="fa fa-file-pdf-o"></i> Pdf</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
