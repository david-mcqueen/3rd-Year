<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 13:10
 */

class shift extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shift_model');
    }

    public function ajaxCalendar(){
        $start = $this->input->get('start', FALSE);
        $end = $this->input->get('end', FALSE);

        header("Content-Type: application/json");
        echo $this->shift_model->get_Data($start, $end);
    }
    
    public function addShift(){
        $session_data = $this->session->userdata('logged_in');
        $userID = $session_data['userID'];
        $start = $this->input->get('start', FALSE);
        echo $this->shift_model->add_shift($start, $userID);
    }
} 