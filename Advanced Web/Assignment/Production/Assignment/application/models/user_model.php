<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 12:46
 */

class user_model extends CI_Model{

    public function __construct()
    {
        $this->load->database();
    }

    public function get_user($userID = 0){
        if ($userID == 0){
            $query = $this->db->get('users');
            return $query->result_array();
        }

        //$query = $this->db->get_where('users', array('userID' => $userID));
        $query = $this->db->query('call user_get(' . $userID .')');
        return $query->row_array();
    }
} 