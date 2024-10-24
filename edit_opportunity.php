<?php
// Include database connection
include 'db.php';
include 'auth_middleware.php';

// Check if opportunity ID is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['opportunity_id'])) {
    $opportunity_id = $_POST['opportunity_id'];

    // Fetch current opportunity details
    $query = "SELECT * FROM opportunities WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $opportunity_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $opportunity = $result->fetch_assoc();
    } else {
        echo "Opportunity not found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission for updating opportunity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $position = $_POST['position'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // Update the opportunity
    $update_query = "UPDATE opportunities SET position = ?, date = ?, location = ?, description = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssssi", $position, $date, $location, $description, $opportunity_id);

    if ($update_stmt->execute()) {
        header("Location: dashboard.php?success=updated");
    } else {
        header("Location: dashboard.php?error=failed");
    }

    $update_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Opportunity</title>
</head>
<body>

<h1>Edit Opportunity</h1>
<form action="edit_opportunity.php" method="POST">
    <input type="hidden" name="opportunity_id" value="<?php echo htmlspecialchars($opportunity['id']); ?>">

    <label for="position">Position:</label>
    <input type="text" id="position" name="position" value="<?php echo htmlspecialchars($opportunity['position']); ?>" required>

    <label for="date">Date:</label>
    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($opportunity['date']); ?>" required>

    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($opportunity['location']); ?>" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($opportunity['description']); ?></textarea>

    <input type="submit" name="update" value="Update Opportunity">
</form>

</body>
</html>
