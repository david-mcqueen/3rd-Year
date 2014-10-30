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

    public function get_shift($userID = 0){
        if ($userID == 0){
            $query = $this->db->get('shifts');
            return $query->result_array();
        }

        $query = $this->db->get_where('shifts', array('userID' => $userID));
        return $query->result_array();
    }

    public function get_calendar(){
        $date = date('Y-m-j');
        $query = $this->db->query('call shift_getDate('. $date .')');
        return $query->result_array();
    }

    public function get_Data(){

        $events =  array(
            array(
                "id"=> "876",
                "title"=> "All Day Event",
                "start"=> "2014-09-01"
            ),array(
                "id"=> "987",
                "title"=> "Long Event",
                "start"=> "2014-09-07",
                "end"=> "2014-09-10"
            ),array(
                "id"=> "999",
                "title"=> "Repeating Event",
                "start"=> "2014-09-09T16:00:00-05:00"
            )
        );

        $jsonevents = array();
        foreach ($events as $entry)
        {
            $jsonevents[] = array(
                'id' => $entry['id'],
                'title' => $entry['title'],
                'start' => $entry['start']
            );
        }
        return json_encode($jsonevents);


    }
} 