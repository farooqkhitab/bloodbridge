<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodbridge";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch Districts
$sql = "SELECT * FROM Districts";
$result = $conn->query($sql);

$districts = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $districts[] = $row;
  }
}

echo json_encode($districts);

$conn->close();
?>