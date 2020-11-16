<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * All user related management after login
 *
 * @author admin
 */
class Usermanager extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user/user_model', 'UM');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home()
    {
        $this->load->view('user/home');
    }

    public function ps()
    {
        $this->load->view('user/pshome');
    }
    public function user_list()
    {
        //   $this->output->enable_profiler(TRUE);
        $this->UM->post_search = $this->input->post('search');
        $this->UM->post_order = $this->input->post('order');
        if ($this->input->post('length') && $this->input->post('length') <= 100) {
            $this->UM->post_length = $this->input->post('length');
            $this->UM->post_start = $this->input->post('start');
        } else {
            $this->UM->post_length = 10;
            $this->UM->post_start = 0;
        }


        $this->UM->post_draw = $this->input->post('draw');

        $list = $this->UM->get_datatables();
        $this->output->set_output(json_encode($list));
    }
    public function psuser_list()
    {
        //   $this->output->enable_profiler(TRUE);
        $this->UM->post_search = $this->input->post('search');
        $this->UM->post_order = $this->input->post('order');
        if ($this->input->post('length') && $this->input->post('length') <= 100) {
            $this->UM->post_length = $this->input->post('length');
            $this->UM->post_start = $this->input->post('start');
        } else {
            $this->UM->post_length = 10;
            $this->UM->post_start = 0;
        }


        $this->UM->post_draw = $this->input->post('draw');

        $list = $this->UM->get_datatables_ps();
        $this->output->set_output(json_encode($list));
    }

    public function frm_new_user()
    {
        $this->load->view('user/frm_new_user');
    }

    public function frm_edit_user()
    {
        $this->load->view('user/frm_edit_user');
    }

    public function frm_change_password()
    {
        $this->load->view('user/frm_change_password');
    }

    public function validate_user()
    {
        $this->load->model('user/user_model');
        $result = 'false';
        if ($this->input->post('username') != '') {

            $this->UM->user_name = $this->input->post('username');
            if ($this->UM->checkUsername()) {
                $result = 'true';
            }
            $this->output->set_output($result);
        }
    }

    public function new_user()
    {
        // Add new user
        $this->has_permission('user-mgt');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('uname', 'Username', 'trim|required|alpha_dash|is_unique[user.user_name]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('urole', 'User role', 'trim|required|is_natural');
        $this->form_validation->set_rules('utrole', 'Unit role', 'trim|required|is_natural');
        $this->form_validation->set_rules('pname', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pen', 'Pen No', 'trim|is_natural');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[10]|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');
        if ($this->form_validation->run()) {
            $this->load->model('user/user_model');
            $this->UM->user_name = $this->input->post('uname');
            $this->UM->user_pass = md5($this->input->post('password'));
            $this->UM->user_role = $this->input->post('urole');
            $this->UM->user_status = $this->input->post('status');
            $this->UM->pen = $this->input->post('pen');
            $this->UM->p_name = $this->input->post('pname');
            $this->UM->email = $this->input->post('email');
            $this->UM->mob = $this->input->post('mobile');
            $this->UM->unit_role = $this->input->post('utrole');

            $user = $this->session->userdata['user'];

            $this->UM->created_by = $user->iduser;
            if ($this->UM->add()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully created new user";
            }
        } else {
            $result['msg'] = validation_errors();
            $result['fields'] = $this->form_validation->error_array();
        }
        $this->output->set_output(json_encode($result));
    }

    public function new_psuser()
    {
        // Add new user
        $this->has_permission('ps-user-mgt');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('uname', 'Username', 'trim|required|alpha_dash|is_unique[user.user_name]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('urole', 'User role', 'trim|required|is_natural|in_list[7,8]');
        $this->form_validation->set_rules('utrole', 'Unit role', 'trim|required|is_natural');
        $this->form_validation->set_rules('pname', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pen', 'Pen No', 'trim|is_natural');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[10]|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');
        if ($this->form_validation->run()) {
            $this->load->model('user/user_model');
            $this->UM->user_name = $this->input->post('uname');
            $this->UM->user_pass = md5($this->input->post('password'));
            $this->UM->user_role = $this->input->post('urole');
            $this->UM->user_status = $this->input->post('status');
            $this->UM->pen = $this->input->post('pen');
            $this->UM->p_name = $this->input->post('pname');
            $this->UM->email = $this->input->post('email');
            $this->UM->mob = $this->input->post('mobile');
            $this->UM->unit_role = $this->input->post('utrole');

            $user = $this->session->userdata['user'];

            $this->UM->created_by = $user->iduser;
            if ($this->allowed_unit($this->input->post('utrole'))) {
                if ($this->UM->add()) {
                    $result['success'] = TRUE;
                    $result['msg'] = "Successfully created new user";
                }
            }
        } else {
            $result['msg'] = validation_errors();
            $result['fields'] = $this->form_validation->error_array();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_user()
    {
        // edit user
        //$this->output->enable_profiler(TRUE);
        $this->has_permission('user-mgt');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]');
        $this->form_validation->set_rules('urole', 'User role', 'trim|required|is_natural');
        $this->form_validation->set_rules('utrole', 'Unit role', 'trim|required|is_natural');
        $this->form_validation->set_rules('pname', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pen', 'Pen No', 'trim|is_natural');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[10]|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        if ($this->form_validation->run()) {
            $this->load->model('user/user_model');
            if ($this->input->post('password') != '') {
                $this->UM->user_pass = md5($this->input->post('password'));
            }
            $this->UM->user_role = $this->input->post('urole');
            $this->UM->user_status = $this->input->post('status');
            $this->UM->pen = $this->input->post('pen');
            $this->UM->p_name = $this->input->post('pname');
            $this->UM->email = $this->input->post('email');
            $this->UM->mob = $this->input->post('mobile');
            $this->UM->unit_role = $this->input->post('utrole');
            $this->UM->iduser = $this->input->post('id');

            $user = $this->session->userdata['user'];

            $this->UM->updated_by = $user->iduser;
            if ($this->UM->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully updated user";
            }
        } else {
            $result['msg'] = validation_errors();
            $result['fields'] = $this->form_validation->error_array();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_psuser()
    {
        // edit user
        //$this->output->enable_profiler(TRUE);
        $this->has_permission('ps-user-mgt');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('password', 'Password', 'trim|min_length[8]');
        $this->form_validation->set_rules('urole', 'User role', 'trim|required|is_natural|in_list[7,8]');
        $this->form_validation->set_rules('utrole', 'Unit role', 'trim|required|is_natural');
        $this->form_validation->set_rules('pname', 'Name', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('pen', 'Pen No', 'trim|is_natural');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'trim|exact_length[10]|numeric');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        if ($this->form_validation->run()) {
            $this->load->model('user/user_model');
            if ($this->input->post('password') != '') {
                $this->UM->user_pass = md5($this->input->post('password'));
            }
            $this->UM->user_role = $this->input->post('urole');
            $this->UM->user_status = $this->input->post('status');
            $this->UM->pen = $this->input->post('pen');
            $this->UM->p_name = $this->input->post('pname');
            $this->UM->email = $this->input->post('email');
            $this->UM->mob = $this->input->post('mobile');
            $this->UM->unit_role = $this->input->post('utrole');
            $this->UM->iduser = $this->input->post('id');

            $user = $this->session->userdata['user'];

            $this->UM->updated_by = $user->iduser;
            if ($this->allowed_unit($this->input->post('utrole'))) {
                if ($this->UM->update()) {
                    $result['success'] = TRUE;
                    $result['msg'] = "Successfully updated user";
                }
            }
        } else {
            $result['msg'] = validation_errors();
            $result['fields'] = $this->form_validation->error_array();
        }
        $this->output->set_output(json_encode($result));
    }

    public function change_password()
    {
        // $this->output->enable_profiler(TRUE);

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('opwd', 'Old Password', 'trim|required|callback_check_password');
        $this->form_validation->set_rules('npwd', 'New Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('cpwd', 'Confirm Password', 'trim|required|matches[npwd]');

        if ($this->form_validation->run()) {
            $this->load->model('user/user_model');
            $cuser = $this->session->userdata('user');
            $this->UM->iduser = $cuser->iduser;

            $this->UM->user_pass = $this->input->post('npwd');
            if ($this->UM->changePassword()) {
                $result['success'] = TRUE;
                $result['msg'] = "Password successfully changed";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function check_password($pass)
    {
        $this->load->model('user/user_model');
        $cuser = $this->session->userdata('user');
        $this->UM->iduser = $cuser->iduser;
        $this->UM->user_pass = $pass;
        if ($this->UM->checkPassword()) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_password', 'Incorrect password');
            return FALSE;
        }
    }

    public function deactivate()
    {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->UM->iduser = $this->input->post('id');
            if ($this->UM->deactivate()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully de-activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function activate()
    {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->UM->iduser = $this->input->post('id');
            if ($this->UM->activate()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function enableUser()
    {
        $this->has_permission('user-enable');
        $this->load->view('user/enableuser');
    }

    public function enableuser_list()
    {
        //   $this->output->enable_profiler(TRUE);
        $this->load->model('enableusermodel', 'EUM');
        $this->EUM->post_search = $this->input->post('search');
        $this->EUM->post_order = $this->input->post('order');
        if ($this->input->post('length') && $this->input->post('length') <= 100) {
            $this->EUM->post_length = $this->input->post('length');
            $this->EUM->post_start = $this->input->post('start');
        } else {
            $this->EUM->post_length = 10;
            $this->EUM->post_start = 0;
        }
        $this->EUM->post_draw = $this->input->post('draw');
        $list = $this->EUM->get_datatables();
        $this->output->set_output(json_encode($list));
    }

    public function deactivateEdit()
    {
        $this->load->model('enableusermodel', 'EUM');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->EUM->iduser = $this->input->post('id');
            if ($this->EUM->deactivateEdit()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully de-activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function activateEdit()
    {
        $this->load->model('enableusermodel', 'EUM');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->EUM->iduser = $this->input->post('id');
            if ($this->EUM->activateEdit()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }


    public function dataByUser()
    {
        $this->load->model('unit_model', 'UM');
        $unitList = $this->UM->allUnitsByUser();
        $unitid = array();
        foreach ($unitList as $value) {
            array_push($unitid, $value['id']);
        }
        return $unitid;
    }

    public function allUserByUserUnit(){
        $user = $this->session->userdata['user'];
        $unit_role = $user->unit_role;

        $this->load->model('user/User_model', 'UM');
        $this->UM->unit_role = $unit_role;
        
        $userlist = $this->UM->viewUsersByUnit();
        $this->output->set_output(json_encode($userlist));


    }
}
