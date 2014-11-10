<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 13:10
 */
session_start();
class user_model extends CI_Model{


    public function __construct()
    {
        $this->load->database();
    }

    public function login($username, $initial, $surname, $password){
        $qry = 'call login(?,?,?,?);';
        $parameters = array($username, $initial, $surname, $password);
        $query = $this->db->query($qry, $parameters);

        if($query -> num_rows() == 1)
        {
            return $query->result();
        }
        else
        {
            return false;
        }
    }

    public function userSettingsGET($userID){
        //Function to get user settings / details
        $sql = 'call user_get(?);';
        $parameters = array($userID);
        $query = $this->db->query($sql, $parameters);
        return $query->result_array();

    }

    public function userSettingsUpdate($userID, $password, $forename, $surname, $email, $phone, $address1, $address2, $city, $postcode){

        $sql = 'call user_edit(?,?,?,?,?,?,?,?,?,?)';
        $parameters = array($userID, $password, $forename, $surname, $email, $phone, $address1, $address2, $city, $postcode);
        $query = $this->db->query($sql, $parameters);

        return $query->result();

    }

    public function userMessagesGet($userID){
        $sql = ('call user_messages(?);');
        $parameters = array($userID);
        $query = $this->db->query($sql, $parameters);
        return $query->result_array();
    }

    public function userMessageConfirm($userID){
        $sql = ('call user_messagesConfirm(?);');
        $parameters = array($userID);
        $query = $this->db->query($sql, $parameters);
        return $query->result_array();
    }

}



