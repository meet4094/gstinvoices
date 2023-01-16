<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Product extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view()
    {
        $this->load->view("admin/product_view");
    }

    function add()
    {
        if (isset($this->param['submit'])) {
            $inserted_fields['product_name'] = $this->param['productname'];
            $inserted_fields['product_code'] = $this->param['product_code'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $status = $this->model_api->add("product_detailes", $inserted_fields);
            if (!isset($status['data']['id']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $this->load->view("admin/product_add", $res);
        } else {
            $this->load->view("admin/product_add");
        }
    }

    function edit($id)
    {
        if (isset($this->param['submit'])) {
            $inserted_fields['product_name'] = $this->param['productname'];
            $inserted_fields['product_code'] = $this->param['product_code'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $inserted_fields['modified_date'] = date("Y-m-d H:i:s");
            $status = $this->model_api->update("product_detailes", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $edit_product = $this->model_api->get("product_detailes", array("id,product_name,product_code,is_del"), array("and" => array("id" => $id)));
            $res['edit_product'] = $edit_product['data'][0];
            $this->load->view("admin/product_edit", $res);
        } else {
            $edit_product = $this->model_api->get("product_detailes", array("id,product_name,product_code,is_del"), array("and" => array("id" => $id)));
            $res['edit_product'] = $edit_product['data'][0];
            $this->load->view("admin/product_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM product_detailes WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/product/view");
    }

    function product_list()
    {
        $iWhere = " WHERE id IS NOT NULL";
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['product_name']) && $this->param['product_name'] != '')
                $iWhere .= " AND product_name LIKE '%" . $this->param['product_name'] . "%'";
            if (isset($this->param['product_code']) && $this->param['product_code'] != '')
                $iWhere .= " AND product_code LIKE '%" . $this->param['product_code'] . "%'";
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(id) cnt FROM product_detailes" . $iWhere);
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
        $iQry = "SELECT id,product_name,product_code,created_date,modified_date,is_del FROM product_detailes " . $iWhere . " ORDER BY id DESC " . $iLimit;
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['product_name'],
                $iUser['product_code'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/product/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a> <a href="' . BASE_URL . "admin/product/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
