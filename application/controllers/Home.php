<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : home
  Created on : Nov 5, 2014, 2:17:09 PM
  Author     : Mukesh MR
  Description:

 */

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // $this->output->enable_profiler(TRUE);
        $this->load->model('menu_model');
        $data['sitename'] = $this->config->item('site_name');
        $data['user'] = $this->session->userdata('user');
        $data['menu'] = $this->menu_model->createUserMenu($data['user']);
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
        $data['date']   = date('d/m/Y', strtotime("-1 days"));
        // $this->load->view('ui/template', $data);
        $this->load->model('dashboard_model', 'DM');
        $this->load->view('ui/header', $data);
        $this->load->view('ui/left', $data);
        $this->load->view('ui/body', $data);
        $this->load->view('ui/footer', $data);
    }

    public function dashboard()
    {
        // Load the library
        $this->load->model('dashboard_model', 'DM');
        $data['user'] = $this->session->userdata('user');
        // $data['raid']   = 0;//$this->DM->getRaids();
        //$data['ps']     = $this->DM->getPsCount();
        // $data['vul']     = $this->DM->getPsCountVul();
        // $data['sens']     = $this->DM->getPsCountSens();
        //$data['lwe']     = $this->DM->getPsCountLwe();
        // $data['inacc']     = $this->DM->getPsCountInacc();
        // $data['nbw']    = 0;//$this->DM->getNbwDetails();
        $data['date']   = date('d/m/Y', strtotime("-1 days"));
        //$data['sec']    = 0;//$this->DM->getSecProDetails();
        //$data['elcase']    =0; //$this->DM->getElecCase();

        $units = $this->dataByUser();
        $data['positive'] = $this->DM->totalPositiveCase($units);
        $data['tracing'] = $this->DM->totalTracingStart($units);
        $data['p'] = $this->DM->totalPrimary($units);
        $data['s'] = $this->DM->totalSecondary($units);
        $data['ap']=$this->DM->totalActivePositive($units);



        $data['t']   = $this->DM->totalCases();

        $this->load->view('ui/dashboard', $data);
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
}
