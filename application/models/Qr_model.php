<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of permission_model
 *
 * @author Mukesh
 */
class Qr_model extends MY_Model
{

    var $id;
    var $random_qr;
    var $dmo_id;
    var $dmo_patient_id;
    var $dist_id;
    var $ps_id;
    var $p_type;
    var $p_name;
    var $age;
    var $gender;
    var $mobile;
    var $address;
    var $address_area;
    var $latitude;
    var $longitude;
    var $lsg;
    var $ward;
    var $lsg_id;
    var $ward_id;
    var $q_location_type;
    var $q_location_details;
    var $q_start_date;
    var $q_end_date;
    var $c_zone;
    var $phc;
    var $symptoms;
    var $p_current_status;
    var $surveillance_status;
    var $last_checking_time;
    var $contact_tracing_status;
    var $linked_status;
    var $created_by;
    var $created_on;
    var $updated_by;
    var $updated_on;
    var $status;

    var $decl_date;

    var $relation_to;
    var $contact_type;
    var $person_from_id;
    var $person_id;


    ///////////////////////
    var $like = array();
    var $select_table = 't_cvd_ct_persons p';
    var $insert_table = 't_cvd_ct_persons';
    var $column_order = array(null, 'p.dmo_patient_id', 'p.p_name', 'p.mobile', 'p.address', 'p.contact_tracing_status', null); //set column field database for datatable orderable
    var $column_search = array('p.dmo_patient_id', 'p.p_name', 'p.mobile', 'p.address'); //set column field database for datatable searchable 
    var $order = array('p.id' => 'desc','p.random_qr' => 'asc'); // default order 
    var $select = array('p.id', 'p.dmo_patient_id', 'CONCAT(p.p_name,"(",p.gender,"-",p.age,")") p_name', 'p.mobile', 'p.address', 'p.contact_tracing_status', 'w.ward_name ward');
    var $join = array(
        array(
            't' => 'unit utd',
            'j' => 'utd.id = p.dist_id',
            'jt' => 'left'
        ),
        array(
            't' => 'unit uts',
            'j' => 'uts.id = p.ps_id',
            'jt' => 'left'
        ),
        array(
            't' => 't_cvd_ct_dmo d',
            'j' => 'd.id = p.dmo_id',
            'jt' => 'left'
        ),
        array(
            't' => 'm_cvd_ct_lsg_wards w',
            'j' => 'w.id = p.ward_id',
            'jt' => 'left'
        ),
    );
    var $where = array();
    var $group_by = array();

    public function __construct()
    {
        parent::__construct();
    }


    public function qr_list_datatable()
    {
        $user = $this->session->userdata['user'];

        $this->column_order = array(null, null, 'p.id', 'p.p_name', 'p.mobile', 'p.address', 'w.ward_name'); //set column field database for datatable orderable

        $this->select = array('p.id', 'p.random_qr', 'CONCAT(p.p_name,"(",p.gender,"-",p.age,":",p.mobile,")") p_name', 'p.address', 'w.ward_name ward','DATE_FORMAT(p.decl_date,"%d-%m-%Y") decl_date');

        $this->join = array(
            array(
                "t" => "m_cvd_ct_lsg_wards w",
                "j" => "w.id = p.ward_id",
                "jt" => "left"
            ),
            array(
                "t" => "vw_unit vu",
                "j" => "p.ps_id = vu.id",
                "jt" => "left"
            ),
        );

        $this->like = array(
            array(
                'q' => 'p_name',
                's' => $this->p_name
            ),
            array(
                'q' => 'gender',
                's' => $this->gender
            ),
            array(
                'q' => 'mobile',
                's' => $this->mobile
            ),
            array(
                'q' => 'address',
                's' => $this->address
            ),
        );
        //////////////////////////////////////////




        if ($this->ps_id != '') {
            $w = "p.ps_id = " . $this->ps_id;
            array_push($this->where, $w);
        }

        if ($this->lsg_id != '') {
            $w = "p.lsg_id = " . $this->lsg_id;
            array_push($this->where, $w);
        }

        if ($this->ward_id != '') {
            $w = "p.ward_id = " . $this->ward_id;
            array_push($this->where, $w);
        }

        if ($this->surveillance_status != '') {
            $w = "p.surveillance_status = '" . $this->surveillance_status."'";
            array_push($this->where, $w);
        }


        if ($this->id != '') {
            $w = "p.id = " . $this->id;
            array_push($this->where, $w);
        }

        // $z = "d.assigned_to = " . $this->db->escape($user->iduser);
        // array_push($this->where, $z);
        $y = "p.p_type ='C' ";
        array_push($this->where, $y);

        
        if ($this->decl_date != '') {
            $p = "p.decl_date = '" . $this->decl_date . "'";
            array_push($this->where, $p);
        }

        $z= "vu.lft >= $user->lft and vu.rgt <=$user->rgt";
        array_push($this->where, $z);

        
        return $this->get_datatables();
    }

