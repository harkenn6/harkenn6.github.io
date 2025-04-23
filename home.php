<?php
// home.php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Logout logic
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: register.php');
    exit;
}

// DELETE logic — prevent deleting admin (id = 1)
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    if ($delete_id === 1) {
        $error_message = "Admin account cannot be deleted!";
    } else {
        $sql = "DELETE FROM users WHERE id = $delete_id";
        if ($conn->query($sql) === TRUE) {
            if ($delete_id === $user_id) {
                session_unset();
                session_destroy();
                header('Location: login.php');
                exit;
            }
        } else {
            $error_message = "Error deleting user: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
        }
        h2 {
            text-align: center;
            margin: 0 0 20px;
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .logout a {
            background-color: #333;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .logout a:hover {
            background-color: #555;
        }
        .alert {
            background-color: #f8d7da;
            padding: 10px;
            color: #721c24;
            border-radius: 5px;
            margin: 10px 0;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        a.button {
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 14px;
            color: white;
        }
        .btn-update {
            background-color: #007BFF;
        }
        .btn-delete {
            background-color: #dc3545;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logout">
        <a href="?logout=true">Logout</a>
    </div>

    <h2>Welcome to the Home Page</h2>

    <?php if (isset($error_message)) echo '<div class="alert">' . $error_message . '</div>'; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['email'] ?></td>
                <td>
                    <a href="update.php?id=<?= $row['id'] ?>" class="button btn-update">Update</a>
                    <?php if ($row['id'] == 1): ?>
                        <span style="color: gray;">Admin</span>
                    <?php else: ?>
                        <a href="?delete_id=<?= $row['id'] ?>" class="button btn-delete" onclick="return confirm('Are you sure you want to delete this account?')">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
