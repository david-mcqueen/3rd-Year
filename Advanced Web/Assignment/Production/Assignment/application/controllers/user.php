<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 12:49
 */

class user extends CI_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        $data['user'] = $this->user_model->get_user();
        $data['title'] = 'User Data';

        $this->load->view('templates/header', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($userID)
    {
        $data['user_data'] = $this->user_model->get_user($userID);

        if (empty($data['user_data']))
        {
            show_404();
        }

        $data['title'] = $data['user_data']['forename'];

        $this->load->view('templates/header', $data);
        $this->load->view('user/view', $data);
        $this->load->view('templates/footer');
    }

} 