<?php

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pos_db", "root", "");
    // echo "Connection Successful"; // You can uncomment this line for debugging if needed.
} catch (PDOException $e) {
    echo $e->getMessage();
}

// Define the product ID you want to retrieve stock for
$productID = 1; // Replace with the actual product ID you're interested in

// SQL query to retrieve the stock value and product name for a specific product
$sql = "SELECT pstock, pname FROM tbl_product WHERE pid = :productID";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':productID', $productID, PDO::PARAM_INT);
$stmt->execute();

if ($stmt !== false) { // Check if the query was successful
    // Fetch the stock value and product name
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stockValue = $row["pstock"];
    $productName = $row["pname"];
} else {
    $stockValue = 0; // Set a default value if there was an issue with the query
    $productName = ""; // Set a default product name
}

// Close the database connection (not necessary when using PDO)
// $conn->close();

// Return the stock value and product name as a JSON response
$response = array("stockValue" => $stockValue, "pname" => $productName);
echo json_encode($response);
?>