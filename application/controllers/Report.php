<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Report_model', 'RM');
    }

    public function course()
    {
        $this->load->view('report/course');
    }

    public function loadreport()
    {

        $this->load->view('report/home');
    }

    public function statics()
    {
        $this->load->view('report/staticshome');
    }

    public function getList($id)
    {
        if ($id) {
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $id;
            $data['d'] = $this->CM->getDistList();
        }
        $this->load->view('reports/distList', $data);
    }

    public function getListDr($id)
    {
        if ($id) {
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $id;
            $data['d'] = $this->CM->getDistListDr();
        }
        $this->load->view('reports/distList', $data);
    }

    public function getListHday($id)
    {
        if ($id) {
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $id;
            $data['d'] = $this->CM->getDistListHday();
        }
        $this->load->view('reports/distList', $data);
    }

    public function stDailyA()
    {
        $this->has_permission('cvd_st_daily_stat');
        $this->load->model('Calendar_model', 'CM');
        $r = array();
        $r['report_title'] = "TABLE A : State Daily Statistics";
        $r['elec_name'] = $this->config->item('elec_name');
        $r['url'] = base_url() . "report/state1_XLS";
        $r['url_b'] = base_url() . "report/stateb_XLS";
        $r['url_c'] = base_url() . "report/statec_XLS";
        $data['r'] = $r;
        $data['d'] = json_encode($this->CM->getDRList());
        $this->load->view('reports/sReports', $data);
    }

    public function stDailyB()
    {
        $this->has_permission('cvd_st_daily_stat');
        $this->load->model('Calendar_model', 'CM');
        $r = array();
        $r['report_title'] = "TABLE B : MIGRANT LABOURERS";
        $r['url'] = base_url() . "report/state2_XLS";
        $data['r'] = $r;
        $data['d'] = json_encode($this->CM->getHdayList());
        $this->load->view('reports/hdayReports', $data);
    }

    public function stMhaRep1()
    {
        $this->has_permission('cvd_mha_report_1');
        $this->load->model('Calendar_model', 'CM');
        $r = array();
        $r['report_title'] = "TABLE 1 : LAW & ORDER";
        $r['url'] = base_url() . "report/mha1_XLS";
        $data['r'] = $r;
        $data['d'] = json_encode($this->CM->getMhaList());
        $this->load->view('reports/mhaReports', $data);
    }

    public function stMhaRep2()
    {
        $this->has_permission('cvd_mha_report_2');
        $this->load->model('Calendar_model', 'CM');
        $r = array();
        $r['report_title'] = "TABLE – 2 : SUPPLY OF ESSENTIAL GOODS";
        $r['url'] = base_url() . "report/mha2_XLS";
        $data['r'] = $r;
        $data['d'] = json_encode($this->CM->getMhaList());
        $this->load->view('reports/mhaReports', $data);
    }

    public function stMhaRep3()
    {
        $this->has_permission('cvd_mha_report_3');
        $this->load->model('Calendar_model', 'CM');
        $r = array();
        $r['report_title'] = "TABLE-3 : ESSENTIAL SERVICES, MANUFACTURING, DISTRIBUTION AND E-COMMERCE";
        $r['url'] = base_url() . "report/mha3_XLS";
        $data['r'] = $r;
        $data['d'] = json_encode($this->CM->getMhaList());
        $this->load->view('reports/mhaReports', $data);
    }

    public function getToDates($d)
    {
        $this->load->model('Calendar_model', 'CM');
        $this->CM->dates = $d;
        $this->output->set_output(json_encode($this->CM->getEndDates()));
    }

    public function stDailyAXLS()
    {
        $this->has_permission('cvd_st_daily_stat');
        $this->load->model('Calendar_model', 'CM');
        if ((int) $this->input->post('id') > 0) {
            $this->CM->id = $this->input->post('id');
            if ($dt = $this->CM->getDateById()) {
                $this->load->model('DailyObsreport_model', 'DM');
                $this->DM->rdate = $dt->dates;
                $dor = ordinalSuffix($dt->dor);
                $dr = $this->DM->stDailyA();
                $t = $this->DM->stDailyATotal();

                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-A";
                $rep_date = date("d-m-Y", strtotime($dt->dates));
                $title = "COVID - 19 DAILY REPORT ON $rep_date";

                $date = date_create($dt->dates);


                $rt_dt = date_format($date, "d");
                $rt_mnt = date_format($date, "m");
                $rt_yr = date_format($date, "Y");
                $file_name = "TABLE-A-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


                $spreadsheet = new Spreadsheet();
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $file_title)
                    ->setCellValue('A2', $title)
                    ->setCellValue('A4', 'Sl No')
                    ->setCellValue('B4', 'District')
                    ->setCellValue('C4', 'Number of Positive Cases')
                    ->setCellValue('F4', 'No of Persons Under Hospital Observation')
                    ->setCellValue('I4', 'No of Persons under Home observation')
                    ->setCellValue('L4', 'No of foreigners under Home Observation ')
                    ->setCellValue('O4', 'No of foreigners under Hospital Observation ')
                    ->setCellValue('R4', 'Total No Of Persons under Observation till Date')
                    ->setCellValue('S4', 'Action taken on Violations of Prohibitory Order if any Details')
                    ->setCellValue('C5', 'Previous')
                    ->setCellValue('D5', 'Today')
                    ->setCellValue('E5', 'Total')
                    ->setCellValue('F5', 'Previous')
                    ->setCellValue('G5', 'Today')
                    ->setCellValue('H5', 'Total')
                    ->setCellValue('I5', 'Previous')
                    ->setCellValue('J5', 'Today')
                    ->setCellValue('K5', 'Total')
                    ->setCellValue('L5', 'Previous')
                    ->setCellValue('M5', 'Today')
                    ->setCellValue('N5', 'Total')
                    ->setCellValue('O5', 'Previous')
                    ->setCellValue('P5', 'Today')
                    ->setCellValue('Q5', 'Total');

                /*
                  $letters = range('A', 'W');
                  $i = 1;
                  foreach ($letters as $l) {
                  $n = 9;
                  $spreadsheet->setActiveSheetIndex(0)->setCellValue("$l$n", $i);
                  $i++;
                  }
                 * 
                 */


                $spreadsheet->getActiveSheet()
                    ->mergeCells('A1:S1')
                    ->mergeCells('A2:S3')
                    ->mergeCells('A4:A5')
                    ->mergeCells('B4:B5')
                    ->mergeCells('C4:E4')
                    ->mergeCells('F4:H4')
                    ->mergeCells('I4:K4')
                    ->mergeCells('L4:N4')
                    ->mergeCells('O4:Q4')
                    ->mergeCells('R4:R5')
                    ->mergeCells('S4:S5');

                /*

                  $spreadsheet->getActiveSheet()->getStyle('A11:W11')
                  ->getAlignment()->setTextRotation(90);

                  $spreadsheet->getActiveSheet()->getStyle('A10:C10')
                  ->getAlignment()->setTextRotation(90);
                  $spreadsheet->getActiveSheet()->getStyle('H10')
                  ->getAlignment()->setTextRotation(90);
                  $spreadsheet->getActiveSheet()->getStyle('M10:P10')
                  ->getAlignment()->setTextRotation(90);
                  $spreadsheet->getActiveSheet()->getStyle('S10')
                  ->getAlignment()->setTextRotation(90);
                 * 
                 */
                $spreadsheet->getActiveSheet()->getStyle('A1:S5')->getAlignment()->setWrapText(true);
                $spreadsheet->getActiveSheet()->getStyle('B6:B24')->getAlignment()->setWrapText(true);
                //                $spreadsheet->getActiveSheet()->getStyle('A25:S25')->getAlignment()->setWrapText(true);


                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000'],
                        ],
                    ],
                ];

                $border = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000'],
                        ],
                    ],
                ];

                $headerStyle1 = [
                    'font' => [
                        'bold' => true,
                        'size' => '18',
                        'color' => ['argb' => '001323'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $data = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $headerStyle2 = [
                    'font' => [
                        'bold' => true,
                        'size' => '12',
                        'color' => ['argb' => '00BFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];

                $tableHeader = [
                    'font' => [
                        'bold' => true,
                        'size' => '12',
                        'color' => ['argb' => 'FF0000'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];


                $totalstyle = [
                    'font' => [
                        'bold' => true,
                        'size' => '12'
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => [
                            'argb' => 'DEDEDE',
                        ]
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000'],
                        ],
                    ],
                ];
                $spreadsheet->getActiveSheet()->getStyle('A1:S1')->applyFromArray($headerStyle1);
                $spreadsheet->getActiveSheet()->getStyle('A2:S3')->applyFromArray($headerStyle2);
                $spreadsheet->getActiveSheet()->getStyle('A4:S5')->applyFromArray($tableHeader);
                //$spreadsheet->getActiveSheet()->getStyle('A9:W11')->applyFromArray($totalstyle);


                $styleArray1 = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000'],
                        ],
                    ],
                ];

                //$spreadsheet->getActiveSheet()->getStyle('G5:Q6')->applyFromArray($styleArray1);

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////

                if ($dr) {
                    $sl = 1;
                    $n = 6;

                    foreach ($dr as $d) {
                        // var_dump($d);
                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->unit_short_code)
                            ->setCellValue("C$n", $d->pnpc)
                            ->setCellValue("D$n", $d->npc)
                            ->setCellValue("E$n", $d->tot_npc)
                            ->setCellValue("F$n", $d->pnphpo)
                            ->setCellValue("G$n", $d->nphpo)
                            ->setCellValue("H$n", $d->tot_nphpo)
                            ->setCellValue("I$n", $d->pnphmo)
                            ->setCellValue("J$n", $d->nphmo)
                            ->setCellValue("K$n", $d->tot_nphmo)
                            ->setCellValue("L$n", $d->pnfhmo)
                            ->setCellValue("M$n", $d->nfhmo)
                            ->setCellValue("N$n", $d->tot_nfhmo)
                            ->setCellValue("O$n", $d->pnfhpo)
                            ->setCellValue("P$n", $d->nfhpo)
                            ->setCellValue("Q$n", $d->tot_nfhpo)
                            ->setCellValue("R$n", $d->tot_t_o)
                            ->setCellValue("S$n", $d->action_taken);
                        $sl++;
                        $n++;
                    }

                    $e = $n - 1;

                    /////////Grand Total/////////////////////


                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$n", "Total")
                        ->setCellValue("C$n", $t->tot_pnpc)
                        ->setCellValue("D$n", $t->tot_npc)
                        ->setCellValue("E$n", $t->tot_tot_npc)
                        ->setCellValue("F$n", $t->tot_pnphpo)
                        ->setCellValue("G$n", $t->tot_nphpo)
                        ->setCellValue("H$n", $t->tot_tot_nphpo)
                        ->setCellValue("I$n", $t->tot_pnphmo)
                        ->setCellValue("J$n", $t->tot_nphmo)
                        ->setCellValue("K$n", $t->tot_tot_nphmo)
                        ->setCellValue("L$n", $t->tot_pnfhmo)
                        ->setCellValue("M$n", $t->tot_nfhmo)
                        ->setCellValue("N$n", $t->tot_tot_nfhmo)
                        ->setCellValue("O$n", $t->tot_pnfhpo)
                        ->setCellValue("P$n", $t->tot_nfhpo)
                        ->setCellValue("Q$n", $t->tot_tot_nfhpo)
                        ->setCellValue("R$n", $t->tot_tot_t_o);
                    //////////////////////////////////////////

                    $spreadsheet->getActiveSheet()->mergeCells("A$n:B$n");
                    //$spreadsheet->getActiveSheet()->getRowDimension('10')->setRowHeight(-1);
                    // $spreadsheet->getActiveSheet()->getStyle("A$n:S$n")->applyFromArray($styleArray1);
                    // $spreadsheet->getActiveSheet()->getStyle("A12:W$e")->applyFromArray($styleArray);
                    $spreadsheet->getActiveSheet()->getStyle("A$n:S$n")->applyFromArray($totalstyle);
                    $spreadsheet->getActiveSheet()->getStyle("A1:S$n")->applyFromArray($data);
                }

                $spreadsheet->getActiveSheet()->getStyle('A4:S24')->getAlignment()->setWrapText(true);
                $spreadsheet->getActiveSheet()->getStyle('A1:S24')->applyFromArray($border);
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function distInitXls($d)
    {
        $this->has_permission('daily_report');
        if ((int) $d > 0) {
            $this->load->model('Initdata_model', 'DR');
            $this->IM->dist = $d;
            if ($dt = $this->IM->getInitData()) {
                /////////////////////////////spreadsheet//////////////
                $file_title = "INITDATA";
                $title = "Initial Data";
                $election_name = $this->config->item('elec_name');
                $file_name = "Initial_Data" . $dt->unit_name . "-" . date('d_m_Y_H_i_s');
                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/INITDIST.xlsx';
                $spreadsheet = $reader->load($template);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('G8', $dt->iarms_seized)
                    ->setCellValue('G9', $dt->expo_catridge)
                    ->setCellValue('G10', $dt->expo_explosives)
                    ->setCellValue('G11', $dt->expo_bombs)
                    ->setCellValue('G12', $dt->iarms_raids)
                    ->setCellValue('G13', $dt->arms_licence_new)
                    ->setCellValue('G14', $dt->arms_deposited)
                    ->setCellValue('G15', $dt->arms_impounded)
                    ->setCellValue('G16', $dt->arms_cancelled)
                    ->setCellValue('G17', $dt->sec_proc_initiated)
                    ->setCellValue('G18', $dt->sec_proc_bound_over)
                    ->setCellValue('G19', $dt->sec_proc_bound_down)
                    ->setCellValue('G20', $dt->nbw_total_exc_all_today)
                    ->setCellValue('G21', $dt->nbw_total_pending_all_cum)
                    ->setCellValue('G22', $dt->ele_vlc_incidents)
                    ->setCellValue('G23', $dt->ele_vlc_killed)
                    ->setCellValue('G24', $dt->ele_vlc_injured)
                    ->setCellValue('G25', $dt->ele_vlc_damage)
                    ->setCellValue('G26', $dt->ele_vlc_arrest)
                    ->setCellValue('G27', $dt->sc_st_act_incidents)
                    ->setCellValue('G28', $dt->ham_vulnerable)
                    ->setCellValue('G29', $dt->ham_persons)
                    ->setCellValue('G30', $dt->ham_prev_action)
                    ->setCellValue('G31', $dt->ham_remarks)
                    ->setCellValue('G32', $dt->nakas_operational)
                    ->setCellValue('G33', $dt->persons_charged_acquited)
                    ->setCellValue('G34', $dt->persons_bound_down);


                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function mha1_XLS()
    {
        $this->has_permission('cvd_mha_report_1');
        if ((int) $this->input->post('id') > 0) {
            $this->load->model('MhaReport_model', 'DR');
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $this->input->post('id');
            $cal = $this->CM->getVal();
            $this->DR->calander_id = (int) $this->input->post('id');
            $dt = $this->DR->getMhaReport1();
            /////////////////////////////spreadsheet//////////////
            $file_title = "TABLE-1";
            $rep_date = date("d-m-Y", strtotime($cal->dates));
            $title = "DATE $rep_date  TIME $cal->hours_desc";

            $date = date_create($cal->dates);


            $rt_dt = date_format($date, "d");
            $rt_mnt = date_format($date, "m");
            $rt_yr = date_format($date, "Y");
            $file_name = "TABLE-1-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


            $spreadsheet = new Spreadsheet();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $template = APPPATH . 'views/mha/templates/TABLE1.xlsx';
            $spreadsheet = $reader->load($template);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A2", $title);

            if ($dt) {

                $sl = 1;
                $n = 8;

                foreach ($dt as $d) {
                    // var_dump($d);
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$n", $sl)
                        ->setCellValue("B$n", $d->unit_short_code)
                        ->setCellValue("C$n", $d->lo_violation)
                        ->setCellValue("D$n", $d->lo_arrest)
                        ->setCellValue("E$n", $d->lo_vehicle_seized)
                        ->setCellValue("F$n", $d->lo_fir)
                        ->setCellValue("G$n", $d->lo_fine)
                        ->setCellValue("H$n", $d->lo_tot_incidents)
                        ->setCellValue("I$n", $d->lo_incident_actiontaken)
                        ->setCellValue("J$n", $d->lo_epidemic)
                        ->setCellValue("K$n", $d->lo_remarks);
                    $sl++;
                    $n++;
                }
            }

            $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
            /////////////////////Data Writing//////////////////////////
            //make object of the Xlsx class to save the excel file
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output'); // download file
            //////////////////////////////////////////////////////
        }
    }

    public function mha2_XLS()
    {
        $this->has_permission('cvd_mha_report_2');
        if ((int) $this->input->post('id') > 0) {
            $this->load->model('MhaReport_model', 'DR');
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $this->input->post('id');
            $cal = $this->CM->getVal();
            $this->DR->calander_id = (int) $this->input->post('id');
            $dt = $this->DR->getMhaReport2();
            /////////////////////////////spreadsheet//////////////
            $file_title = "TABLE-2";
            $rep_date = date("d-m-Y", strtotime($cal->dates));
            $title = "DATE $rep_date  TIME $cal->hours_desc";

            $date = date_create($cal->dates);


            $rt_dt = date_format($date, "d");
            $rt_mnt = date_format($date, "m");
            $rt_yr = date_format($date, "Y");
            $file_name = "TABLE-2-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


            $spreadsheet = new Spreadsheet();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $template = APPPATH . 'views/mha/templates/TABLE2.xlsx';
            $spreadsheet = $reader->load($template);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A2", $title);

            if ($dt) {

                $sl = 1;
                $n = 5;

                foreach ($dt as $d) {
                    // var_dump($d);
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$n", $sl)
                        ->setCellValue("C$n", $d->no_of_dist_croom_essen_suppl)
                        ->setCellValue("D$n", $d->unit_short_code)
                        ->setCellValue("E$n", $d->groceries_open_desc)
                        ->setCellValue("F$n", '')
                        ->setCellValue("G$n", $d->milk_open_desc)
                        ->setCellValue("H$n", '')
                        ->setCellValue("I$n", $d->medicines_open_desc)
                        ->setCellValue("J$n", '')
                        ->setCellValue("K$n", $d->shortage_reported_desc)
                        ->setCellValue("L$n", $d->shortage_remedial_action);
                    $sl++;
                    $n++;
                }
            }

            $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
            /////////////////////Data Writing//////////////////////////
            //make object of the Xlsx class to save the excel file
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output'); // download file
            //////////////////////////////////////////////////////
        }
    }

    public function mha3_XLS()
    {
        $this->has_permission('cvd_mha_report_3');
        if ((int) $this->input->post('id') > 0) {
            $this->load->model('MhaReport_model', 'DR');
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $this->input->post('id');
            $cal = $this->CM->getVal();
            $this->DR->calander_id = (int) $this->input->post('id');
            $dt = $this->DR->getMhaReport3();
            /////////////////////////////spreadsheet//////////////
            $file_title = "TABLE-3";
            $rep_date = date("d-m-Y", strtotime($cal->dates));
            $title = "DATE $rep_date  TIME $cal->hours_desc";

            $date = date_create($cal->dates);


            $rt_dt = date_format($date, "d");
            $rt_mnt = date_format($date, "m");
            $rt_yr = date_format($date, "Y");
            $file_name = "TABLE-3-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


            $spreadsheet = new Spreadsheet();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $template = APPPATH . 'views/mha/templates/TABLE3.xlsx';
            $spreadsheet = $reader->load($template);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A2", $title);

            if ($dt) {

                $sl = 1;
                $n = 5;

                foreach ($dt as $d) {
                    // var_dump($d);
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$n", $sl)
                        ->setCellValue("B$n", $d->unit_short_code)
                        ->setCellValue("C$n", $d->restrict_essential_service_desc)
                        ->setCellValue("D$n", $d->restrict_movement_personal_desc)
                        ->setCellValue("E$n", $d->restrict_commercial_vehicle_desc)
                        ->setCellValue("F$n", $d->restrict_ecommerce_desc)
                        ->setCellValue("G$n", $d->food_migrant_labour_desc)
                        ->setCellValue("H$n", $d->migrant_food_details)
                        ->setCellValue("I$n", $d->essential_service_remarks);
                    $sl++;
                    $n++;
                }
            }

            $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
            /////////////////////Data Writing//////////////////////////
            //make object of the Xlsx class to save the excel file
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output'); // download file
            //////////////////////////////////////////////////////
        }
    }

    public function state1_XLS()
    {
        $this->has_permission('cvd_st_daily_stat');
        $this->load->model('Calendar_model', 'CM');
        if ((int) $this->input->post('id') > 0) {
            $this->CM->id = $this->input->post('id');
            if ($dt = $this->CM->getDateById()) {
                $this->load->model('DailyObsreport_model', 'DM');
                $this->DM->rdate = $dt->dates;
                $dr = $this->DM->stDailyA();

                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-A";
                $rep_date = date("d-m-Y", strtotime($dt->dates));
                $title = "COVID - 19 DAILY REPORT ON $rep_date";

                $date = date_create($dt->dates);


                $rt_dt = date_format($date, "d");
                $rt_mnt = date_format($date, "m");
                $rt_yr = date_format($date, "Y");
                $file_name = "TABLE-A-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');

                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/STATE1.xlsx';
                $spreadsheet = $reader->load($template);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A2", $title);

                if ($dr) {

                    $sl = 1;
                    $n = 6;

                    foreach ($dr as $d) {
                        // var_dump($d);
                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->unit_short_code)
                            ->setCellValue("C$n", $d->pnpc)
                            ->setCellValue("D$n", $d->npc)
                            ->setCellValue("E$n", $d->tot_npc)
                            ->setCellValue("F$n", $d->pcured)
                            ->setCellValue("G$n", $d->cured)
                            ->setCellValue("H$n", $d->tot_cured)
                            ->setCellValue("I$n", $d->pdeceased)
                            ->setCellValue("J$n", $d->deceased)
                            ->setCellValue("K$n", $d->tot_deceased)
                            ->setCellValue("L$n", $d->pnphpo)
                            ->setCellValue("M$n", $d->nphpo)
                            ->setCellValue("N$n", $d->tot_nphpo)
                            ->setCellValue("O$n", $d->pnphmo)
                            ->setCellValue("P$n", $d->nphmo)
                            ->setCellValue("Q$n", $d->tot_nphmo)
                            ->setCellValue("R$n", $d->pnfhmo)
                            ->setCellValue("S$n", $d->nfhmo)
                            ->setCellValue("T$n", $d->tot_nfhmo)
                            ->setCellValue("U$n", $d->pnfhpo)
                            ->setCellValue("V$n", $d->nfhpo)
                            ->setCellValue("W$n", $d->tot_nfhpo)
                            ->setCellValue("X$n", $d->tot_t_o)
                            ->setCellValue("Y$n", $d->action_taken);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function distDR($distid)
    {
        $this->has_permission('daily_report');
        if ((int) $distid > 0) {
            $this->load->model('DailyObsreport_model', 'DM');
            $this->DM->distid = (int) $distid;
            if ($dr = $this->DM->getDRByDist()) {
                //var_dump($dr);
                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-A";


                $file_name = "TABLE-A-" . date('d_m_Y_H_i_s');

                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/DIST1.xlsx';
                $spreadsheet = $reader->load($template);

                if ($dr) {

                    $sl = 1;
                    $n = 6;

                    foreach ($dr as $d) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->unit_short_code)
                            ->setCellValue("C$n", $d->rdate)
                            ->setCellValue("D$n", $d->npc)
                            ->setCellValue("E$n", $d->cured)
                            ->setCellValue("F$n", $d->deceased)
                            ->setCellValue("G$n", $d->nphpo)
                            ->setCellValue("H$n", $d->nphmo)
                            ->setCellValue("I$n", $d->nfhmo)
                            ->setCellValue("J$n", $d->nfhpo)
                            ->setCellValue("K$n", $d->t_o)
                            ->setCellValue("L$n", $d->action_taken);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function distMha1($distid)
    {
        $this->has_permission('daily_report');
        if ((int) $distid > 0) {
            $this->load->model('DailyObsreport_model', 'DM');
            $this->DM->distid = (int) $distid;
            if ($dr = $this->DM->getMhaByDist()) {
                //var_dump($dr);
                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-1";


                $file_name = "TABLE-1-" . date('d_m_Y_H_i_s');

                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/DISTMHA1.xlsx';
                $spreadsheet = $reader->load($template);

                if ($dr) {

                    $sl = 1;
                    $n = 8;

                    foreach ($dr as $d) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->unit_short_code)
                            ->setCellValue("C$n", $d->rdate)
                            ->setCellValue("D$n", $d->hours_desc)
                            ->setCellValue("E$n", $d->lo_violation)
                            ->setCellValue("F$n", $d->lo_arrest)
                            ->setCellValue("G$n", $d->lo_vehicle_seized)
                            ->setCellValue("H$n", $d->lo_fir)
                            ->setCellValue("I$n", $d->lo_fine)
                            ->setCellValue("J$n", $d->lo_tot_incidents)
                            ->setCellValue("K$n", $d->lo_incident_actiontaken)
                            ->setCellValue("L$n", $d->lo_epidemic)
                            ->setCellValue("M$n", $d->lo_remarks);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function distMha2($distid)
    {
        $this->has_permission('daily_report');
        if ((int) $distid > 0) {
            $this->load->model('DailyObsreport_model', 'DM');
            $this->DM->distid = (int) $distid;
            if ($dr = $this->DM->getMhaByDist()) {
                //var_dump($dr);
                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-2";


                $file_name = "TABLE-2-" . date('d_m_Y_H_i_s');

                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/DISTMHA2.xlsx';
                $spreadsheet = $reader->load($template);

                if ($dr) {

                    $sl = 1;
                    $n = 4;

                    foreach ($dr as $d) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->rdate)
                            ->setCellValue("C$n", $d->hours_desc)
                            ->setCellValue("D$n", $d->no_of_dist_croom_essen_suppl)
                            ->setCellValue("E$n", $d->unit_short_code)
                            ->setCellValue("F$n", $d->groceries_open)
                            ->setCellValue("G$n", '')
                            ->setCellValue("H$n", $d->milk_open)
                            ->setCellValue("I$n", '')
                            ->setCellValue("J$n", $d->medicines_open)
                            ->setCellValue("K$n", '')
                            ->setCellValue("L$n", $d->shortage_reported)
                            ->setCellValue("M$n", $d->shortage_remedial_action);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function distMha3($distid)
    {
        $this->has_permission('daily_report');
        if ((int) $distid > 0) {
            $this->load->model('DailyObsreport_model', 'DM');
            $this->DM->distid = (int) $distid;
            if ($dr = $this->DM->getMhaByDist()) {
                //var_dump($dr);
                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-3";


                $file_name = "TABLE-3-" . date('d_m_Y_H_i_s');

                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/DISTMHA3.xlsx';
                $spreadsheet = $reader->load($template);

                if ($dr) {

                    $sl = 1;
                    $n = 4;

                    foreach ($dr as $d) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->rdate)
                            ->setCellValue("C$n", $d->hours_desc)
                            ->setCellValue("D$n", $d->unit_short_code)
                            ->setCellValue("E$n", $d->restrict_essential_service)
                            ->setCellValue("F$n", $d->restrict_movement_personal)
                            ->setCellValue("G$n", $d->restrict_commercial_vehicle)
                            ->setCellValue("H$n", $d->restrict_ecommerce)
                            ->setCellValue("I$n", $d->food_migrant_labour)
                            ->setCellValue("J$n", $d->migrant_food_details)
                            ->setCellValue("K$n", $d->essential_service_remarks);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                /////////////////////////////////////////////////////
            }
        }
    }

    public function distHday($distid)
    {
        $this->has_permission('daily_report');
        if ((int) $distid > 0) {
            $this->load->model('HdayReport_model', 'DM');
            $this->DM->distid = (int) $distid;
            if ($dr = $this->DM->getHdayReportByDist()) {
                //var_dump($dr);
                /////////////////////////////spreadsheet//////////////
                // Start spread sheet            
                //add some data in excel cells

                $file_title = "TABLE-B";


                $file_name = "TABLE-B-" . date('d_m_Y_H_i_s');
                $spreadsheet = new Spreadsheet();
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $template = APPPATH . 'views/dr/templates/DIST2.xlsx';
                $spreadsheet = $reader->load($template);

                if ($dr) {

                    $sl = 1;
                    $n = 4;

                    foreach ($dr as $d) {

                        $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue("A$n", $sl)
                            ->setCellValue("B$n", $d->unit_short_code)
                            ->setCellValue("C$n", $d->rdate)
                            ->setCellValue("D$n", $d->hours_desc)
                            ->setCellValue("E$n", $d->no_of_persons)
                            ->setCellValue("F$n", $d->no_of_camps)
                            ->setCellValue("G$n", $d->problem_faced)
                            ->setCellValue("H$n", $d->action_taken)
                            ->setCellValue("I$n", $d->remarks);
                        $sl++;
                        $n++;
                    }
                }

                $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
                /////////////////////Data Writing//////////////////////////
                //make object of the Xlsx class to save the excel file
                $writer = new Xlsx($spreadsheet);
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
                header('Cache-Control: max-age=0');
                $writer->save('php://output'); // download file
                //////////////////////////////////////////////////////
            }
        }
    }

    public function state2_XLS()
    {
        $this->has_permission('cvd_st_daily_stat');
        if ((int) $this->input->post('id') > 0) {
            $this->load->model('HdayReport_model', 'DR');
            $this->load->model('Calendar_model', 'CM');
            $this->CM->id = (int) $this->input->post('id');
            $cal = $this->CM->getVal2();
            $this->DR->calander_id = (int) $this->input->post('id');
            $dt = $this->DR->getHdayReport();
            /////////////////////////////spreadsheet//////////////
            $file_title = "TABLE-B";
            $rep_date = date("d-m-Y", strtotime($cal->dates));
            $title = "MIGRATION OF LABOURERS FROM ONE STATE TO ANOTHER ON $rep_date  AT $cal->hours_desc";
            $time_desc = "$rep_date." . "$cal->hours_desc";

            $date = date_create($cal->dates);


            $rt_dt = date_format($date, "d");
            $rt_mnt = date_format($date, "m");
            $rt_yr = date_format($date, "Y");
            $file_name = "TABLE-B-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


            $spreadsheet = new Spreadsheet();
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $template = APPPATH . 'views/dr/templates/STATE2.xlsx';
            $spreadsheet = $reader->load($template);
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A3", $title);

            if ($dt) {


                $sl = 1;
                $n = 5;
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("C$n", $time_desc);

                foreach ($dt as $d) {
                    // var_dump($d);
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue("A$n", $sl)
                        ->setCellValue("D$n", $d->unit_short_code)
                        ->setCellValue("E$n", $d->no_of_persons)
                        ->setCellValue("F$n", $d->no_of_camps)
                        ->setCellValue("G$n", $d->problem_faced)
                        ->setCellValue("H$n", $d->action_taken)
                        ->setCellValue("I$n", $d->remarks);
                    $sl++;
                    $n++;
                }
            }

            $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
            /////////////////////Data Writing//////////////////////////
            //make object of the Xlsx class to save the excel file
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output'); // download file
            //////////////////////////////////////////////////////
        }
    }

    public function stateb_XLS()
    {
        $this->has_permission('cvd_st_daily_stat');

        $this->load->model('PositiveCases_model', 'DR');
        $d = $this->input->post('id');
        $this->DR->date_of_positive = toYmd($d);
        $dt = $this->DR->getByDate();
        /////////////////////////////spreadsheet//////////////
        $file_title = "TABLE-B-POSITIVE CASES";
        $rep_date = date("d-m-Y", strtotime($d));
        $title = "POSITIVE COVID - 19 CASES ON $rep_date";

        $date = date_create($d);
        $rt_dt = date_format($date, "d");
        $rt_mnt = date_format($date, "m");
        $rt_yr = date_format($date, "Y");
        $file_name = "TABLE-B-POSITIVE CASES" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');




        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $template = APPPATH . 'views/pc/templates/POSITIVE_STATE.xlsx';
        $spreadsheet = $reader->load($template);

        if ($dt) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A2", $title);

            $sl = 1;
            $n = 4;

            foreach ($dt as $d) {
                // var_dump($d);
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$n", $sl)
                    ->setCellValue("B$n", $d->unit_short_code)
                    ->setCellValue("C$n", $d->name)
                    ->setCellValue("D$n", $d->relative_name)
                    ->setCellValue("E$n", $d->age)
                    ->setCellValue("F$n", $d->sex)
                    ->setCellValue("G$n", $d->address)
                    ->setCellValue("H$n", $d->mobile)
                    ->setCellValue("I$n", $d->hospital)
                    ->setCellValue("J$n", $d->admission_remark);
                $sl++;
                $n++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
        /////////////////////Data Writing//////////////////////////
        //make object of the Xlsx class to save the excel file
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
        //////////////////////////////////////////////////////
    }

    public function statec_XLS()
    {
        $this->has_permission('cvd_st_daily_stat');

        $this->load->model('DailyObsreport_model', 'DR');
        $d = $this->input->post('id');
        $this->DR->rdate = toYmd($d);
        $dt = $this->DR->getCByDate();
        //var_dump($dt);
        /////////////////////////////spreadsheet//////////////
        $file_title = "TABLE-C";
        $rep_date = date("d-m-Y", strtotime($d));
        $title = "Table C - Daily Bulletin of Good Work done by Kerala Police in connection with Covid - 19 on $rep_date";

        $date = date_create($d);
        $rt_dt = date_format($date, "d");
        $rt_mnt = date_format($date, "m");
        $rt_yr = date_format($date, "Y");
        $file_name = "TABLE-C-" . $rt_dt . "-" . $rt_mnt . "-" . $rt_yr . "-" . date('d_m_Y_H_i_s');


        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $template = APPPATH . 'views/dr/templates/TABLEC.xlsx';
        $spreadsheet = $reader->load($template);

        if ($dt) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue("A1", $title);

            $sl = 1;
            $n = 5;

            foreach ($dt as $d) {

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$n", $sl)
                    ->setCellValue("B$n", $d->unit_short_code)
                    ->setCellValue("C$n", $d->check_points)
                    ->setCellValue("D$n", $d->mobile_patrols)
                    ->setCellValue("E$n", $d->q_visit)
                    ->setCellValue("F$n", $d->depl_male)
                    ->setCellValue("G$n", $d->depl_female)
                    ->setCellValue("H$n", $d->enf_fir)
                    ->setCellValue("I$n", $d->enf_arrests)
                    ->setCellValue("J$n", $d->enf_vehicle)
                    ->setCellValue("K$n", $d->enf_fake_news)
                    ->setCellValue("L$n", $d->enf_liqour)
                    ->setCellValue("M$n", $d->enf_ec)
                    ->setCellValue("N$n", $d->lab_camp_visited)
                    ->setCellValue("O$n", $d->gr_redressed)
                    ->setCellValue("P$n", $d->it_service)
                    ->setCellValue("Q$n", $d->supply_commodity)
                    ->setCellValue("R$n", $d->drugs_delivery)
                    ->setCellValue("S$n", $d->travel_asst)
                    ->setCellValue("T$n", $d->no_of_masks)
                    ->setCellValue("U$n", $d->no_of_drones)
                    ->setCellValue("V$n", $d->other_init);
                $sl++;
                $n++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
        /////////////////////Data Writing//////////////////////////
        //make object of the Xlsx class to save the excel file

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file  

        //////////////////////////////////////////////////////
    }
    public function distc_XLS($distid)
    {
        $this->has_permission('daily_report');

        $this->load->model('DailyObsreport_model', 'DR');
        $this->DR->distid = (int) $distid;
        $dt = $this->DR->getCByDist();
        //var_dump($dt);
        /////////////////////////////spreadsheet//////////////
        $file_title = "TABLE-C";

        $file_name = "TABLE-C-" . date('d_m_Y_H_i_s');

        $spreadsheet = new Spreadsheet();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $template = APPPATH . 'views/dr/templates/TABLEC_DIST.xlsx';
        $spreadsheet = $reader->load($template);

        if ($dt) {

            $sl = 1;
            $n = 5;

            foreach ($dt as $d) {

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue("A$n", $d->rdate)
                    ->setCellValue("B$n", $d->unit_short_code)
                    ->setCellValue("C$n", $d->check_points)
                    ->setCellValue("D$n", $d->mobile_patrols)
                    ->setCellValue("E$n", $d->q_visit)
                    ->setCellValue("F$n", $d->depl_male)
                    ->setCellValue("G$n", $d->depl_female)
                    ->setCellValue("H$n", $d->enf_fir)
                    ->setCellValue("I$n", $d->enf_arrests)
                    ->setCellValue("J$n", $d->enf_vehicle)
                    ->setCellValue("K$n", $d->enf_fake_news)
                    ->setCellValue("L$n", $d->enf_liqour)
                    ->setCellValue("M$n", $d->enf_ec)
                    ->setCellValue("N$n", $d->lab_camp_visited)
                    ->setCellValue("O$n", $d->gr_redressed)
                    ->setCellValue("P$n", $d->it_service)
                    ->setCellValue("Q$n", $d->supply_commodity)
                    ->setCellValue("R$n", $d->drugs_delivery)
                    ->setCellValue("S$n", $d->travel_asst)
                    ->setCellValue("T$n", $d->no_of_masks)
                    ->setCellValue("U$n", $d->no_of_drones)
                    ->setCellValue("V$n", $d->other_init);
                $sl++;
                $n++;
            }
        }

        $spreadsheet->getActiveSheet()->setTitle($file_title); //set a title for Worksheet
        /////////////////////Data Writing//////////////////////////
        //make object of the Xlsx class to save the excel file

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file  

        //////////////////////////////////////////////////////
    }


    public function exportCovidData()
    {
        $this->has_permission('data-export-report');
        $this->load->view('reports/loadExportview');
    }


    public function generateReportbyDate()
    {

        $this->load->dbutil();
        $this->load->model('Report_model', 'RM');
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('dor', 'dor', 'trim|required|is_Date');

        if ($this->form_validation->run()) {

            $newdate = array(
                'dateofreporting'  => $this->input->post('dor'),

            );

            $this->session->set_userdata($newdate);
            $result['success'] = TRUE;
            $result['msg'] = "Date Filter Set";
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }


    public function downloadReportasondate()
    {
        // $this->output->enable_profiler(TRUE);
        $this->load->model('Report_model', 'RM');

        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');


        $filename = "covid-master-data.csv";

        $result = $this->RM->generateDataofDate();

        // print "<pre>";
        // print_r($result);
        // print "</pre>";

        $data = $this->dbutil->csv_from_result($result);

        // print_r($data);
        force_download($filename, $data);
    }
}
