<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Captcha_model extends CI_Model{
	

	function __construct()
{
            parent::__construct();
}

    function setCaptcha()
    {
           
		    $this->load->helper('captcha');
            $vals = array(
                'img_path'          => $this->config->item('captcha_dir'),
                'img_url'           => base_url().$this->config->item('captcha_url'),
                'expiration'        => 3600,// one hour
                'img_width'         => 180,
                'img_height'        => 50,
                'max_rotation'      =>5
                
                );
			
            $cap = create_captcha($vals);
            $this->session->set_userdata('systemcaptcha', $cap['word']);
            return $cap['image'] ;
}       

 	
}

?>