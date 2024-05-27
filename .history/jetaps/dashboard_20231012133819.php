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


<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	zoomEnabled: true,
	theme: "dark2",
	title:{
		text: "Growth in Internet Users Globally"
	},
	axisX:{
		title: "Year",
		valueFormatString: "####",
		interval: 2
	},
	axisY:{
		logarithmic: true, //change it to false
		title: "Internet Users (Log)",
		titleFontColor: "#6D78AD",
		lineColor: "#6D78AD",
		gridThickness: 0,
		lineThickness: 1,
		includeZero: false,
		labelFormatter: addSymbols
	},
	axisY2:{
		title: "Internet Users",
		titleFontColor: "#51CDA0",
		logarithmic: false, //change it to false
		lineColor: "#51CDA0",
		gridThickness: 0,
		lineThickness: 1,
		labelFormatter: addSymbols
	},
	legend:{
		verticalAlign: "top",
		fontSize: 16,
		dockInsidePlotArea: true
	},
	data: [{
		type: "line",
		xValueFormatString: "####",
		showInLegend: true,
		name: "Log Scale",
		dataPoints: [
			{ x: 1994, y: 25437639 },
			{ x: 1995, y: 44866595 },
			{ x: 1996, y: 77583866 },
			{ x: 1997, y: 120992212 },
			{ x: 1998, y: 188507628 },
			{ x: 1999, y: 281537652 },
			{ x: 2000, y: 414794957 },
			{ x: 2001, y: 502292245 },
			{ x: 2002, y: 665065014 },
			{ x: 2003, y: 781435983 },
			{ x: 2004, y: 913327771 },
			{ x: 2005, y: 1030101289 },
			{ x: 2006, y: 1162916818 },
			{ x: 2007, y: 1373226988 },
			{ x: 2008, y: 1575067520 },
			{ x: 2009, y: 1766403814 },
			{ x: 2010, y: 2023202974 },
			{ x: 2011, y: 2231957359 },
			{ x: 2012, y: 2494736248 },
			{ x: 2013, y: 2728428107 },
			{ x: 2014, y: 2956385569 },
			{ x: 2015, y: 3185996155 },
			{ x: 2016, y: 3424971237 }
		]
	},
	{
		type: "line",
		xValueFormatString: "####",
		axisYType: "secondary",
		showInLegend: true,
		name: "Linear Scale",
		dataPoints: [
			{ x: 1994, y: 25437639 },
			{ x: 1995, y: 44866595 },
			{ x: 1996, y: 77583866 },
			{ x: 1997, y: 120992212 },
			{ x: 1998, y: 188507628 },
			{ x: 1999, y: 281537652 },
			{ x: 2000, y: 414794957 },
			{ x: 2001, y: 502292245 },
			{ x: 2002, y: 665065014 },
			{ x: 2003, y: 781435983 },
			{ x: 2004, y: 913327771 },
			{ x: 2005, y: 1030101289 },
			{ x: 2006, y: 1162916818 },
			{ x: 2007, y: 1373226988 },
			{ x: 2008, y: 1575067520 },
			{ x: 2009, y: 1766403814 },
			{ x: 2010, y: 2023202974 },
			{ x: 2011, y: 2231957359 },
			{ x: 2012, y: 2494736248 },
			{ x: 2013, y: 2728428107 },
			{ x: 2014, y: 2956385569 },
			{ x: 2015, y: 3185996155 },
			{ x: 2016, y: 3424971237 }
		]
	}]
});
chart.render();

function addSymbols(e){
	var suffixes = ["", "K", "M", "B"];

	var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);
	if(order > suffixes.length - 1)
		order = suffixes.length - 1;

	var suffix = suffixes[order];
	return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
}

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
<script src="../../canvasjs.min.js"></script>
</body>
</html>





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