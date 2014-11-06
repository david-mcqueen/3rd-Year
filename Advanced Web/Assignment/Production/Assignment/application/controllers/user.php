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

            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            $this->load->view('user/settings', $data);
            $this->load->view('templates/footer');
        }else{
            //If no session, redirect to login page
            //redirect('user/login', 'refresh');
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
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('home', 'refresh');
    }

    public function calendar(){

        if($this->session->userdata('logged_in'))
        {
            $session_data = $this->session->userdata('logged_in');
            $data['forename'] = $session_data['forename'];
            $data['surname'] = $session_data['surname'];
            $data['userID'] = $session_data['userID'];
            $data['title'] = 'Calendar';

            $this->load->view('templates/header', $data);
            $this->load->view('templates/userBar', $data);
            $this->load->view('user/calendar', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            //If no session, redirect to login page
            redirect('index.php/user/login', 'refresh');
        }
    }

    public function login(){
        $data['title'] = 'Login';

        $this->load->helper(array('form'));
        $this->load->view('templates/header', $data);
        $this->load->view('user/login');
        $this->load->view('templates/footer');
    }

} 