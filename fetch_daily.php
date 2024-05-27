<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
} catch (PDOException $f) {
    echo $f->getMessage();
}

if (isset($_POST["order_date"])) {
    $order_date = $_POST["order_date"];

    $select = $pdo->prepare("SELECT SUM(paid) AS total_sales FROM tbl_invoice WHERE order_date = :order_date");
    $select->bindParam(":order_date", $order_date);
    $select->execute();

    $row = $select->fetch(PDO::FETCH_OBJ);
    $total_sales = $row->total_sales;

    echo $total_sales;
}
?>
