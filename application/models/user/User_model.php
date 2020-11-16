<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : login_model
  Created on : 22-Nov-2014, 15:38:20
  Author     : Mukesh MR
  Description: User class

 */

class User_model extends CI_Model {

    var $iduser;
    var $empid;
    var $user_name;
    var $user_pass;
    var $user_role;
    var $user_status;
    var $pen;
    var $p_name;
    var $dob;
    var $email;
    var $mob;
    var $user_unit;
    var $unit_role;
    var $profile_status;
    var $date_time_created;
    var $date_time_updated;
    var $created_by;
    var $updated_by;
    var $now;
    var $searchkey;
    var $table = 'user u';
    var $column_order = array(null, 'u.iduser', null, 'u.user_name', 'r.short_tag', 'u.user_status', 'u.pen', 'u.p_name', 'ut.unit_name', null); //set column field database for datatable orderable
    var $column_search = array('u.user_name', 'u.pen', 'u.p_name', 'ut.unit_name'); //set column field database for datatable searchable 
    var $order = array('u.iduser' => 'asc'); // default order 
    var $select = array('u.iduser id', 'u.user_name uname', 'u.user_role urole', 'u.user_status status',
        'u.pen', 'u.p_name pname', 'u.unit_role utrole', 'r.short_tag rst', 'r.description rdesc', 'ut.unit_name unit',
        'utt.unit_type_desc utydesc', 'u.email', 'u.mob mobile');
    var $join = array();
    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;
    var $where=array();

    public function __construct() {
        parent::__construct();
        $this->now = date('Y-m-d H:i:s');
        $this->join = array(
            array(
                't' => 'unit ut',
                'j' => 'ut.id = u.unit_role',
                'jt' => 'left'),
            array(
                't' => 'unit_type utt',
                'j' => 'utt.idunittype = ut.idunittype',
                'jt' => 'left'),
            array(
                't' => 'role r',
                'j' => 'r.idrole = u.user_role',
                'jt' => 'left'),
            array(
                    't' => 'vw_unit vu',
                    'j' => 'u.unit_role = vu.id',
                    'jt' => 'left')
        );
    }

