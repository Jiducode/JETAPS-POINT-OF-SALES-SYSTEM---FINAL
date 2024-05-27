<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
} catch (PDOException $f) {
    echo $f->getMessage();
}

session_start();
if (empty($_SESSION["useremail"])) {
    header("location:index.php");
} elseif ($_SESSION["role"] !== "Admin") {
    header("location:order_user.php");
}

include_once "header.php";

if (isset($_POST["btnaddsales"])) {
    // Insert the sales data into the database
    $dailysales = $_POST["order_date"];
    $totalsales = $_POST["total"];

    $insert = $pdo->prepare("INSERT INTO tbl_invoice (order_date, total) VALUES (:dailysales, :totalsales)");
    $insert->bindParam(":dailysales", $dailysales);
    $insert->bindParam(":totalsales", $totalsales);

    if ($insert->execute()) {
        // Update tbl_sales with daily sales data
        $updateSales = $pdo->prepare("UPDATE tbl_sales SET daily = daily + :totalsales WHERE 1");
        $updateSales->bindParam(":totalsales", $totalsales);
        $updateSales->execute();

        echo "<script type='text/javascript'>
        jQuery(function validation(){
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Saved',
                text: 'Daily Sales Added Successfully',
                showConfirmButton: false,
                timer: 3000
            })
        });
        </script>";
    } else {
        echo "<script type='text/javascript'>
        jQuery(function validation(){
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Failed',
                text: 'Unable to Add Daily Sales',
                showConfirmButton: false,
                timer: 3000
            })
        });
        </script>";
    }
}

// Get the current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Daily Sales
$selectDaily = $pdo->prepare("SELECT 
    inv.invoice_id,
    inv.order_date,
    SUM(inv.total) AS total_earnings
FROM tbl_invoice AS inv
WHERE MONTH(inv.order_date) = :currentMonth AND YEAR(inv.order_date) = :currentYear
GROUP BY inv.order_date
ORDER BY inv.order_date DESC");

$selectDaily->bindParam(":currentMonth", $currentMonth);
$selectDaily->bindParam(":currentYear", $currentYear);
$selectDaily->execute();

// Monthly Sales
$selectMonthly = $pdo->prepare("SELECT 
    MONTH(inv.order_date) AS month,
    YEAR(inv.order_date) AS year,
    SUM(inv.total) AS total_earnings,
    inv.invoice_id
FROM tbl_invoice AS inv
WHERE YEAR(inv.order_date) = :currentYear
GROUP BY MONTH(inv.order_date)
ORDER BY month ASC");

$selectMonthly->bindParam(":currentYear", $currentYear);
$selectMonthly->execute();

// Annual Sales
$selectAnnual = $pdo->prepare("SELECT 
    YEAR(inv.order_date) AS year,
    SUM(inv.total) AS total_earnings,
    inv.invoice_id
FROM tbl_invoice AS inv
GROUP BY YEAR(inv.order_date)
ORDER BY year ASC");

$selectAnnual->execute();
?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Sales</li>
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content container-fluid">
        <!-- Right column -->
        <div class="col-md-12"> <!-- Adjust the column width as needed -->

            <!-- Daily Sales -->
            <div class="card card-info">
                <link rel="stylesheet" href="css/buttons.css">
                <div class="card-header">
                    <h3 class="card-title"> Daily Sales</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="table-responsive">
                            <table id="dailyTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="bg-lightblue">
                                        <th class='justify-center' style='text-align: center;'>Date</th>
                                        <th class='justify-center' style='text-align: center;'>Total Earnings</th>
                                        <th class="justify-center" style="text-align: center; width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $selectDaily->fetch(PDO::FETCH_OBJ)) {
                                        // Format the total earnings with two decimal places
                                        $formattedTotalEarnings = number_format($row->total_earnings, 2);

                                        echo "<tr>
                                            <td class='justify-center' style='text-align: center;'>$row->order_date</td>
                                            <td class='justify-center' style='text-align: center;'>₱$formattedTotalEarnings</td>
                                            <td class='justify-center' style='text-align: center;'>
                                                // <button id='$row->invoice_id' class='btn btn-danger dltBttn' type='button'>
                                                //     <span class='fas fa-trash' style='color:#ffffff' data-toggle='tooltip' title='DELETE'></span>
                                                // </button>
                                                // <a href='dailytotal.php?date=$row->order_date' class='btn btn-info' role='button'>
                                                //     <span class='fas fa-eye' name='editBtn' style='color:#ffffff' data-toggle='tooltip' title='VIEW'></span>
                                                // </a>
                                            </td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"> Monthly Sales</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="monthlyTable" class="table table-bordered table-hover">
                            <thead>
                                <tr class="bg-lightblue">
                                    <th class='justify-center' style='text-align: center;'>Month</th>
                                    <th class='justify-center' style='text-align: center;'>Total Earnings</th>
                                    <th class="justify-center" style="text-align: center; width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                        while ($row = $selectMonthly->fetch(PDO::FETCH_OBJ)) {
                            // Convert numeric month to full month name
                            $monthName = date('F', mktime(0, 0, 0, $row->month, 1));

                            // Format the total earnings with two decimal places
                            $formattedTotalEarnings = number_format($row->total_earnings, 2);

                            echo "<tr>
                                <td class='justify-center' style='text-align: center;'>$monthName {$row->year}</td>
                                <td class='justify-center' style='text-align: center;'>₱$formattedTotalEarnings</td>
                                <td class='justify-center' style='text-align: center;'>
                                    <button id='$row->invoice_id' class='btn btn-danger dltBttn' type='button'>
                                        <span class='fas fa-trash' style='color:#ffffff' data-toggle='tooltip' title='DELETE'></span>
                                    </button>
                                    <a href='monthlytotal.php?month=$row->month&year=$row->year' class='btn btn-info' role='button'>
                                        <span class='fas fa-eye' name='editBtn' style='color:#ffffff' data-toggle='tooltip' title='VIEW'></span>
                                    </a>
                                </td>
                            </tr>";
                        }
                        ?>

                            </tbody>
                        </table>
                    </div>
                </div>
    </section>
</div>

<script> 
$(function () {
       $("#dailyTable").DataTable({
            "responsive": true,
           "lengthChange": false,
           "autoWidth": false,
           "order": [[0, "asc"]],
            "buttons": [
                {
                    extend: "csv",
                    text: '<i class="fas fa-file-csv" style="color: green;"></i> CSV',
                },
                {
                    extend: "excel",
                    text: '<i class="fas fa-file-excel" style="color: orange;"></i> Excel',
                },
                {
                    extend: "pdf",
                    text: '<i class="fas fa-file-pdf" style="color: red;"></i> PDF',
                },
                {
                    extend: "print",
                    text: '<i class="fas fa-print" style="color: purple;"></i> Print',
                },
                "colvis",
            ],
        }).buttons().container().appendTo('#dailyTable_wrapper .col-md-6:eq(0)');
        $("[data-toggle='tooltip']").tooltip();
    });

  </script>