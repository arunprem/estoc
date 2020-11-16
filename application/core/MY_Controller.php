<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
  Document   : MY_Controller
  Author     : Mukesh MR
  Description: Extending core controller class for authentication validation.
  This controller should extend for user login checking.
  For non login page use default controller
  MY_controller - For admin classes
  Web_controller - For User classes
 * 
 * 

 */

class MY_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //check ajax or non ajax request
        if ($this->input->is_ajax_request()) {
            $this->checkAjaxLogin();
        } else {
            $this->checkLogin();
        }
    }

    private function checkAjaxLogin()
    {
        $usr = $this->session->userdata('user');
        if (!($usr->user_role >= 2)) {
            $this->session->sess_destroy();
            $this->output->set_header("SESSION:0");
            $this->output->set_header('LOGOUT:' . base_url() . 'user/logout');
            redirect('user/session_expired');
        }
    }

    private function checkLogin()
    {
        $usr = $this->session->userdata('user');
        if (!($usr->user_role >= 2)) {
            $this->session->sess_destroy();
            redirect('user/login');
        }
    }

    public function has_permission($permission)
    {
        $user = $this->session->userdata('user');
        if (strpos($user->permission_alias, $permission) == false) {
            redirect('cerror/show_401');
        }
    }

    public function allowed_unit($unit)
    {
        $this->load->model('unit_model');
        $this->unit_model->id = $unit;
        return $this->unit_model->isAllowedUnit();
    }

    public function allowed_employee($emp)
    {

        $this->load->model('employee_model');
        $this->employee_model->id = $emp;
        return $this->employee_model->isAllowdUser();
    }
}

class Web_controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //check ajax or non ajax request
        if ($this->input->is_ajax_request()) {
            $this->checkAjaxLogin();
        } else {
            $this->checkLogin();
        }
    }

    private function checkLogin()
    {
        $usr = $this->session->userdata('user');
        if (!($usr->user_role == 1)) {
            $this->session->sess_destroy();
            redirect('user/login');
        }
    }

    private function checkAjaxLogin()
    {
        $usr = $this->session->userdata('user');
        if (!($usr->user_role == 1)) {
            $this->session->sess_destroy();
            $this->output->set_header("SESSION:0");
            $this->output->set_header('LOGOUT:' . base_url() . 'user/logout');
            redirect('user/session_expired');
        }
    }
}
