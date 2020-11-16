<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : Permission
  Created on : Nov 5, 2014, 2:17:09 PM
  Author     : Mukesh MR
  Description:

 */

class Unit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('unit_model', 'UM');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function home() {
        $this->load->view('unit/unit_home');
    }

    public function tree() {
        $id = "unit-tree";
        $data['unit_list'] = $this->UM->createList($id);
        $this->load->view('unit/unit_tree_grid', $data);
    }

    public function unit_combo() {
        $this->UM->searchquery = $this->input->post('q');
        $user = $this->session->userdata['user'];
        $this->UM->pid = $user->unit_role;
        $unit_list = $this->UM->viewComboList();
        $this->output->set_output(json_encode($unit_list));
    }

    public function all_unit_combo() {
        $this->UM->searchquery = $this->input->post('q');
        $unit_list = $this->UM->viewAllComboList();
        $this->output->set_output(json_encode($unit_list));
    }

    public function all_unit_combo_by_user() {
        $unit_list = array();
        if ($this->input->post('q')) {
            $this->UM->searchquery = $this->input->post('q');
            $unit_list = $this->UM->allUnitsByUser();
        }
        $this->output->set_output(json_encode($unit_list));
    }

    public function all_unit_combo_by_user_noquery() {
        $unit_list = array();
        $unit_list = $this->UM->allUnitsByUserNoQuery();

        $this->output->set_output(json_encode($unit_list));
    }

    public function unit_list() {
        //////////////////////////////////////////////////////////////////////////
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

    public function deactivate() {
        $result['success'] = FALSE;
        $result['msg'] = "Error deactivating data";
        $this->UM->id = $this->input->post('id');
        if ($this->UM->delete()) {
            $result['success'] = TRUE;
            $result['msg'] = "Successfully deactivated";
        }
        $this->output->set_output(json_encode($result));
    }

    public function activate() {
        $result['success'] = FALSE;
        $result['msg'] = "Error activating data";
        $this->UM->id = $this->input->post('id');
        if ($this->UM->activate()) {
            $result['success'] = TRUE;
            $result['msg'] = "Successfully activated";
        }
        $this->output->set_output(json_encode($result));
    }

    public function include_unit() {
        $this->load->model('UM');
        $result['success'] = FALSE;
        $result['msg'] = "Error updating data";
        $this->UM->id = $this->input->post('id');
        if ($this->UM->activate()) {
            $result['success'] = TRUE;
            $result['msg'] = "Successfully activated";
        }
        $this->output->set_output(json_encode($result));
    }

    public function unit_tree() {

        $result['data'] = $this->UM->createTreeView();
        $this->output->set_output(json_encode($result));
    }

    public function new_unit() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('ncrb_id', 'NCRB Code', 'trim|numeric');
        $this->form_validation->set_rules('unit_name', 'Unit name', 'trim|required');
        $this->form_validation->set_rules('unit_type', 'Unit type', 'required|trim|integer');
        $this->form_validation->set_rules('head_rank', 'Unit head', 'required|trim|integer');
        $this->form_validation->set_rules('parent_id', 'Parent Unit', 'required|trim|integer');
        $this->form_validation->set_rules('is_parent_unit', 'Is Parent Unit', 'required|trim|in_list[0,1]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[0,1]');

        if ($this->form_validation->run()) {

            $this->UM->ncrb_id = $this->input->post('ncrb_id');
            $this->UM->unit_name = $this->input->post('unit_name');
            $this->UM->idunittype = $this->input->post('unit_type');
            $this->UM->head_rank = $this->input->post('head_rank');
            $this->UM->pid = $this->input->post('parent_id');
            $this->UM->is_parent_unit = $this->input->post('is_parent_unit');
            $this->UM->status = $this->input->post('status');

            if ($this->UM->add()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_unit() {
        $result['success'] = FALSE;
        $result['msg'] = "Error updating data";

        $this->form_validation->set_rules('ncrb_id', 'NCRB Code', 'trim|numeric');
        $this->form_validation->set_rules('unit_name', 'Unit name', 'trim|required');
        $this->form_validation->set_rules('unit_type', 'Unit type', 'required|trim|integer');
        $this->form_validation->set_rules('head_rank', 'Unit head', 'required|trim|integer');
        $this->form_validation->set_rules('parent_id', 'Parent Unit', 'required|trim|integer');
        $this->form_validation->set_rules('is_parent_unit', 'Is Parent Unit', 'required|trim|in_list[0,1]');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|in_list[0,1]');
        $this->form_validation->set_rules('id', 'ID', 'required|trim|integer');

        if ($this->form_validation->run()) {

            $this->UM->ncrb_id = $this->input->post('ncrb_id');
            $this->UM->unit_name = $this->input->post('unit_name');
            $this->UM->idunittype = $this->input->post('unit_type');
            $this->UM->head_rank = $this->input->post('head_rank');
            $this->UM->pid = $this->input->post('parent_id');
            $this->UM->is_parent_unit = $this->input->post('is_parent_unit');
            $this->UM->status = $this->input->post('status');
            $this->UM->id = $this->input->post('id');

            if ($this->UM->update()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }


}
