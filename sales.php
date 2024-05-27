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

$selectDaily = $pdo->prepare("SELECT 
    inv.invoice_id,
    inv.order_date,
    inv_det.product_name,
    inv_det.qty,
    inv_det.price AS total_earnings,
    pid.image
FROM tbl_invoice AS inv
LEFT JOIN tbl_invoice_details AS inv_det ON inv.invoice_id = inv_det.invoice_id
LEFT JOIN tbl_product AS pid ON inv_det.product_id = pid.pid
WHERE MONTH(inv.order_date) = :currentMonth AND YEAR(inv.order_date) = :currentYear
GROUP BY inv_det.product_name, inv.order_date
ORDER BY inv.order_date DESC");

$selectDaily->bindParam(":currentMonth", $currentMonth);
$selectDaily->bindParam(":currentYear", $currentYear);
$selectDaily->execute();


// $selectAnnual->execute();
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Sales </li>
                        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
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
                                    <th class='justify-center' style='text-align: center;'>Invoice Id</th>
                                    <th class='justify-center' style='text-align: center;'>Product Name</th>
                                    <th class='justify-center' style='text-align: center;'>Date</th>
                                    <th class='justify-center' style='text-align: center;'>Total Earnings</th>
                                    <th class='justify-center' style='text-align: center;'>Image</th>
                                    <th class='justify-center' style='text-align: center;'>Total Purchase Items</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = $selectDaily->fetch(PDO::FETCH_OBJ)) {
                                        // Format the total earnings with two decimal places
                                        $formattedTotalEarnings = number_format($row->total_earnings, 2);
                                        echo "<tr>
                                        <td class='justify-center' style='text-align: center;'>$row->invoice_id</td>
                                        <td class='justify-center' style='text-align: center;'>$row->product_name</td>
                                        <td class='justify-center' style='text-align: center;'>$row->order_date</td>
                                        <td class='justify-center' style='text-align: center;'>₱$formattedTotalEarnings</td>
                                        <td class='justify-center' style='text-align: center;'>
                                        <img src='$row->image' width='100px' height='75px'>
                                    </td>
                                        <td class='justify-center' style='text-align: center;'>$row->qty</td>
                                       
                                    </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
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

// </script>
<script src="js/salesdlt.js"></script>

<script>
    function printInvoice() {
        window.print();
    }
</script>


<?php
include_once "footer.php";
?>
