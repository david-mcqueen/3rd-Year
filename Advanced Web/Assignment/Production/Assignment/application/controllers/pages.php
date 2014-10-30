<?php

class Pages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_model');
    }

    public function view($page = 'home')
    {

        if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php'))
        {
            // Whoops, we don't have a page for that!
            show_404();
        }

        $data['title'] = ucfirst($page); // Capitalize the first letter

        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/footer', $data);

    }

    public function login(){


        $result = $this->page_model->checkLogin();
        $data['title'] = 'Welcome';

        if(!$result){
            //Login was successful
            $this->load->view('templates/header', $data);
            $this->load->view('shift/calendar', $data);
            $this->load->view('templates/footer', $data);
        }else{
            $this->load->view('templates/header', $data);
            $this->load->view('pages/home', $data);
            $this->load->view('templates/footer', $data);
        }
    }
}