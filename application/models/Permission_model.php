<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of permission_model
 *
 * @author Mukesh
 */
class Permission_model extends MY_Model {

    var $perm_id;
    var $perm_desc;
    var $idrole;
    var $perm_alias;

    public function __construct() {
        parent::__construct();
        $this->table_name = "permission";
    }

    public function setId($id) {
        $this->perm_id = $id;
        $this->asset_id = $id;
    }

    public function setParent($pid) {
        $this->parent_id = $pid;
    }

    public function add() {

        $data = array(
            'perm_desc' => $this->perm_desc,
            'perm_alias' => $this->perm_alias
        );

        if ($this->db->insert('permission', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->createNode();
            return true;
        } else {

            return false;
        }
    }

    public function addBefore($id) {

        $data = array(
            'perm_desc' => $this->perm_desc,
            'perm_alias' => $this->perm_alias
        );
        if ($this->db->insert('permission', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertLeft($id);
            return true;
        } else {

            return false;
        }
    }

    public function addAfter($id) {


        $data = array(
            'perm_desc' => $this->perm_desc,
            'perm_alias' => $this->perm_alias
        );
        if ($this->db->insert('permission', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertRight($id);
            return true;
        } else {

            return false;
        }
    }

    //update permission
    public function update() {

        $data = array(
            'perm_desc' => $this->perm_desc,
            'perm_alias' => $this->perm_alias
        );

        $this->db->where('perm_id', $this->perm_id);

        if ($this->db->update('permission', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {

        if ($this->hasChild()) {
            return false;
        } else {

            $sql = "DELETE FROM permission WHERE perm_id =" . $this->db->escape($this->perm_id) . ";";
            //echo $sql;
            if ($this->db->query($sql)) {
                $this->asset_id = $this->perm_id;
                return $this->deleteNode();
            } else {

                return false;
            }
        }
    }

    public function viewAll() {

        $sql = "SELECT 
		p.perm_id , 
		p.perm_desc 
		FROM permission p";

        if ($result = $this->db->query($sql)) {
            return $result;
        } else {
            return false;
        }
    }

    public function viewPagedList($page, $rows) {

        $offset = ($page - 1) * $rows;
        $WHERE = '';
        if ($this->perm_cat_id > 0) {
            $WHERE = " WHERE wp.perm_cat_id =" . $this->db->escape($this->perm_cat_id) . " ";
        } else {
            $WHERE = '';
        }

        $sql = "SELECT count(*) row_count FROM permission wp " . $WHERE . "";
        //echo $sql;
        $result['total'] = $this->db->countRows($sql);

        $sql = "SELECT 
		wp.perm_id , 
		wp.perm_desc ,
		wp.perm_cat_id,
		wpc.perm_cat_desc cat_desc
		FROM permission wp 
		LEFT JOIN perm_category wpc ON wpc.perm_cat_id = wp.perm_cat_id
		" . $WHERE . "
		LIMIT " . $this->db->escape($offset) . "," . $this->db->escape($rows);
        //echo $sql;

        $rs = $this->db->query($sql);
        $items = array();
        while ($row = $rs->fetch_object()) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function viewbyId() {


        $sql = "SELECT 
		p.perm_id , 
		p.perm_cat_id,
		pc.perm_cat_desc,
		p.perm_desc 
		FROM permission p
		LEFT JOIN perm_category pc ON pc.perm_cat_id = p.perm_cat_id 
		WHERE perm_id =" . $this->db->escape($this->perm_id);
        $result = $this->db->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

    public function viewbyCategory() {


        $sql = "SELECT 
		p.perm_id , 
		p.perm_cat_id,
		pc.perm_cat_desc,
		p.perm_desc 
		FROM permission p
		LEFT JOIN perm_category pc ON pc.perm_cat_id = p.perm_cat_id  
		WHERE perm_cat_id ='" . $this->db->escape($this->perm_cat_id) . "'";
        $result = $this->db->query($sql);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function addPermissionToRole($role_id, $perm_id) {

        if ($perm_id) {
            $sql = "INSERT IGNORE INTO role_perm (role_id,perm_id)
				VALUES";

            $values = array();
            for ($i = 0; $i < count($perm_id); $i++) {
                $values[] = "(" . $this->db->escape_str($role_id) . ", " . $this->db->escape_str($perm_id[$i]) . ")";
            }
            $sql.=implode(',', $values);



            if ($this->db->query($sql)) {


                $sql = "DELETE FROM role_perm WHERE role_id = " . $this->db->escape_str($role_id) . " AND perm_id NOT IN(" . implode(',', $this->db->escape_str($perm_id)) . ")";

                $this->db->query($sql);
            }
        } else {
            $sql = "DELETE FROM role_perm WHERE role_id = " . $this->db->escape_str($role_id);
            //echo $sql;
            $this->db->query($sql);
        }
        return true;
    }

    public function addPermissionToUser($iduser, $perm_id) {

        if (count($perm_id) > 0) {
            $sql = "INSERT IGNORE INTO user_perm (user_id,perm_id)
				VALUES";

            $values = array();
            for ($i = 0; $i < count($perm_id); $i++) {
                $values[] = "(" . $this->db->escape($iduser) . ", " . $this->db->escape($perm_id[$i]) . ")";
            }
            $sql.=implode(',', $values);



            if ($this->db->query($sql)) {

                foreach ($perm_id as &$value) {
                    $value = $this->db->escape($value);
                }

                $sql = "DELETE FROM user_perm WHERE user_id = " . $this->db->escape($iduser) . " AND perm_id NOT IN(" . implode(',', $perm_id) . ")";

                if ($this->db->query($sql)) {
                    return true;
                }
            }
        } else {
            $sql = "DELETE FROM user_perm WHERE user_id = " . $this->db->escape($iduser);
            //echo $sql;
            if ($this->db->query($sql)) {
                return true;
            }
        }
    }

    public function removePermissionFromRole($role_id, $perm_id) {

        $sql = "DELETE FROM 
				role_perm 
				WHERE
				role_id=" . $this->db->escape($role_id) . " AND perm_id=" . $this->db->escape($perm_id);
        if ($this->db->query($sql)) {
            $user = new user;
            $user->removePermissionToRole($role_id, $perm_id);
            return true;
        } else {

            return false;
        }
    }

    public function getPermissionByRole() {
        $sql = "select group_concat(perm_id) perm_id from role_perm where role_id =" . $this->db->escape($this->idrole);
        $result = $this->db->query($sql);
        if ($row = $result->row()) {
            return $row->perm_id;
        } else {
            return false;
        }
    }

    public function getPermissionByRoleId() {
        $this->db->select('perm_id id');
        $this->db->where('role_id', $this->idrole);
        $rs = $this->db->get('role_perm');

        $data = array();

        foreach ($rs->result_array() as $row) {
            $n = 'n_' . $row['id'];
            $data[$n] = $row['id'];
        }
        return $data;
    }

    public function createTree() {

        $sql = "SELECT 
		perm_id id,
		concat(perm_desc,' @ ',perm_alias)  text,
                perm_desc pdesc,
                perm_alias alias,
		nested_id,
		parent_id,
		root_id,
		lft,
		rgt,
		hlevel
		FROM vw_permission";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }

        return ($this->toNestedArray($items));
    }

    public function createList($id) {

        $sql = "SELECT 
		perm_id id,
		concat(perm_desc,' @ ',perm_alias)  text,
                perm_desc pdesc,
                perm_alias alias,
		nested_id,
		parent_id,
		root_id,
		lft,
		rgt,
		hlevel
		FROM vw_permission";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }

        return ($this->toNestedList($this->toNestedArray($items), $id));
    }

    public function toNestedList($tree, $id) {
        //Base case: an empty array produces no list
        if (empty($tree)) {
            return '';
        }

        //Recursive Step: make a list with child lists
        if ($id != null) {
            $output = '<ul  id=' . $id . '>';
        } else {
            $output = '<ul>';
        }

        foreach ($tree as $branch) {
            $data_all['id'] = $branch['id'];
            $data_all['pdesc'] = $branch['pdesc'];
            $data_all['alias'] = $branch['alias'];
            $data_all['parent_id'] = $branch['parent_id'];
            $output .= '<li id="' . $branch['id'] . '"  data-all =\'' . json_encode($data_all) . '\' >';

            if (!empty($branch['children'])) {

                $output .= '<span><i class="fa fa-sitemap text-primary"></i> <strong>' . $branch['text'] . '</strong> </span>';
                $output .= $this->toNestedList($branch['children'], null);
            } else {

                $output .= '<span><i class="fa fa-caret-right text-warning"></i> <strong>' . $branch['text'] . '</strong> </span>';
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function getPermissionCheckList($id) {
        $sql = "SELECT 
		perm_id id,
		concat(perm_desc,' @ ',perm_alias)  text,
                perm_desc pdesc,
                perm_alias alias,
		nested_id,
		parent_id,
		root_id,
		lft,
		rgt,
		hlevel
		FROM vw_permission
                where parent_id <> 0";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }

        return ($this->getCheckboxedList($this->toNestedArray($items), $id));
    }

    public function getCheckboxedList($tree, $id) {
        if (empty($tree)) {
            return '';
        }

        //Recursive Step: make a list with child lists
        if ($id != null) {
            $output = '<ul  id=' . $id . '>';
        } else {
            $output = '<ul>';
        }

        foreach ($tree as $branch) {
            $data_all['id'] = $branch['id'];
            $data_all['pdesc'] = $branch['pdesc'];
            $data_all['alias'] = $branch['alias'];
            $data_all['parent_id'] = $branch['parent_id'];
            $output .= '<li id="li_' . $branch['id'] . '"   >'
                    . '<input type="checkbox" name="n_' . $branch['id'] . '" id="' . $branch['id'] . '" value="' . $branch['id'] . '"> ';

            if (!empty($branch['children'])) {

                $output .= '<label for="n_' . $branch['id'] . '"> <strong>' . $branch['text'] . '</strong> </label>';
                $output .= $this->getCheckboxedList($branch['children'], null);
            } else {

                $output .= '<label for="n_' . $branch['id'] . '"> <strong>' . $branch['text'] . '</strong> </label>';
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function createTreeByRole($roleid) {
        $sql = "SELECT 
            	p.perm_id id,
            	p.perm_desc text,
            	p.parent_id,
            	p.lft,
            	p.rgt,
		p.hlevel,
            	IF(rp.perm_id IS NULL,'','checked') checked
		FROM vw_permission p
                LEFT JOIN role_perm rp ON rp.perm_id = p.perm_id
		AND rp.role_id = " . $roleid . "  
		AND p.hlevel >1
		WHERE parent_id!=0";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            //var_dump($row);
            array_push($items, $row);
        }

        return ($this->toNestedArray($items));
    }

    public function createTreeByUser($iduser) {
        $sql = "SELECT 
						p.perm_id id,
						p.perm_desc text,
						p.parent_id,
						p.lft,
						p.rgt,
						p.hlevel,
						IF(rp.perm_id IS NULL,'','checked') checked
						FROM vw_permission p
						LEFT JOIN user_perm rp ON rp.perm_id = p.perm_id
						AND rp.user_id = " . $this->db->escape($iduser) . "  
						AND p.hlevel >1
						WHERE parent_id!=0";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        while ($row = $rs->fetch_assoc()) {
            //var_dump($row);
            array_push($items, $row);
        }

        return ($this->toNestedArray($items));
    }

    public function listMenuPermission() {
        $this->db->select('perm_id id, perm_desc title');
        $this->db->where('hlevel >', 0);
        $this->db->order_by('perm_id', "asc");
        $query = $this->db->get('vw_permission');
        return $query->result_array();
    }

}
