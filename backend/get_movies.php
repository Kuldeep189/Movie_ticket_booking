<?php
include "db.php";

if(isset($_GET['id'])){

    // Return SINGLE movie
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM movies WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    $result = $stmt->get_result();
    echo json_encode($result->fetch_assoc());

}else{

    // Return ALL movies
    $result = $conn->query("SELECT * FROM movies");

    $movies = [];

    while($row = $result->fetch_assoc()){
        $movies[] = $row;
    }

    echo json_encode($movies);
}
?>

