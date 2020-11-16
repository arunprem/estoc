<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of datatable
 *
 * @author Mukesh
 */
class Role_model extends CI_Model {

    var $idrole;
    var $description;
    var $short_tag;
    var $table = 'role';
    
    var $column_order = array(null, 'idrole', 'description', 'short_tag'); //set column field database for datatable orderable
    var $column_search = array('description', 'short_tag'); //set column field database for datatable searchable 
    var $order = array('idrole' => 'asc'); // default order 
    var $select = array('idrole id', 'description desc', 'short_tag st');
    
    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;

    public function __construct() {
        parent::__construct();
    }

    private function _get_datatables_query() {

        $this->db->from($this->table);
        if ($this->select) {
            $this->db->select($this->select);
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

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
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
            'description' => $this->description,
            'short_tag' => $this->short_tag
        );
        //echo $sql;
        if ($this->db->insert('role', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewPagedList($rows, $page) {

        $offset = (($page) - 1) * ($rows);
        $result['total'] = $this->db->count_all_results('role');

        $this->db->select('idrole AS id ,description,short_tag');
        $rs = $this->db->get('role', $rows, $offset);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function viewArrayList() {

        $sql = "SELECT 
		idrole id , 
		description title,
		short_tag
		FROM role";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }
    public function viewPsArrayList() {

        $sql = "SELECT 
		idrole id , 
		description title,
		short_tag
        FROM role
        where short_tag in ('ps_admin','ps_enq_officer')";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewAll() {
        $sql = "SELECT 
				idrole , 
				description,
				short_tag
				FROM role";

        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function viewbyId() {

        $sql = "SELECT 
				idrole , 
				description,
				short_tag
				FROM role
				WHERE idrole=" . $this->db->escape($this->idrole) . "";
        $result = $this->db->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

    public function update() {


        $data = array(
            'description' => $this->description,
            'short_tag' => $this->short_tag
        );
        $this->db->where('idrole', $this->idrole);
        //echo $sql;
        if ($this->db->update('role', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $this->db->where('idrole', $this->idrole);
        if ($this->db->delete('role')) {
            return true;
        } else {
            return false;
        }
    }

}
