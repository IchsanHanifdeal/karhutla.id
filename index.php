<?php
session_start();
include 'backend/koneksi.php';

$sqld = "SELECT * FROM karhutla";
$resultd = mysqli_query($conn, $sqld);
$rowd = mysqli_fetch_assoc($resultd);
?>
<!doctype html>
<html lang="en">

<head>
  <title>Karhutla.id</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- Material Kit CSS -->
  <link href="assets/landing/assets/css/material-kit.css?v=3.0.0" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>


</head>

<body>
  <!-- Navbar Transparent -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3 navbar-transparent">
    <div class="container">
      <a class="navbar-brand  text-white " href="#" rel="tooltip" title="Designed and Coded by Creative Tim" data-placement="bottom" target="_blank">
        Karhutla.id
      </a>
      <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon mt-2">
          <span class="navbar-toggler-bar bar1"></span>
          <span class="navbar-toggler-bar bar2"></span>
          <span class="navbar-toggler-bar bar3"></span>
        </span>
      </button>
      <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0 ms-lg-12 ps-lg-5" id="navigation">
        <ul class="navbar-nav navbar-nav-hover ms-auto">
          <li class="nav-item ms-lg-auto my-auto ms-3 ms-lg-0 mt-2 mt-lg-0">
            <a href="login.php" class="btn btn-sm  bg-gradient-primary mb-0 me-1 mt-2 mt-md-0">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->


  <div class="page-header min-vh-80" style="background-image: url('https://images.unsplash.com/photo-1630752708689-02c8636b9141?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2490&q=80')">
    <span class="mask bg-gradient-dark opacity-6"></span>
    <div class="container">
      <div class="row">
        <div class="col-md-8 mx-auto">
          <div class="text-center">
            <h1 class="text-white">Karhutla Riau</h1>
            <h3 class="text-white">Pemetaan Karhutla di Riau</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card card-body shadow-xl mx-3 mx-md-4 mt-n6">
    <div class="container">
      <div class="section text-center">
        <h2 class="title">Peta Lokasi</h2>
        <p class="description">Berikut adalah peta lokasi yang menunjukkan informasi geografis.</p>
      </div>
      <div id="google-map" style="height: 100vh; width: 100%;"></div>
    </div>
  </div>
  <footer class="footer pt-5 mt-5">
    <div class="container">
      <div class=" row">
        <div class="text-center">
          <p class="text-dark my-4 text-sm font-weight-normal">
            All rights reserved. Copyright © <script>
              document.write(new Date().getFullYear())
            </script><a href="#"> Ichsan Hanifdeal</a>.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <script>
    var googleMap;
    var markers = [];
    var circles = [];

    function initGoogleMap() {
      googleMap = new google.maps.Map(document.getElementById("google-map"), {
        center: {
          lat: 0.537488,
          lng: 101.448387
        },
        zoom: 15,
      });

      <?php
      $resultd = mysqli_query($conn, $sqld);
      while ($rowd = mysqli_fetch_assoc($resultd)) {
        $nama = $rowd['nama_daerah'];
        $long = $rowd['longitude'];
        $lat = $rowd['latitude'];
        $level = $rowd['level'];
        $radius = $rowd['radius'];

        if (!empty($lat) && !empty($long) && !empty($radius)) {
          $icon = '';
          $strokeColor = '';
          $fillColor = '';
          if ($level == 'rendah') {
            $icon = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
            $strokeColor = '#0000FF';
            $fillColor = '#0000FF';
          } else if ($level == 'menengah') {
            $icon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
            $strokeColor = '#FFFF00';
            $fillColor = '#FFFF00';
          } else if ($level == 'tinggi') {
            $icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
            $strokeColor = '#FF0000';
            $fillColor = '#FF0000';
          }
      ?>
          var marker = new google.maps.Marker({
            position: {
              lat: <?php echo $lat; ?>,
              lng: <?php echo $long; ?>
            },
            map: googleMap,
            title: '<?php echo $nama; ?>',
            level: '<?php echo $level; ?>',
            icon: '<?php echo $icon; ?>'
          });

          var circle = new google.maps.Circle({
            strokeColor: '<?php echo $strokeColor; ?>',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: '<?php echo $fillColor; ?>',
            fillOpacity: 0.35,
            map: googleMap,
            center: {
              lat: <?php echo $lat; ?>,
              lng: <?php echo $long; ?>
            },
            radius: <?php echo $radius; ?>
          });

          marker.addListener('click', function() {
            updateMarkerColor(this);
            calculateAndDisplayRoute(this.getPosition());
          });

          markers.push(marker);
          circles.push(circle);
      <?php
        }
      }
      ?>

    }
  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAQqCVzh9CHvZAJrfAoR-mVZD-dZxap2Xo&callback=initGoogleMap" async defer></script>
</body>

</html>