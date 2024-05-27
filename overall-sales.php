
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

// Handle different views
$view = isset($_POST['view']) ? $_POST['view'] : 'daily';

$selectMonthly = null;

// Handle different views
if ($view === 'daily') {
    // Daily Sales View
    if (isset($_POST["btnaddsales"])) {
        // Insert the sales data into the database
        $dailysales = $_POST["order_date"];
        $totalsales = $_POST["total"];

        $insert = $pdo->prepare("INSERT INTO tbl_invoice (order_date, total) VALUES (:dailysales, :totalsales)");
        $insert->bindParam(":dailysales", $dailysales);
        $insert->bindParam(":totalsales", $totalsales);

        if ($insert->execute()) {
            // Check if there is a record for the current day in tbl_sales
            $checkDailySales = $pdo->prepare("SELECT * FROM tbl_sales WHERE date = :dailysales");
            $checkDailySales->bindParam(":dailysales", $dailysales);
            $checkDailySales->execute();

            if ($checkDailySales->rowCount() == 0) {
                // If no record exists for the current day, insert a new record with zero sales
                $insertDailySales = $pdo->prepare("INSERT INTO tbl_sales (date, daily) VALUES (:dailysales, 0)");
                $insertDailySales->bindParam(":dailysales", $dailysales);
                $insertDailySales->execute();
            }

            // Update tbl_sales with daily sales data
            $updateSales = $pdo->prepare("UPDATE tbl_sales SET daily = daily + :totalsales WHERE date = :dailysales");
            $updateSales->bindParam(":totalsales", $totalsales);
            $updateSales->bindParam(":dailysales", $dailysales);
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

    $currentMonth = date('m');
    $currentYear = date('Y');

    $selectSales = $pdo->prepare("SELECT 
            inv.invoice_id,
            inv.order_date,
            inv_det.product_name,
            SUM(inv_det.qty) AS total_qty,
            inv_det.price AS total,
            pid.image
        FROM tbl_invoice AS inv
        LEFT JOIN tbl_invoice_details AS inv_det ON inv.invoice_id = inv_det.invoice_id
        LEFT JOIN tbl_product AS pid ON inv_det.product_id = pid.pid
        WHERE MONTH(inv.order_date) = :currentMonth AND YEAR(inv.order_date) = :currentYear
        GROUP BY inv_det.product_name, inv.order_date
        ORDER BY inv.order_date DESC");

    $selectSales->bindParam(":currentMonth", $currentMonth);
    $selectSales->bindParam(":currentYear", $currentYear);
    $selectSales->execute();

    // Calculate and set the values of $totalDailySalesEarnings and $totalDailyPurchase
    $totalDailySalesEarnings = calculateTotalDailySalesEarnings();
    // $totalDailyPurchase = calculateTotalDailyPurchase();
    $totalDailyQuantity = calculateTotalDailyQuantity();
} elseif ($view === 'monthly') {
    // Monthly Sales View
    $currentMonth = date('F');
    $currentYear = date('Y');

    if (isset($_POST['view']) && $_POST['view'] === 'monthly') {
        $selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
        $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');

        $selectMonthly = $pdo->prepare("SELECT 
            inv.invoice_id,
            inv.order_date,
            inv_det.product_name,
            SUM(inv_det.qty) AS total_qty,
            inv_det.price AS total_earnings,
            pid.image
            FROM tbl_invoice AS inv
            LEFT JOIN tbl_invoice_details AS inv_det ON inv.invoice_id = inv_det.invoice_id
            LEFT JOIN tbl_product AS pid ON inv_det.product_id = pid.pid
            WHERE MONTH(inv.order_date) = :selectedMonth AND YEAR(inv.order_date) = :selectedYear
            GROUP BY inv_det.product_name, inv.order_date
            ORDER BY inv.order_date DESC");

        $selectMonthly->bindParam(":selectedMonth", $selectedMonth);
        $selectMonthly->bindParam(":selectedYear", $selectedYear);
        $selectMonthly->execute();

        // Initialize $totalMonthlySalesEarnings here
        $totalMonthlySalesEarnings = calculateTotalMonthlySalesEarnings();
        // $totalMonthlyPurchase = calculateTotalMonthlyPurchase();
        $totalMonthlyQuantity = calculateTotalMonthlyQuantity();
    }
} elseif ($view === 'annual') {
    // Annual Sales View
    $selectedStartYear = isset($_POST['start_year']) ? $_POST['start_year'] : date('Y') - 1;
    $selectedEndYear = isset($_POST['end_year']) ? $_POST['end_year'] : date('Y');

    $selectAnnual = $pdo->prepare("SELECT 
        inv.invoice_id,
        inv.order_date,
        SUM(inv_det.qty) AS total_qty,
        SUM(inv_det.price) AS total_earnings
    FROM tbl_invoice AS inv
    LEFT JOIN tbl_invoice_details AS inv_det ON inv.invoice_id = inv_det.invoice_id
    WHERE YEAR(inv.order_date) BETWEEN :startYear AND :endYear
    GROUP BY inv.invoice_id, inv.order_date
    ORDER BY inv.order_date DESC");

    $selectAnnual->bindParam(":startYear", $selectedStartYear);
    $selectAnnual->bindParam(":endYear", $selectedEndYear);
    $selectAnnual->execute();

    // Calculate and set the values of $totalAnnualSalesEarnings and $totalAnnualPurchase
    $totalAnnualSalesEarnings = calculateTotalAnnualSalesEarnings();
    // $totalAnnualPurchase = calculateTotalAnnualPurchase();
    $totalAnnualQuantity = calculateTotalAnnualQuantity();
}
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
                            <li class="breadcrumb-item active">Sales Report </li>
                            <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content container-fluid">
        <!-- Right column -->
        <div class="col-md-12">
            <!-- Adjust the column width as needed -->

            <!-- Dropdown for switching views -->
            <div class="mb-3">
                <form action="" method="POST">
                    <label for="view">Select View:</label>
                    <select name="view" id="view" onchange="this.form.submit()" class="form-select">
                        <option value="daily" <?= ($view === 'daily') ? 'selected' : '' ?>>Daily</option>
                        <option value="monthly" <?= ($view === 'monthly') ? 'selected' : '' ?>>Monthly</option>
                        <option value="annual" <?= ($view === 'annual') ? 'selected' : '' ?>>Annual</option>
                    </select>

                    <?php if ($view === 'monthly'): ?>
                        <label for="month">Select Month:</label>
                        <select name="month" id="month" class="form-select">
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                $selected = ($i == $selectedMonth) ? 'selected' : '';
                                echo "<option value='$i' $selected>" . date('F', mktime(0, 0, 0, $i, 1)) . "</option>";
                            }
                            ?>
                        </select>
                        <label for="year">Select Year:</label>
                        <select name="year" id="year" class="form-select">
                            <?php
                            $startYear = date('Y') - 5; // Change this to adjust the start year
                            $endYear = date('Y');
                            for ($i = $startYear; $i <= $endYear; $i++) {
                                $selected = ($i == $selectedYear) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="submit_monthly" class="btn btn-primary">Go</button>

                    <?php elseif ($view === 'annual'): ?>
                        <label for="start_year">Select Start Year:</label>
                        <select name="start_year" id="start_year" class="form-select">
                            <?php
                            $startYear = date('Y') - 5; // Change this to adjust the start year
                            $endYear = date('Y');
                            for ($i = $startYear; $i <= $endYear; $i++) {
                                $selected = ($i == $selectedStartYear) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                        <label for="end_year">Select End Year:</label>
                        <select name="end_year" id="end_year" class="form-select">
                            <?php
                            for ($i = $startYear; $i <= $endYear; $i++) {
                                $selected = ($i == $selectedEndYear) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                        <button type="submit" name="submit_annual" class="btn btn-primary">Go</button>
                    <?php endif; ?>
                </form>
            </div>


                <!-- Display sales table based on the selected view -->
                <div class="card card-info">
                    <link rel="stylesheet" href="css/buttons.css">
                    <div class="card-header">
                        <h3 class="card-title"> <?php echo ucfirst($view); ?> Sales</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="table-responsive">
                                <?php if ($view === 'daily'): ?>
                                    <!-- Display daily sales table -->
                                    <table id="salesTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="bg-lightblue">
                                                <th>Invoice ID</th>
                                                <th>Product Name</th>
                                                <th>Date</th>
                                                <th>Total Earnings</th>
                                                <th>Total Purchase Items</th>
                                                <th>Image</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($row = $selectSales->fetch(PDO::FETCH_ASSOC)) : ?>
                                        <tr>
                                            <td><?php echo $row['invoice_id']; ?></td>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo date('F j, Y', strtotime($row['order_date'])); ?></td>
                                            <td><?php echo '₱' . number_format($row['total'], 2); ?></td>
                                            <td><?php echo $row['total_qty']; ?></td>
                                            <td><img src="<?php echo $row['image']; ?>" alt="Product Image" height="50"></td>
                                        </tr>
                                    <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>    
                                          
                                                <th colspan="3">Total Daily Sales Earnings</th>
                                                <th><?php echo '₱' . number_format( $totalDailySalesEarnings, 2); ?></th>
                                                <th><?php echo $totalDailyQuantity; ?></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php elseif ($view === 'monthly'): ?>
                                    <!-- Display monthly sales table -->
                                    <table id="monthlySalesTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Product Name</th>
                                                <th>Date</th>
                                                <th>Total Earnings</th>
                                                <th>Total Purchase Items</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php while ($row = $selectMonthly->fetch(PDO::FETCH_ASSOC)) : ?>
                                                <tr>
                                                    <td><?php echo $row['invoice_id']; ?></td>
                                                    <td><?php echo $row['product_name']; ?></td>
                                                    <td><?php echo date('F j, Y', strtotime($row['order_date'])); ?></td>
                                                    <td><?php echo '₱' . number_format($row['total_earnings'], 2); ?></td>
                                                    <td><?php echo $row['total_qty']; ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                            <th colspan="3">Total Monthly Sales Earnings</th>
                                           <th> <?php echo '₱' . number_format($totalMonthlySalesEarnings, 2); ?></th>
                                         <th> <?php echo $totalMonthlyQuantity; ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php elseif ($view === 'annual'): ?>
                                    <!-- Display annual sales table -->
                                    <table id="annualSalesTable" class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="bg-lightblue">
                                                <th>Invoice ID</th>
                                                <th>Date</th>
                                                <th>Total Earnings</th>
                                                <th>Total Purchase Items</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $selectAnnual->fetch(PDO::FETCH_ASSOC)) : ?>
                                                <tr>
                                                    <td><?php echo $row['invoice_id']; ?></td>
                                                    <td><?php echo date('F j, Y', strtotime($row['order_date'])); ?></td>
                                                    <td><?php echo '₱' . number_format($row['total_earnings'], 2); ?> </td>
                                                    <td><?php echo $row['total_qty']; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                        <tfoot>
                                        <th colspan="2">Total Annual Sales Earnings</th>
                                        <th><?php echo '₱' . number_format($totalAnnualSalesEarnings, 2); ?></th>
                                        <th><?php echo $totalAnnualQuantity; ?></th>
                                        </tfoot>
                                    </table>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
    <?php
// Daily

function calculateTotalDailySalesEarnings() {
    global $pdo;
    $currentDate = date('Y-m-d');
    
    $totalSalesQuery = $pdo->prepare("SELECT SUM(total) as total_earnings FROM tbl_invoice WHERE DATE(order_date) = :currentDate");
    $totalSalesQuery->bindParam(":currentDate", $currentDate);
    
    // Debugging: Print the SQL query
    // echo $totalSalesQuery->queryString;
    
    $totalSalesQuery->execute();
    $totalSalesResult = $totalSalesQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalSalesResult['total_earnings'] !== null) ? $totalSalesResult['total_earnings'] : 0;
}

function calculateTotalDailyQuantity() {
    global $pdo;
    $currentDate = date('Y-m-d');
    
    $totalQuantityQuery = $pdo->prepare("SELECT SUM(qty) as total_qty FROM tbl_invoice_details WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE DATE(order_date) = :currentDate)");
    $totalQuantityQuery->bindParam(":currentDate", $currentDate);
    
    // Debugging: Print the SQL query
    // echo $totalQuantityQuery->queryString;
    
    $totalQuantityQuery->execute();
    $totalQuantityResult = $totalQuantityQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalQuantityResult['total_qty'] !== null) ? $totalQuantityResult['total_qty'] : 0;
}


//MONTNLY 
function calculateTotalMonthlySalesEarnings() {
    global $pdo;
    $selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
    $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
    
    // Replace the following query with your actual logic for monthly sales calculations
    $totalMonthlySalesQuery = $pdo->prepare("SELECT SUM(total) as total_earnings FROM tbl_invoice WHERE MONTH(order_date) = :selectedMonth AND YEAR(order_date) = :selectedYear");
    $totalMonthlySalesQuery->bindParam(":selectedMonth", $selectedMonth);
    $totalMonthlySalesQuery->bindParam(":selectedYear", $selectedYear);
    $totalMonthlySalesQuery->execute();
    $totalMonthlySalesResult = $totalMonthlySalesQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalMonthlySalesResult['total_earnings'] !== null) ? $totalMonthlySalesResult['total_earnings'] : 0;
}

function calculateTotalMonthlyQuantity() {
    global $pdo;
    $selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('m');
    $selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
    
    $totalQuantityQuery = $pdo->prepare("SELECT SUM(qty) as total_qty FROM tbl_invoice_details WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE MONTH(order_date) = :selectedMonth AND YEAR(order_date) = :selectedYear)");
    $totalQuantityQuery->bindParam(":selectedMonth", $selectedMonth);
    $totalQuantityQuery->bindParam(":selectedYear", $selectedYear);
    $totalQuantityQuery->execute();
    $totalQuantityResult = $totalQuantityQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalQuantityResult['total_qty'] !== null) ? $totalQuantityResult['total_qty'] : 0;
}

// function calculateTotalMonthlyPurchase() {
//     global $pdo;
//     $currentDate = date('Y-m-d');

//     try {
//         $totalPurchaseQuery = $pdo->prepare("SELECT SUM(qty * price) as total_qty FROM tbl_invoice_details WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE DATE(order_date) = :currentDate)");
//         $totalPurchaseQuery->bindParam(":currentDate", $currentDate);
//         $totalPurchaseQuery->execute();
//         $totalPurchaseResult = $totalPurchaseQuery->fetch(PDO::FETCH_ASSOC);

//         return ($totalPurchaseResult['total_qty'] !== null) ? $totalPurchaseResult['total_qty'] : 0;
//     } catch (PDOException $e) {
//         // Log or display the error message for debugging
//         echo 'Error: ' . $e->getMessage();
//         return 0; // Return 0 in case of an error
//     }
// }


//ANNUAL
function calculateTotalAnnualSalesEarnings() {
    global $pdo;
    $selectedStartYear = isset($_POST['start_year']) ? $_POST['start_year'] : date('Y') - 1;
    $selectedEndYear = isset($_POST['end_year']) ? $_POST['end_year'] : date('Y');
    
    // Replace the following query with your actual logic for annual sales calculations
    $totalAnnualSalesQuery = $pdo->prepare("SELECT SUM(total) as total_earnings FROM tbl_invoice WHERE YEAR(order_date) BETWEEN :startYear AND :endYear");
    $totalAnnualSalesQuery->bindParam(":startYear", $selectedStartYear);
    $totalAnnualSalesQuery->bindParam(":endYear", $selectedEndYear);
    $totalAnnualSalesQuery->execute();
    $totalAnnualSalesResult = $totalAnnualSalesQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalAnnualSalesResult['total_earnings'] !== null) ? $totalAnnualSalesResult['total_earnings'] : 0;
}

// function calculateTotalAnnualPurchase() {
//     global $pdo;
//     $selectedStartYear = isset($_POST['start_year']) ? $_POST['start_year'] : date('Y') - 1;
//     $selectedEndYear = isset($_POST['end_year']) ? $_POST['end_year'] : date('Y');
    
//     // Replace the following query with your actual logic for annual purchase calculations
//     $totalAnnualPurchaseQuery = $pdo->prepare("SELECT SUM(qty * price) as total_qty FROM tbl_invoice_details WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE YEAR(order_date) BETWEEN :startYear AND :endYear)");
//     $totalAnnualPurchaseQuery->bindParam(":startYear", $selectedStartYear);
//     $totalAnnualPurchaseQuery->bindParam(":endYear", $selectedEndYear);
//     $totalAnnualPurchaseQuery->execute();
//     $totalAnnualPurchaseResult = $totalAnnualPurchaseQuery->fetch(PDO::FETCH_ASSOC);

//     return ($totalAnnualPurchaseResult['total_qty'] !== null) ? $totalAnnualPurchaseResult['total_qty'] : 0;
// }

function calculateTotalAnnualQuantity() {
    global $pdo;
    $selectedStartYear = isset($_POST['start_year']) ? $_POST['start_year'] : date('Y') - 1;
    $selectedEndYear = isset($_POST['end_year']) ? $_POST['end_year'] : date('Y');
    
    $totalAnnualQuantityQuery = $pdo->prepare("SELECT SUM(qty) as total_qty FROM tbl_invoice_details WHERE invoice_id IN (SELECT invoice_id FROM tbl_invoice WHERE YEAR(order_date) BETWEEN :startYear AND :endYear)");
    $totalAnnualQuantityQuery->bindParam(":startYear", $selectedStartYear);
    $totalAnnualQuantityQuery->bindParam(":endYear", $selectedEndYear);
    $totalAnnualQuantityQuery->execute();
    $totalAnnualQuantityResult = $totalAnnualQuantityQuery->fetch(PDO::FETCH_ASSOC);

    return ($totalAnnualQuantityResult['total_qty'] !== null) ? $totalAnnualQuantityResult['total_qty'] : 0;
}
?>
<!-- Add this inside the head section or at the end of your HTML body -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css"></script>

<?php if ($view === 'daily'): ?>
    <script>
        $(document).ready(function () {
            $("#salesTable").DataTable({
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
            }).buttons().container().appendTo('#salesTable_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php elseif ($view === 'monthly'): ?>
    <script>
        $(document).ready(function () {
            $("#monthlySalesTable").DataTable({
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
            }).buttons().container().appendTo('#monthlySalesTable_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php elseif ($view === 'annual'): ?>
    <script>
        $(document).ready(function () {
            $("#annualSalesTable").DataTable({
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
            }).buttons().container().appendTo('#annualSalesTable_wrapper .col-md-6:eq(0)');
        });
    </script>
<?php endif; ?>


    <?php
    include_once "footer.php";
    ?>

   