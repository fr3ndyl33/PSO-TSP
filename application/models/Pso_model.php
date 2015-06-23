<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pso_model extends CI_Model {
	function __construct()
    {        
        parent::__construct();
        $this->load->database();
    }

    function get_init_param($id = null){
    	if($id)
    		$query = $this->db->where('id', $id)->get('init_param');
    	else
    		$query = $this->db->get('init_param');
        return $query;
    }

    function save_result($param){
        $this->db->insert('general_result', $param);
    }

    function get_general_result($init_param_id){
        $result = $this->db->select('v_max, AVG(epoch_number) AS epoch_number, particle_count, shortest_distance')
            ->where('init_param_id', $init_param_id)
            ->group_by(array('v_max', 'particle_count'))
            ->order_by('v_max')
            ->order_by('particle_count')
            ->get('general_result');

        return $result;
    }
}
?>