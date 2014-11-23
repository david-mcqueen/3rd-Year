<?php
/**
 * David McQueen
 * 10153465
 * December 2014
 */

class shift_model extends CI_Model{


    public function __construct()
    {
        $this->load->database();
    }

    public function getEvents_User($start, $end, $userID){
        // Return calendar events between $start and $end, for the supplied $userID
        $qry = ('call getEvents_User(?,?,?);');
        $parameters = array($start, $end, $userID);
        $query = $this->db->query($qry, $parameters);

        return $query->result_array();
    }

    public function getEvents_AllUsers($start, $end){
        // Return calendar events between $start and $end for all users
        $qry = ('call getEvents_AllUsers(?,?);');
        $parameters = array($start, $end);
        $query = $this->db->query($qry, $parameters);

        return $query->result_array();
    }

    public function add_shift($start, $userID, $isAdmin){
        //Add a new shift
        $qry = ('call add_shift(?,?,?);');
        $parameters = array($userID, $start, !$isAdmin);
        $result = $this->db->query($qry, $parameters);

        return $result->result_array();
    }

    public function countCoverNeeded($userID, $shiftID){
        //Count the amount of cover needed for the staff level
        $qry = ('call countCoverNeeded(?,?);');
        $parameters = array($userID, $shiftID);
        $cover = $this->db->query($qry, $parameters);
        $results = $cover->result_array();

        $cover->next_result();
        $cover->free_result();

        return $results;
    }

    public function removeShift_shiftID($shiftID, $isAdmin){
        //Remove a shift by shiftID
        $qry = ('call removeShift_shiftID(?, ?);');
        $parameters = array($shiftID, !$isAdmin);

        return $this->db->query($qry, $parameters);

    }
    public function removeShift_userID($shiftDate, $userID, $isAdmin){
        //Remove a shift by userID and shiftDate
        $qry = ('call removeShift_userID(?,?,?);');
        $parameters = array($shiftDate, $userID, !$isAdmin);

        return $this->db->query($qry, $parameters);
    }

    public function countUserShifts_Week($start, $end, $userID){
        //Count shifts user working between 2 days (week)
        $qry = ('call countUserShifts_Week(?,?, ?);');
        $parameters = array($start, $end, $userID);
        $query = $this->db->query($qry, $parameters);

        $results = $query->result_array();

        $query->next_result();
        $query->free_result();

        return $results;
    }
}



