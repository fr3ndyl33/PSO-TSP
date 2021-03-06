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
  <div class="alert alert-info">
    <div class="alert-header">
      How to Start:
      <ol type="1">
        <li>Kolom - kolom yang dapat diisi (Particle Count, Maximum Velocity, Maximum Iteration, Initial Parameter) digunakan untuk merubah inisialisasi 
        data pencarian</li>
        <li>Nilai default digunakan hanya bila kolom - kolom yang diisi kurang sesuai atau tidak memiliki nilai.</li>
        <li>Tombol Test Result! untuk menampilkan hasil test, sedangkan Tombol Generate &amp; Save Result untuk menampilkan hasil test dan disimpan dalam database.
      </ol>
    </div>
  </div>
  <div class="row">
    <form action="#" method="POST">
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Particle Count : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=2 value=2 class="form-control" id="custom_particle_count" name="particle_count" required="required">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Maximum Velocity (must be <= than city count) : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=1 value=1 class="form-control" id="custom_v_max" name="v_max" required="required">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group">
          <div class="col-md-3">          
            <label>Maximum Iteration : </label>
          </div>
          <div class="col-md-2">
            <input type="number" min=1 value=10000 class="form-control" id="custom_max_epoch" name="max_epoch" required="required">
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
                </div>
                <div class="col-md-6">
                    <label><input type="checkbox" id="custom_target" name="target"> Use Optimal Target</label>
                    <div class="alert alert-info">Optimal target digunakan untuk membatasi jarak minimum yang dicari</div>
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
          <button type="button" id="generate" class="btn btn-primary pull-left">Test Result!</button>
          <button type="button" id="generate-save" class="btn btn-success pull-right">Generate & Save Result!</button>
        </div>
      </div>
    </form>
  </div>
    <div class="panel panel-default" style="margin-top: 20px">
        <div class="panel-heading"><i class="fa fa-clock-o"></i> Result</div>
        <div class="panel-body disp-result" style="height: 400px; overflow-y: scroll;">
            This page is temporarily disabled by the site administrator for some reason.<br> <a href="#">Click here</a> to enable the page.
        </div>        
    </div>
    <input type="hidden" id="latest_route">
    <input type="hidden" id="latest_distance">
    <input type="hidden" id="epoch_number">
    <input type="hidden" id="shortest_route">
    <input type="hidden" id="shortest_distance">
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

    function generate_result(command) {
        var data = {
            'command': command || null,
            'particle_count': $("#custom_particle_count").val(),
            'v_max' : $("#custom_v_max").val(),
            'max_epoch' : $("#custom_max_epoch").val(),
            'init_param_id' : $("#init_param_id").val(),
            'target' : (($("#custom_target").is(":checked")) ? 'Y' : 'N')
        };
        $(".disp-result").html("<i class='fa fa-refresh fa-spin'></i>");
        $.get("<?php echo base_url().'index.php/pso';?>", data, function (res) {
            $(".disp-result").html(res);
        }, "text");
    }

    $("#generate").on("click", function(){
        generate_result();
    });
      $("#generate-save").on("click", function(){
          generate_result('save');
      });
  });
</script>
</body>
</html>