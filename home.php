<?php
// home.php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// DELETE USER LOGIC (Only Admin Can Delete)
if (isset($_GET['delete_id']) && $_SESSION['user_id'] == 1) { // Only admin can delete
    $delete_id = intval($_GET['delete_id']); // Sanitize input
    if ($delete_id != 1) { // Prevent deleting the admin account
        $sql = "DELETE FROM users WHERE id = $delete_id";
        if ($conn->query($sql) === TRUE) {
            header('Location: home.php'); // Redirect to refresh the list
            exit;
        } else {
            $error_message = "Error deleting user: " . $conn->error;
        }
    } else {
        $error_message = "Admin account cannot be deleted!";
    }
}

// FETCH ALL USERS
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
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to the Home Page</h2>';

// Display error message if any
if (isset($error_message)) {
    echo '<div class="alert">' . $error_message . '</div>';
}

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

    echo '<td>';
    // Update button (Only for the logged-in user)
    if ($row['id'] == $user_id) {
        echo '<a href="update.php?id=' . $row['id'] . '">Update</a> ';
    }

    // Delete button (Only for admin)
    if ($_SESSION['user_id'] == 1 && $row['id'] != 1) { // Prevent deleting admin
        echo '<a href="?delete_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete this user?\')">Delete</a>';
    }
    echo '</td></tr>';
}

echo '</table>
</div>
</body>
</html>';
?>

