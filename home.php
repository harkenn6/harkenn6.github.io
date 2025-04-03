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

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        td a {
            padding: 5px 10px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            font-size: 14px;
        }
        td a:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 10px;
            margin: 15px 0;
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            text-align: center;
        }
        .action-buttons {
            display: flex;
            justify-content: space-around;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to the Home Page</h2>';

    echo '<table>
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
            echo '<div class="alert">User deleted successfully!</div>';
        } else {
            echo '<div class="alert">Error deleting user: ' . $conn->error . '</div>';
        }
    }

echo '</div>
</body>
</html>';
?>

