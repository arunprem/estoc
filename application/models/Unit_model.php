<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of unit_model
 *
 * @author Mukesh
 */
class unit_model extends MY_Model {

    var $id;
    var $ncrb_id;
    var $unit_name;
    var $idunittype;
    var $head_rank;
    var $is_parent_unit;
    var $status;
    var $table_name;
    var $pid;
    var $searchquery;
    /////////////////////////////////////////////////////////////////////////////
    var $select_table = 'vw_unit';
    var $insert_table = 'unit';
    var $column_order = array(null, null, null, null, null, 'ncrb_id', 'unit_name', 'unit_type_desc', 'rank_short_tag', null, 'is_parent_unit', null); //set column field database for datatable orderable
    var $column_search = array('ncrb_id', 'unit_name', 'unit_type_desc', 'rank_short_tag'); //set column field database for datatable searchable 
    var $order = array('id' => 'asc'); // default order 
    var $select = array('id', 'IF(ncrb_id=0,\'\', ncrb_id) ncrb_id', 'unit_name', 'status', 'idunittype', 'IFNULL(head_rank,"") head_rank', 'IFNULL(unit_type_desc,"") unit_type_desc', 'IFNULL(unit_type,"") unit_type', 'rank_desc', 'IFNULL(rank_short_tag,"") rank_short_tag', 'parent_id', 'parent_unit', 'is_parent_unit');
    var $join = array();
    /////////////////////////////////////////////////////////////////////////////
    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;

    public function __construct() {
        parent::__construct();
        $this->table_name = "unit";
    }

