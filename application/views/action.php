<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('head_link'); ?>
</head>
<body>	
	<?php $this->load->view('header'); ?>	
<div class="container main">
  <div class="row">
    <h1 class="page-header">
      Custom TSP Problem
    </h1>
  </div>  
  <div class="row">
    <form action="#" method="POST">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Particle Count : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=2 value=2 class="form-control" name="particle_count" required="required">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Maximum Velocity (must be <= than city count) : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=1 value=1 class="form-control" name="v_max" required="required">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Maximum Iteration : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=1 value=10000 class="form-control" name="max_epoch" required="required">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Initial Parameter : </label>
          </div>
          <div class="col-md-7">
            <select name="init_param_id" class="form-control" id="init_param_id">
              <?php 
                foreach ($init_param->result_array() as $param) {
                  echo "<option value='$param[id]'>Case $param[id] with $param[city_count] cities and optimum $param[target]</option>";
                }
              ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Default Maximum Velocity : </label>
          </div>
          <div class="col-md-2" id="v_max">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Default Particle Count : </label>
          </div>
          <div class="col-md-2" id="particle_count">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Default Maximum Iteration : </label>
          </div>
          <div class="col-md-2" id="max_epoch">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Optimal Target : </label>
          </div>
          <div class="col-md-2" id="target">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>X Coordinate : </label>
          </div>
          <div class="col-md-2" id="Xlocs">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Y Coordinate : </label>
          </div>
          <div class="col-md-2" id="YLocs">
            
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>City Count : </label>
          </div>
          <div class="col-md-2" id="city_count">
            
          </div>
        </div>
      </div>      
      <div class="row">
        <div class="col-md-10">
          <button type="button" class="btn btn-success pull-right">Generate Result!</button>
        </div>
      </div>
    </form>
  </div>
</div>
<?php $this->load->view('footer'); ?>

<script type="text/javascript">
  $(document).ready(function(){
    $("#init_param_id").on("change", function(){
      filter_init();
    });

    function filter_init(){
      $.get("<?php echo base_url().'index.php/page/getinit';?>", {id: $("#init_param_id").val()}, function(res){
        $("#v_max").html(res.v_max);
        $("#particle_count").html(res.particle_count);
        $("#max_epoch").html(res.max_epoch);
        $("#target").html(res.target);
        $("#Xlocs").html(res.xlocs);
        $("#YLocs").html(res.ylocs);
        $("#city_count").html(res.city_count+' cities');
      }, "json");
    }
    filter_init();
  });
</script>
</body>
</html>