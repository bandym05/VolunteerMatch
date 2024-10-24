<?php
// Start the session
session_start();

// Include database connection
include 'db.php';

// Check if the user is logged in and has the 'company' role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'company') {
    echo '<error>You do not have permission to access this resource.</error>';
    exit();
}

// Get the company ID from the session (assuming company ID is stored in session)
$companyId = $_SESSION['company_id']; // Ensure this is set during login

// Prepare and execute the query to fetch company opportunities
$query = "SELECT id, position, date, location, description FROM opportunities WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $companyId);
$stmt->execute();
$result = $stmt->get_result();

// Start XML output
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<opportunities>';

// Fetch each opportunity and append to XML
while ($row = $result->fetch_assoc()) {
    echo '<opportunity>';
    echo '<id>' . htmlspecialchars($row['id']) . '</id>';
    echo '<position>' . htmlspecialchars($row['position']) . '</position>';
    echo '<date>' . htmlspecialchars($row['date']) . '</date>';
    echo '<location>' . htmlspecialchars($row['location']) . '</location>';
    echo '<description>' . htmlspecialchars($row['description']) . '</description>';
    echo '</opportunity>';
}

// Close XML output
echo '</opportunities>';

// Close statement and connection
$stmt->close();
$conn->close();
?>
