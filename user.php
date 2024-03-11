<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
require_once('dbConnect.php');

// Handle GET request to fetch data
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $sql = "SELECT * FROM `user`";
    $r = mysqli_query($con, $sql);

    if (!$r) {
        // Error handling if query fails
        echo json_encode(array("error" => "Query failed: " . mysqli_error($con)));
        exit();
    }

    $result = array();

    while ($res = mysqli_fetch_array($r)) {
        // Handle null values for imageUrl
        $imageUrl = isset($res['imageUrl']) ? $res['imageUrl'] : null;

        // Add data to the result array
        $result[] = array(
            "imageUrl" => $imageUrl,
            "name" => $res['name'],
            "email" => $res['email'],
            "address" => $res['address']
        );
    }

    echo json_encode(array("result" => $result));

    mysqli_close($con);
}

// Handle POST request to save data
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming your POST data contains fields 'name', 'email', and 'address'
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // You can perform validation on the received data if needed

    $sql = "INSERT INTO `user` (name, email, address) VALUES ('$name', '$email', '$address')";
    if (mysqli_query($con, $sql)) {
        echo json_encode(array("success" => "Data saved successfully"));
    } else {
        // Error handling if query fails
        echo json_encode(array("error" => "Query failed: " . mysqli_error($con)));
    }

    mysqli_close($con);
}

// Handle PUT request to update data
// Handle PUT request to update 
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Get the raw PUT data
    $putData = file_get_contents("php://input");

    // Check if the data is not empty
    if (empty($putData)) {
        http_response_code(400); // Bad request
        echo json_encode(array("error" => "Empty PUT data"));
        exit();
    }

    // Parse the raw PUT data as JSON into an associative array
    $putParams = json_decode($putData, true);

    // Extract the ID and other fields from the parsed PUT data
    $id = isset($putParams['id']) ? $putParams['id'] : null;
    $name = isset($putParams['name']) ? $putParams['name'] : null;
    $email = isset($putParams['email']) ? $putParams['email'] : null;
    $address = isset($putParams['address']) ? $putParams['address'] : null;

    // Check if all necessary fields are present
    if (empty($id) || empty($name) || empty($email) || empty($address)) {
        http_response_code(400); // Bad request
        echo json_encode(array("error" => "Missing required fields"));
        exit();
    }

    // You can perform validation on the received data if needed

    // Update the user data in the database
    $sql = "UPDATE `user` SET name='$name', email='$email', address='$address' WHERE id=$id";
    if (mysqli_query($con, $sql)) {
        // Set content type to JSON before sending any output
        header('Content-Type: application/json');
        // Return a success message in JSON format
        echo json_encode(array("success" => "Data updated successfully"));
    } else {
        // Set content type to JSON before sending any output
        header('Content-Type: application/json');
        // Return an error message in JSON format
        http_response_code(500); // Internal server error
        echo json_encode(array("error" => "Query failed: " . mysqli_error($con)));
    }

    // Close database connection
    mysqli_close($con);
}



?>
