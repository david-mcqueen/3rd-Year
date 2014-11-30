<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

class shift extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('shift_model');
    }

    public function getCalendarEvents(){
        //Get all events between 2 dates
        if($session_data = $this->session->userdata('logged_in')) {
            header("Content-Type: application/json");

            $userID = $session_data['userID'];
            $start = $this->input->get('start', FALSE);
            $end = $this->input->get('end', FALSE);
            $isAdmin = $session_data['isAdmin'];
            $jsonevents = array();

            if ($isAdmin == 1) {
                $response = $this->shift_model->getEvents_AllUsers($start, $end);
                $editable = true;
            } else {
                $response = $this->shift_model->getEvents_User($start, $end, $userID);
                $editable = false;
            }

            foreach ($response as $entry) {
                if ($isAdmin == 1) {
                    //If Admin: Display user name next to shifts worked
                    $title = $entry['levelName'] . ' ' . $entry['forename'];
                } else {
                    //If not Admin: Display shift count & level, unless the user is working
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
                    'userID' => $entry['userID'],
                    'color' => ($entry['onShift'] && $isAdmin == 0) ? 'green' : 'default'
                );
            }
            echo json_encode($jsonevents);
        }
    }
    
    public function addShift(){
        //Add a new shift
        if ($session_data = $this->session->userdata('logged_in')) {
            header("Content-Type: application/json");

            //Use the logged in sessionID
            $userID = $session_data['userID'];
            $isAdmin = $session_data['isAdmin'];
            $start = $this->input->get('start', FALSE);
            $jsonevents = array();
            $shiftsWorking = 0;

            if ($isAdmin == 1) {
                //Admin is adding shift. Dont do any checks
                //Use the ID provided in the request
                $userID = $this->input->get('userID', FALSE);
            }else{
                //If the user is not admin. Check they are not already working too many shifts for the week
                $time = strtotime($start);

                //If the day clicked is a monday, then use that date. Else use the previous Monday (Start of week).
                $weekStart = date('w', $time) == 1 ? date('Y-m-d', $time) : date('Y-m-d', strtotime('last Monday', $time));
                $weekEnd = date('Y-m-d', strtotime('this Sunday', $time));

                //Count how many shifts user is working for the given week
                $shiftRules = $this->shift_model->countUserShifts_Week($weekStart, $weekEnd, $userID);

                foreach ($shiftRules as $count){
                    $shiftsWorking =  $count['WeekShifts'];
                }
            }

            if($shiftsWorking < 5){
                //The user is not working 5 or more shifts. Add the new shift
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


    public function removeShift_shiftID(){
        //Remove a shift by shiftID
        if($session_data = $this->session->userdata('logged_in')) {
            $userID = $session_data['userID'];
            $isAdmin = $session_data['isAdmin'];
            $shiftID = $this->input->get('id', FALSE);
            $jsonevents = array();
            $coverNeeded = 0;
            $coverAvailable = 0;
            $errors = "";

            if ($isAdmin == 0){
                //Determine how much cover is needed for the users level
                $results = $this->shift_model->countCoverNeeded($userID, $shiftID);

                foreach ($results as $result) {
                    $coverNeeded = (int)$result['coverNeeded'];
                    $coverAvailable = (int)$result['cover'];
                }
            }

            // $coverAvailable is what the level would be without the shift being removed
            if ($isAdmin == 1) {
                $success = $this->shift_model->removeShift_shiftID($shiftID, $isAdmin);
            } else if ($coverAvailable > $coverNeeded) {
                //There is enough cover to let the user remove their shift
                $success = $this->shift_model->removeShift_shiftID($shiftID, $isAdmin);
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

    public function removeShift_userID(){
        //Remove a shift for a specific user, on a specific date
        if($session_data = $this->session->userdata('logged_in')){
            $isAdmin = $session_data['isAdmin'];
            //Only admin can remove shifts by userID
            if($isAdmin == 1){
                $shiftDetails = $this->input->GET(NULL, FALSE);
                $success =  $this->shift_model->removeShift_userID($shiftDetails['shiftDate'], $shiftDetails['userID'], $isAdmin);
            }

            $jsonevents[] = array(
                'success' => $success
            );

            echo json_encode($jsonevents);
        }
    }

} 