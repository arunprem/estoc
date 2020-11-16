<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Menucat_model extends CI_Model
{
     var $id;
     var $menucat;
     var $description;
   
    var $searchkey;

    public function __construct() {
        parent::__construct();
        $this->now = time();
    }

    public function add() {

        $data = array(
           	'menu_cat'=> $this->menucat, 
		'description'=> $this->description 
         );

        if ($this->db->insert('menu_Cat', $data)) {
            return true;
        } else {

            return false;
        }
        
    }

    public function viewPagedList($rows, $page) {

        $offset = ((int) $page - 1) * (int) $rows;
        
        if ($this->searchkey != '') {
            $this->db->like('menu_cat',  $this->searchkey);
        }
        $result['total'] = $this->db->count_all_results('menu_cat');
        
        $this->db->select('id catid,menu_cat,description');
        if ($this->searchkey != '') {
            $this->db->like('menu_cat',  $this->searchkey);
        }
        $rs = $this->db->get('menu_Cat',$rows, $offset);
        $items = array();
        foreach($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
        
        }

    public function viewArrayList() {

        $this->db->select('id,menu_cat');
       
        $rs = $this->db->get('menu_Cat'); 
        $items = array();
        foreach($rs->result_array() as $row) {
            array_push($items, $row);
        }
        $result['rows'] = $items;
        return $result;
    }

    public function update() {
        
        $data = array(
           	'menu_cat'=> $this->menucat, 
		'description'=> $this->description 
         );
        
        $this->db->where('id', $this->id);
        //echo $sql;
        if ($this->db->update('menu_cat', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function deactivate() {
       
        $this->db->where('id', $this->id);
        //echo $sql;
        if ($this->db->delete('menu_cat')) {
            return true;
        } else {
            return false;
        }
    }
    

   
}
