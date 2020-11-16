<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once APPPATH . 'libraries/API_Controller.php';

class Api_login extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->form_validation->set_error_delimiters('&#x26A1;', '<br>');
    }



    public function userLogin()
    {
        // $this->output->enable_profiler(TRUE);
        $this->load->model('api/userapi_model', 'APIUSER');
        // header("Access-Control-Allow-Origin: *");
        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'], // 'GET', 'OPTIONS'
            'limit' => [10, 'ip', '1'],
            'key' => ['header', '438bc4ac706592532b851cfccf6a38ca'],

        ]);
        //Validating login
        $result['success'] = FALSE;
        $result['msg'] = "Error Something whent wrong";
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run()) {
            $this->APIUSER->user_name = $this->input->post('username');
            $this->APIUSER->user_pass = $this->input->post('password');
            if ($user = $this->APIUSER->checkLogin()) {
                $payload = [
                    'data' => $user,

                ];
                // Load Authorization Library or Load in autoload config file
                $this->load->library('authorization_token');
                // generate a token
                $token = $this->authorization_token->generateToken($payload);
                $this->session->set_userdata('user', $user);
                //$token = "";
                $result['success'] = TRUE;
                $result["msg"] = "User Successfully Validated";
                $result["profile"] = [
                    "Name" => $user->p_name,
                    "unit" => $user->runit,
                    "pen" => $user->pen,
                ];
                $result["kp_token"] = $token;
            } else {
                $result['msg'] = "invalid username or password";
            }
        } else {
            $result['msg'] = validation_errors();
        }

        $this->api_return(
            [
                'status' => true,
                "result" => $result,
            ],
            200
        );
    }


    public function regenerateToken()
    {

        header("Access-Control-Allow-Origin: *");

        $this->_apiConfig([
            'methods' => ['POST'], // 'GET', 'OPTIONS'
            //'limit' => [10, 'ip', '1'],
            'key' => ['header', '438bc4ac706592532b851cfccf6a38ca'],

        ]);

        // API Configuration [Return Array: User Token Data]
        $user_data = $this->_apiConfig([
            'methods' => ['POST'],

            'requireAuthorization' => true,

        ]);

        $token = $this->authorization_token->generateToken($user_data['token_data']);

        // return data
        $this->api_return(
            [
                'status' => true,
                "result" => [
                    'token' => $token,
                ],

            ],
            200
        );
    }
}
