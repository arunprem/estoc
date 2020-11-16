<?php

class MY_Form_validation extends CI_Form_validation {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function error_array() {
        if (count($this->_error_array > 0)) {
            return $this->_error_array;
        }
    }

    public function alpha_dash_space($str) {
        return (!preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
    }

    public function alpha_space($str) {
        return (!preg_match("/^([a-zA-Z ])+$/i", $str)) ? FALSE : TRUE;
    }

    function valid_url_format($str) {
        $pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
        if (!preg_match($pattern, $str)) {
            $this->set_message('valid_url_format', 'The URL you entered is not correctly formatted.');
            return FALSE;
        }

        return TRUE;
    }

    public function check_phone($phone) {
        if (preg_match('/^[0-9]{4}\-[0-9]{4}\-[0-9]{4}\-[0-9]{4}$/', $phone)) {
            return true;
        } else {
            $this->form_validation->set_message('check_phone', '%s ' . $phone . ' is invalid format');
            return false;
        }
    }
    
    public function check_cug($phone) {
        if (preg_match('/^[789]\d{9}$/', $phone)) {
            return true;
        } else {
            $this->form_validation->set_message('check_cug', '%s ' . $phone . ' is invalid format');
            return false;
        }
    }

    // --------------------------------------------------------------------

    /**
     * Validates that a URL is accessible. Also takes ports into consideration.
     * Note: If you see "php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known"
     *          then you are having DNS resolution issues and need to fix Apache
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function url_exists($url) {
        $url_data = parse_url($url); // scheme, host, port, path, query
        if (!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)) {
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }

        return TRUE;
    }

    function valid_date($my_date) {
        return (!preg_match("/(\d{2})\/(\d{2})\/(\d{4})$/", $my_date)) ? FALSE : TRUE;
    }

    function is_json($j) {
        $json_object = json_decode($j);
        $isValid = ($json_object != null && !is_string($json_object));
        if (!$isValid) {
            $this->set_message('is_json', 'Error in %s.');
        }
        return $isValid;
    }

    function is_json_null($j) {
        $json_object = json_decode($j);
        $isValid = (!is_string($json_object));
        if (!$isValid) {
            $this->set_message('is_json', 'Error in %s.');
        }
        return $isValid;
    }

    function is_dor($dor) {

        $currentDate = strtotime(date('d-m-Y'));
        $startDate = strtotime("01-01-1980");
        $dor = strtotime($dor);
        if ($dor <= $currentDate && $dor >= $startDate) {
            return TRUE;
        } else {
            $this->set_message('is_dor', 'Error in %s.');
            return FALSE;
        }
    }

    function is_Date($date) {
        
            $d = explode("-", $date);
            if (checkdate($d[1], $d[0], $d[2])) {
                return true;
            } else {
                $this->set_message('is_Date', 'Error in %s.');
                return false;
            }
        
    }

    function isDateBefore($date) {
        $time1 = strtotime(date("d-m-Y", strtotime($date)));
        if (time() >= $time1) {
            return true;
        } else {
            $this->set_message('isDateBefore', 'Error in %s.');
            return false;
        }
    }
    
    function isPen($pen){
         if (preg_match('/^[1-9]{1}[0-9]{5}$/', $pen)) {
            return true;
        } else {
            $this->form_validation->set_message('isPen', '%s ' . $pen . ' is invalid pen');
            return false;
        }
    }
    
    function isPan($pan){
         if (preg_match('/^([a-zA-Z]{5})([0-9]{4})([a-zA-Z]{1})$/', $pan)) {
            return true;
        } else {
            $this->form_validation->set_message('isPan', '%s ' . $pan . ' is invalid PAN');
            return false;
        }
    }
    
    
            

}
