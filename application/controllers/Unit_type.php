<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Unit_type extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('unittype_model');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home() {
        $this->load->view('unit_type/home');
    }

    public function ut_list() {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('unittype_model','UT');
        $this->UT->post_search = $this->input->post('search');
        $this->UT->post_order = $this->input->post('order');
        $this->UT->post_length = $this->input->post('length');
        $this->UT->post_start = $this->input->post('start');
        $this->UT->post_draw = $this->input->post('draw');
        
        $list = $this->UT->get_datatables();        
        $this->output->set_output(json_encode($list));
    }

    public function new_ut() {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        if ($this->form_validation->run()) {
            $this->unittype_model->unit_type_desc = $this->input->post('desc');
            $this->unittype_model->status = $this->input->post('status');
            if ($this->unittype_model->add()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_ut() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('desc', 'Description', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('id', 'Id', 'trim|required|integer');

        if ($this->form_validation->run()) {

            $this->unittype_model->unit_type_desc = $this->input->post('desc');
            $this->unittype_model->status = $this->input->post('status');
            $this->unittype_model->idunittype = $this->input->post('id');
            if ($this->unittype_model->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function deactivate() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->unittype_model->idunittype = $this->input->post('id');
            if ($this->unittype_model->deactivate()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully de-activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }
    
    public function activate() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required|integer');

        if ($this->form_validation->run()) {


            $this->unittype_model->idunittype = $this->input->post('id');
            if ($this->unittype_model->activate()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function combo_list() {
        $typelist = $this->unittype_model->viewArrayList();
        $this->output->set_output(json_encode($typelist));
    }

}

?>
