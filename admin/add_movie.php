<?php
include "_auth.php";
include "../backend/db.php";

$message = "";

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $title        = trim($_POST['title']);
    $duration     = trim($_POST['duration']);
    $status       = trim($_POST['status']);
    $description  = trim($_POST['description']);
    $director     = trim($_POST['director']);
    $cast_members = trim($_POST['cast_members']);
    $trailer_url  = trim($_POST['trailer_url']);

    $posterName = $_FILES['poster']['name'];
    $posterTmp  = $_FILES['poster']['tmp_name'];

    $uploadPath = "../uploads/" . basename($posterName);

    if(move_uploaded_file($posterTmp, $uploadPath)){

        $stmt = $conn->prepare("
            INSERT INTO movies 
            (title, duration, status, poster, description, director, cast_members, trailer_url)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssss",
            $title,
            $duration,
            $status,
            $posterName,
            $description,
            $director,
            $cast_members,
            $trailer_url
        );

        $stmt->execute();

        $message = "Movie added successfully!";
    } else {
        $message = "Poster upload failed.";
    }
}
?>

<?php include "layout.php"; ?>

<style>
.form-container{
    display: flex;
    justify-content: center;
    align-items: center;
    margin-left:260px;
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
}

.movie-form{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
    max-width:700px;
}

.movie-form h2{
    margin-bottom:20px;
}

.movie-form input,
.movie-form textarea,
.movie-form select{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ddd;
    font-size:14px;
}

.movie-form textarea{
    height:100px;
    resize:none;
}

.movie-form button{
    padding:12px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
}

.movie-form button:hover{
    background:#1d4ed8;
}
.success-msg{
    color:green;
    margin-bottom:15px;
}
.add{
     display: flex;
    justify-content: center;
    align-items: center;
    font-style: italic;
    font-size: 60;
}
</style>

<div class="form-container">

    <div class="movie-form">
        <h2 class='add'>Add Movie</h2>

        <?php if($message): ?>
            <p class="success-msg"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <input type="text" name="title" placeholder="Movie Title" required>

            <input type="text" name="duration" placeholder="Duration (e.g., 120,60,145)" required>

            <select name="status" required>
                <option value="">Select Status</option>
                <option value="NOW_SHOWING">NOW_SHOWING</option>
                <option value="UPCOMING">UPCOMING</option>
                <option value="TRENDING">TRENDING</option>
            </select>

            <input type="text" name="director" placeholder="Director Name">

            <input type="text" name="cast_members" placeholder="Cast Members (comma separated)">

            <input type="text" name="trailer_url" placeholder="Trailer URL (YouTube link)">

            <textarea name="description" placeholder="Movie Description"></textarea>

            <input type="file" name="poster" required>

            <button type="submit">Add Movie</button>

        </form>
    </div>

</div>
