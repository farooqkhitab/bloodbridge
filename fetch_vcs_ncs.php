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

// Get Tehsil ID from query parameter
$tehsilId = $_GET["tehsil_id"];

// Fetch VCs_NCs
$sql = "SELECT * FROM VCs_NCs WHERE tehsil_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tehsilId);
$stmt->execute();
$result = $stmt->get_result();

$vcs_ncs = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $vcs_ncs[] = $row;
  }
}

echo json_encode($vcs_ncs);

$stmt->close();
$conn->close();
?>