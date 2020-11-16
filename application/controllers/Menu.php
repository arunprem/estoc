<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of menu
 *
 * @author Mukesh
 */
class menu extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('menu_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home() {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('menu_model');
        $id = "menu-tree";
        $result['menu_list'] = $this->menu_model->createList($id);
        $this->load->view('menu/home', $result);
    }

    public function menu_list() {

        //$this->output->enable_profiler(TRUE);
        $result = $this->menu_model->createTree();
        $this->output->set_output(json_encode($result));
    }

    public function frm_new_menu() {
        $this->load->view('menu/frm_new_menu');
    }

    public function frm_edit_menu() {
        if ($this->input->get('id')) {
            $this->menu_model->setId($this->input->get('id'));
            $data['menu'] = json_encode($this->menu_model->getMenuById());
            $this->load->view('menu/frm_edit_menu', $data);
        }
    }

    public function new_menu() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";


        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('alias', 'Alias', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('path', 'Path', 'trim');
        $this->form_validation->set_rules('permission', 'Permission', 'trim|required|integer');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|integer');
        $this->form_validation->set_rules('params', 'Params', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        $this->form_validation->set_rules('act', 'Action', 'required|trim|in_list[create-before,create-after,create-child]');
        //$actioncheck = in_array($this->input->post('act'), $action);


        if ($this->form_validation->run()) {

            $this->menu_model->title = $this->input->post('title');
            $this->menu_model->alias = $this->input->post('alias');
            $this->menu_model->description = $this->input->post('description');
            $this->menu_model->path = $this->input->post('path');
            $this->menu_model->perm_id = $this->input->post('permission');
            $this->menu_model->params = $this->input->post('params');
            $this->menu_model->status = $this->input->post('status');


            switch ($this->input->post('act')) {
                case 'create-before':
                    $do = $this->menu_model->addBefore($this->input->post('id'));
                    break;
                case 'create-after':
                    $do = $this->menu_model->addAfter($this->input->post('id'));
                    break;
                case 'create-child':
                    $this->menu_model->parent_id = $this->input->post('id');
                    $do = $this->menu_model->add();
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

    public function edit_menu() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";


        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('alias', 'Alias', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim');
        $this->form_validation->set_rules('path', 'Path', 'trim');
        $this->form_validation->set_rules('permission', 'Permission', 'trim|required|integer');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|integer');
        $this->form_validation->set_rules('params', 'Params', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        


        if ($this->form_validation->run()) {

            $this->menu_model->title = $this->input->post('title');
            $this->menu_model->alias = $this->input->post('alias');
            $this->menu_model->description = $this->input->post('description');
            $this->menu_model->path = $this->input->post('path');
            $this->menu_model->perm_id = $this->input->post('permission');
            $this->menu_model->params = $this->input->post('params');
            $this->menu_model->status = $this->input->post('status');
            $this->menu_model->id = $this->input->post('id');

            if ($this->menu_model->update()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    function move_menu() {

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
            $this->menu_model->setId($this->input->post('id'));
            switch ($this->input->post('point')) {
                case 'top':
                    $do = $this->menu_model->moveToLeftOf($this->input->post('targetId'));
                    break;
                case 'bottom':
                    $do = $this->menu_model->moveToRightOf($this->input->post('targetId'));
                    break;
                case 'append':
                    $do = $this->menu_model->moveTo($this->input->post('targetId'));
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

    public function remove_menu() {
        // $this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error deleting data";
        $this->form_validation->set_rules('id', 'id', 'required|trim|integer');
        if ($this->form_validation->run()) {
            $this->menu_model->setId($this->input->post('id'));
            if ($this->menu_model->delete()) {
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

}
