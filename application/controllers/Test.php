<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//include the classes needed to create and write .xlsx file
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Test extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index($ps = NULL) {
        //$this->output->enable_profiler(TRUE);
        $sql = "SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'MAX(IF(yr = ''',
      yr,
      ''', val, NULL)) AS ''',
      yr ,''''
    )
  ) str
FROM `vw_crime_sub`;";
        $rs = $this->db->query($sql);
        $s = $rs->row();
        $sql2 = "SELECT id_ps,m_head,ps_link,s_head,head_eng,head_mal, " . $s->str . " FROM `vw_crime_sub`
where id_ps = '1' and m_head = '2'
GROUP BY id_ps,s_head;";
        $rs = $this->db->query($sql2);
        // echo $this->db->last_query();
        $t = $rs->row();
        var_dump($t);
    }

    public function getUnitAndChield() {
        $this->load->model('unit_model');
        $units = $this->unit_model->listAllUnitsUnderParent();
        foreach ($units as $unit) {
            foreach ($unit as $value) {
                echo '\'' . $value . '\',';
            }
        }
    }

    public function getUserLoginAtemptCount() {
        //   echo time();
        $ctime = time();
        echo $ctime . "</br>";
        $timestamp = strtotime('25-09-2018');

        echo $timestamp . "</br>";
        if ($timestamp + 86400 < $ctime) {
            echo "reached 24 hrs";
        } else {
            echo " not reached 24 hrs";
        }
    }

    public function dataByUser() {
        $this->load->model('unit_model', 'UM');
        $unitList = $this->UM->allUnitsByUser();
        $unitid = array();
        foreach ($unitList as $value) {
            array_push($unitid, $value['id']);
        }
        return $unitid;
    }

    public function getUnitIds() {
        //$this->output->enable_profiler(TRUE);
        $user = $this->dataByUser();
        //  print_r($user);
        //$count= $this->db->select('unit_id','count(*)')->where_in('unit_id', $user)->from('t_employee')->count_all_results(); 
        $this->db->select('unit.unit_short_code,t_employee.unit_id, COUNT(t_employee.unit_id) as total');


        $this->db->join('user u', 'u.user_name=t_employee.pen', 'left');
        $this->db->join('unit', 'unit.id=t_employee.unit_id', 'left');

        $this->db->where_in('t_employee.unit_id', $user);
        //  $this->db->where('user.user_role', '1');
        $this->db->group_by('t_employee.unit_id');


        $count = $this->db->get('t_employee');
        $edited = $this->db->query('select count(*),t_employee.unit_id from t_employee where pen in(select user_name from user where user_unit=909) group by t_employee.unit_id');

        print "<pre>";
        print_r($edited->result_array());
        print "</pre>";


        print "<pre>";
        print_r($count->result_array());
        print "</pre>";
        // $id = implode(',', $user);
    }

    public function getPhotoName() {
        $this->output->enable_profiler(TRUE);
        $this->load->helper('file');
        $data = get_filenames('./public/web/images/profile');
        foreach ($data as $value) {
            $name = strtok($value, '.');
            $info = pathinfo('./public/web/images/profile/' . $value);
            $this->updatePhotoName($name, $info['extension']);
        }
    }

    public function updatePhotoName($name, $ext) {
        $newname = md5($name) . '.' . $ext;
        rename('./public/web/images/profile/' . $name . '.' . $ext, './public/web/images/profile/' . $newname);
        $this->db->set('photo', $newname);
        $this->db->where('photo', $name . '.' . $ext);
        $this->db->or_where('pen', $name);
        if ($this->db->update('t_employee')) {
            echo "Updated";
        }
    }

    public function getPdf() {
        $mpdf = new \Mpdf\Mpdf();
        $html = $this->load->view('search/viewEmp', [], true);
        $mpdf->WriteHTML($html);
        $mpdf->Output(); // opens in browser
        //$mpdf->Output('arjun.pdf','D'); // it downloads the file into the user system, with give name
    }

    public function encryptiontest() {
        $this->load->library('encryption');
        $arun = $this->encryption->encrypt(6);
        echo $arun . "<br>";

        $prem = $this->encryption->decrypt($arun);
        echo $prem;
    }

    public function getStateWiseReport() {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->load->model('dashboard_model', 'DM');
        $this->db->trans_start();
        $this->db->select('id');
        $this->db->where('is_parent_unit', '1');
        $rs = $this->db->get('unit');

        $result = $rs->result_array($rs);
        $this->db->trans_complete();

        $strength = array();
        foreach ($result as $value) {

            $st = $this->dataByParent($value['id']);

            foreach ($st as $value) {
                $data['profile'] = $this->DM->getTotalByUnit($st);
                // $data['editedProfile'] = $this->DM->getTotalEditedDataByUnit($st);
                // $data['verifiedProfile'] = $this->DM->getTotalVerifiedDataByUnit($st);
                array_push($strength, $data);
            }
        }

        print_r($strength);
    }

    public function dataByParent($id) {
        $this->load->model('unit_model', 'UM');
        $this->db->trans_start();
        $unitList = $this->UM->getChildByParant($id);
        $unitid = array();
        foreach ($unitList as $value) {
            array_push($unitid, $value['id']);
        }
        $this->db->trans_complete();
        return $unitid;
    }

    public function getstrength() {
        $this->output->enable_profiler(TRUE);
        ini_set('max_execution_time', 0);
        $this->load->model('dashboard_model', 'DM');
        $s = $this->DM->getUnitWiseDEStatus();
        var_dump($s);
    }

    public function testMap() {
        // Load the library
        $this->load->library('googlemaps');
        $config['center'] = '37.4419, -122.1419';
        $config['zoom'] = 'auto';
        $this->googlemaps->initialize($config);

        $polygon = array();
        $polygon['points'] = array('37.425, -122.1321',
            '37.4422, -122.1622',
            '37.4412, -122.1322',
            '37.425, -122.1021');
        $polygon['strokeColor'] = '#000099';
        $polygon['fillColor'] = '#000099';
        $this->googlemaps->add_polygon($polygon);
        $data['map'] = $this->googlemaps->create_map();
// Load our view, passing the map data that has just been created
        $this->load->view('test/my_view', $data);
    }

    public function newChart() {
        $this->load->view('chart/basic');
    }

    public function excel() {

        //object of the Spreadsheet class to create the excel data
        $spreadsheet = new Spreadsheet();

        //add some data in excel cells
        $title = "Election Commission of India - LAW&ORDER Report - 1";
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', $title)
                ->setCellValue('A2', 'SL No')
                ->setCellValue('B2', 'District')
                ->setCellValue('C2', 'Date of Report')
                ->setCellValue('D3', 'Arms / Weapons')
                ->setCellValue('E3', 'Explosives')
                ->setCellValue('F3', 'Catridges')
                ->setCellValue('G3', 'Bombs')
                ->setCellValue('H2', 'No. of Illicit Arms manufacturing centres raided and seizures made')
                ->setCellValue('I3', 'Licensed Arms')
                ->setCellValue('J3', 'Deposited')
                ->setCellValue('K3', 'Impounded')
                ->setCellValue('L3', 'Cancelled')
                ->setCellValue('M2', 'No. of cases put up under  of CrPC')
                ->setCellValue('N2', 'No. of persons bound over under preventive sections of CrPC')
                ->setCellValue('O2', 'No. of persons bound down under preventive sections of CrPC')
                ->setCellValue('P2', 'No of Nakas Operational')
                ->setCellValue('Q3', 'Executed')
                ->setCellValue('R3', 'Pending')
                ->setCellValue('S2', 'Number of incidents occurred under Atrocities Act 1989 during election')
                ->setCellValue('T3', 'No. of incidents')
                ->setCellValue('U3', 'Total killed')
                ->setCellValue('V3', 'Total injured')
                ->setCellValue('W3', 'Damage to property (RS.in Lakhs)')
                ->setCellValue('D2', 'No. of Unlicensed Arms/Explosives seized')
                ->setCellValue('I2', 'Licensed Arms')
                ->setCellValue('Q2', 'Execution of Non-bailable Warrants')
                ->setCellValue('T2', 'No. of incidents of violence related to Poll campaign, Political rivalry etc');

        $spreadsheet->getActiveSheet()
                ->mergeCells('A1:W1')
                ->mergeCells('D2:G2')
                ->mergeCells('I2:L2')
                ->mergeCells('Q2:R2')
                ->mergeCells('T2:W2')
                ->mergeCells('A2:A3')
                ->mergeCells('B2:B3')
                ->mergeCells('C2:C3')
                ->mergeCells('H2:H3')
                ->mergeCells('M2:M3')
                ->mergeCells('N2:N3')
                ->mergeCells('O2:O3')
                ->mergeCells('P2:P3')
                ->mergeCells('S2:S3');

        $spreadsheet->getActiveSheet()->getStyle('A3:W3')
                ->getAlignment()->setTextRotation(90);

        $spreadsheet->getActiveSheet()->getStyle('A2:C2')
                ->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('H2')
                ->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('M2:P2')
                ->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('S2')
                ->getAlignment()->setTextRotation(90);
        $spreadsheet->getActiveSheet()->getStyle('A1:W3')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('A2:W2')->getAlignment()->setWrapText(true);
        /*
          $spreadsheet->setActiveSheetIndex(0)
          ->setCellValue('A2', 'CoursesWeb.net')
          ->setCellValue('B2', 'Web Development')
          ->setCellValue('C2', '4000');

          $spreadsheet->setActiveSheetIndex(0)
          ->setCellValue('A3', 'MarPlo.net')
          ->setCellValue('B3', 'Courses & Games')
          ->setCellValue('C3', '15000');
          $filename = 'name-of-the-generated-file';
         * 
         */
        //set style for A1,B1,C1 cells
        $cell_st = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['bottom' => ['style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ];


        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000'],
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('A2:W3')->applyFromArray($styleArray);





        $spreadsheet->getActiveSheet()->setTitle('Simple'); //set a title for Worksheet
        //make object of the Xlsx class to save the excel file
        $writer = new Xlsx($spreadsheet);
        $filename = "test";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file

        /*
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
          $sheet->setCellValue('A1', 'Hello World !');
          $writer = new Xlsx($spreadsheet);
          $filename = 'name-of-the-generated-file';

          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
          header('Cache-Control: max-age=0');
          $writer->save('php://output'); // download file
         * 
         */
    }

    public function dist1() {

        $title1 = "Election Commission of India";
        $report_no = "LAW & ORDER REPORT-II";
        $report_name = "Daily Law & Order Report for State for Electoral Events";
        $election_name = " General Election To Kerala Legislative Assembly , 2016";
        $report_period_label = "(Report for a period should cover a period of 24 hours from 6 AM of that day to 6 AM of next day)";
        $st_dt_label = "Start date for these reports:";
        $rt_day = "Report for 59th day";
        $dt_label = "Date";
        $mnt_label = "Month";
        $yr_label = "Year";

        $dt = "22";
        $mnt = "02";
        $yr = "2019";
        $jurisdiction = "Name of District";



        $spreadsheet = new Spreadsheet();

        //add some data in excel cells
        $title = "Election Commission of India - LAW&ORDER Report - 1";
        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('F1', $title)
                ->setCellValue('S1', $report_no)
                ->setCellValue('A2', $report_name)
                ->setCellValue('A3', $election_name)
                ->setCellValue('A4', $report_period_label)
                ->setCellValue('G5', $st_dt_label)
                ->setCellValue('G6', $rt_day)
                ->setCellValue('L5', $dt_label)
                ->setCellValue('M5', $dt)
                ->setCellValue('N5', $dt_label)
                ->setCellValue('O5', $mnt)
                ->setCellValue('P5', $yr_label)
                ->setCellValue('Q5', $yr)
                ->setCellValue('L6', $dt_label)
                ->setCellValue('M6', $dt)
                ->setCellValue('N6', $dt_label)
                ->setCellValue('O6', $mnt)
                ->setCellValue('P6', $yr_label)
                ->setCellValue('Q6', $yr)
                ->setCellValue('A7', $jurisdiction)
                ->setCellValue('A10', 'SL No')
                ->setCellValue('B10', 'District')
                ->setCellValue('C10', 'Date of Report')
                ->setCellValue('D11', 'Arms / Weapons')
                ->setCellValue('E11', 'Explosives')
                ->setCellValue('F11', 'Catridges')
                ->setCellValue('G11', 'Bombs')
                ->setCellValue('H10', 'No. of Illicit Arms manufacturing centres raided and seizures made')
                ->setCellValue('I11', 'Licensed Arms')
                ->setCellValue('J11', 'Deposited')
                ->setCellValue('K11', 'Impounded')
                ->setCellValue('L11', 'Cancelled')
                ->setCellValue('M10', 'No. of cases put up under  of CrPC')
                ->setCellValue('N10', 'No. of persons bound over under preventive sections of CrPC')
                ->setCellValue('O10', 'No. of persons bound down under preventive sections of CrPC')
                ->setCellValue('P10', 'No of Nakas Operational')
                ->setCellValue('Q11', 'Executed')
                ->setCellValue('R11', 'Pending')
                ->setCellValue('S10', 'Number of incidents occurred under Atrocities Act 1989 during election')
                ->setCellValue('T11', 'No. of incidents')
                ->setCellValue('U11', 'Total killed')
                ->setCellValue('V11', 'Total injured')
                ->setCellValue('W11', 'Damage to property (RS.in Lakhs)')
                ->setCellValue('D10', 'No. of Unlicensed Arms/Explosives seized')
                ->setCellValue('I10', 'Licensed Arms')
                ->setCellValue('Q10', 'Execution of Non-bailable Warrants')
                ->setCellValue('T10', 'No. of incidents of violence related to Poll campaign, Political rivalry etc');

        $letters = range('A', 'W');
        $i = 1;
        foreach ($letters as $l) {
            $n = 9;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue("$l$n", $i);
            $i++;
        }


        $spreadsheet->getActiveSheet()
                ->mergeCells('F1:R1')
                ->mergeCells('S1:W1')
                ->mergeCells('A2:W2')
                ->mergeCells('A3:W3')
                ->mergeCells('A4:W4')
                ->mergeCells('G5:K5')
                ->mergeCells('G6:K6')
                ->mergeCells('A7:W7')
                ->mergeCells('D10:G10')
                ->mergeCells('I10:L10')
                ->mergeCells('Q10:R10')
                ->mergeCells('T10:W10')
                ->mergeCells('A10:A11')
                ->mergeCells('B10:B11')
                ->mergeCells('C10:C11')
                ->mergeCells('H10:H11')
                ->mergeCells('M10:M11')
                ->mergeCells('N10:N11')
                ->mergeCells('O10:O11')
                ->mergeCells('P10:P11')
                ->mergeCells('S10:S11');

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
        $spreadsheet->getActiveSheet()->getStyle('A1:W11')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('A9:W11')->getAlignment()->setWrapText(true);
        $spreadsheet->getActiveSheet()->getStyle('D10')->getAlignment()->setWrapText(true);


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

        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => '14'
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:W7')->applyFromArray($headerStyle);
        $spreadsheet->getActiveSheet()->getStyle('A9:W11')->applyFromArray($styleArray);


        $styleArray1 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000'],
                ],
            ],
        ];

        $spreadsheet->getActiveSheet()->getStyle('G5:Q6')->applyFromArray($styleArray1);

        $spreadsheet->getActiveSheet()->setTitle('Simple'); //set a title for Worksheet
        //make object of the Xlsx class to save the excel file
        $writer = new Xlsx($spreadsheet);
        $filename = "test";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output'); // download file
    }

    public function testingData() {
        $this->output->enable_profiler(TRUE);
        $data = date('Y-m-d', strtotime("-1 days"));
        $sql = "select sum(sec_proc_initiated_cum)sec_proc
                from `vw_dr` 
                where `date`= $data and `submit_status`='1'";
        $rs = $this->db->query($sql);
        $items = array();
        foreach ($rs->result_array() as $row) {
            array_push($items, $row);
        }
        print_r($item);
    }

    public function gitTest(){
        echo "testing git ";
    }

}

?>
