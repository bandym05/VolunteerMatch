<?php
// Check if user is logged in
include 'auth_middleware.php';

// Get the user's role (volunteer or company)
$role = $_SESSION['role'];
$name = $_SESSION['name'];

// Include database connection
include 'db.php';

// Display success/error messages
if (isset($_GET['success']) && $_GET['success'] === 'applied') {
    echo '<p style="color: green;">Successfully applied for the opportunity!</p>';
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'failed') {
        echo '<p style="color: red;">Failed to apply for the opportunity. Please try again.</p>';
    } elseif ($_GET['error'] === 'no_opportunity') {
        echo '<p style="color: red;">No opportunity selected.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Volunteer Matching Platform</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Include Google Maps API (replace YOUR_API_KEY with your actual API key) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBv34rZDv5AtaTRQKr8UuxczgQQYOMjwyQ"></script>

    <script>
        function fetchOpportunities() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_opportunities.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(xhr.responseText, 'text/xml');
                    const opportunities = xmlDoc.getElementsByTagName('opportunity');
                    let output = '';
                    for (let i = 0; i < opportunities.length; i++) {
                        const location = opportunities[i].getElementsByTagName('location')[0].textContent;
                        output += '<div class="card">' +
                            '<h4>' + opportunities[i].getElementsByTagName('position')[0].textContent + '</h4>' +
                            '<p><strong>Date:</strong> ' + opportunities[i].getElementsByTagName('date')[0].textContent + '</p>' +
                            '<p><strong>Location:</strong> ' + location + ' <a href="#" onclick="showMap(\'' + location + '\')">View Map</a></p>' +
                            '<p><strong>Description:</strong> ' + opportunities[i].getElementsByTagName('description')[0].textContent + '</p>' +
                            '<form action="apply_opportunity.php" method="POST">' +
                            '<input type="hidden" name="opportunity_id" value="' + opportunities[i].getElementsByTagName('id')[0].textContent + '">' +
                            '<input type="submit" class="card-btn" value="Apply">' + 
                            '</form>' +
                            '</div>'; 
                    }
                    document.getElementById('xml-opportunities-list').innerHTML = output;
                } else {
                    console.error('Error fetching opportunities: ' + xhr.status);
                }
            };
            xhr.send();
        }

        function fetchApplications() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_applications.php', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(xhr.responseText, 'text/xml');
                    const applications = xmlDoc.getElementsByTagName('application');
                    let output = '';
                    for (let i = 0; i < applications.length; i++) {
                        output += '<div class="card">' +
                            '<h4>' + applications[i].getElementsByTagName('volunteer')[0].textContent + '</h4>' +
                            '<p><strong>Email:</strong> ' + applications[i].getElementsByTagName('email')[0].textContent + '</p>' +
                            '<p><strong>Applied for:</strong> ' + applications[i].getElementsByTagName('position')[0].textContent + '</p>' +
                            '<p><strong>Date:</strong> ' + applications[i].getElementsByTagName('date')[0].textContent + '</p>' +
                            '</div>';
                    }
                    document.getElementById('applications-list').innerHTML = output;
                } else {
                    console.error('Error fetching applications: ' + xhr.status);
                }
            };
            xhr.send();
        }

        function showMap(location) {
        const modal = document.getElementById('mapModal');
        const mapArea = document.getElementById('map');

        // Check if Google Maps API is loaded
        if (typeof google === 'undefined') {
            alert('Google Maps API failed to load.');
            return;
        }

        // Append ', Eswatini' to the location for more accurate geocoding
        const fullLocation = location + ', Eswatini';

        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': fullLocation }, function(results, status) {
            if (status === 'OK') {
                const mapOptions = {
                    center: results[0].geometry.location,
                    zoom: 14
                };
                const map = new google.maps.Map(mapArea, mapOptions);

                // Place a marker at the location
                new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map
                });

                // Show the modal
                modal.style.display = 'block';
            } else {
                alert('Could not find the location: ' + status);
                console.log('Geocode error: ' + status);
            }
        });
    }

        // Function to close the map modal
        function closeModal() {
            document.getElementById('mapModal').style.display = 'none';
        }

        // Automatically fetch opportunities and applications when the page loads
        window.onload = function() {
            fetchOpportunities();
            fetchApplications();
        };
    </script>
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<div class="container">
    <h2>Your Dashboard</h2>

    <?php if ($role === 'volunteer'): ?>
        <!-- Volunteer Dashboard -->
        <section>
            <h3>Available Volunteer Opportunities</h3>
            <div id="xml-opportunities-list" class="card-container"></div>
        </section>

    <?php elseif ($role === 'company'): ?>
        <!-- Company Dashboard -->
        <section>
            <h3>Post a New Volunteer Opportunity</h3>
            <form action="post_opportunity.php" method="POST" class="form-card">
                <label for="position">Position:</label>
                <input type="text" id="position" name="position" required>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <input type="submit" class="form-btn" value="Post Opportunity">
            </form>

            <h3>Received Applications</h3>
            <div id="applications-list" class="card-container"></div>
        </section>
    <?php endif; ?>
</div>

<!-- Map Modal (hidden by default) -->
<div id="mapModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="map" style="width: 100%; height: 400px;"></div>
    </div>
</div>

<footer>
    <p>&copy; 2024 VolunteerMatch. All rights reserved.</p>
</footer>

<style>
    /* Modal styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4); /* Black with transparency */
}

.modal-content {
    background-color: #fff;
    margin: 10% auto; /* Adjusted margin */
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 600px;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
}

</style>

</body>
</html>
