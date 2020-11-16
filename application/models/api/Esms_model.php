<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of esms
 *
 * @author Mukesh
 */
class Esms_model extends CI_Model {

    var $username;
    var $password;
    var $message;
    var $number;
    var $numbers = array();
    var $senderid;
    var $url;
    var $tocken;
    var $type = 'registration';

    public function __construct() {
        parent::__construct();
        $this->url = $this->config->item('sms_url');
        $this->username = $this->config->item('sms_username');
        $this->password = $this->config->item('sms_password');
        $this->senderid = $this->config->item('sms_senderid');
    }

    public function sendOTP() {
        $this->message = "Kerala Police Training Portal OTP : " . $this->tocken . " for " . $this->type;
        $surl = $this->url . "username=" . $this->username . "&password=" . $this->password . "&message=" . $this->message . "&numbers=" . $this->number . "&senderid=" . $this->senderid;        
        try {
            $post = curl_init();

            curl_setopt($post, CURLOPT_URL, $surl);
            curl_setopt($post, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($post);
            curl_close($post);
            return TRUE;
        } catch (Exception $ex) {
            return FALSE;
        }
    }

}
