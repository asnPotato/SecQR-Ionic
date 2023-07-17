<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
  $file = $_FILES['image'];

  // Specify the destination directory where you want to save the uploaded file
  $uploadDirectory = 'upload/';

  // Generate a unique filename for the uploaded file
  $filename = $file['name'];

  // Move the uploaded file to the destination directory
  $targetPath = $uploadDirectory . $filename;
  if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // File uploaded successfully

    // Generate a unique 5-digit ID
    $uniqueID = generateUniqueID(5);

    // Check if the generated ID already exists in the database
    $idExists = checkIDExists($uniqueID);

    // If the ID exists, generate a new unique ID
    while ($idExists) {
      $uniqueID = generateUniqueID(5);
      $idExists = checkIDExists($uniqueID);
    }

    // Execute the encryption Python script with the unique ID as an argument
    $pythonScript = 'encrypt.py';
    $command = 'python ' . $pythonScript . ' ' . $uniqueID;
    $output = shell_exec($command);

    // Display success message
    echo 'File uploaded successfully! Unique ID: ' . $uniqueID;
  } else {
    // Failed to upload file
    echo 'Failed to upload file.';
  }
} else {
  // No file uploaded or invalid request
  echo 'Invalid request.';
}

/**
 * Generates a unique ID of a specified length.
 * @param int $length The length of the unique ID.
 * @return string The generated unique ID.
 */
function generateUniqueID($length) {
  $uniqueID = '';

  // Generate a random ID of the specified length
  $uniqueID = str_pad(mt_rand(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);

  return $uniqueID;
}

/**
 * Checks if the ID exists in the database.
 * @param string $id The ID to check.
 * @return bool True if the ID exists, False otherwise.
 */
function checkIDExists($id) {
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
  $stmt = $conn->prepare("SELECT * FROM hidden_data WHERE id = ?");
  $stmt->bind_param("s", $id);
  $stmt->execute();

  // Get the result
  $result = $stmt->get_result();

  // Check if the ID exists
  if ($result->num_rows > 0) {
    $conn->close();
    return true;
  } else {
    $conn->close();
    return false;
  }
}
?>