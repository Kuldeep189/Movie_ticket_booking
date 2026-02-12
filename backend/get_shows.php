<?php
include "db.php";

header("Content-Type: application/json");

$movie_id = $_GET['movie_id'];

$result = $conn->query("
SELECT id, theatre_name, show_time, show_date 
FROM shows 
WHERE movie_id=$movie_id
ORDER BY theatre_name
");

$shows = [];

while($row = $result->fetch_assoc()){
    $shows[] = $row;
}

echo json_encode($shows);
