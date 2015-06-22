<!DOCTYPE html>
<html>
<head>
	<?php $this->load->view('head_link'); ?>
</head>
<body>	
	<?php $this->load->view('header'); ?>
	<!--begin bg-carousel-->
<div id="bg-fade-carousel" class="carousel slide carousel-fade" data-ride="carousel">
<!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <div class="slide1"></div>
        </div>
        <div class="item">
            <div class="slide2"></div>
        </div>
        <div class="item">
            <div class="slide3"></div>
        </div>
    </div><!-- .carousel-inner --> 
    <div class="container carousel-overlay text-center">
        <h1>Simple Approach of PSO Algorithm for TSP Problem</h1>
        <p class="lead">Simple representation of algorithm performance</p>
        <a class="btn btn-lg btn-success fp-buttons" href="#">
            <span class="fa fa-bar-chart"></span> General Performance Report
        </a>
        <a class="btn btn-lg btn-danger fp-buttons" href="<?php echo base_url().'index.php/page/pso_action';?>">
            <span class="fa fa-clock-o"></span> See Algorithm in Action
        </a>
    </div>
</div><!-- .carousel --> 
<!--end bg-carousel-->

<div class="container">
  <div class="row text-center">
  	<h2>
  		Let's Learn Particle Swarm Optimization
  	</h2>
  </div> 
  <hr>
  <div class="row">
    <div class="col-md-7 col-md-push-5">
      <h2 class="featurette-heading">Particle Swarm Optimization </h2>
      <p class="lead">Particle Swarm Optimization merupakan metode komputasi yang mengoptimalisasi penyelesaian masalah dengan meng-iterasikan kandidat solusi berdasarkan kualitas hasil pengukuran yang diberikan.</p>
      <p class="lead">Particle Swarm Optimization adalah salah satu metode optimasi yang terinspirasi dari perilaku gerakan kawanan hewan seperti  ikan (school of fish), hewan herbivor (herd), dan burung (flock) yang kemudian tiap objek hewan disederhanakan menjadi sebuah partikel. Suatu partikel dalam ruang memiliki posisi yang dikodekan sebagai vektor koordinat. Vektor posisi ini dianggap sebagai keadaan yang sedang ditempati oleh suatu partikel di ruang pencarian. Setiap posisi dalam ruang pencarian merupakan alternatif solusi yang dapat dievaluasi menggunakan fungsi objektif. Setiap partikel bergerak dengan kecepatan v.</p>
    </div>
    <div class="col-md-5 col-md-pull-7 text-center">
      <img src="<?php echo base_url().'assets/images/pso.png';?>" alt="placeholder" class="featurette-image img-responsive">
    </div>
  </div> 
  <div class="row">
  	<div class="col-md-5 col-md-push-7 text-center">
      <img src="<?php echo base_url().'assets/images/tsp.jpg';?>" alt="placeholder" class="featurette-image img-responsive">
    </div>
    <div class="col-md-7 col-md-pull-5">
      <h2 class="featurette-heading">Travelling Salesman Problem </h2>
      <p class="lead">Travelling Salesman Problem merupakan sebuah problem untuk mengoptimasi dan menemukan perjalanan terpendek. TSP adalah problem untuk menentukan urutan kota ( jarak antar kota diketahui ) yang harus dilalui oleh salesman untuk mencapai tujuan dengan pengeluaran biaya dan jarak yang paling minimal dengan syarat bahwa setiap kota yang dilalui tidak boleh dilalui lagi.</p>
    </div>    
  </div> 
  <hr>
  <div class="row text-center">
  	<a class="btn btn-lg btn-danger" href="<?php echo base_url().'index.php/page/pso_action';?>">
        <span class="fa fa-clock-o"></span> Let's See the Algorithm in Action!
    </a>
    OR
    <a class="btn btn-lg btn-success" href="#">
        <span class="fa fa-bar-chart"></span> Let's See Our Test Results Report!
    </a>
  </div>
</div>
<?php $this->load->view('footer'); ?>
</body>
</html>