    public function qr_linklist_datatable()
    {
        $user = $this->session->userdata['user'];
        $this->column_order = array(null, 'p.p_name', 'p.mobile', 'p.address'); //set column field database for datatable orderable

        $this->select = array('p.id', 'p.random_qr', 'CONCAT(p.p_name,"(",p.gender,"-",p.age,":",p.mobile,")") p_name', 'p.address');

        $this->join = array(

            array(
                "t" => "vw_unit vu",
                "j" => "p.ps_id = vu.id",
                "jt" => "left"
            ),
        );
        $this->like = array(
            array(
                'q' => 'p.p_name',
                's' => $this->p_name
            ),
            array(
                'q' => 'p.mobile',
                's' => $this->mobile
            ),
            array(
                'q' => 'p.address',
                's' => $this->address
            ),
        );
        //////////////////////////////////////////




        // $z = "d.assigned_to = " . $this->db->escape($user->iduser);
        // array_push($this->where, $z);
        $y = "p.p_type ='C' ";
        array_push($this->where, $y);

        $z = "p.id NOT in (SELECT id FROM t_cvd_ct_persons WHERE random_qr ='$this->random_qr' and status=1 )";
        array_push($this->where, $z);

        $p= "vu.lft >= $user->lft and vu.rgt <=$user->rgt";
        array_push($this->where, $p);
        
        //echo $this->db->last_query();
        return $this->get_datatables();
    }


    