    private function _get_datatables_query() {

        if ($this->where) {
            foreach ($this->where as $w) {
                $this->db->where($w);
            }
        }

        $this->db->from($this->table);
        if ($this->select) {
            $this->db->select($this->select);
        }
        if ($this->join) {
            foreach ($this->join as $jtable) {
                $this->db->join($jtable['t'], $jtable['j'], $jtable['jt']);
            }
        }

        $i = 0;

        

        foreach ($this->column_search as $item) { // loop column 
            if ($this->post_search['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->post_search['value']);
                } else {
                    $this->db->or_like($item, $this->post_search['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }

        if (isset($this->post_order)) { // here order processing
            $this->db->order_by($this->column_order[$this->post_order['0']['column']], $this->post_order['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    private function _count_filtered() {
        $this->_get_datatables_query();
        
        $query = $this->db->get();
       // echo $this->db->last_query();
        return $query->num_rows();
    }

    private function _count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_datatables() {
        $this->_get_datatables_query();
        if ($this->post_length != -1)
            $this->db->limit($this->post_length, $this->post_start);
        $rs = $this->db->get();
        $items = array();
        $n = 1;
        foreach ($rs->result_array() as $row) {
            $row['sl'] = $this->post_start + $n;
            array_push($items, $row);
            $n++;
        }
        $output = array(
            "draw" => $this->post_draw,
            "recordsTotal" => $this->_count_all(),
            "recordsFiltered" => $this->_count_filtered(),
            "data" => $items,
        );
        return $output;
    }
    public function get_datatables_ps() {

        $user = $this->session->userdata('user');
        $w= "vu.lft >= $user->lft and  vu.rgt <= $user->rgt and utt.idunittype =21";
        array_push($this->where, $w);

        $this->_get_datatables_query();
        if ($this->post_length != -1)
            $this->db->limit($this->post_length, $this->post_start);
        $rs = $this->db->get();
        $items = array();
        $n = 1;
        foreach ($rs->result_array() as $row) {
            $row['sl'] = $this->post_start + $n;
            array_push($items, $row);
            $n++;
        }
        $output = array(
            "draw" => $this->post_draw,
            "recordsTotal" => $this->_count_all(),
            "recordsFiltered" => $this->_count_filtered(),
            "data" => $items,
        );
        return $output;
    }

    public function add() {


        $data = array(
            'user_name' => $this->user_name,
            'user_pass' => $this->user_pass,
            'user_role' => $this->user_role,
            'pen' => $this->pen,
            'p_name' => $this->p_name,
            'email' => $this->email,
            'mob' => $this->mob,
            'user_unit' => $this->user_unit,
            'unit_role' => $this->unit_role,
            'user_status' => $this->user_status,
            'date_time_created' => $this->now,
            'created_by' => $this->created_by
        );

        if ($this->db->insert('user', $data)) {
            return true;
        } else {

            return false;
        }
    }

    public function register() {

        $emp = $this->getEmp();
        $data = array(
            'empid' => $emp->id,
            'user_name' => $this->user_name,
            'user_pass' => $this->user_pass,
            'user_role' => $this->user_role,
            'pen' => $this->pen,
            'p_name' => $emp->emp_name,
            'mob' => $this->mob,
            'user_unit' => $this->user_unit,
            'profile_status' => $this->profile_status,
            'user_status' => 1,
            'date_time_created' => $this->now,
            'created_by' => $this->created_by
        );

        if ($this->db->insert('user', $data)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function resetPassword() {

        $data = array(
            'user_name' => $this->user_name,
            'user_pass' => $this->user_pass,
            //'p_name' => $this->p_name,       
            'date_time_updated' => $this->now
        );

        $this->db->where('user_name', $this->user_name);

        if ($this->db->update('user', $data)) {
            return TRUE;
        } else {
            return false;
        }
    }

    private function getEmp() {
        $this->db->select('id,emp_name');
        $this->db->where('pen', $this->user_name);
        $rs = $this->db->get('t_employee');
        if ($rs->num_rows() == 1) {
            $r = $rs->row();
            return $r;
        }
    }

    public function check_pen($pen, $dob) {
        $d = explode("/", $dob);
        $dob = $d[2] . "-" . $d[1] . "-" . $d[0];

        $this->db->where('pen', $pen);
        $this->db->where('dob', $dob);
        if ($this->db->count_all_results('t_employee') == 1) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getById() {

        $sql = "SELECT
				u.iduser,
				u.user_name,
				u.user_role,
				u.pen,
				u.p_name,
				u.email,
				u.mob,
				u.user_status,
				us.status_desc,
				u.user_unit, 
				u.unit_role,
				utu.unit_name uunit,
				utr.unit_name runit,				
				u.date_time_created,
				u.created_by,
				r.description role_desc,
				r.short_tag role_tag
				FROM user u
				LEFT JOIN role r ON r.idrole = u.user_role
				LEFT JOIN user_status us ON us.id = u.user_status
				LEFT JOIN unit utu ON utu.id = u.user_unit
				LEFT JOIN unit utr ON utr.id = u.unit_role
				WHERE u.iduser ='" . $this->db->escape($this->iduser) . "'";

        $result = $this->db->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

    public function getByUsername() {


        $sql = "SELECT
				u.iduser,
				u.user_name,
				u.user_role,
				u.pen,
				u.p_name,
				u.email,
				u.mob,
				u.user_status,
				us.status_desc,
				u.user_unit, 
				u.unit_role,
				utu.unit_name uunit,
				utr.unit_name runit,				
				u.date_time_created,
				u.created_by,
				r.description role_desc,
				r.short_tag role_tag
				FROM user u
				LEFT JOIN role r ON r.idrole = u.user_role
				LEFT JOIN user_status us ON us.id = u.user_status
				LEFT JOIN unit utu ON utu.id = u.user_unit
				LEFT JOIN unit utr ON utr.id = u.unit_role
				WHERE user_name =" . $this->db->escape($this->user_name) . "";


        $result = $this->db->query($sql);

        if ($result->num_rows() == 1) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

    public function checkUser() {

        $sql = "SELECT iduser FROM user 
				WHERE user_name LIKE BINARY " . $this->db->escape($this->user_name) . " 
				AND user_pass LIKE BINARY " . $this->db->escape(md5($this->user_pass)) . " 
				AND user_status=1";



        $result = $this->db->query($sql);

        if ($result->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function checkPassword() {

        $sql = "SELECT iduser FROM user 
				WHERE iduser = " . $this->db->escape($this->iduser) . " 
				AND user_pass LIKE BINARY " . $this->db->escape(md5($this->user_pass));



        $result = $this->db->query($sql);

        if ($result->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkUsername() {

        $sql = "SELECT iduser FROM user 
                WHERE user_name LIKE BINARY " . $this->db->escape($this->user_name) . "";
        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function viewByRole($role_id) {

        $sql = "SELECT
				u.iduser,
				u.user_name,
				u.user_role,
				u.pen,
				u.p_name,
				u.email,
				u.mob,
				u.user_status,
				us.status_desc,
				u.user_unit, 
				u.unit_role,
				utu.unit_name uunit,
				utr.unit_name runit,				
				u.date_time_created,
				u.created_by,
				r.description role_desc,
				r.short_tag role_tag
				FROM user u
				LEFT JOIN role r ON r.idrole = u.user_role
				LEFT JOIN user_status us ON us.id = u.user_status
				LEFT JOIN unit utu ON utu.id = u.user_unit
				LEFT JOIN unit utr ON utr.id = u.unit_role
				WHERE u.user_role =" . $this->db->escape($role_id) . "";

        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }

    public function delete() {

        $sql = "UPDATE user set user_status=0 WHERE iduser = " . $this->db->escape($this->iduser) . "";

        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword() {

        $sql = "UPDATE user 
		SET user_pass=" . $this->db->escape(md5($this->user_pass)) . " 
		WHERE iduser = " . $this->db->escape($this->iduser) . "";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {

        $user_pass = '';

        if ($this->user_pass != '') {
            $user_pass = " user_pass = " . $this->db->escape($this->user_pass) . " ,";
        }


        $sql = "UPDATE user SET  " . $user_pass . " 
		user_role = " . $this->db->escape($this->user_role) . ",
		pen = " . $this->db->escape($this->pen) . ", 
		p_name =" . $this->db->escape($this->p_name) . ", 
		email = " . $this->db->escape($this->email) . ",
		mob = " . $this->db->escape($this->mob) . ",  
		user_status = " . $this->db->escape($this->user_status) . ", 
		user_unit= " . $this->db->escape($this->user_unit) . ", 
		unit_role= " . $this->db->escape($this->unit_role) . ", 
		date_time_updated = now(), 
		updated_by = " . $this->db->escape($this->updated_by) . "
        WHERE iduser = " . $this->db->escape($this->iduser) . "";

        //echo $sql;
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function addPermissionByRole($user_id, $role_id) {

        $sql = "INSERT INTO user_perm (user_id,perm_id)
				VALUES";
        $role_perm = new Permission;
        $role_perm->idrole = $role_id;
        $rs = $role_perm->getPermissionByRole();
        $values = array();
        while ($row = $rs->fetch_object()) {
            $values[] = "(" . $this->db->escape($user_id) . ", " . $this->db->escape($row->perm_id) . ")";
        }
        $sql.=implode(',', $values);

        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePermissionByRole($user_id, $role_id) {
        $sql = "DELETE FROM user_perm WHERE user_id = " . $this->db->escape($user_id) . " ";
        if ($this->db->query($sql)) {
            return $this->addPermissionByRole($user_id, $role_id);
        }
    }

    public function addPermissionToRole($role_id, $perm_id) {
        $usersbyrole = $this->viewByRole($role_id);
        $values = array();
        $users = array();

        while ($row = $usersbyrole->fetch_object()) {

            for ($i = 0; $i < count($perm_id); $i++) {
                $values[] = "(" . $this->db->escape($row->iduser) . ", " . $this->db->escape($perm_id[$i]) . ")";
            }
            $users[] = $row->iduser;
        }

        if (count($perm_id) > 0) {

            $sql = "INSERT IGNORE INTO user_perm(user_id,perm_id) VALUES " . implode(',', $values);
            //echo $sql;
            if ($this->db->query($sql)) {

                foreach ($perm_id as &$perm) {
                    $perm = $this->db->escape($perm);
                }

                $sql = "DELETE FROM user_perm WHERE user_id IN(" . implode(',', $users) . ") AND perm_id NOT IN(" . implode(',', $perm_id) . ")";
                $this->db->query($sql);
//echo $sql;
                return true;
            }
        } else {

            $sql = "DELETE FROM user_perm WHERE user_id IN(" . implode(',', $users) . ")";
            $this->db->query($sql);
            return true;
        }
    }

    public function removePermissionToRole($role_id, $perm_id) {

        $usersbyrole = $this->viewByRole($role_id);
        $values = array();
        while ($row = $usersbyrole->fetch_object()) {
            $values[] = $this->db->escape($row->user_id);
        }

        $sql = "DELETE FROM user_perm WHERE user_id IN( " . implode(',', $values) . ")
						AND perm_id = " . $this->db->escape($perm_id);

        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewPagedList($page, $rows) {

        $offset = ((int) $page - 1) * (int) $rows;
        $match = '';
        if ($this->searchkey != '') {
            $match = "WHERE (CONCAT(u.user_name,u.p_name,utr.unit_name)) LIKE '%" . $this->db->escape_like_str($this->searchkey) . "%' AND u.user_status <>0";
        }
        $sql = "SELECT count(*) row_count FROM user u  
		LEFT JOIN role r ON r.idrole = u.user_role 
		LEFT JOIN user_status us ON us.id = u.user_status 
		LEFT JOIN unit utu ON utu.id = u.user_unit 
		LEFT JOIN unit utr ON utr.id = u.unit_role 
		 " . $match . " 
				";

        $rscount = $this->db->query($sql);
        $rowcount = $rscount->row();

        $result['total'] = $rowcount->row_count;
        $sql = "SELECT
				u.iduser,
				u.user_name uname,
				u.user_role urole,
				u.pen,
				u.p_name,
				u.email,
				u.mob,
				u.user_status status,
				us.status_desc,
				u.user_unit utuser, 
				u.unit_role utrole,
				utu.unit_name uunit,
				utr.unit_name runit,
				ut.unit_type_desc utrdesc,				
				u.date_time_created,
				u.created_by,
				r.description role_desc,
				r.short_tag role_tag
				FROM user u
				LEFT JOIN role r ON r.idrole = u.user_role
				LEFT JOIN user_status us ON us.id = u.user_status
				LEFT JOIN unit utu ON utu.id = u.user_unit
				LEFT JOIN unit utr ON utr.id = u.unit_role
				LEFT JOIN unit_type ut ON ut.idunittype = utr.idunittype
				 " . $match . " 
				LIMIT " . $offset . "," . $rows;
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            //var_dump($ccode);
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function get_user_permissions($iduser) {
        $this->iduser = $iduser;
        $sql = "SELECT
                u.iduser,
                u.user_name,
                u.user_role,
                u.pen,
                u.p_name,
                u.email,
                u.mob,
                u.user_status,
                us.status_desc,
                u.user_unit, 
                u.unit_role,
                utu.unit_name uunit,
                utr.unit_name runit,				
                u.date_time_created,
                u.created_by,
                r.description role_desc,
                r.short_tag role_tag,
                vu.lft lft,
                vu.rgt rgt,
                GROUP_CONCAT(rp.perm_id) permissions,
                GROUP_CONCAT(p.perm_alias) permission_alias
                FROM user u
                LEFT JOIN role r ON r.idrole = u.user_role
                LEFT JOIN user_status us ON us.id = u.user_status
                LEFT JOIN unit utu ON utu.id = u.user_unit
                LEFT JOIN unit utr ON utr.id = u.unit_role
                LEFT JOIN role_perm rp ON rp.role_id = u.user_role
                LEFT JOIN permission p ON p.perm_id = rp.perm_id
                LEFT JOIN vw_unit vu on vu.id=u.unit_role 
                WHERE u.iduser =" . $this->db->escape($this->iduser) . "
                 ";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function deactivate() {
        $data = array(
            'user_status' => 0
        );
        $this->db->where('u.iduser', $this->iduser);
        //echo $sql;
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function activate() {
        $data = array(
            'user_status' => 1
        );
        $this->db->where('u.iduser', $this->iduser);
        //echo $sql;
        if ($this->db->update($this->table, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewUsersByUnit() {

        $sql = "SELECT 
		iduser id , 
		CONCAT(p_name,'-',user_name) title
        FROM user
        where unit_role= $this->unit_role and status = 1 and user_status=1";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

}
