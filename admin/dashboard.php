<?php
include "_auth.php";
include "../backend/db.php";

$totalMovies = $conn->query("SELECT COUNT(*) as total FROM movies")->fetch_assoc()['total'];
$totalUsers = $conn->query("SELECT COUNT(*) as total FROM users")->fetch_assoc()['total'];
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM bookings")->fetch_assoc()['total'];
?>

<?php include "layout.php"; ?>

<div style="margin-left:260px;padding:30px;background:#f4f6f9;min-height:100vh;">

    <h1 style="margin-bottom:30px;text-align:center;">Admin Dashboard</h1>

    <div style="display:flex;gap:20px;flex-wrap:wrap;">

        <div style="flex:1;min-width:200px;background:white;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.1);">
            <h3>Total Movies</h3>
            <h2><?php echo $totalMovies; ?></h2>
        </div>

        <div style="flex:1;min-width:200px;background:white;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.1);">
            <h3>Total Users</h3>
            <h2><?php echo $totalUsers; ?></h2>
        </div>

        <div style="flex:1;min-width:200px;background:white;padding:20px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.1);">
            <h3>Total Bookings</h3>
            <h2><?php echo $totalBookings; ?></h2>
        </div>

    </div>

</div>
