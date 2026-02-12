<?php
session_start();
include "db.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    MIN(b.id) AS booking_id,
    m.title,
    s.theatre_name,
    s.show_date,
    m.poster,
    s.show_time,
    GROUP_CONCAT(b.seat_no ORDER BY b.seat_no ASC) AS seats,
    COUNT(b.seat_no) AS total_seats,
    (COUNT(b.seat_no) * 200) AS total_price
FROM bookings b
JOIN shows s ON b.show_id = s.id
JOIN movies m ON s.movie_id = m.id
WHERE b.user_id = ?
GROUP BY b.show_id
ORDER BY booking_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);
?>
