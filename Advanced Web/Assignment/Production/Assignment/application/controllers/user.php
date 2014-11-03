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
        calendar();
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
            $data['title'] = 'Calendar';

            $this->load->view('templates/header', $data);
            $this->load->view('user/calendar', $data);
            $this->load->view('templates/footer');
        }
        else
        {
            //If no session, redirect to login page
            redirect('user/login', 'refresh');
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