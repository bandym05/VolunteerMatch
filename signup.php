<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Insert user data into the database
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    if ($stmt->execute([$name, $email, $password, $role])) {
        header('Location: login.html');
        exit();
    } else {
        echo "Error creating account. Please try again.";
    }
}
?>
