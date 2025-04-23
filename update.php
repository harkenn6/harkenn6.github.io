<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get target user ID from URL
if (!isset($_GET['id'])) {
    echo "<p class='error'>No user ID specified.</p>";
    exit;
}

$target_id = intval($_GET['id']);

// Fetch user data
$sql = "SELECT * FROM users WHERE id = $target_id";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo "<p class='error'>User not found.</p>";
    exit;
}

$user = $result->fetch_assoc();

// Handle update
if (isset($_POST['update'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql_update = "UPDATE users SET username = '$username', email = '$email' WHERE id = $target_id";
    if ($conn->query($sql_update) === TRUE) {
        header('Location: home.php');
        exit;
    } else {
        echo "<p class='error'>Error updating details: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-size: 14px;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Update User</h2>
    <form action="update.php?id=<?= $target_id ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

        <button type="submit" name="update">Update</button>
    </form>
</div>

</body>
</html>
