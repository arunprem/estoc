<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of rank
 *
 * @author Mukesh
 */
class Rank extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('rank_model', 'RM');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }

    public function home() {
        $this->load->view('rank/home');
    }

    public function rank_head_combo() {
        $rankhead = $this->RM->viewUnitHeadList();
        $this->output->set_output(json_encode($rankhead));
    }

    public function rank_combo() {
        $ranks = $this->RM->listAllRank();
        $this->output->set_output(json_encode($ranks));
    }

    public function rank_list() {
        //   $this->output->enable_profiler(TRUE);

        $this->RM->post_search = $this->input->post('search');
        $this->RM->post_order = $this->input->post('order');
        $this->RM->post_length = $this->input->post('length');
        $this->RM->post_start = $this->input->post('start');
        $this->RM->post_draw = $this->input->post('draw');


        $list = $this->RM->get_datatables();
        $this->output->set_output(json_encode($list));
    }

    public function new_rank() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('desc', 'Rank Name', 'trim|required|alpha_space|is_unique[m_rank.rank_desc]');
        $this->form_validation->set_rules('st', 'short tag', 'trim|required|alpha|is_unique[m_rank.rank_short_tag]');

        $this->form_validation->set_rules('status', 'status', 'trim|required|in_list[0,1]');

        if ($this->form_validation->run()) {
            $this->RM->rank_desc = $this->input->post('desc');
            $this->RM->rank_short_tag = $this->input->post('st');

            $this->RM->status = $this->input->post('status');

            if ($this->RM->add()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully added";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function edit_rank() {
        //$this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('desc', 'Rank Name', 'trim|required|alpha_space');
        $this->form_validation->set_rules('st', 'short tag', 'trim|required|alpha');

        $this->form_validation->set_rules('status', 'status', 'trim|required|in_list[0,1]');
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->RM->rank_desc = $this->input->post('desc');
            $this->RM->rank_short_tag = $this->input->post('st');

            $this->RM->status = $this->input->post('status');
            $this->RM->idrank = $this->input->post('id');

            if ($this->RM->update()) {

                $result['success'] = TRUE;
                $result['msg'] = "Successfully updated";
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


            $this->RM->idrank = $this->input->post('id');
            if ($this->RM->deactivate()) {
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


            $this->RM->idrank = $this->input->post('id');
            if ($this->RM->activate()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully activated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }
    
   

}
