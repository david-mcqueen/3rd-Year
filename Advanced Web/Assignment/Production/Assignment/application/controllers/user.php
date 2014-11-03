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

    public function calendar($date){
        $data['events'] = $this->user_model->get_Data($date, $date);
        $data['title'] = 'Calendar';

        $this->load->view('templates/header', $data);
        $this->load->view('user/calendar', $data);
        $this->load->view('templates/footer');
    }

    public function login(){
        $data['title'] = 'Login';

        $this->load->view('templates/header', $data);
        $this->load->view('user/login');
        $this->load->view('templates/footer');
    }

} 