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
        $session_data = $this->session->userdata('logged_in');
        $userID = $session_data['userID'];
        $start = $this->input->get('start', FALSE);
        $end = $this->input->get('end', FALSE);

        header("Content-Type: application/json");
        $response = $this->shift_model->get_Data($start, $end, $userID);

        $jsonevents = array();
        foreach ($response as $entry)
        {
            $title = $entry['levelName'] . ' ' . $entry['shiftNumbers'];
            if ($entry['onShift'] == 1){
                $title = $title . ' ' . $session_data['forename'];
            }
            $jsonevents[] = array(
                'id' => $entry['shiftID'],
                'title' =>  $title,
                'start' => $entry['shiftDate'],
                'editable' => false,
                'onShift' => $entry['onShift'],
                'shiftDate' => $entry['shiftDate']
            );
        }
        echo json_encode($jsonevents);
    }
    
    public function addShift(){
        $session_data = $this->session->userdata('logged_in');
        $userID = $session_data['userID'];
        $start = $this->input->get('start', FALSE);
        echo $this->shift_model->add_shift($start, $userID);
    }

    public function removeShift(){
        $session_data = $this->session->userdata('logged_in');
        $userID = $session_data['userID'];
        $shiftID = $this->input->get('id', FALSE);
        echo $this->shift_model->remove_shift($shiftID, $userID);
    }
} 