<?php
require_once 'db.php';

// Fetch all opportunities
$stmt = $pdo->query('SELECT * FROM volunteer_postings');
$opportunities = $stmt->fetchAll();

// Output as XML
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<opportunities>';
foreach ($opportunities as $opp) {
    echo '<opportunity>';
    echo '<id>' . htmlspecialchars($opp['id']) . '</id>';
    echo '<position>' . htmlspecialchars($opp['position']) . '</position>';
    echo '<date>' . htmlspecialchars($opp['date']) . '</date>';
    echo '<location>' . htmlspecialchars($opp['location']) . '</location>';
    echo '<description>' . htmlspecialchars($opp['description']) . '</description>';
    echo '</opportunity>';
}
echo '</opportunities>';
?>
