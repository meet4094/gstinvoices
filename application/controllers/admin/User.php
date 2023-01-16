<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view()
    {
        $this->load->view("admin/user_view");
    }

    function add()
    {
        if (isset($this->param['submit'])) {
            $inserted_fields['trade_name'] = $this->param['trade_name'];
            $inserted_fields['leagal_name'] = $this->param['leagal_name'];
            $inserted_fields['gst_number'] = $this->param['gst_number'];
            $inserted_fields['email'] = $this->param['email'];
            $inserted_fields['mobile_number'] = $this->param['mobile_number'];
            $inserted_fields['address'] = $this->param['address'];
            $inserted_fields['is_create_profile'] = $this->param['is_create_profile'];
            $inserted_fields['is_active'] = $this->param['is_active'];
            $inserted_fields['is_del'] = $this->param['is_del'];

            $status = $this->model_api->add("sh_users", $inserted_fields);
            if (!isset($status['data']['id']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $this->load->view("admin/user_add", $res);
        } else {
            $this->load->view("admin/user_add");
        }
    }

    function edit($uid)
    {
        if (isset($this->param['submit'])) {
            $inserted_fields['trade_name'] = $this->param['trade_name'];
            $inserted_fields['leagal_name'] = $this->param['leagal_name'];
            $inserted_fields['gst_number'] = $this->param['gst_number'];
            $inserted_fields['email'] = $this->param['email'];
            $inserted_fields['mobile_number'] = $this->param['mobile_number'];
            $inserted_fields['address'] = $this->param['address'];
            $inserted_fields['is_create_profile'] = $this->param['is_create_profile'];
            $inserted_fields['is_active'] = $this->param['is_active'];
            $inserted_fields['is_del'] = $this->param['is_del'];

            $status = $this->model_api->update("sh_users", $inserted_fields, array("and" => array("uid" => $uid)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }
            $users = $this->model_api->get("sh_users", array("uid,trade_name,leagal_name,gst_number,email,mobile_number,address,is_create_profile,is_active,is_del"), array("and" => array("uid" => $uid)));

            $res['users'] = $users['data'][0];
            $this->load->view("admin/user_edit", $res);
        } else {
            $users = $this->model_api->get("sh_users", array("uid,trade_name,leagal_name,gst_number,email,mobile_number,address,is_create_profile,is_active,is_del"), array("and" => array("uid" => $uid)));

            $res['users'] = $users['data'][0];
            $this->load->view("admin/user_edit", $res);
        }
    }

    function delete($uid)
    {
        $iQry = "DELETE FROM sh_users WHERE uid = {$uid}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/user/view");
    }

    function users_list()
    {
        $iWhere = " WHERE uid IS NOT NULL";
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['trade_name']) && $this->param['trade_name'] != '')
                $iWhere .= " AND trade_name LIKE '%" . $this->param['trade_name'] . "%'";
            if (isset($this->param['leagal_name']) && $this->param['leagal_name'] != '')
                $iWhere .= " AND leagal_name LIKE '%" . $this->param['leagal_name'] . "%'";
            if ($this->param['gst_number'] != '')
                $iWhere .= " AND gst_number LIKE '%" . $this->param['gst_number'] . "%'";
            if ($this->param['email'] != '')
                $iWhere .= " AND email LIKE '%" . $this->param['email'] . "%'";
            if ($this->param['mobile_number'] != '')
                $iWhere .= " AND mobile_number LIKE '%" . $this->param['mobile_number'] . "%'";
            if (isset($this->param['is_create_profile']) && $this->param['is_create_profile'] != '')
                $iWhere .= " AND is_create_profile = " . $this->param['is_create_profile'];
            if (isset($this->param['is_active']) && $this->param['is_active'] != '')
                $iWhere .= " AND is_active = " . $this->param['is_active'];
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(uid) cnt FROM sh_users" . $iWhere);
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

        $activeList = array(
            array("danger" => "Inactive"),
            array("success" => "Active")
        );

        $activeProfileList = array(
            array("success" => "Complete"),
            array("danger" => "Incomplete")
        );

        $iLimit = "LIMIT " . $iDisplayStart . ", " . $iDisplayLength;
        $iQry = "SELECT uid,trade_name,leagal_name,gst_number,email,mobile_number,address,created_date,modified_date,is_active,is_create_profile,is_del FROM sh_users " . $iWhere . " ORDER BY uid DESC " . $iLimit;
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $iActiveStatus = $activeList[$iUser['is_active']];
            $iActiveProfileStatus = $activeProfileList[$iUser['is_create_profile']];
            $records["data"][] = array(
                $iUser['uid'],
                $iUser['trade_name'],
                $iUser['leagal_name'],
                $iUser['gst_number'],
                $iUser['email'],
                $iUser['mobile_number'],
                $iUser['address'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($iActiveProfileStatus)) . '">' . (current($iActiveProfileStatus)) . '</span>',
                '<span class="label label-sm label-' . (key($iActiveStatus)) . '">' . (current($iActiveStatus)) . '</span>',
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/user/edit/" . $iUser['uid'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Edit</a> <a href="' . BASE_URL . "admin/user/delete/" . $iUser['uid'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-eye"></i> Delete</a>',
                // '<a href="' . BASE_URL . "admin/shopper/view/" . $iUser['uid'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Shoppers</a> <a href="' . BASE_URL . "admin/customer/view/" . $iUser['uid'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-eye"></i> Customers</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
