<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * DT_Model done all datatble operations 
 *
 * @author admin
 */
class DT_Model extends MY_Model {

      /////////////////////////////////////////////////////////////////////////////
    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;

    public function __construct($table_name = NULL) {
        parent::__construct();

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
        
        if ($this->where) {
            foreach ($this->where as $w) {
                $this->db->where($w);
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
    

}
