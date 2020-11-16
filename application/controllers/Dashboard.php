<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Dashboard extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dashboard_model');
    }

    public function listLwarrent()
    {

        //  $this->output->enable_profiler(TRUE);
        $barchartUnitList = $this->dashboard_model->generateLwarrent();
        $this->output->set_output(json_encode($barchartUnitList));
    }

    public function listFine()
    {
        $line = $this->dashboard_model->generateFineDetails();
        $this->output->set_output(json_encode($line));
    }

    public function listallpolingstation()
    {
        $locations = $this->dashboard_model->get_psData();
        $this->output->set_output(json_encode($locations));
    }

    public function psInLwe()
    {
        $locations = $this->dashboard_model->get_psDataLwe();
        $this->output->set_output(json_encode($locations));
    }

    public function psInacc()
    {
        $locations = $this->dashboard_model->get_psDataAcc();
        $this->output->set_output(json_encode($locations));
    }

    public function PsInSensitive()
    {
        $locations = $this->dashboard_model->get_psDataSens();
        $this->output->set_output(json_encode($locations));
    }

    public function PsInCritical()
    {
        $locations = $this->dashboard_model->get_psDataCrit();
        $this->output->set_output(json_encode($locations));
    }

    public function getdataSubmitCount()
    {
        $submittedStatus = $this->dashboard_model->get_SubmitedCount();

        $this->output->set_output($submittedStatus['0']['total_submit']);
    }

    public function getSubmitDistrictList()
    {
        $submittedList = $this->dashboard_model->getDistrictSubmitedStatus();
        $this->output->set_output(json_encode($submittedList));
    }

    //////////////////////////c19-control

    public function listObservation()
    {

        //  $this->output->enable_profiler(TRUE);
        $barchartUnitList = $this->dashboard_model->getObsPositive();
        $this->output->set_output(json_encode($barchartUnitList));
    }

    public function listObsDetails()
    {
        $piechart = $this->dashboard_model->generateObsDetails();
        $this->output->set_output(json_encode($piechart));
    }

    public function lockViolations()
    {
        $barchart = $this->dashboard_model->generateViolationDetails();
        $this->output->set_output(json_encode($barchart));
    }

    public function listFirArrest()
    {
        $line = $this->dashboard_model->generateFirDetails();
        $this->output->set_output(json_encode($line));
    }


    public function getPsWisePositiveandTraced()
    {
        $units = $this->dataByUser();
        $line = $this->dashboard_model->getPsWisePositiveandTraced($units);
        $this->output->set_output(json_encode($line));
    }

    public function getPsWisePositiveAndContactcount()
    {
        $units = $this->dataByUser();
        $line = $this->dashboard_model->getPsWisePostiveandContact($units);
        $this->output->set_output(json_encode($line));
    }

    public function getQurantineCheck()
    {
        $line = $this->dashboard_model->quratineCheck();
        $this->output->set_output(json_encode($line));
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


    public function getJurisdictionChange()
    {
        //$this->output->enable_profiler(TRUE);
        $this->dashboard_model->units = $this->dataByUser();
        $this->dashboard_model->post_search = $this->input->post('search');
        $this->dashboard_model->post_order = $this->input->post('order');
        $this->dashboard_model->post_length = $this->input->post('length');
        $this->dashboard_model->post_start = $this->input->post('start');
        $this->dashboard_model->post_draw = $this->input->post('draw');


        $list = $this->dashboard_model->get_datatables();
        $this->output->set_output(json_encode($list));
    }

    public function qdetails()
    {
        // $this->output->enable_profiler(TRUE);
        $data['qcheck'] = $this->dashboard_model->getQcheckDetails();
        $this->load->view('qcheck/qcheckdetails_veiw', $data);
    }

    public function generateCheckingDetails()
    {


        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');


        $filename = "covid-Qurentine-Cheking-details.csv";

        $result = $this->dashboard_model->generateData();


        $data = $this->dbutil->csv_from_result($result);

        // print_r($data);
        force_download($filename, $data);
    }
}
