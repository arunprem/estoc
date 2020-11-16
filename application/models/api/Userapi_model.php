
<?php
class userapi_model extends MY_Model
{

    var $user_name;
    var $user_pass;

    public function __construct()
    {
        parent::__construct();
    }


    public function checkLogin()
    {

        $this->db->where('user_name', $this->user_name);
        $this->db->where('user_pass', md5($this->user_pass));
        $this->db->where('user_status', 1);
        $rs = $this->db->get('user');
        if ($rs->num_rows() == 1) {
            $user = $rs->row();
            $this->load->model('user/user_model');

            $user_with_permissions = $this->user_model->get_user_permissions($user->iduser);
            return $user_with_permissions;
        } else {
            return FALSE;
        }
    }
}
