<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menutype extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('menutype_model');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function home() {
        $this->load->helper('ckeditor');
        $data['ckeditor'] = array(
            //ID of the textarea that will be replaced
            'id' => 'message',
            'path' => 'public/js/ckeditor',
            //Optionnal values
            'config' => array(
                'toolbar' => "Full", //Using the Full toolbar
                'width' => "550px", //Setting a custom width
                'height' => '100px', //Setting a custom height
                'filebrowserBrowseUrl' => base_url('browser/browse.php'),
                'filebrowserUploadUrl' => base_url('uploader/upload.php'),
                'filebrowserWindowWidth' => '640',
                'filebrowserWindowHeight' => '480'
            )
        );
        $this->load->view('menutype/menutype_home', $data);
    }

    public function menutype_list() {
        $this->has_permission('menu_type_mgt');

        $page = ($this->input->post('page')) ? $this->input->post('page') : 1;
        $rows = ($this->input->post('rows')) ? $this->input->post('rows') : 10;
        $menutypelist = $this->menutype_model->viewPagedList($rows, $page);
        $this->output->set_output(json_encode($menutypelist));
    }

    public function new_menutype() {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('menutype', 'Menu Type', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->menutype_model->menutype = $this->input->post('menutype');
            $this->menutype_model->description = $this->input->post('description');
            $this->menutype_model->status = $this->input->post('status');
            if ($this->menutype_model->add()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_menutype() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $_POST['id'] = $this->input->get('id');
        $this->form_validation->set_rules('menutype', 'Menu Type', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('status', 'status', 'trim|required|integer');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');


        if ($this->form_validation->run()) {

            $this->menutype_model->menutype = $this->input->post('menutype');
            $this->menutype_model->description = $this->input->post('description');
            $this->menutype_model->status = $this->input->post('status');
            $this->menutype_model->id = $this->input->post('id');
            if ($this->menutype_model->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function remove_menutype() {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'id', 'trim|required');

        if ($this->form_validation->run()) {


            $this->menutype_model->idut = $this->input->post('id');
            if ($this->menutype_model->delete()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Removed";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function menutype_combo_list() {
        $typelist = $this->menutype_model->viewArrayList();
        $this->output->set_output(json_encode($typelist));
    }

}

?>
