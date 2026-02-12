<?php
session_start();
include "db.php";

header('Content-Type: application/json');

// Check login
if(!isset($_SESSION['user_id'])){
    echo json_encode(["error"=>"Please login first"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Validate request
if (!isset($_POST['show_id'], $_POST['seat_no'])) {
    echo json_encode(["error"=>"Missing data"]);
    exit;
}

$show_id   = intval($_POST['show_id']);
$seat_list = explode(",", $_POST['seat_no']);

foreach($seat_list as $seat){

    $seat = $conn->real_escape_string(trim($seat));

    // Check already booked
    $check = $conn->query("
        SELECT id FROM seats 
        WHERE show_id=$show_id 
        AND seat_no='$seat' 
        AND status='BOOKED'
    ");

    if($check && $check->num_rows > 0){
        echo json_encode(["message"=>"Seat $seat already booked"]);
        exit;
    }

    // Update seat
    $conn->query("
        UPDATE seats 
        SET status='BOOKED'
        WHERE show_id=$show_id AND seat_no='$seat'
    ");

    // Insert booking (NO user_name)
    $conn->query("
        INSERT INTO bookings (show_id, seat_no, user_id)
        VALUES ($show_id,'$seat',$user_id)
    ");
}

echo json_encode(["message"=>"Booking successful"]);
?>
