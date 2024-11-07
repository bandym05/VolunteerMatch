<?php
include 'auth_middleware.php';
include 'db.php';

if (isset($_GET['id'])) {
    $volunteerId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT name, email, location, skills, interests, availability, preferred_locations FROM users WHERE id = :id AND role = 'volunteer'");
    $stmt->execute(['id' => $volunteerId]);
    $volunteer = $stmt->fetch();

    if ($volunteer) {
        echo '<h3>Volunteer Profile</h3>';
        echo '<p><strong>Name:</strong> ' . htmlspecialchars($volunteer['name']) . '</p>';
        echo '<p><strong>Email:</strong> ' . htmlspecialchars($volunteer['email']) . '</p>';
        echo '<p><strong>Location:</strong> ' . htmlspecialchars($volunteer['location']) . '</p>';
        echo '<p><strong>Skills:</strong> ' . htmlspecialchars($volunteer['skills']) . '</p>';
        echo '<p><strong>Interests:</strong> ' . htmlspecialchars($volunteer['interests']) . '</p>';
        echo '<p><strong>Availability:</strong> ' . htmlspecialchars($volunteer['availability']) . '</p>';
        echo '<p><strong>Preferred Locations:</strong> ' . htmlspecialchars($volunteer['preferred_locations']) . '</p>';
    } else {
        echo '<p style="color: red;">Volunteer profile not found.</p>';
    }
} else {
    echo '<p style="color: red;">No volunteer ID provided.</p>';
}
?>
