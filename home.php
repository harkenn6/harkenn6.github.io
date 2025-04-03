<?php
// home.php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

echo '<h2>Welcome to the Home Page</h2>';
echo '<table border="1">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>
            <td>' . $row['id'] . '</td>
            <td>' . $row['username'] . '</td>
            <td>' . $row['email'] . '</td>';
    if ($row['id'] == $user_id) {
        echo '<td><a href="update.php?id=' . $row['id'] . '">Update</a></td>';
    }
    echo '<td><a href="?delete_id=' . $row['id'] . '">Delete</a></td>
          </tr>';
}

echo '</table>';

// Delete User Logic
if (isset($_GET['delete_id']) && $_SESSION['user_id'] == 1) { // Check if the user is admin (assuming admin has id 1)
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM users WHERE id = $delete_id";
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully!";
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>
