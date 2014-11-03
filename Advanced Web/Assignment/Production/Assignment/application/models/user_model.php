<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 27/10/14
 * Time: 13:10
 */

class user_model extends CI_Model{


    public function __construct()
    {
        $this->load->database();
    }

    public function get_Data($start, $end){
       // $query = $this->db->query('call shift_getDate(\'' . $start .'\', \'' . $end . '\');');
        $query = $this->db->query('call shift_getDate(\''.$end.'\', \'2015-01-01\');');
        $events = $query->result_array();

        $jsonevents = array();
        foreach ($events as $entry)
        {
            $jsonevents[] = array(
                'id' => $entry['levelID'],
                'title' =>  $entry['levelName'] . ' ' . $entry['shiftNumbers'],
                'start' => $entry['shiftDate'],
                'editable' => false
            );
        }
        return json_encode($jsonevents);
    }
}



