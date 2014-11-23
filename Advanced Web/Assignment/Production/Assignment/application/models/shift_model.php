<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 13:10
 */

class shift_model extends CI_Model{


    public function __construct()
    {
        $this->load->database();
    }

    public function get_calendar(){
        $date = date('Y-m-j');
        $qry = 'call shift_getDate(?);';
        $parameters = array($date);
        $query = $this->db->query($qry, $parameters);

        return $query->result_array();
    }

    public function get_Data($start, $end, $userID){
        // Return calendar events between $start and $end
        // for the $user
        $qry = ('call shift_getDate(?,?,?);');
        $parameters = array($start, $end, $userID);
        $query = $this->db->query($qry, $parameters);
        return $query->result_array();

    }

    public function get_DataAll($start, $end){
        // Return calendar events between $start and $end
        // for all users
        $qry = ('call shift_getDateAll(?,?);');
        $parameters = array($start, $end);
        $query = $this->db->query($qry, $parameters);

        return $query->result_array();
    }

    public function add_shift($start, $userID, $isAdmin){
        $qry = ('call shift_add(?,?,?);');
        $parameters = array($userID, $start, !$isAdmin);
        $result = $this->db->query($qry, $parameters);

        return $result->result_array();

    }

    public function countCoverNeeded($userID, $shiftID){
        $qry = ('call shift_coverNeeded(?,?);');
        $parameters = array($userID, $shiftID);
        $cover = $this->db->query($qry, $parameters);
        $results = $cover->result_array();

        $cover->next_result();
        $cover->free_result();

        return $results;
    }

    public function remove_shift($shiftID, $isAdmin){
        $qry = ('call shift_remove(?, ?);');
        $parameters = array($shiftID, !$isAdmin);

        return $this->db->query($qry, $parameters);

    }
    public function remove_shiftDate($shiftDate, $userID, $isAdmin){
        $qry = ('call shift_removeDate(?,?,?);');
        $parameters = array($shiftDate, $userID, !$isAdmin);

        return $this->db->query($qry, $parameters);
    }

    public function countShiftsWeek($start, $end, $userID){
        $qry = ('call shift_userCountWeek(?,?, ?);');
        $parameters = array($start, $end, $userID);
        $query = $this->db->query($qry, $parameters);

        $results = $query->result_array();

        $query->next_result();
        $query->free_result();

        return $results;

    }

}



