<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  Document   : user
  Author     : Mukesh MR
  Description:

 */

class User extends CI_Controller {

    public function index() {
        redirect(base_url());
    }

    public function login() {
        if ($user = $this->session->userdata('user')) {           
                redirect('home');
            
        }

        $this->load->model('plugins/captcha_model');
        $data['sitename'] = $this->config->item('site_name');
        $data['captcha'] = $this->captcha_model->setCaptcha();

        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');

        $this->load->view('user/login', $data);
    }

    public function logout() {
        if (isset($this->session->userdata['user'])) {
            $this->load->model('userlog_model');
            $this->userlog_model->idlog = $this->session->userdata('idlog');
            $this->userlog_model->logout();
            $this->session->sess_destroy();
            redirect('');
        } else {
            redirect('');
        }
    }

    public function getCaptcha() {
        $this->load->model('plugins/captcha_model');
        $data['captcha'] = $this->captcha_model->setCaptcha();
        $this->load->view('user/captcha', $data);
    }

    public function session_expired() {

        $this->load->view('user/session_expired');
    }

    public function checklogin() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('captcha', 'captcha', 'trim|required|callback_captcha_check');
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $msg = array();
        $msg['status'] = FALSE;
        $msg['csrf'] = $this->security->get_csrf_hash();
        if ($this->form_validation->run() == FALSE) {
            if (form_error('captcha')) {
                $msg['error'] = 'Wrong Security Code';
            } else {
                $msg['error'] = 'Please check all fields';
            }
        } else {
            $ctime = time();
            $this->load->model('user/login_model');
            $this->login_model->user_name = $this->input->post('username');
            $this->login_model->user_pass = $this->input->post('password');

            //login attempt counter check
            $timeStamp = $this->login_model->getCurrentTimeStampAndBlockStatus();           
            if($timeStamp){
                if ($timeStamp['blocked_status'] == 1) {
                    if ($timeStamp['login_timestamp'] + 3600 < $ctime) {
                        $this->login_model->remove_block();
                        if ($user = $this->login_model->checkLogin()) {
                            $this->session->set_userdata('user', $user);
                            $this->load->model('userlog_model');
                            $this->userlog_model->iduser = $user->iduser;
                            $this->userlog_model->sess_id = $this->session->userdata('session_id');
                            $this->session->set_userdata('idlog', $this->userlog_model->add());
                            $msg['status'] = TRUE;
                            if ($user) {
                               
                                $msg['URL'] = base_url('home');
                            }
                        } else {
                            $logintCount = $this->login_model->getCurrentCount();
                            if ($logintCount <= 2) {
                                $this->login_model->login_count = $logintCount + 1;
                                $this->login_model->updateLoginCount();
                                $msg['error'] = 'Incorrect Username/Passoword';
                            } else {
                                $this->login_model->blockUserForMultippleTry();
                                $msg['error'] = 'Sorry You are Blocked Please Try After one hour';
                            }
                        }
                    } else {
                        $msg['error'] = 'Sorry You are Blocked Please Try After one hour';
                    }
                } else if ($timeStamp['blocked_status'] == 0) {
                    if ($user = $this->login_model->checkLogin()) {
                        $this->session->set_userdata('user', $user);
                        $this->load->model('userlog_model');
                        $this->userlog_model->iduser = $user->iduser;
                        $this->userlog_model->sess_id = $this->session->userdata('session_id');
                        $this->session->set_userdata('idlog', $this->userlog_model->add());
                        $msg['status'] = TRUE;
                        if ($user) {
                           
                            $msg['URL'] = base_url('home');
                        }
                    } else {
                        $logintCount = $this->login_model->getCurrentCount();
                        if ($logintCount <= 2) {
                            $this->login_model->login_count = $logintCount + 1;
                            $this->login_model->updateLoginCount();
                            $msg['error'] = 'Incorrect Username/Passoword';
                        } else {
                            $this->login_model->blockUserForMultippleTry();
                            $msg['error'] = 'Sorry You are Blocked Please Try After 24 hrs';
                        }
                    }
                } else {
                    $msg['error'] = 'Sorry You are Blocked Please Try After 24 hrs';
                }
            }else{
                $msg['error'] = 'User does not exists';
            }
            
        }
        $this->output->set_output(json_encode($msg));
    }

    public function captcha_check($ucaptcha) {

        if ($ucaptcha != $this->session->userdata['systemcaptcha']) {
            $this->form_validation->set_message('captcha_check', 'Wrong security code');
            return FALSE;
        } else {
            return TRUE;
        }
    }

   
}

?>
