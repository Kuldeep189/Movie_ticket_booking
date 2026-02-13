<?php
include "_auth.php";
include "../backend/db.php";

/* Delete Movie */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    // Delete poster file first
    $res = $conn->query("SELECT poster FROM movies WHERE id=$id");
    $movie = $res->fetch_assoc();

    if($movie && file_exists("../uploads/".$movie['poster'])){
        unlink("../uploads/".$movie['poster']);
    }

    $conn->query("DELETE FROM movies WHERE id=$id");

    header("Location: manage_movies.php");
    exit;
}

/* Fetch Movies */
$result = $conn->query("SELECT * FROM movies ORDER BY id DESC");
?>

<?php include "layout.php"; ?>

<style>
.content{
    margin-left:260px;
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
}

.movie-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:20px;
}

.movie-card{
    background:white;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
    overflow:hidden;
}

.movie-card img{
    width:100%;
    height:250px;
    object-fit:cover;
}

.movie-info{
    padding:15px;
}

.movie-info h3{
    margin:0 0 10px;
}

.status{
    display:inline-block;
    padding:5px 8px;
    border-radius:5px;
    font-size:12px;
    margin-bottom:10px;
}

.now{
    background:#16a34a;
    color:white;
}

.coming{
    background:#f59e0b;
    color:white;
}

.delete-btn{
    display:inline-block;
    margin-top:10px;
    padding:6px 10px;
    background:#dc2626;
    color:white;
    border-radius:5px;
    text-decoration:none;
    font-size:13px;
}

.delete-btn:hover{
    background:#b91c1c;
}
</style>

<div class="content">

    <h2>Manage Movies</h2>

    <div class="movie-grid">

        <?php while($row = $result->fetch_assoc()): ?>
        <div class="movie-card">

            <img src="../<?php echo $row['poster']; ?>">

            <div class="movie-info">

                <h3><?php echo $row['title']; ?></h3>

                <span class="status <?php echo ($row['status'] == 'Now Showing') ? 'now' : 'coming'; ?>">
                    <?php echo $row['status']; ?>
                </span>

                <p><strong>Duration:</strong> <?php echo $row['duration']; ?></p>
                <p><strong>Director:</strong> <?php echo $row['director']; ?></p>

                <a class="delete-btn"
                   href="?delete=<?php echo $row['id']; ?>"
                   onclick="return confirm('Delete this movie?')">
                   Delete
                </a>

            </div>
        </div>
        <?php endwhile; ?>

    </div>

</div>
