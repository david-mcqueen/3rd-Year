<?php
/**
 * David McQueen
 * 10153465
 * December 2014
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
            //Login. User redirected to login page with error message
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
        //Check the database for a matching user

        $input = explode('@', $this->input->post('username')); //Split the username on @

        $username = $input[0]; //The part before @nhs.org

        //If $username contains a period(.) then get the initial & surname
        if (strpos($username, '.') !== false){
            list($initial, $surname) = explode('.', $username); //Split on period(.)
            $username = -1; //So the DB knows not to look for a match on userID
        }else{
            $initial = '';
            $surname = '';
        }

        //query the database
        $result = $this->user_model->login($username, $initial, $surname, $password);

        if($result)
        {
            //If successful, create a session to hold user data
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
            //Unsuccessful login
            return false;
        }
    }
}
?>