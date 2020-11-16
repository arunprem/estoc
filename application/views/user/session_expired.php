<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
  Document   : session_expired
  Created on : Nov 5, 2014, 2:00:24 PM
  Author     : Mukesh MR
  Description: Page will be served for ajax requests after session expired

 */
$this->session->sess_destroy();
$this->output->set_header("SESSION:0");
$this->output->set_header('LOGOUT:'.base_url().'user/logout');
?>