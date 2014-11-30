<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

session_start();
class user_model extends CI_Model{


    public function __construct()
    {
        $this->load->database();
    }

    public function login($username, $initial, $surname, $password){
        //Check the user credentials match an account in the DB
        $qry = 'call login(?,?,?,?);';
        $parameters = array($username, $initial, $surname, $password);
        $query = $this->db->query($qry, $parameters);

        //If a row is returned, then credentials are correct
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

    public function userSettingsUpdate($userSettings){
        //Update the users settings
        $sql = 'call user_edit(?,?,?,?,?,?,?,?,?,?)';
        $parameters = array($userSettings['userID'], $userSettings['password'], $userSettings['forename'], $userSettings['surname'], $userSettings['email'], $userSettings['phone'], $userSettings['address1'], $userSettings['address2'],$userSettings['city'], $userSettings['postcode']);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function userMessagesGet($userID){
        //Get all messages that need displaying to the user
        $sql = ('call user_messages(?);');
        $parameters = array($userID);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function userMessagesConfirm($userID, $deleted){
        //Mark the messages as read
        $sql = ('call user_messagesConfirm(?, ?);');
        $parameters = array($userID, $deleted);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function countUsersShifts($startDate, $endDate){
        //Count the amount of shifts each user working between 2 dates
        $sql = ('call countUserShifts(?,?);');
        $parameters = array($startDate, $endDate);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function usersGetAll(){
        //Get all users in the DB
        $sql = ('call user_getAll();');
        $query = $this->db->query($sql);

        return $query->result_array();
    }


}



