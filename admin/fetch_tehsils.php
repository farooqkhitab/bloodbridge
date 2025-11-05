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

// Get District ID from query parameter
$districtId = $_GET["district_id"];

// Fetch Tehsils
$sql = "SELECT * FROM Tehsils WHERE district_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $districtId);
$stmt->execute();
$result = $stmt->get_result();

$tehsils = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $tehsils[] = $row;
  }
}

echo json_encode($tehsils);

$stmt->close();
$conn->close();
?>