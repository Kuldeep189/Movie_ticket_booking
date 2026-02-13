<?php
include "_auth.php";
include "../backend/db.php";

/* Delete User */
if(isset($_GET['delete'])){

    $delete_id = intval($_GET['delete']);

    // Prevent admin from deleting himself
    if($delete_id != $_SESSION['user_id']){

        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
    }
 
    header("Location: manage_users.php");
    exit;
}

/* Fetch Users */
$users = $conn->query("SELECT id, name, email, role FROM users ORDER BY id DESC");
?>

<?php include "layout.php"; ?>

<style>
.content{
    margin-left:260px;
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
    border-radius:10px;
    overflow:hidden;
}

th, td{
    padding:12px;
    text-align:left;
}

th{
    background:#2563eb;
    color:white;
}

tr:nth-child(even){
    background:#f2f2f2;
}

.role-badge{
    padding:5px 8px;
    border-radius:5px;
    font-size:12px;
    color:white;
}

.admin{
    background:#dc2626;
}

.user{
    background:#16a34a;
}

.delete-btn{
    background:#dc2626;
    color:white;
    padding:6px 10px;
    border-radius:5px;
    text-decoration:none;
    font-size:13px;
}

.delete-btn:hover{
    background:#b91c1c;
}
</style>

<div class="content">

    <h2>Manage Users</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td>
                <span class="role-badge <?php echo $row['role']; ?>">
                    <?php echo ucfirst($row['role']); ?>
                </span>
            </td>
            <td>

                <?php if($row['id'] != $_SESSION['user_id']): ?>
                    <a class="delete-btn"
                       href="?delete=<?php echo $row['id']; ?>"
                       onclick="return confirm('Delete this user?')">
                       Delete
                    </a>
                <?php else: ?>
                    <span style="color:gray;">You</span>
                <?php endif; ?>

            </td>
        </tr>
        <?php endwhile; ?>

    </table>

</div>
