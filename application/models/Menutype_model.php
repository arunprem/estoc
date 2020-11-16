<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menutype_model extends CI_Model {

    var $id;
    var $menutype;
    var $description;
    var $status;

    public function viewPagedList($rows, $page) {
        $offset = (($page) - 1) * ($rows);
        $result['total'] = $this->db->count_all_results('menu_types');
        $this->db->select('id ,menutype,title,description,status');
        $rs = $this->db->get('menu_types', $rows, $offset);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function add() {
        $data = array(
            'menutype' =>$this->menutype,
            'description' => $this->description,
            'status' => $this->status,
        );
        //echo $sql;
        if ($this->db->insert('menu_types', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update() {
        $data = array(
            'menutype' =>$this->menutype,
            'description' => $this->description,
            'status' => $this->status,
        );
        $this->db->where('id', $this->id);
        //echo $sql;
        if ($this->db->update('menu_types', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function delete() {
        $data = array(
            'status' => 0
        );
        $this->db->where('id', $this->id);
        //echo $sql;
        if ($this->db->update('menu_types', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function viewArrayList() {

        $sql = "SELECT 
                id , 
		menutype
		FROM menu_types
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
		id , 
		menutype
		FROM menu_types";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return false;
        }
    }

    public function viewbyId() {

        $sql = "SELECT 
		id , 
		menutype
		FROM menu_types
		WHERE id=" . $this->db->escape($this->id) . "";
        $result = $this->db->query($sql);

        if ($result->num_rows() == 1) {
            return $result->row();
        } else {
            return false;
        }
    }

}

?>