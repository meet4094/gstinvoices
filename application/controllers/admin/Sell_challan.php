<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Sell_Challan extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function view($id = 0)
    {

        $res['id'] = $id;
        $this->load->view("admin/sell_challan_view", $res);
    }

    function add()
    {
        $users = $this->model_api->get("sh_users", array("uid,trade_name,is_del"));
        $customers = $this->model_api->get("customer", array("id,trade_name,is_del"));
        $products = $this->model_api->get("product_detailes", array("id,product_name,is_del"));
        if (isset($this->param['submit'])) {
            $inserted_fields['user_id'] = $this->param['user_id'];
            $inserted_fields['customer_id'] = $this->param['customer_id'];
            $inserted_fields['challan_date'] = $this->param['challan_date'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $status = $this->model_api->add("sell_challan", $inserted_fields);

            $Id = $status['data']['id'];

            foreach ($this->param['product_id'] as $key => $productid) {
                $inserted_field['challan_id'] = $Id;
                $inserted_field['product_id'] = $productid;
                $inserted_field['product_quantity'] = $this->param['product_quantity'][$key];
                $inserted_field['product_rate'] = $this->param['product_rate'][$key];
                $inserted_field['total_amount'] = $this->param['total_amount'][$key];
                $status = $this->model_api->add("sell_challan_product", $inserted_field);
                if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                    $res = error_res();
                } else {
                    $res = success_res();
                }
            }

            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/sell_challan_add", $res);
        } else {
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $res['users'] = $users['data'];
            $this->load->view("admin/sell_challan_add", $res);
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
            $inserted_fields['challan_date'] = $this->param['challan_date'];
            $inserted_fields['is_del'] = $this->param['is_del'];
            $inserted_fields['modified_date'] = date("Y-m-d H:i:s");
            // echo '<pre>';
            // print_r($this->param);
            // die;
            $status = $this->model_api->update("sell_challan", $inserted_fields, array("and" => array("id" => $id)));
            if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                $res = error_res();
            } else {
                $res = success_res();
            }

            $sellchallanproducts = $this->model_api->get("sell_challan_product", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));
            foreach ($sellchallanproducts['data'] as $skey => $sellchallanproduct) {
                $sellchallanproductId = $sellchallanproduct['id'];
                foreach ($this->param['product_id'] as $key => $productid) {
                    $inserted_field['product_id'] = $productid;
                    $inserted_field['product_quantity'] = $this->param['product_quantity'][$key];
                    $inserted_field['product_rate'] = $this->param['product_rate'][$key];
                    $inserted_field['total_amount'] = $this->param['total_amount'][$key];
                    $status = $this->model_api->update("sell_challan_product", $inserted_field, array("and" => array("id" => $sellchallanproductId)));
                    if (!isset($status['statuscode']) || $status['statuscode'] != 1) {
                        $res = error_res();
                    } else {
                        $res = success_res();
                    }
                }
            }
            $edit_sell_challan = $this->model_api->get("sell_challan", array("id,user_id,customer_id,challan_date,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("sell_challan_product", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));

            $res['edit_sell_challan'] = $edit_sell_challan['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $this->load->view("admin/sell_challan_edit", $res);
        } else {
            $edit_sell_challan = $this->model_api->get("sell_challan", array("id,user_id,customer_id,challan_date,is_del"), array("and" => array("id" => $id)));
            $edit_product_detailes = $this->model_api->get("sell_challan_product", array("id,challan_id,product_id,product_quantity,product_rate,total_amount,"), array("and" => array("challan_id" => $id)));

            $res['edit_sell_challan'] = $edit_sell_challan['data'][0];
            $res['edit_product_detailes'] = $edit_product_detailes['data'];
            $res['users'] = $users['data'];
            $res['products'] = $products['data'];
            $res['customers'] = $customers['data'];
            $this->load->view("admin/sell_challan_edit", $res);
        }
    }

    function delete($id)
    {
        $iQry = "DELETE FROM sell_challan WHERE id = {$id}";
        $this->model_api->execute_query($iQry);
        redirect(BASE_URL . "admin/sell_challan/view");
    }

    function sellChallan_pdf($id)
    {
        $sell_invoice_pdf = $this->model_api->get("sell_challan", array("id,user_id,invoices_date,customer_id,gst,sub_total,product_sgst,product_cgst,round_off,invoices_total,is_del"), array("and" => array("id" => $id)));

        $custommer = $sell_invoice_pdf['data'][0]['customer_id'];
        $custommerName = $custommer;

        $custommer = $sell_invoice_pdf['data'][0]['user_id'];
        $userId = $custommer;

        $userDetailes = $this->model_api->get("sh_users", array("uid,trade_name,gst_number,mobile_number,address,is_del"), array("and" => array("uid" => $userId)));

        $customerDetailes = $this->model_api->get("customer", array("id,trade_name,address,gst_number,place_of_supply,is_del"), array("and" => array("id" => $custommerName)));

        $productId = $sell_invoice_pdf['data'][0]['id'];
        $sellInvoiceId = $productId;

        $productDetailes = $this->model_api->get("sell_challan_product", array("id,challan_id,product_id,product_hsn_sac,product_quantity,product_rate,total_amount,is_del"), array("and" => array("challan_id" => $sellInvoiceId)));

        $productName = $productDetailes['data'][0]['product_id'];
        $productId = $productName;

        $product = $this->model_api->get("product_detailes", array("id,product_name,product_code,is_del"), array("and" => array("id" => $productId)));

        $total = $sell_invoice_pdf['data'][0]['sub_total'];
        $percentage = $sell_invoice_pdf['data'][0]['product_sgst'];
        $newtotal = ($percentage / 100) * $total;

        $number = $newtotal + $newtotal;
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety'
        );
        $digits = array('', 'Hndred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
        $res['gst_words'] = $result . "Rupees  " . $points . " Paise";

        $number = $sell_invoice_pdf['data'][0]['invoices_total'];
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
            '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
            '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
            '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
            '60' => 'Sixty', '70' => 'Seventy',
            '80' => 'Eighty', '90' => 'Ninety'
        );
        $digits = array('', 'Hndred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str[] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " .
            $words[$point = $point % 10] : '';
        $res['invoices_total_word'] = $result . "Rupees  " . $points . " Paise";

        $res['sell_invoice_pdf'] = $sell_invoice_pdf['data'][0];
        $res['userDetailes'] = $userDetailes['data'][0];
        $res['customerDetailes'] = $customerDetailes['data'][0];
        $res['productDetailes'] = $productDetailes['data'];
        $res['product'] = $product['data'][0];
        $res['s_cgst'] = $newtotal;

        $this->load->view("admin/sellchallan_pdf_view", $res);
    }

    function sellchallan_list($id = 0)
    {
        $iWhere = " WHERE sell_challan.id IS NOT NULL";
        if ($id != 0) {
            $iWhere .= " AND sell_challan.customer_id = '$id'";
        }
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if (isset($this->param['challan_date']) && $this->param['challan_date'] != '')
                $iWhere .= " AND sell_challan.challan_date LIKE '%" . $this->param['challan_date'] . "%'";
            if (isset($this->param['customer_id']) && $this->param['customer_id'] != '')
                $iWhere .= " AND sell_challan.customer_id LIKE '%" . $this->param['customer_id'] . "%'";
            if (isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND sell_challan.is_del = " . $this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(sell_challan.id) cnt FROM sell_challan" . $iWhere);
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
        $iQry = "SELECT sell_challan.id,sell_challan.challan_date,sell_challan.customer_id,sell_challan.created_date,sell_challan.is_del,customer.trade_name as customername FROM sell_challan JOIN customer ON (customer.id = sell_challan.customer_id) {$iWhere} ORDER BY sell_challan.id DESC {$iLimit}";
        $iUsers = $this->model_api->execute_query($iQry);

        foreach ($iUsers['data'] as $key => $iUser) {
            $iStatus = $statusList[$iUser['is_del']];
            $records["data"][] = array(
                $iUser['id'],
                $iUser['challan_date'],
                $iUser['customername'],
                $iUser['created_date'],
                '<span class="label label-sm label-' . (key($iStatus)) . '">' . (current($iStatus)) . '</span>',
                '<a href="' . BASE_URL . "admin/sell_challan/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline blue"><i class="fa fa-edit"></i> Edit</a><a href="' . BASE_URL . "admin/sell_challan/edit/" . $iUser['id'] . '" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
                '<a href="' . BASE_URL . "admin/sell_challan/sellchallan_pdf/" . $iUser['id'] . '" class="btn btn-sm btn-outline yellow"><i class="fa fa-file-pdf-o"></i> Pdf</a>',
            );
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}
