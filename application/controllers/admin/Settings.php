<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    function view() {
        if (isset($_FILES['logo']['error']) && $_FILES['logo']['error'] == 0) {
            $iData = $this->mylib->get_global_settings(array("logo"));
            if (isset($iData['logo']) && !empty($iData['logo']))
                file_exists(IMAGE_DIR_PATH.DS.$iData['logo']) ? unlink(IMAGE_DIR_PATH.DS.$iData['logo']) : "";
            $fileName = 'logo.png';
            move_uploaded_file($_FILES['logo']['tmp_name'], IMAGE_DIR_PATH.DS.$fileName);
            $this->param['logo'] = $fileName;
        }
        if (isset($_FILES['logo_wide']['error']) && $_FILES['logo_wide']['error'] == 0) {
            $iData = $this->mylib->get_global_settings(array("logo_wide"));
            if (isset($iData['logo_wide']) && !empty($iData['logo_wide']))
                file_exists(IMAGE_DIR_PATH.DS.$iData['logo_wide']) ? unlink(IMAGE_DIR_PATH.DS.$iData['logo_wide']) : "";
            $fileName = 'logo_wide.png';
            move_uploaded_file($_FILES['logo_wide']['tmp_name'], IMAGE_DIR_PATH.DS.$fileName);
            $this->param['logo_wide'] = $fileName;
        }
        if (isset($_FILES['favicon']['error']) && $_FILES['favicon']['error'] == 0) {
            $iData = $this->mylib->get_global_settings(array("favicon"));
            if (isset($iData['favicon']) && !empty($iData['favicon']))
                file_exists(BASE_DIR_PATH.DS.$iData['favicon']) ? unlink(BASE_DIR_PATH.DS.$iData['favicon']) : "";
            $fileName = 'favicon.ico';
            move_uploaded_file($_FILES['favicon']['tmp_name'], BASE_DIR_PATH.DS.$fileName);
            $this->param['favicon'] = $fileName;
        }
        $getSettings = array("site_name","unlock_login_interval","no_of_login_attampt","site_status","admin_email","logo","logo_wide","favicon");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/settings", $data);  
        }
    }

    function app_settings() {
        if (isset($_FILES['postman_collection']['error']) && $_FILES['postman_collection']['error'] == 0) {
            $iData = $this->mylib->get_global_settings(array("postman_collection"));
            if (isset($iData['postman_collection']) && !empty($iData['postman_collection']))
                file_exists(IMAGE_DIR_PATH.DS.$iData['postman_collection']) ? unlink(IMAGE_DIR_PATH.DS.$iData['postman_collection']) : "";
            $iExt = strtolower(substr($_FILES['postman_collection']['name'], strrpos($_FILES['postman_collection']['name'], ".") + 1));
            $fileName = md5(time()) . "." . $iExt;
            move_uploaded_file($_FILES['postman_collection']['tmp_name'], IMAGE_DIR_PATH.DS.$fileName);
            $this->param['postman_collection'] = $fileName;
        }
        $getSettings = array("api_server_url","request_token","postman_collection");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/app_settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/app_settings", $data);  
        }
    }

    function android_settings() { 
        $getSettings = array("latest_apk_version_name","latest_apk_version_code","apk_file_url","whats_new_on_latest_apk","update_skipable");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/android_settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/android_settings", $data);  
        }
    }

    function mobile_ads_settings() { 
        $getSettings = array("ads_enable","ads_network","admob_open_id","admob_banner_ads_id","admob_interstitial_ads_id","admob_native_ads_placement_id","adx_open_id","adx_banner_ads_id","adx_interstitial_ads_id","adx_native_ads_placement_id","ads_clicks");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/mobile_ads_settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/mobile_ads_settings", $data);  
        }
    }

    function push_notification_settings() { 
        $getSettings = array("fcm_url","fcm_key");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/push_notification_settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/push_notification_settings", $data);  
        }
    }

    function support_settings() { 
        $getSettings = array("support_email","privacy_policy_url","share_app_link");
        if (isset($this->param['submit'])) {
            $data = $this->mylib->save_global_setting($this->param);
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/support_settings", $data);
        } else {
            $data['iSettings'] = $this->mylib->get_global_settings($getSettings);
            $this->load->view("admin/support_settings", $data);  
        }
    }

    function refreshtoken() { 
        $requestToken = $this->getnewtoken();
        $data = array();
        $data['request_token'] = $requestToken;
        $this->mylib->save_global_setting($data);
        redirect(BASE_URL."admin/settings/app_settings");
    }

    function getnewtoken() {
        $requestToken = random_str(15);
        $getSettings = array("request_token");
        $iSettings = $this->mylib->get_global_settings($getSettings);
        if (isset($iSettings['request_token']) && $iSettings['request_token'] == $requestToken)
            return $this->getnewtoken();
        return $requestToken;
    }

    function database_backup() { 
        $this->load->view("admin/database_backup");
    }

    function database_backup_add() {
        $this->load->dbutil();
        $this->load->helper('file');
        $prefs = array(
            'format' => 'zip', // gzip, zip, txt
            'filename' => 'Database-Backup_'.date('Y-m-d_H-i').'.sql', // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n" // Newline character used in backup file
        );
        $backup = $this->dbutil->backup($prefs);
        if (!write_file(DATABASE_BACKUP_PATH.DS.'Database-Backup_'.date('Y-m-d_H-i').'.zip', $backup)) {
            echo "Error while creating auto database backup!";
        } else {
            echo "Database backup has been successfully Created";
        }
    }

    function database_backup_delete($id) {
        if ($id && is_numeric($id)) {
            $iOriginal = $this->model_api->get("sh_databasebackup", array("name"), array("and" => array("id" => $id)));
            if (isset($iOriginal['data'][0]['name']) && !empty($iOriginal['data'][0]['name'])) {
                $iOriginal = DATABASE_BACKUP_PATH.DS.$iOriginal['data'][0]['name'];
                if (file_exists($iOriginal))
                    @unlink($iOriginal);
            }
            $iQry = "DELETE FROM sh_databasebackup WHERE id = {$id}";
            $this->model_api->execute_query($iQry);
        }
        redirect(BASE_URL."admin/settings/database_backup");

    }

    function load_database_backup() {
        $iWhere = " WHERE id IS NOT NULL";
        if (isset($this->param['action']) && $this->param['action'] == 'filter') {
            if(isset($this->param['name']) && $this->param['name'] != '')
                $iWhere .= " AND name LIKE '%".$this->param['name']."%'";
            if(isset($this->param['is_del']) && $this->param['is_del'] != '')
                $iWhere .= " AND is_del = ".$this->param['is_del'];
        }

        $iResCount = $this->model_api->execute_query("SELECT COUNT(id) cnt FROM sh_databasebackup ".$iWhere);
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

        $iLimit = "LIMIT ".$iDisplayStart.", ".$iDisplayLength;
        $iQry = "SELECT id,name,created_date,is_del FROM sh_databasebackup {$iWhere} ORDER BY id DESC {$iLimit}";
        $iDataLists = $this->model_api->execute_query($iQry);
        foreach ($iDataLists['data'] as $key => $iData) {
            if (isset($iData['name']) && $iData['name'] != "")
                $iData['name'] = '<a target="_blank" href="'.DATABASE_BACKUP_URL.DS.$iData['name'].'">'.$iData['name'].'</a>';
            $iStatus = $statusList[$iData['is_del']];
            $records["data"][] = array(
                $iData['id'],
                $iData['name'],
                date("d M, Y g:i A", strtotime($iData['created_date'])),
                '<span class="label label-sm label-'.(key($iStatus)).'">'.(current($iStatus)).'</span>',
                '<a href="'.BASE_URL."admin/settings/database_backup_delete/".$iData['id'].'" class="btn btn-sm btn-outline red"><i class="fa fa-trash"></i> Delete</a>',
            );
        }


        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
    }
}