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
class Master_model extends MY_Model {


    public function __construct() {
        parent::__construct();
    }

    public function getSecProc() {
        //Create where clause
        $this->db->select('id,type_desc text ');
        $this->db->where('status' , 1);
        $rs = $this->db->get('m_sec_proc');
        return $rs->result();
    }
    public function getProperty() {
        //Create where clause
        $this->db->select('id,CONCAT(prop_desc,"-",unit_desc) text ');
        $this->db->where('status' , 1);
        $rs = $this->db->get('m_prop');
        //echo $this->db->last_query();
        return $rs->result();
    }

    public function listLsg()
    {
        $this->db->select('id,lsg_name text');
        $this->db->where('status=1');
        $rs = $this->db->get('m_cvd_ct_lsg');
        return $rs->result();
    }

    public function getWardByLsg($lsgid)
    {
        $this->db->select('id,ward_name text');
        $this->db->where('lsg_id', $lsgid);
        $this->db->where('status=1');
        $rs = $this->db->get('m_cvd_ct_lsg_wards');
        return $rs->result();
    }

    public function getWardByLsgCombo($lsgid)
    {
        $this->db->select('id,ward_name text');
        $this->db->where('lsg_id', $lsgid);
        $this->db->where('status=1');
        $rs = $this->db->get('m_cvd_ct_lsg_wards');
        $items = array();
        foreach ($rs->result_array() as $row) {
            $items[$row['id']] = $row['text'];
        }
        return $items;
    }

    public function uniqStr(){
        $this->load->helper('string');
        return sha1(uniqid(random_string('alnum',16)));
    }


}
