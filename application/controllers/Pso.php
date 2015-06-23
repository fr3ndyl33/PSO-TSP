<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pso extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->PARTICLE_COUNT = 6;
        $this->V_MAX = 8;
        $this->MAX_EPOCH = 10000;
        $this->CITY_COUNT = 8;
        $this->TARGET = 86.63;
        $this->particles = [];
        $this->map = [];
        $this->XLocs = [30, 40, 40, 29, 19, 9, 9, 20];  //City X coordinate
        $this->YLocs = [5, 10, 20, 25, 25, 19, 9, 5];   //City Y coordinate
        $this->totalEpoch = 0;
        $this->shortestRoute = '';
        $this->shortestDistance = 0.0;
    }
    
    public function index()
    {
        $this->load->model('pso_model');
        $init = $this->input->get();
        $init_param = $this->pso_model->get_init_param($init['init_param_id'])->row_array();
        if($init['particle_count'] > 0){
            $this->PARTICLE_COUNT = $init['particle_count'];
        }else{
            $this->PARTICLE_COUNT = $init_param['particle_count'];
        }
        if($init['v_max'] > $init_param['city_count'] || $init['v_max'] < 1){
            $this->V_MAX = $init_param['v_max'];
        }else{
            $this->V_MAX = $init['v_max'];
        }
        if($init['max_epoch'] > 0){
            $this->MAX_EPOCH = $init['max_epoch'];
        }else{
            $this->MAX_EPOCH = $init_param['max_epoch'];
        }

        if(isset($init['target']) && $init['target'] == "Y"){
            $this->TARGET = $init_param['target'];
        }else{
            $this->TARGET = 0;
        }

        $this->CITY_COUNT = $init_param['city_count'];

        $this->XLocs = [];
        foreach(explode(',',$init_param['xlocs']) as $x){
            array_push($this->XLocs, $x);
        }
        $this->YLocs = [];
        foreach(explode(',',$init_param['ylocs']) as $y){
            array_push($this->YLocs, $y);
        }

        $this->initMap();
        $this->PSOAlgorithm();
        $this->printBestSolution();

        if($init['command'] == 'save')
            $this->save_result($init);
    }

    public function save_result($init){
        $this->load->model('pso_model');
        $data = [
            'init_param_id' => $init['init_param_id'],
            'v_max'         => $this->V_MAX,
            'max_epoch'     => $this->MAX_EPOCH,
            'particle_count'=> $this->PARTICLE_COUNT,
            'epoch_number'  => $this->totalEpoch,
            'shortest_route'=> $this->shortestRoute,
            'shortest_distance'=> $this->shortestDistance
            ];

        $this->pso_model->save_result($data);
    }    

    public function initMap()
    {
        for($i = 0; $i < $this->CITY_COUNT; $i++)
        {
            $city = new CCity();
            $city->setX($this->XLocs[$i]);
            $city->setY($this->YLocs[$i]);

            array_push($this->map, $city);
        }
    }

    public function PSOAlgorithm(){
        $aParticle = null;
        $epoch = 0;
        $done = FALSE;

        $this->initialize();

        while(!$done)
        {
            // Two conditions can end this loop:
            //    if the maximum number of epochs allowed has been reached, or,
            //    if the Target value has been found.
            if($epoch < $this->MAX_EPOCH){
                echo "<br><br>Iteration number: ".$epoch."<br>";

                for($i = 0; $i < $this->PARTICLE_COUNT; $i++){
                    $aParticle = $this->particles[$i];
                    echo "Particle <strong>".$aParticle->label()."</strong> ";
                    echo "Route: ";
                    for($j = 0; $j < $this->CITY_COUNT; $j++){
                        echo $aParticle->data($j)." - ";
                    }

                    $this->getTotalDistance($i);
                    echo "Distance: ".$aParticle->pBest().'<br>';
                    if($aParticle->pBest() <= $this->TARGET){
                        $this->shortestDistance = $aParticle->pBest();
                        for($j = 0; $j < $this->CITY_COUNT; $j++) {
                            $this->shortestRoute.= $aParticle->data($j) . ",";
                        }
                        $done = TRUE;
                    }
                }

                $this->bubbleSort(); // sort particles by their pBest scores, best to worst.

                $this->getVelocity();

                $this->updateParticle();

                $epoch++;
            }else{
                $done = TRUE;
            }

            $this->totalEpoch = $epoch;
        }
    }

    public function printBestSolution(){
        if($this->particles[0]->pBest() <= $this->TARGET){
            echo "<h4>Target Reached</h4>";
        }else{
            echo "<h4>Target not Reached</h4>";
        }
        echo "<h5>Shortest Route:";
        for($i = 0; $i < $this->CITY_COUNT; $i++){
                echo $this->particles[0]->data($i)."-";
        }
        echo "</h5>";
        echo "<h5>Distance :".$this->particles[0]->pBest()."</h5>";
    }

    private function initialize(){
        for($i = 0; $i < $this->PARTICLE_COUNT; $i++){
            $newParticle = new Particle();
            $newParticle->setlabel($i+1);

            for($j = 0; $j < $this->CITY_COUNT; $j++){
                $newParticle->setData($j, $j);
            }
            array_push($this->particles, $newParticle);
            for($j = 0; $j < 10; $j++){
                $this->randomlyArrange(array_search($newParticle, $this->particles));
            }
            //console.log("cetak " + particles.indexOf(newParticle));
            $this->getTotalDistance(array_search($newParticle, $this->particles));
        }
    }

    private function randomlyArrange($index){
        $cityA = rand(0, $this->CITY_COUNT - 1);
        $cityB = 0;
        $done = FALSE;

        while(!$done){
            $cityB = rand(0, $this->CITY_COUNT - 1);
            if($cityB != $cityA)
                $done = TRUE;
        }

        $temp = $this->particles[$index]->data($cityA);
        $this->particles[$index]->setData($cityA, $this->particles[$index]->data($cityB));
        $this->particles[$index]->setData($cityB, $temp);
    }

    private function getVelocity(){
        $worstResult = $this->particles[$this->PARTICLE_COUNT - 1]->pBest();

        for($i = 0; $i < $this->PARTICLE_COUNT; $i++){
            $vValue = ($this->V_MAX * $this->particles[$i]->pBest()) / $worstResult;

            if($vValue > $this->V_MAX){
                $this->particles[$i]->setVelocity($this->V_MAX);
            }elseif($vValue < 0.0){
                $this->particles[$i]->setVelocity(0.0);
            }else{
                $this->particles[$i]->setVelocity($vValue);
            }
        }
    }

    private function updateParticle(){
        echo "Sort :<br>";
        echo "Best is Particle <strong>".$this->particles[0]->label()."</strong> <strong>Distance: ".$this->particles[0]->pBest().'</strong><br>';
        // Best is at index 0, so start from the second best.
        for($i = 1; $i < $this->PARTICLE_COUNT; $i++){
            // The higher the velocity score, the more changes it will need.
            $changes = (int)(floor(abs($this->particles[$i]->velocity())));
            echo "Changes velocity for particle <strong>".$this->particles[$i]->label()."</strong>: ".$changes;
            echo " <strong>Distance: ".$this->particles[$i]->pBest().'</strong><br>';
            for($j = 0; $j < $changes; $j++){
                if(rand(0,1) == 1)
                    $this->randomlyArrange($i);

                // Push it closer to it's best neighbor.
                $this->copyFromParticle($i - 1, $i);
            }

            // Update pBest value.
            $this->getTotalDistance($i);
        }
    }

    private function copyFromParticle($source, $destination){
        // push destination's data points closer to source's data points.
        $best = $this->particles[$source];
        $targetA = rand(0, $this->CITY_COUNT - 1); // source's city distance to target.
        $targetB = $indexA = $indexB = $tempIndex = 0;

        // targetB will be source's neighbor immediately succeeding targetA (circular).
        for($i = 0; $i < $this->CITY_COUNT; $i++){
            if($best->data($i) == $targetA) {
                if ($i == $this->CITY_COUNT - 1)
                    $targetB = $best->data(0); // if end of array, take from beginning.
                else
                    $targetB = $best->data($i + 1);
                break;
            }
        }

        // Move targetB next to targetA by switching values.
        for($j = 0; $j < $this->CITY_COUNT; $j++){
            if($this->particles[$destination]->data($j) == $targetA)
                $indexA = $j;
            if($this->particles[$destination]->data($j) == $targetB)
                $indexB = $j;
        }

        // get temp index succeeding indexA.
        if($indexA == $this->CITY_COUNT - 1)
            $tempIndex = 0;
        else
            $tempIndex = $indexA + 1;

        // Switch indexB value with tempIndex value.
        $temp = $this->particles[$destination]->data($tempIndex);
        $this->particles[$destination]->setData($tempIndex, $this->particles[$destination]->data($indexB));
        $this->particles[$destination]->setData($indexB, $temp);
    }

    private function getTotalDistance($index){
        $thisParticle = $this->particles[$index];
        $thisParticle->setpBest(0.0);

        for($i = 0; $i < $this->CITY_COUNT; $i++){
            if(($this->CITY_COUNT - 1) == $i){
                $thisParticle->setpBest($thisParticle->pBest() + $this->getDistance($thisParticle->data($this->CITY_COUNT - 1), $thisParticle->data(0)));
            }else{
                $thisParticle->setpBest($thisParticle->pBest() + $this->getDistance($thisParticle->data($i), $thisParticle->data($i+1)));
            }
        }
    }

    private function getDistance($firstCity, $secondCity){
        $cityA = $cityB = NULL;
        $a2 = $b2 = 0;

        $cityA = $this->map[$firstCity];
        $cityB = $this->map[$secondCity];
        $a2 = pow(abs($cityA->x() - $cityB->x()), 2);
        $b2 = pow(abs($cityA->y() - $cityB->y()), 2);

        return sqrt($a2 + $b2);
    }

    private function bubbleSort(){
        $done = false;
        while(!$done){
            $changes = 0;
            $listSize = count($this->particles);

            for($i = 0; $i < $listSize -1; $i++){
                if($this->particles[$i]->compareTo($this->particles[$i + 1]) == 1){
                    $temp = $this->particles[$i];
                    $this->particles[$i] = $this->particles[$i+1];
                    $this->particles[$i+1] = $temp;
                    $changes++;
                }
            }

            if($changes == 0){
                $done = true;
            }
        }
    }
}

class CCity
{

    function __construct()
    {
        $this->mX = 0;
        $this->mY = 0;
    }

    public function x(){
        return $this->mX;
    }

    public function y(){
        return $this->mY;
    }

    public function setX($xCoordinate){
        $this->mX = $xCoordinate;
    }

    public function setY($yCoordinate){
        $this->mY = $yCoordinate;
    }
}

class Particle
{

    function __construct()
    {
        $this->mData = [];
        $this->mpBest = 0;
        $this->mVelocity = 0.0;
        $this->label = 0;
    }

    public function compareTo($that){
        if($this->pBest() < $that->pBest())
            return -1;
        elseif($this->pBest() > $that->pBest())
            return 1;
        else
            return 0;
    }

    public function data($index){
        return $this->mData[$index];
    }

    public function setData($index, $value){
        $this->mData[$index] = $value;
    }

    public function pBest(){
        return $this->mpBest;
    }

    public function setpBest($value){
        $this->mpBest = $value;
    }

    public function velocity(){
        return $this->mVelocity;
    }

    public function setVelocity($velocityScore){
        $this->mVelocity = $velocityScore;
    }

    public function label(){
        return $this->label;
    }

    public function setlabel($value){
        $this->label = $value;
    }
}
