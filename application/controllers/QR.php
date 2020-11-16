<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Qr extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function home()
    {
        $this->has_permission('qr-gen');
        $this->load->model('Unit_model', 'UM');
        $this->load->model('Master_model', 'MM');
        $data['ps'] = json_encode($this->UM->psByUserQ());
        $data['lsg'] = json_encode($this->MM->listLsg());
        $this->load->view('qr/home', $data);
    }



    public function contactsList()
    {
       // $this->output->enable_profiler(TRUE);
        //$this->has_permission('contacts-mgt');

        $this->form_validation->set_rules('id', 'ID', 'trim|integer');
        $this->form_validation->set_rules('name', 'Name', 'trim|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('gender', 'gender', 'trim|in_list[M,F,T]');
        $this->form_validation->set_rules('mobile', 'mobile', 'trim|integer');
        $this->form_validation->set_rules('address', 'address', 'trim');
        $this->form_validation->set_rules('surveillance_status', 'Surveillance', 'trim|in_list[Y,N]');
        $this->form_validation->set_rules('ps_id', 'PS', 'trim|integer');
        $this->form_validation->set_rules('lsg_id', 'LSG', 'trim|integer');
        $this->form_validation->set_rules('ward_id', 'Ward', 'trim|integer');
        $this->form_validation->set_rules('decl_date', 'Date', 'trim|valid_date');


        if ($this->form_validation->run()) {

            $this->load->model('Qr_model', 'PM');

            $this->PM->p_type = 'C';
            $this->PM->id = $this->input->post('id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->decl_date=toYmd($this->input->post('decl_date'));

            $this->PM->surveillance_status = $this->input->post('surveillance_status');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');


            $this->PM->post_search = $this->input->post('search');
            $this->PM->post_order = $this->input->post('order');
            $this->PM->post_length = $this->input->post('length');
            $this->PM->post_start = $this->input->post('start');
            $this->PM->post_draw = $this->input->post('draw');
            if ($result = $this->PM->qr_list_datatable()) {
                $this->output->set_output(json_encode($result));
            }
        } else {
            echo validation_errors();
        }
    }
    public function linkcontactsList()
    {
        //$this->output->enable_profiler(TRUE);
        //$this->has_permission('contacts-mgt');

        $this->form_validation->set_rules('id', 'ID', 'trim|integer|required');
        $this->form_validation->set_rules('random_qr', 'QR', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('mobile', 'mobile', 'trim|integer');
        $this->form_validation->set_rules('address', 'address', 'trim');        


        if ($this->form_validation->run()) {

            $this->load->model('Qr_model', 'QM');

            $this->QM->p_type = 'C';
            $this->QM->id = $this->input->post('id');
            $this->QM->random_qr = $this->input->post('random_qr');
            $this->QM->p_name = $this->input->post('name');        
            $this->QM->mobile = $this->input->post('mobile');
            $this->QM->address = $this->input->post('address');

            $this->QM->post_search = $this->input->post('search');
            $this->QM->post_order = $this->input->post('order');
            $this->QM->post_length = $this->input->post('length');
            $this->QM->post_start = $this->input->post('start');
            $this->QM->post_draw = $this->input->post('draw');
            if ($result = $this->QM->qr_linklist_datatable()) {
                $this->output->set_output(json_encode($result));
            }
        } else {
            echo validation_errors();
        }
    }

    public function FrmLinkContact()
    {
        $id =  $this->input->post('id');
        if ($id > 0 && $this->input->post('random_qr')!='') {
            $p['id'] = $this->input->post('id');
            $p['random_qr'] = $this->input->post('random_qr');
            $this->load->view('qr/frm_link_contact', $p);
        }
    }

    public function setQRLink()
    {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        $this->form_validation->set_rules('from_id', 'From ID', 'trim|required|integer');



        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');



            //////////////////////////////////////////////////////////////
            $this->PM->id = $this->input->post('from_id');
            $lp = $this->PM->getPersonById();
            $this->PM->id = $this->input->post('id');
            $this->PM->random_qr = $lp->random_qr;
            $this->PM->longitude = $lp->longitude;
            $this->PM->latitude = $lp->latitude;
            

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->linkQRContact()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Tagged";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function addContact()
    {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('parent', 'Parent Contact', 'trim|required|integer');
        $this->form_validation->set_rules('relation_to', 'Relation', 'trim|required|in_list[Family,Relative,Neighbour,Colleague,Outsider]');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('ps_id', 'PS', 'trim|integer');
        $this->form_validation->set_rules('contact_type', '', 'trim|required|in_list[P,S]');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');
        $this->form_validation->set_rules('mobile', '', 'trim|required|integer');
        $this->form_validation->set_rules('address', '', 'trim|required');
        $this->form_validation->set_rules('address_area', '', 'trim|required');
        $this->form_validation->set_rules('latitude', '', 'trim|decimal');
        $this->form_validation->set_rules('longitude', '', 'trim|decimal');
        $this->form_validation->set_rules('lsg_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('ward_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('q_location_type', '', 'trim|required|in_list[HQ,IQ,CFLTC,HOSPITAL]');
        $this->form_validation->set_rules('q_location_details', '', 'trim|required');
        $this->form_validation->set_rules('q_start_date', '', 'trim|required|valid_date');
        $this->form_validation->set_rules('c_zone', '', 'trim|required|in_list[CCZ,CZ,NCZ]');
        $this->form_validation->set_rules('symptoms', '', 'trim|required|in_list[Y,N]');
        $this->form_validation->set_rules('p_current_status', '', 'trim|required|in_list[A,D]');


        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'C';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->q_start_date = toYmd($this->input->post('q_start_date'));
            $date = new DateTime($this->PM->q_start_date);
            $date->add(new DateInterval('P13D'));
            $this->PM->q_end_date = $date->format('Y-m-d');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = 'Y';
            $this->PM->contact_tracing_status = 1;

            $this->PM->relation_to = $this->input->post('relation_to');
            $this->PM->person_from_id = $this->input->post('parent');
            $this->PM->contact_type = $this->input->post('contact_type');

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->add()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Tagged";
                $this->session->unset_userdata('person');
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function editTracingCompleted()
    {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        $this->form_validation->set_rules('person_from_id', 'From ID', 'trim|required|integer');
        $this->form_validation->set_rules('relation_to', 'Relation', 'trim|required|in_list[Family,Relative,Neighbour,Colleague,Outsider]');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('ps_id', 'PS', 'trim|integer');
        $this->form_validation->set_rules('contact_type', '', 'trim|required|in_list[P,S]');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');
        $this->form_validation->set_rules('mobile', '', 'trim|required|integer');
        $this->form_validation->set_rules('address', '', 'trim|required');
        $this->form_validation->set_rules('address_area', '', 'trim|required');
        $this->form_validation->set_rules('latitude', '', 'trim|decimal');
        $this->form_validation->set_rules('longitude', '', 'trim|decimal');
        $this->form_validation->set_rules('lsg_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('ward_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('q_location_type', '', 'trim|required|in_list[HQ,IQ,CFLTC,HOSPITAL]');
        $this->form_validation->set_rules('q_location_details', '', 'trim|required');
        $this->form_validation->set_rules('q_start_date', '', 'trim|required|valid_date');
        $this->form_validation->set_rules('c_zone', '', 'trim|required|in_list[CCZ,CZ,NCZ]');
        $this->form_validation->set_rules('symptoms', '', 'trim|required|in_list[Y,N]');
        $this->form_validation->set_rules('p_current_status', '', 'trim|required|in_list[A,D]');


        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->id = $this->input->post('id');
            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'C';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->q_start_date = toYmd($this->input->post('q_start_date'));
            $date = new DateTime($this->PM->q_start_date);
            $date->add(new DateInterval('P13D'));
            $this->PM->q_end_date = $date->format('Y-m-d');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = $this->input->post('surveillance_status');
            $this->PM->contact_tracing_status = 1;

            $this->PM->person_from_id = $this->input->post('person_from_id');
            $this->PM->relation_to = $this->input->post('relation_to');
            $this->PM->contact_type = $this->input->post('contact_type');


            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function editPositiveCompleted()
    {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('ps_id', 'PS', 'trim|integer');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');
        $this->form_validation->set_rules('mobile', '', 'trim|required|integer');
        $this->form_validation->set_rules('address', '', 'trim|required');
        $this->form_validation->set_rules('address_area', '', 'trim|required');
        $this->form_validation->set_rules('latitude', '', 'trim|decimal');
        $this->form_validation->set_rules('longitude', '', 'trim|decimal');
        $this->form_validation->set_rules('lsg_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('ward_id', '', 'trim|required|integer');
        $this->form_validation->set_rules('q_location_type', '', 'trim|required|in_list[HQ,IQ,CFLTC,HOSPITAL]');
        $this->form_validation->set_rules('q_location_details', '', 'trim|required');
        $this->form_validation->set_rules('c_zone', '', 'trim|required|in_list[CCZ,CZ,NCZ]');
        $this->form_validation->set_rules('symptoms', '', 'trim|required|in_list[Y,N]');
        $this->form_validation->set_rules('p_current_status', '', 'trim|required|in_list[A,D]');


        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->id = $this->input->post('id');
            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'P';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = $this->input->post('surveillance_status');
            $this->PM->contact_tracing_status = 1;

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->updatePositive()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }

    public function savePositive()
    {

        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');



        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->id = $this->input->post('id');
            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'P';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = $this->input->post('surveillance_status');
            $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->updatePositive()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }


    public function saveContacts()
    {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('parent', 'Parent Contact', 'trim|required|integer');
        $this->form_validation->set_rules('relation_to', 'Relation', 'trim|required|in_list[Family,Relative,Neighbour,Colleague,Outsider]');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('contact_type', '', 'trim|required|in_list[P,S]');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');

        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'C';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->q_start_date = toYmd($this->input->post('q_start_date'));
            $date = new DateTime($this->PM->q_start_date);
            $date->add(new DateInterval('P13D'));
            $this->PM->q_end_date = $date->format('Y-m-d');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = 'Y';
            $this->PM->contact_tracing_status = 0;

            $this->PM->relation_to = $this->input->post('relation_to');
            $this->PM->person_from_id = $this->input->post('parent');
            $this->PM->contact_type = $this->input->post('contact_type');

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->add()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Saved";
                $this->session->unset_userdata('person');
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }


    public function saveEditedContact()
    {
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";

        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');
        $this->form_validation->set_rules('person_from_id', 'From ID', 'trim|required|integer');
        $this->form_validation->set_rules('parent', 'Parent Contact', 'trim|required|integer');
        $this->form_validation->set_rules('relation_to', 'Relation', 'trim|required|in_list[Family,Relative,Neighbour,Colleague,Outsider]');
        $this->form_validation->set_rules('dist_id', 'District', 'trim|required|integer');
        $this->form_validation->set_rules('contact_type', '', 'trim|required|in_list[P,S]');
        $this->form_validation->set_rules('name', '', 'trim|required|regex_match[/^[a-zA-Z\s\.]+$/]');
        $this->form_validation->set_rules('age', '', 'trim|required|greater_than[0]|less_than[151]');
        $this->form_validation->set_rules('gender', '', 'trim|required|in_list[M,F,T]');

        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');


            //////////////////////////////////////////////////////////////

            $this->PM->id = $this->input->post('id');
            $this->PM->random_qr = $this->input->post('random_qr');
            $this->PM->dist_id = $this->input->post('dist_id');
            $this->PM->ps_id = $this->input->post('ps_id');
            $this->PM->p_name = $this->input->post('name');
            $this->PM->p_type = 'C';
            $this->PM->age = $this->input->post('age');
            $this->PM->gender = $this->input->post('gender');
            $this->PM->mobile = $this->input->post('mobile');
            $this->PM->address = $this->input->post('address');
            $this->PM->address_area = $this->input->post('address_area');
            $this->PM->latitude = $this->input->post('latitude');
            $this->PM->longitude = $this->input->post('longitude');
            $this->PM->lsg_id = $this->input->post('lsg_id');
            $this->PM->ward_id = $this->input->post('ward_id');
            $this->PM->q_location_type = $this->input->post('q_location_type');
            $this->PM->q_location_details = $this->input->post('q_location_details');
            $this->PM->q_start_date = toYmd($this->input->post('q_start_date'));
            $date = new DateTime($this->PM->q_start_date);
            $date->add(new DateInterval('P13D'));
            $this->PM->q_end_date = $date->format('Y-m-d');
            $this->PM->c_zone = $this->input->post('c_zone');
            $this->PM->phc = $this->input->post('phc');
            $this->PM->symptoms = $this->input->post('symptoms');
            $this->PM->p_current_status = $this->input->post('p_current_status');
            $this->PM->surveillance_status = $this->input->post('surveillance_status');
            $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');

            $this->PM->person_from_id = $this->input->post('person_from_id');
            $this->PM->relation_to = $this->input->post('relation_to');
            $this->PM->contact_type = $this->input->post('contact_type');

            // $this->PM->contact_tracing_status = $this->input->post('contact_tracing_status');           

            /////////////////////////////////////////////////////////////


            if ($this->PM->update()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully Updated";
            }
        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));
    }
    public function useDetails()
    {
        $result['success'] = FALSE;
        $result['msg'] = "Error copying data";
        $id = (int) $this->input->post('id');
        if ($id > 0) {
            $this->load->model('Persons_model', 'PM');
            $this->PM->id = $id;
            if ($this->PM->setDetails()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully copied";
            }
        }
        $this->output->set_output(json_encode($result));
    }
    public function unsetDetails()
    {
        $this->output->enable_profiler(TRUE);
        $result['success'] = FALSE;
        $result['msg'] = "Error removing data";
        $this->session->unset_userdata('person');
        if ($this->session->userdata('person') == null) {
            $result['success'] = TRUE;
            $result['msg'] = "Copied details successfully removed";
        }
        $this->output->set_output(json_encode($result));
    }


    ///////////////////////////////////////

    public function pdf()
    {
        $this->load->library('ciqrcode');

        header("Content-Type: image/png");
        $params['data'] = 'This is a text to encode become QR Code';
        $this->ciqrcode->generate($params);
    }

    public function downloadQr()
    {
        $this->form_validation->set_rules('id[]', "ID", "trim|required|numeric");

        if ($this->form_validation->run() && count($this->input->post('id[]')) <= 20) {

            $this->load->model('CT_model', 'CM');
            $this->CM->ids = $this->input->post('id[]');
            if ($qr = $this->CM->getQRbyIds()) {

                $this->load->helper('pdf_helper');
                $data['q'] = $qr;
                $this->load->view('qr/qr_pdf', $data);

            }
            //////////////////////////////////////////////////////

        } else {
            echo validation_errors();
        }
    }

    public function regenerateQR(){
        $result['success'] = FALSE;
        $result['msg'] = "Error saving data";
        $this->form_validation->set_rules('id', 'ID', 'trim|required|integer');

        if ($this->form_validation->run()) {
            $this->load->model('Persons_model', 'PM');
            $this->PM->id = $this->input->post('id');

            if ($this->PM->regenerateQR()) {
                $result['success'] = TRUE;
                $result['msg'] = "Successfully gereated";
            }

        } else {
            $result['msg'] = validation_errors();
        }
        $this->output->set_output(json_encode($result));

    }
}
