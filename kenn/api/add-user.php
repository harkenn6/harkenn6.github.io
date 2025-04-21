<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['name'], $data['email'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (?,
?)");
$stmt->execute([$data['name'], $data['email']]);
echo json_encode(["message" => "User added"]);