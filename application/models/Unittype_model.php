<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unittype_model extends CI_Model {

    var $idunittype;
    var $unit_type_desc;
    var $status;

    var $table = 'unit_type';
    var $column_order = array(null, 'idunittype', 'unit_type_desc', 'status'); //set column field database for datatable orderable
    var $column_search = array('unit_type_desc'); //set column field database for datatable searchable 
    var $order = array('idunittype' => 'asc'); // default order 
    var $select = array('idunittype id', 'unit_type_desc desc', 'status');
    
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
            'unit_type_desc' => $this->unit_type_desc,
            'status' => $this->status
        );
        //echo $sql;
        if ($this->db->insert('unit_type', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'unit_type_desc' => $this->unit_type_desc,
            'status' => $this->status
        );
        $this->db->where('idunittype', $this->idunittype);
        //echo $sql;
        if ($this->db->update('unit_type', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivate() {
        $data = array(
            'status' => 0
        );
        $this->db->where('idunittype', $this->idunittype);
        //echo $sql;
        if ($this->db->update('unit_type', $data)) {
            return true;
        } else {
            return false;
        }
    }
    
     public function activate() {
        $data = array(
            'status' => 1
        );
        $this->db->where('idunittype', $this->idunittype);
        //echo $sql;
        if ($this->db->update('unit_type', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewArrayList() {

        $sql = "SELECT 
                idunittype id , 
		unit_type_desc
		FROM unit_type
		WHERE status =1";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewAll() {
        $sql = "SELECT 
		idunittype id , 
		unit_type_desc
		FROM unit_type";

        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function viewbyId() {

        $sql = "SELECT 
		idunittype , 
		unit_type_desc
		FROM unit_type
		WHERE idunittype=" . $this->db->escape($this->idunittype) . "";
        $result = $this->db->query($sql);

        if ($result->num_rows == 1) {
            return $result->fetch_object();
        } else {
            return false;
        }
    }

}

?>
