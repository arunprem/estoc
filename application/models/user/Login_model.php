<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : usermodel
  Created on : 22-Nov-2014, 15:38:20
  Author     : Mukesh MR
  Description: User class

 */

class Login_model extends CI_Model {

    var $iduser;
    var $user_name;
    var $user_pass;
    var $user_role;
    var $user_status;
    var $pen;
    var $p_name;
    var $email;
    var $mob;
    var $user_unit;
    var $unit_role;
    var $date_time_created;
    var $date_time_updated;
    var $created_by;
    var $updated_by;
    var $profile_completion_status;
    var $status;
    var $now;
    var $login_count;

    public function __construct() {
        parent::__construct();
        $this->now = time();
    }

    public function checkLogin() {
        $this->db->where('user_name', $this->user_name);
        $this->db->where('user_pass', md5($this->user_pass));
        $this->db->where('user_status', 1);
        $rs = $this->db->get('user');
        if ($rs->num_rows() == 1) {
            $user = $rs->row();
            $this->load->model('user/user_model');
            $this->remove_block();
            $user_with_permissions = $this->user_model->get_user_permissions($user->iduser);
            return $user_with_permissions;
        } else {
            return FALSE;
        }
    }

    public function getCurrentTimeStampAndBlockStatus() {
        $this->db->select('login_timestamp,blocked_status');
        $this->db->from('user');
        $this->db->where('user_name', $this->user_name);


        $query = $this->db->get();
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row;
        } else {
            return FALSE;
        }
    }

    public function getCurrentCount() {
        $this->db->select('login_count');
        $this->db->from('user');
        $this->db->where('user_name', $this->user_name);


        $query = $this->db->get();
        
        $row = array();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
        }
        return $row['login_count'];
    }

    public function remove_block() {
        $data = array(
            'login_timestamp' => time(),
            'blocked_status' => 0,
            'login_count' => 0
        );
        $this->db->where('user_name', $this->user_name);
        //echo $sql;
        if ($this->db->update('user', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLoginCount() {
        $data = array(
            'login_count' => $this->login_count
        );
        $this->db->where('user_name', $this->user_name);
        //echo $sql;
        if ($this->db->update('user', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function blockUserForMultippleTry() {
        $data = array(
            'login_timestamp' => time(),
            'blocked_status' => 1
        );
        $this->db->where('user_name', $this->user_name);
        //echo $sql;
        if ($this->db->update('user', $data)) {
            return true;
        } else {
            return false;
        }
    }

}

?>