    private function _get_datatables_query() {

        $this->db->from($this->select_table);
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

    public function add() {
        $data = array(
            'ncrb_id' => $this->ncrb_id,
            'unit_name' => $this->unit_name,
            'idunittype' => $this->idunittype,
            'head_rank' => $this->head_rank,
            'is_parent_unit' => $this->is_parent_unit,
            'status' => $this->status
        );
        //echo $sql;
        if ($this->db->insert('unit', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->parent_id = $this->pid;
            $this->createNode();
            return true;
        } else {
            return false;
        }
    }

    public function addAfter($id) {
        //echo $sql;
        $data = array(
            'ncrb_id' => $this->ncrb_id,
            'unit_name' => $this->unit_name,
            'idunittype' => $this->idunittype,
            'is_parent_unit' => $this->is_parent_unit,
            'head_rank' => $this->head_rank
        );
        if ($this->db->insert('unit', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertRight($id);
            return true;
        } else {
            return false;
        }
    }

    public function addBefore($id) {
        $data = array(
            'ncrb_id' => $this->ncrb_id,
            'unit_name' => $this->unit_name,
            'idunittype' => $this->idunittype,
            'is_parent_unit' => $this->is_parent_unit,
            'head_rank' => $this->head_rank
        );
        if ($this->db->insert('unit', $data)) {
            $this->asset_id = $this->db->insert_id();
            $this->insertLeft($id);
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'ncrb_id' => $this->ncrb_id,
            'unit_name' => $this->unit_name,
            'idunittype' => $this->idunittype,
            'head_rank' => $this->head_rank,
            'is_parent_unit' => $this->is_parent_unit,
            'status' => $this->status
        );
        $this->db->where('id', $this->id);
        if ($this->db->update('unit', $data)) {
            $node = $this->getNode($this->id);
            if ($node) {
                if ($node->parent_id != $this->pid) {
                    $this->asset_id = $this->id;
                    $this->moveTo($this->pid);
                }
            } else {
                $this->asset_id = $this->id;
                $this->parent_id = $this->pid;
                $this->createNode();
            }
            return true;
        } else {
            return false;
        }
    }

    public function viewPagedList($page, $rows) {
        $match = '';
        if ($this->searchkey != '') {
            $match = "WHERE u.unit_name LIKE '%" . $this->searchkey . "%' ";
        }

        $offset = ((int) $page - 1) * (int) $rows;
        $sql = "SELECT count(*) row_count FROM vw_unit u " . $match . " ";

        $rscount = $this->db->query($sql);
        $rowcount = $rscount->row();

        $result['total'] = $rowcount->row_count;

        $sql = "SELECT 
		u.id,         
		IF(u.ncrb_id=0,'', u.ncrb_id) ncrb_id,  
		u.unit_name uname, 
		u.idunittype utypeid, 
		IF(u.head_rank=0,'', u.head_rank) hou,  
		u.status, 
		u.unit_type_desc utype,
		d.unit_name punit,
		d.id pid 
		FROM vw_unit u 
		LEFT JOIN vw_unit d ON d.id = u.parent_id 
		 " . $match . " 
		ORDER BY  u.idunittype, u.unit_name 
		LIMIT " . $offset . "," . $rows;
        //echo $sql;	

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function viewArrayList() {

        $sql = "SELECT
		u.id,        
                IF(u.ncrb_id=0,'', u.ncrb_id) ncrb_id,  
		u.unit_name utname, 
		u.idunittype utypeid,
		IF(u.head_rank=0,'', u.head_rank) dist,  
		u.status,
		ut.idunittype utype,
		d.unit_name dist_name
		FROM unit u 
		LEFT JOIN idunittype ut ON ut.id = u.idunittype
		LEFT JOIN unit d ON d.ncrb_id = u.head_rank AND d.ncrb_id<>0 AND  u.idunittype =1
		WHERE u.status=1
		ORDER BY  u.head_rank,u.ncrb_id";
        //echo $sql;

        $rs = $this->db->query($sql);
        $items = array();
        while ($row = $rs->fetch_object()) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewComboList() {

        $parent = $this->getNode($this->pid);


        $sql = "SELECT
		u.id,        
		u.ncrb_id,  
		u.unit_name utname, 
		u.unit_type_desc utype,
		u.rank_desc rank
		FROM vw_unit u 
		WHERE u.status=1
		AND u.lft >= " . $parent->lft . " AND u.rgt <= " . $parent->rgt . "
		AND u.unit_name LIKE '%" . $this->db->escape_like_str($this->searchquery) . "%' ESCAPE '!'";
        //echo $sql;

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewAllComboList() {

        $sql = "SELECT 
                id, 
                CONCAT(unit_name,'-',unit_type_desc,'-',rank_short_tag) text
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND CONCAT(unit_name,' ',unit_type_desc,' ',rank_short_tag) like '%" . $this->db->escape_str($this->searchquery) . "%'";

        //echo $sql;

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function districtArray() {

        $sql = "SELECT  
		id,  
		unit_name distname 
		FROM unit 
		WHERE idunittype=11 AND status=1";
        //echo $sql;

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function delete() {

        $sql = "UPDATE unit 
		SET			
		status = 0
		WHERE id='" . $this->id . "'";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function activate() {

        $sql = "UPDATE unit 
		SET			
		status = 1
		WHERE id='" . $this->id . "'";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUnitType($uid) {
        $sql = "SELECT idunittype FROM unit WHERE id = '" . $uid . "'";
        $rs = $this->db->query($sql);
        $result = $rs->fetch_object();
        return $result->idunittype;
    }

    public function getDistCode($uid) {
        $sql = "SELECT ncrb_id FROM unit WHERE id = '" . $uid . "'";
        $rs = $this->db->query($sql);
        $result = $rs->fetch_object();
        return $result->ncrb_id;
    }

    public function psByDist() {

        $parent = $this->getNode($this->id);

        $sql = "SELECT 
		id,         
		unit_name psname 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function allPs() {
        $sql = "SELECT 
		id,         
		unit_name psname 
		FROM vw_unit 
		WHERE 
		status =1		
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function psByUser($unit) {

        $parent = $this->getNode($unit);

        $sql = "SELECT 
		id, 
		ncrb_id,         
		unit_name uname 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function psByUsertoDropDown($unit) {

        $parent = $this->getNode($unit);

        $sql = "SELECT 
		id, 		     
		unit_name text 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function unitsByUser() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
				id, 
                                ncrb_id,
				unit_name uname, 
                                unit_type_desc
				FROM vw_unit 
				WHERE 
				status =1
				AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
				AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function allUnitsByUser() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
                id, 
                CONCAT(unit_name,'-',unit_type_desc,'-',rank_short_tag) text
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND CONCAT(unit_name,' ',unit_type_desc,' ',rank_short_tag) like '%" . $this->db->escape_str($this->searchquery) . "%'
                AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                ORDER BY unit_name,parent_id,unit_type";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function allUnitsByUserNoQuery() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
                id, 
                CONCAT(unit_name,'-',unit_type_desc,'-',rank_short_tag) text
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                ORDER BY hlevel,id,unit_type,unit_name";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function allUnitsNoQuery() {

        $sql = "SELECT 
                id, 
                unit_name text
                FROM vw_unit 
                WHERE 
                status =1 and parent_id <>0               
                ORDER BY hlevel,id,unit_type,unit_name";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function createTree() {

        $sql = "SELECT 
		id,
		unit_name,
		parent_id,
		root_id,
		unit_type_desc utype,
		lft,
		rgt,
		hlevel
		FROM vw_unit
		WHERE parent_id <>0
		AND status=1";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }

        return ($this->toNestedArray($items));
    }

    public function createList($id) {

        $sql = "SELECT 
		id,
		unit_name,
		parent_id,
		root_id,
		unit_type_desc utype,
		lft,
		rgt,
		hlevel
		FROM vw_unit
		WHERE parent_id <>0
		AND status=1";
        //echo $sql;
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }

        return ($this->toNestedLi($this->toNestedArray($items), $id));
    }

    public function isAllowedPs($unitid) {
        $user = $this->session->userdata('user');
        $myunit = $this->getNode($user->unit_role);

        $sql = "SELECT id FROM vw_unit WHERE lft >='" . $myunit->lft . "' AND rgt <= '" . $myunit->rgt . "' AND ncrb_id =" . $this->db->escape($unitid) . " ";
        $rs = $this->db->query($sql);
        if ($rs->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    

    public function pagedunitsByUser($rows, $page) {

        $offset = (($page) - 1) * ($rows);
        $this->db->like('unit_name', $this->searchquery);
        $result['total'] = $this->db->count_all_results('vw_unit');
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT  
		id,       
		unit_name uname, 
                unit_type_desc
		FROM vw_unit 
		WHERE 
		status =1 
		AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                AND unit_name LIKE '%" . $this->db->escape_str($this->searchquery) . "%'     
		LIMIT  " . $this->db->escape_str($offset) . "," . $this->db->escape_str($rows) . " ";


        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function getParrentId() {
        $sql = "SELECT parent_id FROM vw_unit WHERE id = " . $this->db->escape($this->id) . "";
        $rs = $this->db->query($sql);
        foreach ($rs->result_array()as $id) {
            return $id;
        }
    }

    public function getNcrbidByuser() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
		
		ncrb_id         
		
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21";
        $rs = $this->db->query($sql);


        $unitrs = $rs->result_array();
        foreach ($unitrs as $key => $value) {
            $singleArray[$key] = $value['ncrb_id'];
        }
        return $singleArray;
    }

    public function ChieldUnitsByUser() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $userlevel = $this->getUserUnit();
        $level = $userlevel['hlevel'];
        if ($level > 0) {
            $level = $level + 1;
        } elseif ($level == 0) {
            $level = $level + 4;
        }

        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
				id, 
                                ncrb_id,
				unit_name uname, 
                                unit_type_desc
				FROM vw_unit 
				WHERE 
				status =1
				AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
				AND hlevel = $level AND idunittype in(11,19,20,21,36) ";
        $rs = $this->db->query($sql);
        if ($rs->num_rows() > 0) {

            $unitrs = $rs->result_array();
            foreach ($unitrs as $key => $value) {
                $singleArray[$key] = $value['id'];
            }
            return $singleArray;
        }
    }

    public function getUserUnit() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $sql = "SELECT u.hlevel,u.unit_name,r.rank_desc FROM vw_unit u
                LEFT JOIN rank r ON u.head_rank=r.idrank
                WHERE u.id=" . $this->db->escape($user_unit) . "";
        $rs = $this->db->query($sql);

        foreach ($rs->result_array() as $row) {
            $items = $row;
        }
        return $items;
    }

    public function getSubunits() {
        $user = $this->session->userdata['user'];
        $user_unit = $user->unit_role;
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
				id
				FROM vw_unit 
				WHERE 
				status =1
				AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
				 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result() as $row) {
            array_push($items, $row->id);
        }
        return $items;
    }

    public function isAllowedUnit() {

        $user = $this->session->userdata('user');
        $myunit = $this->getNode($user->unit_role);
        //$subunits = $this->unit_model->getSubunits();

        $this->db->select('count(*) as row_count');
        $this->db->where('id', $this->id);
        $this->db->where("lft >= $myunit->lft and rgt <= $myunit->rgt");
        $this->db->where('status', 1);

        $rscount = $this->db->get('vw_unit');
        $rowcount = $rscount->row();
        if ($rowcount->row_count > 0) {
            return true;
        } else {
            return false;
        }
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
            $output .= '<li class="button">';
            $output .= '<strong>' . $branch['unit_name'] . '</strong>-' . $branch['utype'];
            if (!empty($branch['children'])) {
                $output .= $this->toNestedList($branch['children'], null);
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function toNestedLi($tree, $id) {
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

            $output .= '<li id="' . $branch['id'] . '">';

            if (!empty($branch['children'])) {

                $output .= '<span><i class="fa fa-sitemap text-primary"></i> <strong>' . $branch['unit_name'] . '</strong>-' . $branch['utype'] . '</span>';
                $output .= $this->toNestedLi($branch['children'], null);
            } else {

                $output .= '<span><i class="fa fa-caret-right text-warning"></i> <strong>' . $branch['unit_name'] . '</strong>-' . $branch['utype'] . '</span>';
            }
            $output .= '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function getDistricts() {
        $sql = "SELECT 
                id, 
                unit_name text
                FROM vw_unit 
                WHERE 
                status =1 
                and parent_id <>0 
                AND unit_type = 11
                or id = 918                
                ORDER BY hlevel,id,unit_type,unit_name
                ";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getSd($d) {
        $parent = $this->getNode($d);

        $sql = "SELECT 
                id, 
                unit_name text
                FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                AND unit_type = 19    
				 ";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getCircle($sd) {
        $parent = $this->getNode($sd);

        $sql = "SELECT 
                id, 
                unit_name text
                FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                AND unit_type = 20    
				 ";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getParentUnits() {
        $sql = "SELECT 
                id, 
                unit_name text
                FROM vw_unit 
                WHERE 
                status =1 
                and parent_id <>0
                and is_parent_unit = 1                           
                ORDER BY hlevel,id,unit_type,unit_name
                ";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function listAllPs($dist) {
        $this->db->select('id,unit_name text');
        $this->db->where('idunittype=21');
        $this->db->where('status=1');
        $this->db->where('dist',$dist);
        $rs = $this->db->get('unit');
        return $rs->result();
    }

    public function listAllPoliceDistrict(){
        $this->db->select('id,unit_name text');
        $this->db->where('idunittype=11');
        $this->db->where('status=1');
        $rs = $this->db->get('unit');
        return $rs->result();
    }

    public function listAllUnitsUnderParent($user_unit) {


        $parent = $this->getNode($user_unit);
        $sql = "SELECT 
                id
               
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                ORDER BY hlevel,id,unit_type,unit_name";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getAllParentUnit() {
        $this->db->select('id,unit_name text');
        $this->db->where('is_parent_unit', 1);
        $this->db->where('status', 1);
        $rs = $this->db->get('unit');
        return $rs->result();
    }

    public function getAllPoliceStation($user) {
        $this->db->select('id,unit_name text');
        $this->db->where_in('id', $user);
        $this->db->where('idunittype', 21);
        $this->db->where('status', 1);
        $rs = $this->db->get('unit');
        return $rs->result();
    }

    public function getAllUnit() {
        $this->db->select('id,unit_name text');
        $this->db->where('status', 1);

        $rs = $this->db->get('unit');
        return $rs->result();
    }

    public function getAllsubunits($user_unit) {
        $parent = $this->getNode($user_unit);

        $sql = "SELECT 
                id, 
                CONCAT(unit_name,'-',unit_type_desc,'-',rank_short_tag) text
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND CONCAT(unit_name,' ',unit_type_desc,' ',rank_short_tag) like '%" . $this->db->escape_str($this->searchquery) . "%'
                AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                ORDER BY unit_name,parent_id,unit_type";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getUnitInfo() {
        $this->db->select('id,unit_name, unit_type_desc,status');
        $this->db->where('status=1');
        $this->db->where('id', $this->id);
        $rs = $this->db->get('vw_unit');
        if ($rs) {
            return $rs->row();
        } else {
            return false;
        }
    }

    public function getChildByParant($parent_id) {

        $parent = $this->getNode($parent_id);

        $sql = "SELECT 
                id, 
                CONCAT(unit_name,'-',unit_type_desc,'-',rank_short_tag) text
                FROM vw_unit 
                WHERE 
                STATUS =1
                AND CONCAT(unit_name,' ',unit_type_desc,' ',rank_short_tag) like '%" . $this->db->escape_str($this->searchquery) . "%'
                AND lft >= " . $this->db->escape($parent->lft) . " AND rgt <= " . $this->db->escape($parent->rgt) . "
                ORDER BY unit_name,parent_id,unit_type";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function getDistrictsQ() {

        $this->db->select('id, unit_name text');
        $this->db->where('status', 1);
        $this->db->where('is_qdata', 1);
        $this->db->order_by('list_order');
        $rs = $this->db->get('unit');
        //echo $this->db->last_query();
        return $rs->result();
    }

    public function psByDistCombo($dist) {

        $parent = $this->getNode($dist);

        $sql = "SELECT 
		id,         
		unit_name text 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            $items[$row['id']] = $row['text'];
        }
        return $items;
    }
    public function psByDistQ($dist) {

        $parent = $this->getNode($dist);

        $sql = "SELECT 
		id,         
		unit_name text 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        return $rs->result();
    }

    public function psByUserQ() {

        $user= $this->session->userdata('user');
        $parent = $this->getNode($user->unit_role);

        $sql = "SELECT 
		id,         
		unit_name text 
		FROM vw_unit 
		WHERE 
		status =1
		AND lft >= " . $parent->lft . " AND rgt <= " . $parent->rgt . "
		AND unit_type = 21 ";

        $rs = $this->db->query($sql);
        return $rs->result();
    }

}
