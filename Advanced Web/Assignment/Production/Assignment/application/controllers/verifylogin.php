<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 03/11/14
 * Time: 10:32
 */

class VerifyLogin extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model','',TRUE);
    }

    function index()
    {
        //This method will have the credentials validation
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

        if($this->form_validation->run() == FALSE)
        {
            //Field validation failed.  User redirected to login page
            $this->load->view('user/login');
        }
        else
        {
            //Go to private area
            redirect('index.php/user/calendar', 'refresh');
        }

    }

    function check_database($password)
    {
        //Field validation succeeded.  Validate against database
        $input = explode('@', $this->input->post('username')); //Split on @

        $username = $input[0]; //The part before @nhs.org
        //If $username contains a period(.) then get the initial & surname
        if (strpos($username, '.') !== false){
            list($initial, $surname) = explode('.', $username); //Split on period(.)
        }else{
            $initial = '';
            $surname = '';
        }


        //query the database
        $result = $this->user_model->login($username, $initial, $surname, $password);

        if($result)
        {
            $sess_array = array();
            foreach($result as $row)
            {
                $sess_array = array(
                    'userID' => $row->userID,
                    'forename' => $row->forename,
                    'surname' => $row->surname,
                    'staffID' => $row->staffID,
                    'levelID' => $row->levelID,
                    'isAdmin' => $row->isAdmin
                );
                $this->session->set_userdata('logged_in', $sess_array);
            }
            return TRUE;
        }
        else
        {
            $this->form_validation->set_message('check_database', 'Invalid username or password');
            return false;
        }
    }
}
?>