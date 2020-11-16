<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getSubDomain')) {

    function getSubDomain($ps = 'ps') {
        $ci = & get_instance();
        if ($ci->config->item('set_subdomain') == TRUE) {
            return 'http://' . $ps . '.keralapolice.gov.in';
        } else {
            return base_url() . $ps . '-ps';
        }
    }

}