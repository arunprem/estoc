<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Master extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function get() {
        $this->load->model('unit_model', 'UM');
        $this->load->model('district_model', 'DM');
        //  $this->load->model('rank_model', 'RM');
        //  $this->load->model('designation_model', 'DM');
       // $result['user_units'] = $this->UM->allUnitsByUserNoQuery();
       // $result['units'] = $this->UM->allUnitsNoQuery();
         //$result['dist'] = $this->DM->listDistricts();
        //  $result['designation'] = $this->DM->allDesignationNoQuery();
       $this->output->set_output(json_encode($result));
    }

    public function sdpo($d = NULL) {
        $result['sdpo'] = '';
        if ($d != NULL) {
            $this->load->model('Unit_model', 'UM');
            $result['sdpo'] = $this->UM->getSd($d);
        }
        $this->output->set_output(json_encode($result));
    }

    public function circle($d = NULL) {
        $result['circle'] = '';
        if ($d != NULL) {
            $this->load->model('Unit_model', 'UM');
            $result['circle'] = $this->UM->getCircle($d);
        }
        $this->output->set_output(json_encode($result));
    }

    public function all_major_agency() {
        $this->load->model('Agencymajor_model');
        $this->Agencymajor_model->agency = $this->input->post('q');
        $ag = $this->Agencymajor_model->ViewByName();
        $this->output->set_output(json_encode($ag));
    }

    public function all_major_course() {
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Coursemajor_model');
        $this->Coursemajor_model->subject = $this->input->post('q');
        $ag = $this->Coursemajor_model->ViewByName();
        $this->output->set_output(json_encode($ag));
    }

    public function getSubjGroup() {
        $this->load->model('group_model', "GM");
        $sg = $this->GM->getGroupByUser();
        $this->output->set_output(json_encode($sg));
    }

    public function psByDistCombo($d=NULL){
        $result=null;
        if ($d != NULL) {
            $this->load->model('Unit_model', 'UM');
            $result = $this->UM->psByDistCombo($d);
        }
        $this->output->set_output(json_encode($result));
    }
    public function wardByLsgCombo($d=NULL){
        $result=null;
        if ($d != NULL) {
            $this->load->model('Master_model', 'MM');
            $result = $this->MM->getWardByLsgCombo($d);
        }
        $this->output->set_output(json_encode($result));
    }

    public function wardByLsg($d=NULL){
        if ($d != NULL) {
            $this->load->model('Master_model', 'MM');
            $result = $this->MM->getWardByLsg($d);
        }
        $this->output->set_output(json_encode($result));
    }
    

}
