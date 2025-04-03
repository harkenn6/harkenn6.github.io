<?php
// update.php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    
    $sql_update = "UPDATE users SET username = '$username', email = '$email' WHERE id = $user_id";
    if ($conn->query($sql_update) === TRUE) {
        echo "Details updated successfully!";
    } else {
        echo "Error updating details: " . $conn->error;
    }
}
?>

<form action="update.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" value="<?php echo $user['username']; ?>" required><br>
    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $user['email']; ?>" required><br>
    <button type="submit" name="update">Update Details</button>
</form>
