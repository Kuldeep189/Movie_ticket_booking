<?php
include "db.php";

$show_id = $_GET['show_id'];

$result = $conn->query("SELECT seat_no,status FROM seats WHERE show_id=$show_id");

$seats=[];

while($row=$result->fetch_assoc()){
    $seats[]=$row;
}

echo json_encode($seats);
