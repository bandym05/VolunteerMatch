<?php
// Include database connection
require_once 'db.php';

// Start session to access user information
session_start();

// Check if the user is logged in and has a company role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

// Fetch applications for the company's posted opportunities
$company_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT applications.*, users.name, users.email, volunteer_postings.position, volunteer_postings.date 
                            FROM applications 
                            JOIN users ON applications.volunteer_id = users.id 
                            JOIN volunteer_postings ON applications.id = volunteer_postings.id 
                            WHERE volunteer_postings.company_id = ?");
    $stmt->execute([$company_id]);
    
    // Output as XML
    header('Content-Type: application/xml');
    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo '<applications>';
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<application>';
        echo '<volunteer>' . htmlspecialchars($row['name']) . '</volunteer>';
        echo '<email>' . htmlspecialchars($row['email']) . '</email>';
        echo '<position>' . htmlspecialchars($row['position']) . '</position>';
        echo '<date>' . htmlspecialchars($row['date']) . '</date>';
        echo '</application>';
    }
    
    echo '</applications>';
    
} catch (PDOException $e) {
    // Handle error
    echo '<error>' . htmlspecialchars($e->getMessage()) . '</error>';
}
?>
