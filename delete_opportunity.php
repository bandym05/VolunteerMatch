<?php
// Include database connection
include 'db.php';
include 'auth_middleware.php';

// Check if opportunity ID is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['opportunity_id'])) {
    $opportunity_id = $_POST['opportunity_id'];

    // Delete the opportunity
    $query = "DELETE FROM opportunities WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $opportunity_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=deleted");
    } else {
        header("Location: dashboard.php?error=failed");
    }

    $stmt->close();
} else {
    header("Location: dashboard.php?error=invalid");
}

$conn->close();
?>
