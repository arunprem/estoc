<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Role management
 *
 * @author Mukesh
 */
class Role extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home() {
        $this->load->model('permission_model');
        $data['perm_list'] = $this->permission_model->getPermissionCheckList('role-perm-checkbox');
        $this->load->view('role/home',$data);
    }

    public function role_list_b() {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('role_model', 'RM');
        $this->RM->length = $this->input->post('length');
        $this->RM->value = $this->input->post('search');
        $this->RM->columns = $this->input->post('columns');
        $this->RM->order = $this->input->post('order[0]');
        $this->RM->start = $this->input->post('start');
        $this->RM->draw = $this->input->post('draw');
        $result = $this->RM->getDataTable();
        $this->output->set_output(json_encode($result));
    }

    public function role_list() {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('role_model');
        $this->role_model->post_search = $this->input->post('search');
        $this->role_model->post_order = $this->input->post('order');
        $this->role_model->post_length = $this->input->post('length');
        $this->role_model->post_start = $this->input->post('start');
        $this->role_model->post_draw = $this->input->post('draw');
        
        $list = $this->role_model->get_datatables();        
        $this->output->set_output(json_encode($list));
    }

    public function new_role() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('desc', 'description', 'trim|required');
        $this->form_validation->set_rules('st', 'short tag', 'trim|required|is_unique[role.short_tag]');
        if ($this->form_validation->run()) {
            $this->load->model('role_model');
            $this->role_model->description = $this->input->post('desc');
            $this->role_model->short_tag = $this->input->post('st');
            if ($this->role_model->add()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_role() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('desc', 'description', 'trim|required');
        $this->form_validation->set_rules('st', 'short tag', 'trim|required');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->load->model('role_model');
            $this->role_model->description = $this->input->post('desc');
            $this->role_model->short_tag = $this->input->post('st');
            $this->role_model->idrole = $this->input->post('id');
            if ($this->role_model->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function remove_role() {
        $result['success'] = FALSE;
        $result['msg'] = "Error deleting data";
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        if ($this->form_validation->run()) {
            $this->load->model('role_model');
            $this->role_model->idrole = $this->input->post('id');
            if ($this->role_model->delete()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully deleted";
            } else {
                $result['msg'] = "A database constraint occured";
            }
        } else {
            $result['msg'] = validation_errors();
        }

        $this->output->set_output(json_encode($result));
    }

    public function get_permission_list_role() {
        // Get list of permissions assigned to a particular role by its role id

        $this->load->model('permission_model');
        if ($this->input->get('id') > 0) {
            $this->permission_model->idrole = $this->input->get('id');
            $permissionlist = $this->permission_model->getPermissionByRoleId();
            //$permissionlist = $this->permission_model->getPermissionById($this->input->get('id'));
            $this->output->set_output(json_encode($permissionlist));
        }
    }

    public function save_role_permission() {
        // save permissions for a Role
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error deleting data";
        $this->form_validation->set_rules('role', 'role', 'required|trim|integer');
        if ($this->form_validation->run()) {
            $this->load->model('permission_model');

            if ($this->permission_model->addPermissionToRole($this->input->post('role'), $this->input->post('nodes'))) {
                $result['success'] = TRUE;
                $result['msg'] = "Permission successfully updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function role_list_combo() {
        $this->load->model('role_model');
        $rolelist = $this->role_model->viewArrayList();
        $this->output->set_output(json_encode($rolelist));
    }

    public function psrole_list_combo() {
        $this->load->model('role_model');
        $rolelist = $this->role_model->viewPsArrayList();
        $this->output->set_output(json_encode($rolelist));
    }

}
