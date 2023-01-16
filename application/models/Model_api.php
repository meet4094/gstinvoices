<?php

class Model_api extends CI_Model {
    
    function update($tbl_name, $setArray, $where, $limit = -1) {
        if(empty($setArray))
            return error_res("empty_set_argument");
        
        if ($limit != -1)
            $this->db->limit($limit, 0);

        $newOperator = array(" !=", " >", " <");
        $oldOperator = array("!=", ">", "<");

        $and = isset($where['and']) ? $where['and'] : array();
        $or = isset($where['or']) ? $where['or'] : array();
        $in = isset($where['in']) ? $where['in'] : array();
        $nin = isset($where['nin']) ? $where['nin'] : array();                     //$nin = array('age' => '"10","15","16"');
        $like = isset($where['like']) ? $where['like'] : array();                  //$like = array('username'=>'paresh','website' => 'green');

        $or_str = "(";
        foreach ($or as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            if (strpos($key, "!") !== FALSE || strpos($key, "=") !== FALSE || strpos($key, ">") !== FALSE || strpos($key, "<") !== FALSE)
                $or_str .= $this->db->escape_str($key) . " '" . $this->db->escape_str($val) . "' OR ";
            else
                $or_str .= $this->db->escape_str($key) . " = '" . $this->db->escape_str($val) . "' OR ";
        }
        $or_str = rtrim($or_str, " OR ");
        $or_str .= ")";
        if (!empty($or)) {
            $this->db->where($or_str);
        }
        foreach ($and as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            $this->db->where($this->db->escape_str($key), $this->db->escape_str($val));
        }

        foreach ($in as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_in($this->db->escape_str($key), $this->db->escape_str($val));
        }

        foreach ($nin as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_not_in($this->db->escape_str($key), $this->db->escape_str($val));
        }

        $like_str = "(";
        foreach ($like as $key => $val) {
                $like_str .= $this->db->escape_str($key) . " LIKE '%" . $this->db->escape_str($val) . "%' OR ";
        }
        
        $like_str = rtrim($like_str, " OR ");
        $like_str .= ")";

        if (!empty($like)) {
            $this->db->where($like_str);
        }
        
        $this->db->update($this->db->escape_str($tbl_name), $setArray);
        $res = array();
        if ($this->db->error()['code']) {
            print_rlog($this->db->error()['message'], "sql_error");
            $res = sql_error_res($this->db->error()['message']);
        } else {
            $res = success_res("updated_success");
        }
        return $res;
    }
    
    function delete($tbl_name, $where, $limit = -1) {
        if ($limit != -1) {
            $this->db->limit($limit, 0);
        }
        if (empty($where)) {
            return sql_error_res("call_delete_all_function");
        }
        
        $newOperator = array(" !=", " >", " <");
        $oldOperator = array("!=", ">", "<");
        
        $and = isset($where['and']) ? $where['and'] : array();
        $or = isset($where['or']) ? $where['or'] : array();
        $in = isset($where['in']) ? $where['in'] : array();
        $nin = isset($where['nin']) ? $where['nin'] : array();                     //$nin = array('age' => '"10","15","16"');
        $like = isset($where['like']) ? $where['like'] : array();                  //$like = array('username'=>'paresh','website' => 'green');

        
        $or_str = "(";
        foreach ($or as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            if (strpos($key, "!") !== FALSE || strpos($key, "=") !== FALSE || strpos($key, ">") !== FALSE || strpos($key, "<") !== FALSE)
                $or_str .= $this->db->escape_str($key) . " '" . $this->db->escape_str($val) . "' OR ";
            else
                $or_str .= $this->db->escape_str($key) . " = '" . $this->db->escape_str($val) . "' OR ";
        }
        $or_str = rtrim($or_str, " OR ");
        $or_str .= ")";
        if (!empty($or)) {
            $this->db->where($or_str);
        }
        foreach ($and as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            $this->db->where($this->db->escape_str($key), $this->db->escape_str($val));
        }

        foreach ($in as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_in($this->db->escape_str($key), $this->db->escape_str($val));
        }

        foreach ($nin as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_not_in($this->db->escape_str($key), $this->db->escape_str($val));
        }

        $like_str = "(";
        foreach ($like as $key => $val) {
                $like_str .= $this->db->escape_str($key) . " LIKE '%" . $this->db->escape_str($val) . "%' OR ";
        }
        $like_str = rtrim($like_str, " OR ");
        $like_str .= ")";
        
        if (!empty($like)) {
            $this->db->where($like_str);
        }
        
        $this->db->delete($this->db->escape_str($tbl_name));
        if ($this->db->error()['code']) {
            print_rlog($this->db->error()['message'], "sql_error");
            $res = sql_error_res($this->db->error()['message']);
        } else {
            $res = success_res("delete_success");
        }
        return $res;
    }
    
    function add($tbl_name, $data, $is_batch = false) {
        if($is_batch) {
            if(!empty($data)) {
                $this->db->insert_batch($tbl_name, $data);
            }
            $data = array();
        } else {
            $this->db->insert($tbl_name, $data);
            $data = array('id' => $this->db->insert_id());
        }
        if ($this->db->error()['code']) {
            print_rlog($this->db->error()['message'], "sql_error");
            $res = sql_error_res($this->db->error()['message']);
        } else {
            $res = success_res("add_success");
        }
        $res['data'] = $data;
        return $res;
    }