    public function add()
    {
        $user = $this->session->userdata['user'];
        $this->created_by = $user->iduser;
        $this->created_on = date('Y-m-d H:i:s');
        if ($this->random_qr == '') {
            $this->load->helper('string');
            $this->random_qr = sha1(uniqid(random_string('alnum', 16)));
        }

        $data = array(
            'random_qr' => $this->random_qr,
            'dist_id' => $this->dist_id,
            'ps_id' => $this->ps_id,
            'p_type' => $this->p_type,
            'p_name' => $this->p_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'address_area' => $this->address_area,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'lsg_id' => $this->lsg_id,
            'ward_id' => $this->ward_id,
            'q_location_type' => $this->q_location_type,
            'q_location_details' => $this->q_location_details,
            'q_start_date' => $this->q_start_date,
            'q_end_date' => $this->q_end_date,
            'c_zone' => $this->c_zone,
            'phc' => $this->phc,
            'symptoms' => $this->symptoms,
            'p_current_status' => $this->p_current_status,
            'surveillance_status' => $this->surveillance_status,
            'contact_tracing_status' => $this->contact_tracing_status,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on
        );
        $sql1 = $this->db->set($data)->get_compiled_insert('t_cvd_ct_persons');
        $this->db->trans_start();
        $this->db->query($sql1);
        $id = $this->db->insert_id();
        $data2 = array(
            'dist_id' => $this->dist_id,
            'ps_id' => $this->ps_id,
            'person_id' => $id,
            'person_from_id' => $this->person_from_id,
            'relation_to' => $this->relation_to,
            'contact_type' => $this->contact_type,
            'date_of_contact' => $this->q_start_date,
            'q_start_date' => $this->q_start_date,
            'q_end_date' => $this->q_end_date,
            'surveillance_status' => $this->surveillance_status,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
        );
        $sql2 = $this->db->set($data2)->get_compiled_insert('t_cvd_ct_contact_tracing');
        $this->db->query($sql2);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function linkContact()
    {
        $user = $this->session->userdata['user'];
        $this->created_by = $user->iduser;
        $this->created_on = date('Y-m-d H:i:s');


        $data = array(
            'dist_id' => $this->dist_id,
            'ps_id' => $this->ps_id,
            'person_id' => $this->person_id,
            'person_from_id' => $this->person_from_id,
            'contact_type' => $this->contact_type,
            'relation_to' => $this->relation_to,
            'date_of_contact' => $this->q_start_date,
            'q_start_date' => $this->q_start_date,
            'q_end_date' => $this->q_end_date,
            'surveillance_status' => $this->surveillance_status,
            'created_by' => $this->created_by,
            'created_on' => $this->created_on,
        );

        if ($this->db->insert('t_cvd_ct_contact_tracing', $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function update()
    {
        $user = $this->session->userdata['user'];
        $this->updated_by = $user->iduser;
        $this->updated_on = date('Y-m-d H:i:s');
        if ($this->random_qr == '') {
            $this->load->helper('string');
            $this->random_qr = sha1(uniqid(random_string('alnum', 16)));
        }



        $data = array(
            'random_qr' => $this->random_qr,
            'dist_id' => $this->dist_id,
            'ps_id' => $this->ps_id,
            'p_type' => $this->p_type,
            'p_name' => $this->p_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'address_area' => $this->address_area,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'lsg_id' => $this->lsg_id,
            'ward_id' => $this->ward_id,
            'q_location_type' => $this->q_location_type,
            'q_location_details' => $this->q_location_details,
            'q_start_date' => $this->q_start_date,
            'q_end_date' => $this->q_end_date,
            'c_zone' => $this->c_zone,
            'phc' => $this->phc,
            'symptoms' => $this->symptoms,
            'p_current_status' => $this->p_current_status,
            'surveillance_status' => $this->surveillance_status,
            'contact_tracing_status' => $this->contact_tracing_status,
            'updated_by' => $this->updated_by,
            'updated_on' => $this->updated_on
        );
        $sql1 = $this->db->where('id', $this->id)->set($data)->get_compiled_update('t_cvd_ct_persons');
        // echo $sql1;
        $this->db->trans_start();
        $this->db->query($sql1);
        if ($this->p_type == 'C') {
            $data2 = array(
                'dist_id' => $this->dist_id,
                'ps_id' => $this->ps_id,
                'person_id' => $this->id,
                'person_from_id' => $this->person_from_id,
                'relation_to' => $this->relation_to,
                'contact_type' => $this->contact_type,
                'date_of_contact' => $this->q_start_date,
                'q_start_date' => $this->q_start_date,
                'q_end_date' => $this->q_end_date,
                'surveillance_status' => $this->surveillance_status,
                'updated_by' => $this->updated_by,
                'updated_on' => $this->updated_on,
            );

            $sql2 = $this->db->where('person_id', $this->id)->where('person_from_id', $this->person_from_id)->set($data2)->get_compiled_update('t_cvd_ct_contact_tracing');
            // echo $sql2;
            $this->db->query($sql2);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        } else {
            return true;
        }
    }

    public function updatePositive()
    {
        $user = $this->session->userdata['user'];
        $this->updated_by = $user->iduser;
        $this->updated_on = date('Y-m-d H:i:s');
        if ($this->random_qr == '') {
            $this->load->helper('string');
            $this->random_qr = sha1(uniqid(random_string('alnum', 16)));
        }

        $data = array(
            'random_qr' => $this->random_qr,
            'dist_id' => $this->dist_id,
            'ps_id' => $this->ps_id,
            'p_type' => $this->p_type,
            'p_name' => $this->p_name,
            'age' => $this->age,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'address_area' => $this->address_area,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'lsg_id' => $this->lsg_id,
            'ward_id' => $this->ward_id,
            'q_location_type' => $this->q_location_type,
            'q_location_details' => $this->q_location_details,
            'c_zone' => $this->c_zone,
            'phc' => $this->phc,
            'symptoms' => $this->symptoms,
            'p_current_status' => $this->p_current_status,
            'surveillance_status' => $this->surveillance_status,
            'contact_tracing_status' => $this->contact_tracing_status,
            'updated_by' => $this->updated_by,
            'updated_on' => $this->updated_on
        );
        $sql1 = $this->db->where('id', $this->id)->set($data)->get_compiled_update('t_cvd_ct_persons');
        // echo $sql1;
        if ($this->db->query($sql1)) {
            return true;
        } else {
            return false;
        }
    }

    public function getPersonById()
    {
        $this->db->where('id', $this->id);
        $rs = $this->db->get('t_cvd_ct_persons');
        if ($rs->num_rows() == 1) {
            return $rs->row();
        } else {
            return false;
        }
    }

    public function getContactsByIds()
    {
        $this->db->where('person_id', $this->id);
        $this->db->where('person_from_id', $this->person_from_id);
        $this->db->where('status', 1);
        $rs = $this->db->get('t_cvd_ct_contact_tracing');
        if ($rs->num_rows() == 1) {
            return $rs->row();
        } else {
            return false;
        }
    }
}
