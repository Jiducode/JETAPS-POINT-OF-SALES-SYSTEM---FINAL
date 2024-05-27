<?php

try {
  $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
  //echo "connection Sucessfull";

} catch (PDOException $f) {

  echo $f->getmessage();
}




//include_once"conectdb.php";
session_start();
if ($_SESSION["useremail"] == "" or $_SESSION["role"] == "user") {

  header("location:index.php");
}
include_once "header.php";
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="logout.php">LOGOUT</a></li>
            <li class="breadcrumb-item active"><a href="changepassword.php">Change Password</a></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">

    <!-- Content Row -->
    <div class="row align-items-center">

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Total Users Registered</div>

              </div>
              <div class="col-auto">
                <i class="fas fa-calendar fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Total Products</div>

              </div>
              <div class="col-auto">
                <i class="fas fa-user fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>


      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Sales</div>
                <?php
                $con = mysqli_connect("localhost", "root", "", "pos_db");
                if (!$con) {
                  die("Connection Failed:" . mysqli_connect_error());
                }
                ?>
                <?php
                $results = mysqli_query($con, "SELECT sum(paid) FROM tbl_invoice") or die(mysqli_error());
                while ($rows = mysqli_fetch_array($results)) { ?>
                  <?php echo $rows['sum(paid)']; ?>
                <?php
                }
                ?>
              </div>
              <div class="col-auto">
                <i class="fas fa-money-bill fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                  Total Invoice</div>

              </div>
              <div class="col-auto">
                <i class="fas fa-file-alt fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Content Row -->

    <!-- Pie Chart -->
    <div class="col-xl-4 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-primary">Product and Total Remaining Stocks:</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">

          <?php

          $conn = mysqli_connect('localhost', 'root', '', 'pos_db');
          $sql = "SELECT * FROM tbl_product";
          $result = mysqli_query($conn, $sql);
          while ($row = mysqli_fetch_assoc($result)) {
            $dataPoints[] = array("Country" => $row['pname'], "y" => $row['pstock']);
          }
          ?>

          <!DOCTYPE HTML>
          <html>

          <head>
            <script>
              window.onload = function() {
                var chart = new CanvasJS.Chart("chartContainer", {
                  animationEnabled: true,
                  data: [{
                    type: "pie", // bar,doughnut,funnel,pyramid
                    yValueFormatString: "#,##0.\"\"",
                    indexLabel: "{Country}  ({y})",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                  }]
                });
                chart.render();
              }
            </script>
          </head>

          <body>
            <div id="chartContainer" style="height: 300px; width: 100%;"></div>
            <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
          </body>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	exportEnabled: true,
	animationEnabled: true,
	title:{
		text: "Car Parts Sold in Different States"
	},
	subtitles: [{
		text: "Click Legend to Hide or Unhide Data Series"
	}], 
	axisX: {
		title: "States"
	},
	axisY: {
		title: "Oil Filter - Units",
		titleFontColor: "#4F81BC",
		lineColor: "#4F81BC",
		labelFontColor: "#4F81BC",
		tickColor: "#4F81BC"
	},
	axisY2: {
		title: "Clutch - Units",
		titleFontColor: "#C0504E",
		lineColor: "#C0504E",
		labelFontColor: "#C0504E",
		tickColor: "#C0504E"
	},
	toolTip: {
		shared: true
	},
	legend: {
		cursor: "pointer",
		itemclick: toggleDataSeries
	},
	data: [{
		type: "column",
		name: "Oil Filter",
		showInLegend: true,      
		yValueFormatString: "#,##0.# Units",
		dataPoints: [
			{ label: "New Jersey",  y: 19034.5 },
			{ label: "Texas", y: 20015 },
			{ label: "Oregon", y: 25342 },
			{ label: "Montana",  y: 20088 },
			{ label: "Massachusetts",  y: 28234 }
		]
	},
	{
		type: "column",
		name: "Clutch",
		axisYType: "secondary",
		showInLegend: true,
		yValueFormatString: "#,##0.# Units",
		dataPoints: [
			{ label: "New Jersey", y: 210.5 },
			{ label: "Texas", y: 135 },
			{ label: "Oregon", y: 425 },
			{ label: "Montana", y: 130 },
			{ label: "Massachusetts", y: 528 }
		]
	}]
});
chart.render();

function toggleDataSeries(e) {
	if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
		e.dataSeries.visible = false;
	} else {
		e.dataSeries.visible = true;
	}
	e.chart.render();
}

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
<script src="../../canvasjs.min.js"></script>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
  <!-- Control sidebar content goes here -->
  <div class="p-3">
    <h5>Title</h5>
    <p>Sidebar content</p>
  </div>
</aside>
<!-- /.control-sidebar -->

<?php
include_once "footer.php";


?>