<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 30/10/14
 * Time: 13:38
 */

class page_model extends CI_Model{

    public function __construct()
    {
        $this->load->database();
    }

    public function checkLogin(){
        $username = $this->input->post('userName');
        if(strcmp($username, 'David')){
            return true;
        }
        return false;
    }
} 