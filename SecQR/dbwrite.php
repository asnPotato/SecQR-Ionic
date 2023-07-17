<?php
// Define the database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secqr";

// Execute the encryption Python script
$pythonScript = 'DataInsert.py';
$command = 'python ' . $pythonScript;
$output = shell_exec($command);

// Separate the ID and datetime from the output
$outputArray = explode(" ", trim($output));
$id = $outputArray[0];
$datetime = $outputArray[1];

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  // Prepare and execute the SQL INSERT statement
  $stmt = $conn->prepare("INSERT INTO hidden_data (id, data_time) VALUES (?, ?)");
  $stmt->bind_param("is", $id, $datetime);
  if ($stmt->execute()) {
    // Insertion successful
    echo 'Data inserted into the database.';
  } else {
    // Failed to insert into the database
    echo 'Failed to insert into the database.';
  }
  
  // Close the database connection
  $conn->close();
  ?>