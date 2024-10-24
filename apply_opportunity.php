<?php

include 'auth_middleware.php';

// Include database connection
include 'db.php';

// Get the volunteer ID from the session
$volunteer_id = $_SESSION['user_id'];

// Get the opportunity ID from the POST request
if (isset($_POST['opportunity_id'])) {
    $opportunity_id = $_POST['opportunity_id'];

    // Prepare and execute the SQL query to insert the application into the database
    $query = "INSERT INTO applications (volunteer_id, posting_id, application_date) VALUES (?, ?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$volunteer_id, $opportunity_id]);

    if ($stmt->execute()) {
        // Redirect back to the dashboard with a success message
        header("Location: dashboard.php?success=applied");
    } else {
        // Redirect back to the dashboard with an error message
        header("Location: dashboard.php?error=failed");
    }

    // Close statement and connection
    $stmt->close();
    $pdo = null; // Close PDO connection
} else {
    // Redirect if opportunity_id is not set
    header("Location: ../dashboard.php?error=no_opportunity");
    exit;
}
?>
