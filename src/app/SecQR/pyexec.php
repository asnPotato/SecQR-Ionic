<?php
//this code is used to execute the decode python file and extract the output from it


$pythonScript = 'decode.py';
$command = 'python ' . $pythonScript;
$output = shell_exec($command);


$outputArray = explode(" ", trim($output));
$urldec = isset($outputArray[0]) ? $outputArray[0] : '';
$id = isset($outputArray[1]) ? $outputArray[1] : '';
$datetime = isset($outputArray[2]) ? $outputArray[2] : '';


$idExists = checkIDExists($id, $datetime);

$response = array(
    'urldec' => $urldec,
    'idExists' => $idExists
);

// Convert the response array to JSON format
$jsonResponse = json_encode($response);

// Set the response content type to JSON
header('Content-Type: application/json');

// Output the JSON response
echo $jsonResponse;

function checkIDExists($id,$datetime) {
    // Replace with your database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "secqr";
  
    // Create a new connection
    $conn = new mysqli($servername, $username, $password, $dbname);
  
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
  
    // Prepare and execute the SQL SELECT statement
    $stmt = $conn->prepare("SELECT * FROM hidden_data WHERE id = ? AND data_time = ?");
    $stmt->bind_param("ss", $id,$datetime);
    $stmt->execute();
  
    // Get the result
    $result = $stmt->get_result();
  
    // Check if the ID exists
    if ($result->num_rows > 0) {
        $conn->close();
        return "QR CODE SECURE!";
    } else {
        $conn->close();
        return "QR CODE NOT SECURE!";
    }
  }
?>