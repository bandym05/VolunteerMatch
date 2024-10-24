<?php
require_once 'db.php';
require_once 'auth_middleware.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'company') {
    $position = $_POST['position'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $company_id = $_SESSION['user_id'];

    // Insert opportunity into the database
    $stmt = $pdo->prepare('INSERT INTO volunteer_postings (company_id, position, date, location, description) VALUES (?, ?, ?, ?, ?)');
    if ($stmt->execute([$company_id, $position, $date, $location, $description])) {
        echo "Opportunity posted successfully!";
    } else {
        echo "Error posting opportunity.";
    }
}
?>
