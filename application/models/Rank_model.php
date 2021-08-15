<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rank_model
 *
 * @author Mukesh
 */
class Rank_model extends CI_Model {

    var $id;
    var $rank_desc;
    var $rank_short;
    var $priority;
    var $status;
    var $table = 'rank';
    var $column_order = array(null, 'id', 'rank_desc', 'rank_short', 'status'); //set column field database for datatable orderable
    var $column_search = array('rank_desc', 'rank_short'); //set column field database for datatable searchable 
    var $order = array('id' => 'asc'); // default order 
    var $select = array('idrank id', 'rank_desc desc', 'rank_short_tag st', 'status status');
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
            'rank_desc' => $this->rank_desc,
            'rank_short_tag' => $this->rank_short_tag,
            'status' => $this->status
        );
        //echo $sql;
        if ($this->db->insert('m_rank', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewArrayList() {


        $sql = "SELECT 
        idrank id , 
        rank_desc rname,
        rank_short_tag rshort
        FROM rank 
        WHERE status =1
        ORDER BY rank_priority
                ";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewUnitHeadList() {


        $sql = "SELECT 
        idrank id , 
        rank_desc rname,
        rank_short_tag rshort
        FROM rank 
        WHERE status =1 AND unit_head =1 
        ORDER BY rank_priority";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewAll() {
        $sql = "SELECT 
        idrank id, 
        rank_desc rcode,
        rank_short_tag rname
        FROM rank";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function allRanksNoQuery() {

        $sql = "SELECT 
                id, 
                post_desc text
                FROM m_post 
                WHERE 
                STATUS =1              
                ORDER BY post_desc";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function listAllRank() {
        $sql = "SELECT 
        id idrank , 
        rank_desc rcode,
        rank_short rname
        FROM m_rank";

        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        return $items;
    }

    public function viewbyidrank() {

        $sql = "SELECT 
        idrank  id, 
        rank_desc rcode,
        rank_short_tag rname
        FROM rank
        WHERE idrank=" . $this->db->escape($this->idrank) . "";
        $result = $this->db->query($sql);

        if ($result->num_rows() == 1) {
            return $result->row();
        } else {
            return false;
        }
    }

    public function update() {


        $data = array(
            'rank_desc' => $this->rank_desc,
            'rank_short_tag' => $this->rank_short_tag,
            'status' => $this->status
        );
        //echo $sql;
        $this->db->where('idrank', $this->idrank);
        if ($this->db->update('rank', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {

        $sql = "DELETE 
        FROM 
        rank 
        WHERE idrank=" . $this->db->escape($this->idrank) . "";
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivate() {
        $data = array(
            'status' => 0
        );
        $this->db->where('idrank', $this->idrank);
        //echo $sql;
        if ($this->db->update('rank', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function activate() {
        $data = array(
            'status' => 1
        );
        $this->db->where('idrank', $this->idrank);
        //echo $sql;
        if ($this->db->update('rank', $data)) {
            return true;
        } else {
            return false;
        }
    }

}
