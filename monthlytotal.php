



<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
} catch (PDOException $ex) {
    echo $ex->getMessage();
}

if (isset($_POST["selectedDate"])) {
    $selectedDate = $_POST["selectedDate"];

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
    WHERE DATE(inv.order_date) = :selectedDate
    GROUP BY inv_det.product_name, inv.order_date
    ORDER BY inv.order_date DESC");

    $selectDaily->bindParam(":selectedDate", $selectedDate);
    $selectDaily->execute();

    while ($row = $selectDaily->fetch(PDO::FETCH_OBJ)) {
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
}
?>

