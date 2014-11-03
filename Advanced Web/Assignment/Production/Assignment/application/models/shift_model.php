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
        $query = $this->db->query('call shift_getDate('. $date .');');
        return $query->result_array();
    }

    public function get_Data($start, $end, $userID){
        $query = $this->db->query('call shift_getDate(\''.$start.'\', \'' . $end . '\', '. $userID .');');
        $events = $query->result_array();

        return $events;

    }

    public function add_shift($start, $userID){
        $qry = ('call shift_add(' . $userID . ', \'' . $start .'\');');
        $result = $this->db->query($qry);

        $jsonevents = array();


            $jsonevents[] = array(
                'id' => '123',
                'title' =>  'New Shift',
                'start' => $start,
                'editable' => false
            );
        return json_encode($jsonevents);
    }


}



