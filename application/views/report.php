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
      Test Report in 3D graph
    </h1>
  </div>  
  <div class="row">
      <div class="col-md-9">
          <div id="mygraph"></div>
          <div id="info"></div>
      </div>
      <div class="col-md-3">
        <table class="table table-bordered">
            <tr>
                <th>Init Param ID</th>
                <td>
                    <select name="init_param_id" class="form-control" id="init_param_id">
                        <?php
                        foreach ($init_param->result_array() as $param) {
                            echo "<option value='$param[id]'>Case $param[id] with $param[city_count] cities and optimum $param[target]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th>City Count</th><td id="city_count"></td>
            </tr>
            <tr>
                <th>Optimum Target</th><td id="target"></td>
            </tr>
            <tr>
                <th>City Location xCoordinate</th><td id="locx"></td>
            </tr>
            <tr>
                <th>City Location yCoordinate</th><td id="locy"></td>
            </tr>
            <tr>
                <td colspan="2"><button type="button" class="btn btn-success" id="draw">Draw Graph <i class="fa fa-pencil"></i> </button> </td>
            </tr>
        </table>
      </div>
  </div>
</div>
<?php $this->load->view('footer'); ?>
<script type="text/javascript" src="<?php echo base_url().'assets/graph/vis.js';?>"></script>
<script type="text/javascript">
    var data = null;
    var graph = null;

    function custom(x, y) {
        return (Math.sin(x/50) * Math.cos(y/50) * 50 + 50);
    }

    // Called when the Visualization API is loaded.
    function drawVisualization(jsondata) {
        // Create and populate a data table.
        data = new vis.DataSet();
        // create some nice looking data with sin/cos
        /*var counter = 0;
        var steps = 50;  // number of datapoints will be steps*steps
        var axisMax = 314;
        var axisStep = axisMax / steps;
        for (var x = 0; x < axisMax; x+=axisStep) {
            for (var y = 0; y < axisMax; y+=axisStep) {
                var value = custom(x,y);
                data.add({id:counter++,x:x,y:y,z:value,style:value});
            }
        }*/

        $.each(jsondata, function( index, value ){
            data.add({id: index, x:parseInt(value.v_max), y: parseInt(value.particle_count), z: parseFloat(value.epoch_number)});
        });

        // specify options
        var options = {
            width:  '800px',
            height: '800px',
            style: 'surface',
            showPerspective: true,
            showGrid: true,
            showShadow: false,
            keepAspectRatio: true,
            verticalRatio: 0.5,
            xLabel: 'Particle Count',
            yLabel: 'Maximum Velocity',
            zLabel: 'Iteration'
        };

        // Instantiate our graph object.
        var container = document.getElementById('mygraph');
        graph = new vis.Graph3d(container, data, options);
    }

    $(document).ready(function(){
        $.get("<?php echo base_url().'index.php/page/reportJSON';?>", {id: 1}, function (res) {
            drawVisualization(res);
        }, "json");

        $("#init_param_id").on("change", function(){
            filter_init();
        });

        $("#draw").on("click", function(){
            $.get("<?php echo base_url().'index.php/page/reportJSON';?>", {id: $("#init_param_id").val()}, function (res) {
                drawVisualization(res);
            }, "json");
        });

        function filter_init(){
            $.get("<?php echo base_url().'index.php/page/getinit';?>", {id: $("#init_param_id").val()}, function(res){
                $("#target").html(res.target);
                $("#locx").html(res.xlocs);
                $("#locy").html(res.ylocs);
                $("#city_count").html(res.city_count+' cities');
            }, "json");
        }
        filter_init();
    })
</script>
</body>
</html>