    function get($tbl_name, $fields = array(),$where = array(), $group_by = array(), $order_by = array(), $start = 0, $len = 0) {
        $oldOperator = array("!=", ">", "<");
        $newOperator = array(" !=", " >", " <");
        
        $and = isset($where['and']) ? $where['and'] : array();
        $or = isset($where['or']) ? $where['or'] : array();
        $in = isset($where['in']) ? $where['in'] : array();
        $nin = isset($where['nin']) ? $where['nin'] : array();                     //$nin = array('age' => '"10","15","16"');
        $like = isset($where['like']) ? $where['like'] : array();                  //$like = array('username'=>'paresh','website' => 'green');
       
        if(!empty($fields)) {
            $field_str = implode(",", $fields);
            $this->db->select($field_str);
        }
        
        $or_str = "(";
        foreach ($or as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            if (strpos($key, "!") !== FALSE || strpos($key, "=") !== FALSE || strpos($key, ">") !== FALSE || strpos($key, "<") !== FALSE) {
                $or_str .= $key . " '" . $val . "' OR ";
            } else {
                $or_str .= $key . " = '" . $val . "' OR ";
            }
        }
        $or_str = rtrim($or_str, " OR ");
        $or_str .= ")";
        
        if (!empty($or)) {
            $this->db->where($or_str);
        }
        
        foreach ($and as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            $this->db->where($key, $val);
        }

        foreach ($in as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_in($key, $val);
        }

        foreach ($nin as $key => $val) {
            $val = explode(",", $val);
            $this->db->where_not_in($key, $val);
        }

        $like_str = "(";
        foreach ($like as $key => $val) {
                $like_str .= $key . " LIKE '%" . $val . "%' OR ";
        }
        $like_str = rtrim($like_str, " OR ");
        $like_str .= ")";

        if (!empty($like)) {
            $this->db->where($like_str);
        }
        
        $this->db->group_by($group_by);
        
        for ($i = 0; $i < count($order_by); $i++) {
            list($fielname, $sort) = explode(" ", trim($order_by[$i]));
            $this->db->order_by($fielname, $sort);
        }

        if ($len != 0)
            $this->db->limit(intval($len), intval($start));

        $query = $this->db->get($tbl_name);
        if ($this->db->error()['code']) {
            print_rlog($this->db->error()['message'], "sql_error");
            $res = sql_error_res($this->db->error()['message']);
        } else {
            $result = $query->result_array();
            $res = success_res("get_success");
            $res['data'] = $result;
        }
        return $res;
    }

    function execute_query($qry){
        $qry = trim($qry);
        $link = $this->db->query($qry);
        if(!$link) {
            print_rlog($this->db->error()['message'], "sql_error");
            return sql_error_res($this->db->error()['message']);
        }
        if(preg_match("/^select/", strtolower($qry))) {
            $data = array();
            foreach ($link->result_array() as $row) {
                array_push($data, $row);
            }
            $res = success_res("success");
            $res['data'] = $data;
            return $res;
        }
        $rowcnt = $this->db->affected_rows();
        return success_res("row_affected",array($rowcnt));
    }
    
    function exists($tbl_name,$where) {
        if(empty($where))
            return error_res("empty_where");
        
        $oldOperator = array("!=", ">", "<");
        $newOperator = array(" !=", " >", " <");
        
        $qry = "SELECT EXISTS(SELECT 1 FROM `{$tbl_name}` WHERE ";
  
        $and = isset($where['and']) ? $where['and'] : array();
        $or = isset($where['or']) ? $where['or'] : array();
        $in = isset($where['in']) ? $where['in'] : array();
        $nin = isset($where['nin']) ? $where['nin'] : array();                     //$nin = array('age' => '"10","15","16"');
        $like = isset($where['like']) ? $where['like'] : array();                  //$like = array('username'=>'paresh','website' => 'green');

        $or_str = "(";
        foreach ($or as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            if (strpos($key, "!") !== FALSE || strpos($key, "=") !== FALSE || strpos($key, ">") !== FALSE || strpos($key, "<") !== FALSE) {
                $or_str .= $key . " '" . $val . "' OR ";
            } else {
                $or_str .= $key . " = '" . $val . "' OR ";
            }
        }
        $or_str = rtrim($or_str, " OR ");
        $or_str .= ")";
        
        if (!empty($or)) {
            $qry .= $or_str." AND ";
        }
        
        foreach ($and as $key => $val) {
            $key = str_replace($oldOperator, $newOperator, $key);
            if(preg_match("/[^a-zA-Z0-9 \'\"]/", $key))
                $qry .= $key." ".$val;
            else
                $qry .= $key." = '".$val."'";
        }

        $qry .= " AND ";
        
        foreach ($in as $key => $val) {
            $qry .= $key." IN (".$val.") AND ";
        }

        foreach ($nin as $key => $val) {
            $qry .= $key."NOT IN (".$val.") AND ";
        }

        $like_str = "(";
        foreach ($like as $key => $val) {
                $like_str .= $key . " LIKE '%" . $val . "%' OR ";
        }
        $like_str = rtrim($like_str, " OR ");
        $like_str .= ")";

        if (!empty($like)) {
            $qry .= $like_str." AND ";
        }

        $qry = rtrim($qry, " AND ");
        $qry .= ") as cnt";
        $link = $this->db->query($qry);
        if(!$link) {
            print_rlog($this->db->error()['message'], "sql_error");
            return sql_error_res($this->db->error()['message']);
        }
        
        $row = $link->result_array();
        $res = success_res();
        $res['data'] = $row;
        return $res;
    }
}


?>