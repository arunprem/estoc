<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Menucategory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menucat_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function home() {
        $this->load->view('menucategory/home');
    }

    

    public function menucat_list() {
        //$this->output->enable_profiler(TRUE);
        $page = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows') : 10;
        $this->menucat_model->searchkey = $this->input->post('key');
        $actlist = $this->menucat_model->viewPagedList($rows, $page);

        $this->output->set_output(json_encode($actlist));
    }

    public function newcat() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('menu_cat', 'Menu Category', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');

        if ($this->form_validation->run()) {
            $this->menucat_model->menucat = $this->input->post('menu_cat');
            $this->menucat_model->description = $this->input->post('description');

            if ($this->menucat_model->add()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function editcat() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $_POST['id'] = $this->input->get('id');
        $this->form_validation->set_rules('menu_cat', 'Menu Category', 'trim|required');
        $this->form_validation->set_rules('description', 'description', 'trim|required');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->menucat_model->menucat = $this->input->post('menu_cat');
            $this->menucat_model->description = $this->input->post('description');
            $this->menucat_model->id = $this->input->post('id');

            if ($this->menucat_model->update()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

  

    public function removect() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error removing";
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->menucat_model->id = $this->input->post('id');

            if ($this->menucat_model->deactivate()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully Removed";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

}
