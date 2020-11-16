<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Input
 *
 * @author Mukesh
 */
class MY_Input extends CI_Input {

    function __construct()
    {
        parent::__construct();
    }
    //Overide ip_address() with your own function
    function ip_x_forwarded() 
    {
        //Obtain the IP address however you'd like, you may want to do additional validation, etc..
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            return FALSE;
        }
    }
}
