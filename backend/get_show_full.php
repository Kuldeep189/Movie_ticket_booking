<?php
include "db.php";

$show_id=$_GET['show_id'];

$sql="
SELECT m.title,m.poster,m.director,
s.theatre_name,s.show_time,s.show_date
FROM shows s
JOIN movies m ON s.movie_id=m.id
WHERE s.id=$show_id
";

$res=$conn->query($sql);
echo json_encode($res->fetch_assoc());
?>
