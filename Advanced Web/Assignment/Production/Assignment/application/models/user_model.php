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

    public function userSettingsUpdate($userSettings){
        $sql = 'call user_edit(?,?,?,?,?,?,?,?,?,?)';
        $parameters = array($userSettings['userID'], $userSettings['password'], $userSettings['forename'], $userSettings['surname'], $userSettings['email'], $userSettings['phone'], $userSettings['address1'], $userSettings['address2'],$userSettings['city'], $userSettings['postcode']);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function userMessagesGet($userID){
        $sql = ('call user_messages(?);');
        $parameters = array($userID);
        $query = $this->db->query($sql, $parameters);
        return $query->result_array();
    }

    public function userMessagesConfirm($userID, $deleted){
        $sql = ('call user_messagesConfirm(?, ?);');
        $parameters = array($userID, $deleted);
        $query = $this->db->query($sql, $parameters);

        return $query->result_array();
    }

    public function countUsersShifts($startDate, $endDate){
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



