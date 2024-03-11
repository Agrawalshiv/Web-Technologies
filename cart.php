<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once('dbConnect.php');

    // Retrieve data from URL parameters
    $productid = $_GET['productId'];
    $quantity = $_GET['quantity'];

    // Sanitize user input
    $productid = mysqli_real_escape_string($con, $productid);
    $quantity = mysqli_real_escape_string($con, $quantity);

    // Insert data into cart table
    $sql = "INSERT INTO `cart` (productid, quantity) VALUES (?, ?)";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $productid, $quantity);

    $response = array();

    if (mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = mysqli_error($con);
    }

    // Query product details
    $sql1 = "SELECT name, imageUrl, category, price FROM `products` WHERE productid=?";
    $stmt1 = mysqli_prepare($con, $sql1);
    mysqli_stmt_bind_param($stmt1, "s", $productid);

    if (mysqli_stmt_execute($stmt1)) {
        $result = mysqli_stmt_get_result($stmt1);
        $productDetails = mysqli_fetch_assoc($result);
        $response['productDetails'] = $productDetails;
    } else {
        $response['productDetails'] = null;
        $response['error'] = mysqli_error($con);
    }

    echo json_encode($response);

    mysqli_close($con);
}
?>
