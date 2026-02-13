<?php
session_start();
include "db.php";

header('Content-Type: application/json');

if (
    empty($_POST['name']) ||
    empty($_POST['email']) ||
    empty($_POST['password'])
) {
    echo json_encode(["error" => "All fields are required"]);
    exit;
}

$name     = trim($_POST['name']);
$email    = trim($_POST['email']);
$password = trim($_POST['password']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["error" => "Invalid email format"]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(["error" => "Password must be at least 6 characters"]);
    exit;
}

$check = $conn->query("SELECT id FROM users WHERE email='$email'");
if ($check && $check->num_rows > 0) {
    echo json_encode(["error" => "Email already registered"]);
    exit;
}
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->query("
    INSERT INTO users (name, email, password)
    VALUES ('$name', '$email', '$hashedPassword')
");

if ($insert) {
    echo json_encode(["message" => "Registration successful"]);
} else {
    echo json_encode(["error" => "Registration failed"]);
}
?>
