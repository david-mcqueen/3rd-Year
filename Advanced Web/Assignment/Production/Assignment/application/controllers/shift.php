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
        if($session_data = $this->session->userdata('logged_in')) {
            header("Content-Type: application/json");

            $userID = $session_data['userID'];
            $start = $this->input->get('start', FALSE);
            $end = $this->input->get('end', FALSE);
            $isAdmin = $session_data['isAdmin'];

            if ($isAdmin == 1) {
                $response = $this->shift_model->get_DataAll($start, $end);
                $editable = true;
            } else {
                $response = $this->shift_model->get_Data($start, $end, $userID);
                $editable = false;
            }

            $jsonevents = array();
            foreach ($response as $entry) {
                if ($isAdmin == 1) {
                    $title = $entry['levelName'] . ' ' . $entry['forename'];
                } else {
                    $title = $entry['levelName'] . ' ' . $entry['shiftNumbers'];
                    if ($entry['onShift'] == 1) {
                        $title = $title . ' ' . $session_data['forename'];
                    }
                }

                $jsonevents[] = array(
                    'id' => $entry['shiftID'],
                    'title' => $title,
                    'start' => $entry['shiftDate'],
                    'editable' => $editable,
                    'onShift' => $entry['onShift'],
                    'shiftDate' => $entry['shiftDate'],
                    'shiftUserName' => $entry['forename'],
                    'userID' => $entry['userID']
                );
            }
            echo json_encode($jsonevents);
        }
    }
    
    public function addShift(){
        if ($session_data = $this->session->userdata('logged_in')) {
            header("Content-Type: application/json");
            $userID = $session_data['userID'];
            $isAdmin = $session_data['isAdmin'];
            $start = $this->input->get('start', FALSE);
            $jsonevents = array();
            $shiftsWorking = 0;

            if ($isAdmin == 1) {
                $userID = $this->input->get('userID', FALSE);
            }else{
                //If the user is not admin. Check they are not already working too many shifts
                $time = strtotime($start);

                //If the day clicked is a monday, then use that date. Else use the previous Monday (Start of week).
                $weekStart = date('w', $time) == 1 ? date('Y-m-d', $time) : date('Y-m-d', strtotime('last Monday', $time));

                $weekEnd = date('Y-m-d', strtotime('this Sunday', $time));

                $shiftRules = $this->shift_model->countShiftsWeek($weekStart, $weekEnd, $userID);

            }
            foreach ($shiftRules as $count){
                $shiftsWorking =  $count['WeekShifts'];
            }

            if($shiftsWorking < 5){
                $result = $this->shift_model->add_shift($start, $userID, $isAdmin);
                foreach ($result as $newShift) {
                    $jsonevents[] = array(
                        'success' => $newShift['Success'],
                        'id' => '123',
                        'title' => 'New Shift',
                        'start' => $start,
                        'editable' => false
                    );
                }
            }else{
                $jsonevents[] = array(
                    'success' => false,
                    'id' => '123',
                    'title' => 'New Shift',
                    'start' => $start,
                    'editable' => false
                );
            }
            echo json_encode($jsonevents);
        }
    }


    public function removeShift()
    {
        if($session_data = $this->session->userdata('logged_in')) {
            $userID = $session_data['userID'];
            $isAdmin = $session_data['isAdmin'];
            $shiftID = $this->input->get('id', FALSE);
            $jsonevents = array();
            $coverNeeded = 0;
            $coverAvailable = 0;
            $errors = "";
            $results = $this->shift_model->countCoverNeeded($userID, $shiftID);

            foreach ($results as $result) {
                $coverNeeded = (int)$result['coverNeeded'];
                $coverAvailable = (int)$result['cover'];
            }

            // $coverAvailable is what the level would be without the shift being removed
            if ($isAdmin == 1) {
                $success = $this->shift_model->remove_shift($shiftID, $isAdmin);
            } else if ($coverAvailable >= $coverNeeded) {
                //There is enough cover to let the user remove their shift
                $success = $this->shift_model->remove_shift($shiftID, $isAdmin);
            } else {
                $success = false;
                $errors = "There is not enough cover for this shift";
            }

            $jsonevents[] = array(
                'success' => $success,
                'errors' => $errors,
                'coverNeeded' => $coverNeeded,
                'coverAvailable' => $coverAvailable
            );

            echo json_encode($jsonevents);
        }
    }

    public function remove_shiftDate(){
        if($session_data = $this->session->userdata('logged_in')){
            $isAdmin = $session_data['isAdmin'];
            if($isAdmin == 1){
                $shiftDetails = $this->input->GET(NULL, FALSE);
                $success =  $this->shift_model->remove_shiftDate($shiftDetails['shiftDate'], $shiftDetails['userID'], $isAdmin);
            }

            $jsonevents[] = array(
                'success' => $success
            );
            echo json_encode($jsonevents);
        }
    }

} 