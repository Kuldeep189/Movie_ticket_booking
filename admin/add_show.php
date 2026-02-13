<?php
include "_auth.php";
include "../backend/db.php";

$message = "";

if(isset($_SESSION['success_msg'])){
    $message = $_SESSION['success_msg'];
    unset($_SESSION['success_msg']); 
}
$movies = $conn->query("SELECT id, title FROM movies ORDER BY title ASC");

if($_SERVER["REQUEST_METHOD"] === "POST"){

    $movie_id     = intval($_POST['movie_id']);
    $theatre_name = trim($_POST['theatre_name']);
    $show_date    = trim($_POST['show_date']);
    $show_time    = trim($_POST['show_time']);

    if($movie_id && $theatre_name && $show_date && $show_time){

        $conn->begin_transaction();

        try {

            // 1️⃣ Insert Show
            $stmt = $conn->prepare("
                INSERT INTO shows (movie_id, show_time, theatre_name, show_date)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param("isss",
                $movie_id,
                $show_time,
                $theatre_name,
                $show_date
            );

            $stmt->execute();

            $show_id = $stmt->insert_id;
            $rows = ['A','B','C','D','E','F','G','H','I','J'];

            foreach($rows as $row){

                if($row == 'A' || $row == 'B'){
                    $seat_limit = 12;
                } else {
                    $seat_limit = 10;
                }

                for($i = 1; $i <= $seat_limit; $i++){

                    $seat_no = $row . $i;

                    $seatStmt = $conn->prepare("
                        INSERT INTO seats (show_id, seat_no, status)
                        VALUES (?, ?, 'AVAILABLE')
                    ");

                    $seatStmt->bind_param("is", $show_id, $seat_no);
                    $seatStmt->execute();
                }
            }

            $conn->query("
                UPDATE movies 
                SET status = 'Now Showing'
                WHERE id = $movie_id AND status = 'Coming Soon'
            ");

            $conn->commit();

            $_SESSION['success_msg'] = "Show added & seats generated successfully!";
            header("Location: add_show.php");
            exit;

        } catch (Exception $e) {

            $conn->rollback();
            $message = "Something went wrong!";
        }

    } else {
        $message = "All fields are required!";
    }
}
?>

<?php include "layout.php"; ?>

<style>
    .popup{
    position:fixed;
    top:20px;
    right:20px;
    padding:15px 20px;
    border-radius:8px;
    color:white;
    font-weight:500;
    box-shadow:0 10px 25px rgba(0,0,0,.2);
    animation:slideIn .4s ease;
    z-index:9999;
}

.success-popup{
    background:#16a34a;
}

.close-btn{
    margin-left:15px;
    cursor:pointer;
    font-weight:bold;
}

@keyframes slideIn{
    from{
        transform:translateX(100%);
        opacity:0;
    }
    to{
        transform:translateX(0);
        opacity:1;
    }
}

.content{
    margin-left:260px;
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.form-card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
    max-width:600px;
    width:100%;
}

.form-card h2{
    margin-bottom:20px;
    text-align:center;
}

.form-card input,
.form-card select{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    border:1px solid #ddd;
}

.form-card button{
    padding:12px;
    background:#2563eb;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

.form-card button:hover{
    background:#1d4ed8;
}

.success{
    color:green;
    margin-bottom:15px;
    text-align:center;
}
.error{
    color:red;
    margin-bottom:15px;
    text-align:center;
}
</style>

<div class="content">

    <div class="form-card">
        <h2>Add Show</h2>

        <?php if($message): ?>
    <div id="popupAlert" class="popup success-popup">
        ✅ <?php echo $message; ?>
        <span class="close-btn" onclick="closePopup()">×</span>
    </div>
<?php endif; ?>



        <form method="POST">

            <select name="movie_id" required>
                <option value="">Select Movie</option>
                <?php while($movie = $movies->fetch_assoc()): ?>
                    <option value="<?php echo $movie['id']; ?>">
                        <?php echo $movie['title']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <select name="theatre_name" required>
                <option value="">Select Theatre</option>
                <option value="WW Cinemas">WW Cinemas</option>
                <option value="Cinepolis">Cinepolis</option>
                <option value="INOX">INOX</option>
                <option value="PVR Cinemas">PVR Cinemas</option>
            </select>

            <input type="date" name="show_date" required>

            <select name="show_time" required>
                <option value="">Select Time</option>
                <option value="09:00 AM">09:00 AM</option>
                <option value="12:00 PM">12:00 PM</option>
                <option value="03:00 PM">03:00 PM</option>
                <option value="06:00 PM">06:00 PM</option>
                <option value="09:00 PM">09:00 PM</option>
            </select>

            <button type="submit">Add Show</button>

        </form>
    </div>

</div>
