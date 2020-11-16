<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu_model
 *
 * @author Mukesh
 */
class menu_model extends MY_Model {

    //put your code here


    var $id;
    var $menu_cat;
    var $title;
    var $alias;
    var $description;
    var $path;
    var $type;
    var $ordering;
    var $navtype;
    var $perm_id;
    var $style_id;
    var $params;
    var $language;
    var $status;
    var $user_id = NULL;
    var $table_name;

    public function __construct() {
        parent::__construct();
        $this->table_name = "menu";
    }

    public function setId($id) {
        $this->id = $id;
        $this->asset_id = $id;
    }

    public function add() {


        $sql = "INSERT INTO menu 
				( 
				title, 
				alias, 
				description, 
				path, 
				type,  
				ordering, 
				navtype, 
				perm_id, 
				style_id, 
				params, 
				language, 
				status
				)
				VALUES
				( 
				" . $this->db->escape($this->title) . ",  
				" . $this->db->escape($this->alias) . ",  
				" . $this->db->escape($this->description) . ",  
				" . $this->db->escape($this->path) . ",  
				" . $this->db->escape($this->type) . ",   
				" . $this->db->escape($this->ordering) . ",  
				" . $this->db->escape($this->navtype) . ",  
				" . $this->db->escape($this->perm_id) . ",  
				" . $this->db->escape($this->style_id) . ",  
				" . $this->db->escape($this->params) . ",  
				" . $this->db->escape($this->language) . ",  
				" . $this->db->escape($this->status) . "
				)";

        if ($this->db->query($sql)) {
            $this->asset_id = $this->db->insert_id();
            $this->createNode();
            return true;
        } else {

            return false;
        }
    }

    public function addBefore($id) {


        $sql = "INSERT INTO menu 
				( 
				title, 
				alias, 
				description, 
				path, 
				type,  
				navtype, 
				perm_id, 
				style_id, 
				params, 
				language, 
				status
				)
				VALUES
				( 
				" . $this->db->escape($this->title) . ",  
				" . $this->db->escape($this->alias) . ",  
				" . $this->db->escape($this->description) . ",  
				" . $this->db->escape($this->path) . ",  
				" . $this->db->escape($this->type) . ",    
				" . $this->db->escape($this->navtype) . ",  
				" . $this->db->escape($this->perm_id) . ",  
				" . $this->db->escape($this->style_id) . ",  
				" . $this->db->escape($this->params) . ",  
				" . $this->db->escape($this->language) . ",  
				" . $this->db->escape($this->status) . "
				)";

        if ($this->db->query($sql)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertLeft($id);
            return true;
        } else {

            return false;
        }
    }

    public function addAfter($id) {


        $sql = "INSERT INTO menu 
				( 
				title, 
				alias, 
				description, 
				path, 
				type,  
				ordering, 
				navtype, 
				perm_id, 
				style_id, 
				params, 
				language, 
				status
				)
				VALUES
				( 
				" . $this->db->escape($this->title) . ",  
				" . $this->db->escape($this->alias) . ",  
				" . $this->db->escape($this->description) . ",  
				" . $this->db->escape($this->path) . ",  
				" . $this->db->escape($this->type) . ",   
				" . $this->db->escape($this->ordering) . ",  
				" . $this->db->escape($this->navtype) . ",  
				" . $this->db->escape($this->perm_id) . ",  
				" . $this->db->escape($this->style_id) . ",  
				" . $this->db->escape($this->params) . ",  
				" . $this->db->escape($this->language) . ",  
				" . $this->db->escape($this->status) . "
				)";

        if ($this->db->query($sql)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertRight($id);
            return true;
        } else {

            return false;
        }
    }

    public function update() {


        $sql = "UPDATE menu 
		SET
		title= " . $this->db->escape($this->title) . ",
		alias= " . $this->db->escape($this->alias) . ",
		description=" . $this->db->escape($this->description) . ",
		path= " . $this->db->escape($this->path) . ",
		ordering= " . $this->db->escape($this->ordering) . ",
		perm_id= " . $this->db->escape($this->perm_id) . ",
		params= " . $this->db->escape($this->params) . ",
		language= " . $this->db->escape($this->language) . ",
		status= " . $this->db->escape($this->status) . " 
		WHERE id= " . $this->db->escape($this->id) . "";
        //echo $sql;
        if ($this->db->query($sql)) {

            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        if ($this->hasChild()) {
            return false;
        } else {

            $sql = "DELETE FROM menu WHERE id =" . $this->db->escape($this->id) . ";";
            //echo $sql;
            if ($this->db->query($sql)) {
                $this->asset_id = $this->id;
                return $this->deleteNode();
            } else {

                return false;
            }
        }
    }

    public function createTree() {
        $sql = "SELECT 
		id,
		title text, 
		alias, 
		description, 
		path, 
		type,  
		ordering, 
		navtype, 
		perm_id, 
		style_id, 
		params, 
		language, 
		status,
		parent_id,
		root_id,
		lft,
		rgt,
		hlevel
		FROM vw_menu
		";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array()as $row) {
            //var_dump($row);
            array_push($items, $row);
        }

        return ($this->toNestedArray($items));
    }

