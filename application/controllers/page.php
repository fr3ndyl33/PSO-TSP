<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');        
    }
    public function index()
    {        
        $this->load->view('main');
    }

    public function pso_action()
    {                
        $this->load->model('pso_model');
        $data['init_param'] = $this->pso_model->get_init_param();
        $this->load->view('action', $data);
    }

    public function getInit(){
        $id = $_GET['id'];
        $this->load->model('pso_model');

        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->pso_model->get_init_param($id)->row());
    }

    public function report(){
        $this->load->model('pso_model');
        $data['init_param'] = $this->pso_model->get_init_param();
        $this->load->view('report', $data);
    }

    public function reportJSON(){
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
        }else{
            $id = 1;
        }
        $this->load->model('pso_model');
        $this->output->set_header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->pso_model->get_general_result($id)->result());
    }
}