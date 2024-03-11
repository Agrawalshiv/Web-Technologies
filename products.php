<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require_once('dbConnect.php');

    $sql = "SELECT * FROM `products`";
    $r = mysqli_query($con, $sql);

    $result = array();

    while ($res = mysqli_fetch_array($r)) {
        $result[] = array(
            "productid"=>$res['productid'],
            "name" => $res['name'],
            "imageUrl" => $res['imageUrl'],
            "price" => $res['price'],
            "category" => $res['category'],
            "rating" => $res['rating'],
            "description" => $res['description']
        );
    }


    echo json_encode($result);

    mysqli_close($con);
}
?>