    public function createList($id) {
        $sql = "SELECT 
		id,
		title text, 
		alias, 
		description, 
		path, 
		type,  
		ordering, 
		navtype, 
		perm_id, 
		style_id, 
		params, 
		language, 
		status,
		parent_id,
		root_id,
		lft,
		rgt,
		hlevel
		FROM vw_menu
		";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array()as $row) {
            //var_dump($row);
            array_push($items, $row);
        }

        return ($this->toNestedList($this->toNestedArray($items), $id));
    }

    public function getMenuById() {

        $sql = "SELECT 	
                wm.id, 
		wm.title, 
		wm.alias, 
		wm.description, 
		wm.path, 
		wm.type, 
		wm.ordering, 
		wm.navtype, 
		wm.perm_id permission, 
		wm.style_id, 
		wm.params, 
		wm.language, 
		wm.status
		FROM menu wm
		WHERE id= " . $this->db->escape($this->id) . ";";
        //echo $sql;

        if ($rs = $this->db->query($sql)) {
            $row = $rs->row();
            $mparams = array();
            if ($row->params) {
                $params = json_decode($row->params);
                $i = 0;
                foreach ($params as $key => $value) {
                    $mparams[$i]['pname'] = $key;
                    $mparams[$i]['pval'] = $value;
                    $i++;
                }

                $row->params = $mparams;
            }
            return $row;
        } else {
            return false;
        }
    }

    public function createMenu($user) {
        $this->load->model('permission_model');
        $this->permission_model->idrole = $user->user_role;
        $menu_tree = array();
        $roles = $this->permission_model->getPermissionByRole();
        if ($roles) {
            $sql = "SELECT 
                    id,
                    title text, 
                    alias, 
                    description, 
                    path, 
                    type,  
                    ordering, 
                    navtype, 
                    perm_id, 
                    style_id, 
                    params, 
                    language, 
                    status,
                    parent_id,
                    root_id,
                    lft,
                    rgt,
                    hlevel
                    FROM vw_menu
                    where perm_id IN (" . $this->db->escape_str($roles) . ")";
            $rs = $this->db->query($sql);
            $items = array();
            foreach ($rs->result_array()as $row) {
                //var_dump($row);
                array_push($items, $row);
            }

            $menu_tree = $this->toNestedArray($items);
        }

        if (count($menu_tree) > 0) {
            return $this->menuRender($menu_tree);
        } else {
            return NULL;
        }
    }

    public function createUserMenu($user) {
        $menu_tree = array();
        $roles = $user->permissions;
        if ($roles) {
            $sql = "SELECT 
                    id,
                    title text, 
                    alias, 
                    description, 
                    path, 
                    type,  
                    ordering, 
                    navtype, 
                    perm_id, 
                    style_id, 
                    params, 
                    language, 
                    status,
                    parent_id,
                    root_id,
                    lft,
                    rgt,
                    hlevel
                    FROM vw_menu
                    where perm_id IN (" . $this->db->escape_str($roles) . ") 
                    AND status =1";
            $rs = $this->db->query($sql);
            $items = array();
            foreach ($rs->result_array()as $row) {
                array_push($items, $row);
            }

            $menu_tree = $this->toNestedArray($items);
        }

        if (count($menu_tree) > 0) {
            return $this->menuRender($menu_tree);
        } else {
            return NULL;
        }
    }

    public function menuRender($arraytree) {
        $menu = '';
        
        foreach ($arraytree as $tree) {  
            if ($tree['path'] != "0") {
                $menu.='<li class="nav-li treeview">
                        <a href="#" onClick="loadLayout(\'' . $tree['text'] . '\', \'' . $tree['path'] . '\')">
                          <i class="fa ' . $tree['params'] . '"></i> <span>' . $tree['text'] . ' </a>
                        <li>';
            } else {
                $menu.='<li class="treeview nav-li">
                        <a href="#">
                          <i class="fa ' . $tree['params'] . '"></i> <span>' . $tree['text'] . '</span>
                          <span class="pull-right-container">
                            <i class="fa fa-angle-right pull-right"></i>
                          </span>
                        </a>
                        <ul class="treeview-menu">';

                foreach ($tree['children'] as $submenu) {
                    $menu.="<li class='nav-sub-li'><a href='#' onClick=\"loadLayout('" . $submenu['text'] . "', '" . $submenu['path'] . "')\"><i class='" . $submenu['params'] . "'></i> " . $submenu['text'] . "</a></li>";
                }

                $menu.='</ul></li>';
            }
        }
        return $menu;
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
            $data_all['title'] = $branch['text'];
            $data_all['alias'] = $branch['alias'];
            $data_all['description'] = $branch['description'];
            $data_all['path'] = $branch['path'];
            $data_all['permission'] = $branch['perm_id'];
            $data_all['status'] = $branch['status'];
            $data_all['params'] = $branch['params'];
            $data_all['parent'] = $branch['parent_id'];

            $classname = $data_all['status'] == 1 ? 'active' : 'inactive';


            $output .= '<li class = "' . $classname . '" id="' . $branch['id'] . '"  data-all =\'' . json_encode($data_all) . '\' >';

            if (!empty($branch['children'])) {

                $output .= '<span><i class="' . $data_all['params'] . ' text-primary"></i> <strong>' . $branch['text'] . '</strong> </span>';
                $output .= $this->toNestedList($branch['children'], null);
            } else {

                $output .= '<span><i class="' . $data_all['params'] . '" text-warning"></i> <strong>' . $branch['text'] . '</strong> </span>';
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

}
