<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

class user extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    function settingsDisplay($messages = ''){
        //Display the current users settings
        if($session_data = $this->session->userdata('logged_in')){

            $userID = $session_data['userID'];

            $user = $this->user_model->userSettingsGET($userID);

            $data['userID'] = $session_data['userID'];
            $data['forename'] = $session_data['forename'];
            $data['surname'] = $session_data['surname'];
            $data['title'] = 'Settings';

            //Pass any messages to the view. Messages will be success or failure for Update
            $data['messages'] = $messages;

            foreach ($user as $setting){
                $data['forename'] = $setting['forename'];
                $data['surname'] = $setting['surname'];
                $data['levelName'] = $setting['levelName'];
                $data['staffID'] = $setting['staffID'];
                $data['emailAddress'] = $setting['emailAddress'];
                $data['phoneNumber'] = $setting['phoneNumber'];
                $data['address1'] = $setting['address1'];
                $data['address2'] = $setting['address2'];
                $data['city'] = $setting['city'];
                $data['postcode'] = $setting['postcode'];
            }

            $this->load->library('javascript');
            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            $this->load->view('user/settings', $data);
            $this->load->view('templates/footer');
        }else{
            //If no session, redirect to login page
            redirect('', 'refresh');
        }
    }

    function settingsUpdate(){
        //Update the users settings
        if($session_data = $this->session->userdata('logged_in')) {

            $userID = $session_data['userID'];
            //Regex which the password must match
            $passwordPattern = "/(?=^.{7,}$)((?=.*\d))(?=.*[A-Z])(?=.*[a-z]).*$/";

            //Get all of the POST user settings into an array
            $newSettings = $this->input->post(NULL, FALSE);

            $newPassword = $newSettings['password'];
            $newPasswordConfirm = $newSettings['confirmPassword'];

            //Check that the new password matches the refex
            $validPassword = preg_match($passwordPattern, $newPassword);

            //Check that the new password matches the confirmPassword
            $passwordsMatch = strcmp($newPassword, $newPasswordConfirm);

            //If the password passes validation, the passwords match
            //OR if a password is not being changed
            if(($validPassword == 1 && $passwordsMatch == 0)
                || strcmp($newPassword, '') == 0)
            {
                $userSettings = array(
                    'userID' => $userID,
                    'password' => $newSettings['password'],
                    'forename' => $newSettings['forename'],
                    'surname' => $newSettings['surname'],
                    'email' => $newSettings['emailAddress'],
                    'phone' => $newSettings['phoneNumber'],
                    'address1' => $newSettings['address1'],
                    'address2' => $newSettings['address2'],
                    'city' => $newSettings['city'],
                    'postcode' => $newSettings['postcode']
                );
                $this->user_model->userSettingsUpdate($userSettings);
                $result = 'Success';
            }else{
                $result = 'Validation Failed! Please check details and try again';
            }

            //Load up the settings page, passing the success / failed message
            $this->settingsDisplay($result);

        }else{
            //If no session, redirect to login page
            redirect('', 'refresh');
        }
    }

    function logout()
    {
        //Logs the user out and redirects them back to the login screen
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('', 'refresh');
    }

    public function login(){
        $data['title'] = 'Login';

        $this->load->helper(array('form'));
        $this->load->view('user/login');
    }

    public function confirmMessages(){
        //The user has 'read' the messages.
        //Flag as 'read' so they don't show again.
        if($session_data = $this->session->userdata('logged_in')){

            $userID = $session_data['userID'];
            $deleted = $this->input->get('deleted', FALSE);
            $onfirmResponse = $this->user_model->userMessagesConfirm($userID, $deleted);

            $jsonevents[] = array(
                'success' => $onfirmResponse,
                'deleted' => $deleted
            );
            echo json_encode($jsonevents);
        }
    }

    public function countUsersShifts(){
        //Count the shifts worked by each staff, between 2 dates (a week)
        if ($session_data = $this->session->userdata('logged_in')) {
            $isAdmin = $session_data['isAdmin'];
            $jsonevents[] = array();

            if($isAdmin == 1){

                $dates = $this->input->get(NULL, FALSE);
                $results =  $this->user_model->countUsersShifts($dates['start'], $dates['finish']);

                foreach ($results as $countShift) {
                    $jsonevents[] = array(
                        'userID' => $countShift['userID'],
                        'forename' => $countShift['forename'],
                        'surname' => $countShift['surname'],
                        'shiftsCount' => $countShift['shiftsCount']
                    );
                }
            }
            echo json_encode($jsonevents);
        }
    }

    public function calendar(){
        //Display the main calendar page
        if($session_data = $this->session->userdata('logged_in'))
        {
            $isAdmin = $session_data['isAdmin'];
            $userID = $session_data['userID'];
            $data['forename'] = $session_data['forename'];
            $data['surname'] = $session_data['surname'];
            $data['userID'] = $userID;
            $data['levelID'] = $session_data['levelID'];
            $data['isAdmin'] = $isAdmin;
            $data['title'] = 'Calendar';
            $data['users'] = 'Calendar';

            if($isAdmin == 0){
                //Get messages to display to the user
                $userMessages = $this->user_model->userMessagesGet($userID);
                $data['userMessages'] = $userMessages;
            }
            else{
                //Get a list of all users in the DB
                $data['users'] = $this->user_model->usersGetAll();
                $data['userMessages'] = '';
            }

            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            if($isAdmin == 1){
                //Load the Admin functionality
                $this->load->view('user/calendarAdminScript', $data);
                $this->load->view('user/calendarAdmin', $data);
            }else{
                //Load the standard user functionality
                $this->load->view('user/calendarStandardUser', $data);
            }

            $this->load->view('user/calendar', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            //If no session, redirect to login page
            redirect('', 'refresh');
        }
    }

} 