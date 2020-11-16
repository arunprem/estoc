<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : Permission
  Created on : Nov 5, 2014, 2:17:09 PM
  Author     : Mukesh MR
  Description:

 */

class Permission extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home() {
        //Get all permissions in tree format
        $this->load->model('permission_model');
        $id = "perm-tree";
//$this->output->enable_profiler(TRUE);
        $result['perm_list'] = $this->permission_model->createList($id);
        $this->load->view('permission/home', $result);
    }

    public function get_permission_list() {
//Get all permissions in tree format
        $this->load->model('permission_model');
//$this->output->enable_profiler(TRUE);
        $result['perm_list'] = $this->permission_model->listMenuPermission();
        $this->output->set_output(json_encode($result['perm_list']));
    }

    public function edit_permission() {
//edit permission
//$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error updating data";

        $this->form_validation->set_rules('pdesc', 'Permission description', 'trim|required');
        $this->form_validation->set_rules('alias', 'Alias', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        if ($this->form_validation->run()) {
            $this->load->model('permission_model');
            $this->permission_model->perm_desc = $this->input->post('pdesc');
            $this->permission_model->perm_alias = $this->input->post('alias');
            $this->permission_model->perm_id = $this->input->post('id');
            if ($this->permission_model->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function new_permission() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $action = array(
            "create-before", "create-after", "create-child"
        );
                
        $this->form_validation->set_rules('pdesc', 'permission', 'trim|required');
        $this->form_validation->set_rules('alias', 'alias', 'trim|required|is_unique[permission.perm_alias]');
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        $this->form_validation->set_rules('act', 'action', 'required|trim');
        $actioncheck = in_array($this->input->post('act'), $action);
        if ($this->form_validation->run() && $actioncheck) {
            $this->load->model('permission_model');
            $this->permission_model->perm_desc = $this->input->post('pdesc');
            $this->permission_model->perm_alias = $this->input->post('alias');
            switch ($this->input->post('act')) {
                case 'create-before':
                    $do = $this->permission_model->addBefore($this->input->post('id'));
                    break;
                case 'create-after':
                    $do = $this->permission_model->addAfter($this->input->post('id'));
                    break;
                case 'create-child':
                    $this->permission_model->parent_id = $this->input->post('id');
                    $do = $this->permission_model->add();
                    break;
            }
            if ($do) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function remove_permission() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error deleting data";
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        if ($this->form_validation->run()) {
            $this->load->model('permission_model');
            $this->permission_model->setId($this->input->post('id'));
            if ($this->permission_model->delete()) {
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

    public function move_permission() {
        $result['success'] = FALSE;
        $result['msg'] = "Error moving data";
        $action = array(
            'top', 'bottom', 'append'
        );

        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        $this->form_validation->set_rules('targetId', 'target id', 'required|trim|integer');
        $this->form_validation->set_rules('point', 'id', 'required|trim');
        $actioncheck = in_array($this->input->post('point'), $action);
        if ($this->form_validation->run() && $actioncheck) {
            $this->load->model('permission_model');
            $this->permission_model->setId($this->input->post('id'));
            switch ($this->input->post('point')) {
                case 'top':
                    $do = $this->permission_model->moveToLeftOf($this->input->post('targetId'));
                    break;
                case 'bottom':
                    $do = $this->permission_model->moveToRightOf($this->input->post('targetId'));
                    break;
                case 'append':
                    $do = $this->permission_model->moveTo($this->input->post('targetId'));
                    break;
            }


            if ($do) {
                $result['success'] = true;
                $result['msg'] = "Successfully moved";
            } else {
                $result['msg'] = "A Db constraint occured";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

}
