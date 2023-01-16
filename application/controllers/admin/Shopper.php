<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Shopper extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view($id = 0)
    {
        $res['id'] = $id;

        $this->load->view("admin/shopper_view", $res);
    }

    function add()
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"), array("and" => array("is_del" => 0)));

        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['trade_name'] = $this->param['trade_name'];
            $inserted_fields['dealer_name'] = $this->param['dealer_name'];
            $inserted_fields['gst_number'] = $this->param['gst_number'];
            $inserted_fields['mobile_number'] = $this->param['mobile_number'];
            $inserted_fields['address'] = $this->param['address'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $status = $this->model_api->add("shopper", $inserted_fields);
            if (!isset($status['data']['id']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $res['users'] = $users['data'];
            $this->load->view("admin/shopper_add", $res);
        } else {
            $res['users'] = $users['data'];
            $this->load->view("admin/shopper_add", $res);
        }
    }

    function edit($id)
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"), array("and" => array("is_del" => 0)));
        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['trade_name'] = $this->param['trade_name'];
            $inserted_fields['dealer_name'] = $this->param['dealer_name'];
            $inserted_fields['gst_number'] = $this->param['gst_number'];
            $inserted_fields['mobile_number'] = $this->param['mobile_number'];
            $inserted_fields['address'] = $this->param['address'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $inserted_fields['modified_date'] = date("Y-m-d H:i:s");
            $status = $this->model_api->update("shopper", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $edit_shopper = $this->model_api->get("shopper", array("id,user_id,trade_name,dealer_name,address,mobile_number,gst_number,is_del"), array("and" => array("id" => $id)));
            $res['edit_shopper'] = $edit_shopper['data'][0];
            $res['users'] = $users['data'];
            $this->load->view("admin/shopper_edit", $res);
        } else {
            $edit_shopper = $this->model_api->get("shopper", array("id,user_id,trade_name,dealer_name,address,mobile_number,gst_number,is_del"), array("and" => array("id" => $id)));
            $res['edit_shopper'] = $edit_shopper['data'][0];
            $res['users'] = $users['data'];
            $this->load->view("admin/shopper_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM shopper WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/shopper/view");
    }

    function shopper_list($id = 0)
    {
        $iWhere = " WHERE id IS NOT NULL";
        if ($id != 0) {
            $iWhere .= " AND shopper.user_id = '$id'";
        }
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['trade_name']) && $this->param['trade_name'] != '')
                $iWhere .= " AND trade_name LIKE '%" . $this->param['trade_name'] . "%'";
            if (isset($this->param['dealer_name']) && $this->param['dealer_name'] != '')
                $iWhere .= " AND dealer_name LIKE '%" . $this->param['dealer_name'] . "%'";
            if (isset($this->param['mobile_number']) && $this->param['mobile_number'] != '')
                $iWhere .= " AND mobile_number = " . $this->param['mobile_number'];
            if (isset($this->param['gst_number']) && $this->param['gst_number'] != '')
                $iWhere .= " AND gst_number = " . $this->param['gst_number'];
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(id) cnt FROM shopper" . $iWhere);
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
        $iQry = "SELECT id,user_id,trade_name,dealer_name,address,mobile_number,gst_number,created_date,modified_date,is_del FROM shopper " . $iWhere . " ORDER BY id DESC " . $iLimit;
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['trade_name'],
                $iUser['dealer_name'],
                $iUser['gst_number'],
                $iUser['mobile_number'],
                $iUser['address'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/shopper/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a> <a href="' . BASE_URL . "admin/shopper/delete/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
                '<a href="' . BASE_URL . "admin/purchase_invoice/view/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Invoice</a><a href="' . BASE_URL . "admin/challan/view/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Challan</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
