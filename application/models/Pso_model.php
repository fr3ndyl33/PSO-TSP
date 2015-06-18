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
}
?>