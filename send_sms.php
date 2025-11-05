<?php
require 'db_config.php';
require 'lifetimesms_config.php';

$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['ids'] ?? [];
$message = $data['message'] ?? '';

if (empty($ids) || empty($message)) {
    http_response_code(400);
    echo "⚠️ Invalid request. Donors or message missing.";
    exit;
}

// Get contact numbers from database
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT contact FROM donors WHERE id IN ($placeholders)");
$stmt->execute($ids);
$contacts = $stmt->fetchAll(PDO::FETCH_COLUMN);

if (!$contacts) {
    echo "❌ No contacts found.";
    exit;
}

// Format numbers (Ensure they're in 92XXXXXXXXXX format)
$formattedNumbers = array_map(function ($num) {
    return preg_replace('/^0/', '92', $num);
}, $contacts);

// Build request
$url = "https://lifetimesms.com/json";
$parameters = [
    "api_token"   => LIFETIMESMS_API_TOKEN,
    "api_secret"  => LIFETIMESMS_API_SECRET,
    "to"          => implode(',', $formattedNumbers),
    "from"        => LIFETIMESMS_FROM_NAME,
    "message"     => $message
];

// Send POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
curl_close($ch);

echo $response;
