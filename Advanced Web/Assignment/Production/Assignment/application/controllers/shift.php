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

    public function index()
    {
        $data['shift'] = $this->shift_model->get_shift();
        $data['title'] = 'Shift Data';

        $this->load->view('templates/header', $data);
        $this->load->view('shift/index', $data);
        $this->load->view('templates/footer');
    }

    public function view($userID)
    {
        //$data['shift_data'] = $this->shift_model->get_shift($userID);
        $data['shift'] = $this->shift_model->get_shift($userID);
        if (empty($data['shift']))
        {
            show_404();
        }

        $data['title'] = 'Shift Data';

        $this->load->view('templates/header', $data);
        $this->load->view('shift/view', $data);
        $this->load->view('templates/footer');
    }

    public function calendar($date){

        $data['events'] = $this->shift_model->get_Data($date, $date);
        $data['title'] = 'Calendar';

        $this->load->view('templates/header', $data);
        $this->load->view('shift/calendar', $data);
        $this->load->view('templates/footer');
    }

    public function ajaxCalendar(){
        $start = $this->input->get('start', FALSE);
        $end = $this->input->get('end', FALSE);

        header("Content-Type: application/json");
        echo $this->shift_model->get_Data($start, '2014-10-10');
    }

} 