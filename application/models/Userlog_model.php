<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of userlog_model
 *
 * @author admin
 */
class userlog_model extends CI_Model {

    var $idlog;
    var $iduser;
    var $sess_id;
    var $login_time;
    var $logout_time;
    var $status;
    var $login_ip;

    public function __construct() {
        parent::__construct();
    }

    public function add() {
        $this->login_time = date('Y-m-d H:i:s');
        $this->login_ip = "IP : " . $this->input->ip_address();

        if ($this->input->server('HTTP_X_FORWARDED_FOR')) { //to check ip is pass from proxy
            $this->login_ip .="IP X FORWARDED : " . $this->input->server('HTTP_X_FORWARDED_FOR');
        }

        $sql = "insert into user_log
				(
				iduser, 
				sess_id, 
				login_time, 
				login_ip
				)
				values
				(
				 " . $this->db->escape($this->iduser) . ", 
				 " . $this->db->escape($this->sess_id) . ", 
				 " . $this->db->escape($this->login_time) . ",
				 " . $this->db->escape($this->login_ip) . "
				)";
        //echo $sql;
        if ($this->db->query($sql)) {
            return $this->db->insert_id();
        } else {
            return false;
        }
    }

    public function logout() {
        $this->logout_time = date('Y-m-d H:i:s');

        $sql = "UPDATE user_log SET 
				logout_time = " . $this->db->escape($this->logout_time) . ", 
				status = 'logged out' 
				WHERE idlog = " . $this->db->escape($this->idlog) . "";
        //echo $sql;
        if ($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

}
