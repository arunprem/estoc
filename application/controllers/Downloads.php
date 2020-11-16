
<?php


if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Downloads extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function covidAppDownload()
    {
        $this->has_permission('app@download');
        $this->load->view('down/covidapp');
    }

    public function downloadApp()
    {
        $this->has_permission('app@download');
        // $this->output->enable_profiler(TRUE);
        $this->load->helper('download');
        $data = 'App Not Released Yet!';
        $name = 'message.txt';
        force_download('./public/kp/download/androidapp/qpscheck.apk', NULL);
    }
}
