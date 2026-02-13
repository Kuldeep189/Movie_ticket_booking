<style>
    .sidebar{
    width:240px;
    height:100vh;
    background:linear-gradient(180deg,#111827,#1f2937);
    position:fixed;
    left:0;
    top:0;
    padding:25px 15px;
    box-shadow:4px 0 10px rgba(0,0,0,.2);
}

.sidebar .logo{
    color:white;
    margin-bottom:30px;
    font-size:20px;
    text-align:center;
}


.sidebar a{
    display:block;
    padding:12px 15px;
    margin:8px 0;
    border-radius:8px;
    text-decoration:none;
    color:#d1d5db;
    font-weight:500;
    transition:all .3s ease;
}

.sidebar a:hover{
    background:#374151;
    color:white;
    transform:translateX(5px);
}

</style>
<div class="sidebar">
    <h2 class="logo"> Admin Panel</h2>

    <a href="dashboard.php">Dashboard</a>
    <a href="add_movie.php">Add Movie</a>
    <a href="manage_movies.php">Manage Movies</a>
    <a href="add_show.php">Add Show</a>
    <a href="manage_users.php">Users</a>
</div>
