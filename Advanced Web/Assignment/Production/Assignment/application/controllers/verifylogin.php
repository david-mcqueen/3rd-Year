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
        //Check if the credentials supplied match a user

        $password = $this->input->POST('password', FALSE); //Get the supplied password
        $success = $this->check_database($password);

        if($success == FALSE)
        {
            //Password validation failed. User redirected to login page
            $data['errors'] = 'Invalid username or password';
            $this->load->view('user/login', $data);
        }
        else
        {
            //Logged in successfully. Proceed to calendar
            redirect('index.php/user/calendar', 'refresh');
        }
    }

    function check_database($password)
    {
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
            $session_array = array();
            foreach($result as $row)
            {
                $session_array = array(
                    'userID' => $row->userID,
                    'forename' => $row->forename,
                    'surname' => $row->surname,
                    'staffID' => $row->staffID,
                    'levelID' => $row->levelID,
                    'isAdmin' => $row->isAdmin
                );
                $this->session->set_userdata('logged_in', $session_array);
            }
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>