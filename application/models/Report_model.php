<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Report_model extends CI_Model
{

    var $emp_rank;
    var $emp_unit;
    var $subunit;
    var $subunits;
    var $dor;
    var $table = 't_employee';
    var $column_order = array(null, 'count(t_employee.id) total', 'unit.unit_name', 'm_post.post_desc'); //set column field database for datatable orderable
    var $column_search = array(); //set column field database for datatable searchable 
    var $order = array('unit.unit_name' => 'asc'); // default order 
    var $select = array('count(t_employee.id) total', 'unit.unit_name', 'm_post.post_desc');
    var $join1 = "unit.id = t_employee.unit_id";
    var $join2 = "m_post.id = t_employee.c_post";
    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        if ($this->select) {
            $this->db->select($this->select);
            $this->db->join('unit', $this->join1, 'left');
            $this->db->join('m_post', $this->join2, 'left');
            $this->db->group_by('unit.unit_name');
            $this->db->group_by('m_post.post_desc');
        }
        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($this->post_search['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->post_search['value']);
                } else {
                    $this->db->or_like($item, $this->post_search['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }

        if (isset($this->post_order)) { // here order processing
            $this->db->order_by($this->column_order[$this->post_order['0']['column']], $this->post_order['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }


        if ($this->emp_rank != '') {
            $this->db->where('c_post', $this->emp_rank);
        }
        if ($this->emp_unit != '') {
            if ($this->subunit == 'true') {
                $this->db->group_start();
                $this->db->where_in('unit_id', $this->subunits);
                $this->db->group_end();
            } else {

                $this->db->group_start();
                $this->db->where('unit_id', $this->emp_unit);
                $this->db->group_end();
            }
        }
    }

    private function _count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function serach_user_datatable()
    {

        $this->_get_datatables_query();
        if ($this->post_length != -1)
            $this->db->limit($this->post_length, $this->post_start);
        $rs = $this->db->get();
        $items = array();
        $n = 1;
        foreach ($rs->result_array() as $row) {
            $row['sl'] = $this->post_start + $n;
            array_push($items, $row);
            $n++;
        }
        $output = array(
            "draw" => $this->post_draw,
            "recordsTotal" => $this->_count_all(),
            "recordsFiltered" => $this->_count_filtered(),
            "data" => $items,
        );
        return $output;
    }

    public function getStaticalReportCsv($emp_rank, $unit_id)
    {
        $this->db->select('u.unit_name Unit,p.post_desc Rank,count(te.id) Total');
        $this->db->join('unit u', 'u.id=te.unit_id', 'left');
        $this->db->join('m_post p', 'p.id=te.c_post', 'left');
        if ($unit_id != '') {
            $this->db->where_in('te.unit_id', $unit_id);
        }
        if ($emp_rank != '') {
            $this->db->where('c_post', $emp_rank);
        }
        $this->db->order_by("u.unit_name", "desc");
        $this->db->group_by('u.unit_name');
        $this->db->group_by('p.post_desc');

        $rs = $this->db->get('t_employee te');

        return $rs;
    }

    public function getDataEntryCsv()
    {
        $this->db->select('unit.unit_short_code Units,
                sum(case when user.user_role=1 then 1 else 0 end) Registered,
                sum(case when user.profile_status=5 then 1 else 0 end)ProfileCompleted');

        $this->db->join('unit', 'unit.id=user.user_unit', 'left');
        $this->db->order_by('Registered', 'desc');
        $this->db->group_by('user.user_unit');
        $rs = $this->db->get('user');

        return $rs;
    }



    public function generateDataofDate()
    {
        $reportdate  =  $this->session->userdata('dateofreporting');

        $date = toYmd($reportdate);

        $sql = "SELECT pos.id,pos.decl_date,pos.dmo_patient_id,pri.unit_name,pos.p_name,
        pos.mobile,pos.age,pos.gender,pos.ward_name,pos.address,pos.q_location_type,pos.q_location_details,pos.q_start_date,
        pos.q_end_date,pos.c_zone,pri.p_name FROM 
        
        (SELECT t_cvd_ct_persons.id,decl_date,dmo_patient_id,ps_id,p_name,wards.ward_name,u.unit_name,
        mobile,age,address,gender,ward_id,q_location_type,q_location_details,q_start_date,q_end_date,c_zone,phc,symptoms,    
        p_current_status,surveillance_status,contact_tracing_status FROM t_cvd_ct_persons LEFT JOIN unit u ON t_cvd_ct_persons.ps_id=u.id
        LEFT JOIN m_cvd_ct_lsg_wards wards ON t_cvd_ct_persons.ward_id=wards.id WHERE t_cvd_ct_persons.status=1 AND p_type='P' AND u.dist=899 AND t_cvd_ct_persons.decl_date='2020-8-24'
        ) pos LEFT OUTER JOIN   (
        SELECT per.id,per.decl_date,trace.person_from_id,dmo_patient_id,trace.ps_id,p_name,trace.contact_type,wards.ward_name,u.unit_name,
        mobile,age,address,gender,ward_id,q_location_type,q_location_details,per.q_end_date,c_zone,phc,symptoms,
        p_current_status,trace.surveillance_status,contact_tracing_status ,trace.relation_to,trace.q_start_date,latitude,longitude
        FROM t_cvd_ct_persons per LEFT OUTER JOIN t_cvd_ct_contact_tracing trace ON per.id=trace.person_id
        LEFT JOIN unit u ON trace.ps_id=u.id
        LEFT JOIN m_cvd_ct_lsg_wards wards ON per.ward_id=wards.id
        WHERE per.STATUS=1 AND per.p_type='C' AND per.decl_date='2020-8-24' AND 
        u.dist=899 AND trace.status=1 AND trace.contact_type='P' 
        ) pri        ON pos.id=pri.person_from_id LEFT OUTER JOIN 
        (
        SELECT per.decl_date,trace.person_from_id,dmo_patient_id,trace.ps_id,p_name,trace.contact_type,wards.ward_name,u.unit_name,
        mobile,age,gender,address,ward_id,q_location_type,q_location_details,per.q_end_date,c_zone,phc,symptoms,
        p_current_status,trace.surveillance_status,contact_tracing_status ,trace.relation_to,trace.q_start_date,latitude,longitude
        FROM t_cvd_ct_persons per LEFT OUTER JOIN t_cvd_ct_contact_tracing trace ON per.id=trace.person_id
        LEFT JOIN unit u ON trace.ps_id=u.id
        LEFT JOIN m_cvd_ct_lsg_wards wards ON per.ward_id=wards.id
         WHERE per.STATUS=1 AND per.p_type='C' AND per.decl_date='2020-8-24' AND u.dist=899 AND 
         trace.status=1 AND trace.contact_type='S' ) sec ON pri.id=sec.person_from_id";



        $query = $this->db->query($sql);

        //echo $sql;
        return $query;
    }
}
