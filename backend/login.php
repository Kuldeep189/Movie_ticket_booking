<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare(
  "SELECT id, name, password, role FROM users WHERE email = ?"
);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
  $user = $result->fetch_assoc();

  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['role'] = $user['role']; 

    echo json_encode([
    "success" => true,
    "role" => $user['role']
]);

  } else {
    echo json_encode(["error" => "Invalid credentials"]);
  }
} else {
  echo json_encode(["error" => "User not found"]);
}
