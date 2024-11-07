<?php
// Check if user is logged in
include 'auth_middleware.php';

// Get user details from session and database
$role = $_SESSION['role'];
$name = $_SESSION['name'];
$userId = $_SESSION['user_id'];
include 'db.php';

// Fetch user data
function fetchUserData($userId) {
    global $pdo;
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch();
}

$user = fetchUserData($userId);
if (!$user) {
    echo "User not found.";
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle location and basic info update
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'basic_info') {
        $location = $_POST['location'];
        $sql = "UPDATE users SET location = :location WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['location' => $location, 'id' => $userId]);
    }
    // Handle skills and preferences update
    elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'skills_preferences') {
        $skills = $_POST['skills'];
        $interests = $_POST['interests'];
        $availability = $_POST['availability'];
        $preferred_locations = $_POST['preferred_locations'];
        $sql = "UPDATE users SET skills = :skills, interests = :interests, availability = :availability, preferred_locations = :preferred_locations WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'skills' => $skills,
            'interests' => $interests,
            'availability' => $availability,
            'preferred_locations' => $preferred_locations,
            'id' => $userId
        ]);
    }
    // Handle communication preferences update
    elseif (isset($_POST['form_type']) && $_POST['form_type'] === 'communication_preferences') {
        $receive_email_updates = isset($_POST['email-updates']) ? 1 : 0;
        $receive_sms_updates = isset($_POST['sms-updates']) ? 1 : 0;
        $subscribe_newsletter = isset($_POST['newsletter']) ? 1 : 0;
        $sql = "UPDATE users SET receive_email_updates = :receive_email_updates, receive_sms_updates = :receive_sms_updates, subscribe_newsletter = :subscribe_newsletter WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'receive_email_updates' => $receive_email_updates,
            'receive_sms_updates' => $receive_sms_updates,
            'subscribe_newsletter' => $subscribe_newsletter,
            'id' => $userId
        ]);
    }
    // Refresh data after update
    $user = fetchUserData($userId);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Profile - Volunteer Matching Platform</title>
     <style>
        
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-container h2 {
            text-align: center;
        }
        .profile-info, .skills-preferences, .communication-preferences {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"], input[type="email"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .save-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff6f61;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #2575fc;
        }
        .upload-btn {
            background-color: #2575fc;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>User Profile</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <section class="profile-container">
        <h2>Manage Your Profile</h2>

        <!-- Basic Info Section -->
        <form action="profile.php" method="POST">
            <input type="hidden" name="form_type" value="basic_info">
            <label for="username">Username</label>
            <input type="text" id="username" readonly="readonly" name="username" value="<?= htmlspecialchars($user['name']) ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" readonly="readonly" value="<?= htmlspecialchars($user['email']) ?>">

            <label for="location">Location</label>
            <input type="text" id="location" name="location" value="<?= htmlspecialchars($user['location']) ?>">
            
            <button type="submit" class="save-btn">Save Basic Info</button>
        </form>

        <!-- Skills and Preferences Section -->
        <form action="profile.php" method="POST">
            <input type="hidden" name="form_type" value="skills_preferences">
            <h3>Skills</h3>
            <input type="text" name="skills" placeholder="E.g., Teaching, Web Development" value="<?= htmlspecialchars($user['skills']) ?>">

            <h3>Interests</h3>
            <input type="text" name="interests" placeholder="E.g., Education, Environment" value="<?= htmlspecialchars($user['interests']) ?>">

            <h3>Availability</h3>
            <select name="availability">
                <option value="weekends" <?= $user['availability'] == 'weekends' ? 'selected' : '' ?>>Weekends</option>
                <option value="weekdays" <?= $user['availability'] == 'weekdays' ? 'selected' : '' ?>>Weekdays</option>
                <option value="evenings" <?= $user['availability'] == 'evenings' ? 'selected' : '' ?>>Evenings</option>
                <option value="anytime" <?= $user['availability'] == 'anytime' ? 'selected' : '' ?>>Anytime</option>
            </select>

            <h3>Preferred Locations</h3>
            <input type="text" name="preferred_locations" value="<?= htmlspecialchars($user['preferred_locations']) ?>">

            <button type="submit" class="save-btn">Save Preferences</button>
        </form>

        <!-- Communication Preferences Section -->
        <form action="profile.php" method="POST">
            <input type="hidden" name="form_type" value="communication_preferences">
            <label>
                <input type="checkbox" name="email-updates" <?= $user['receive_email_updates'] ? 'checked' : '' ?>>
                Receive Email Updates
            </label>
            <label>
                <input type="checkbox" name="sms-updates" <?= $user['receive_sms_updates'] ? 'checked' : '' ?>>
                Receive SMS Notifications
            </label>
            <label>
                <input type="checkbox" name="newsletter" <?= $user['subscribe_newsletter'] ? 'checked' : '' ?>>
                Subscribe to Monthly Newsletter
            </label>
            <button type="submit" class="save-btn">Update Communication Preferences</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 VolunteerMatch. All rights reserved.</p>
    </footer>
</body>
</html>
