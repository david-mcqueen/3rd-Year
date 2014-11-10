<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 13:10
 */

class user extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    function index()
    {
        if(!$this->session->userdata('logged_in'))
        {
            //If no session, redirect to login page
            //redirect('user/login', 'refresh');
        }

    }

    function settings(){
        if($session_data = $this->session->userdata('logged_in')){

            $userID = $session_data['userID'];

            $user = $this->user_model->userSettingsGET($userID);
            $data['userID'] = $session_data['userID'];
            $data['forename'] = $session_data['forename'];
            $data['surname'] = $session_data['surname'];
            $data['title'] = 'Settings';

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
            redirect('user/login', 'refresh');
        }
    }

    function updateSettings(){
        if($session_data = $this->session->userdata('logged_in')){

            $userID = $session_data['userID'];
            //Get all of the POST user settings into an array
            $newSettings = $this->input->post(NULL, FALSE);

            $this->user_model->userSettingsUpdate($userID, $newSettings['password'], $newSettings['forename'], $newSettings['surname'], $newSettings['emailAddress'], $newSettings['phoneNumber'], $newSettings['address1'], $newSettings['address2'], $newSettings['city'], $newSettings['postcode']);


            $data['userID'] = $userID;
            $data['forename'] = $session_data['forename'];
            $data['surname'] = $session_data['surname'];
            $data['title'] = $newSettings['password'];

            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            $this->load->view('user/success', $data);

            $this->load->view('templates/footer');
        }
    }

    function logout()
    {
        //Logs the user out and redirects them back to the login screen,

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

    public function calendar(){

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

            if($isAdmin == 0){
                $userMessages = $this->user_model->userMessagesGet($userID);
                $data['userMessages'] = $userMessages;
            }else{
                $data['userMessages'] = '';
            }

            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            if($isAdmin == 1){
                $this->load->view('user/calendarAdmin', $data);
            }else{
                $this->load->view('user/calendarStandardUser', $data);
            }

            $this->load->view('user/calendar', $data);

            $this->load->view('templates/footer');
        }
        else
        {
            //If no session, redirect to login page
            redirect('index.php/user/login', 'refresh');
        }
    }

} 