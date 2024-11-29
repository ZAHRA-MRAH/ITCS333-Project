<?php
session_start();
require('Connection.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to view your bookings.";
    exit;
}

$user_id = $_SESSION['user_id']; // Get the logged-in user's ID

// Fetch active bookings for the user
$sql = "SELECT * FROM bookings WHERE user_id = ? AND status = 'active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body>

<div class="container">
    <h1>Your Active Bookings</h1>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='booking'>";
            echo "<p><strong>Booking ID:</strong> " . htmlspecialchars($row['booking_id']) . "</p>";
            echo "<p><strong>Room ID:</strong> " . htmlspecialchars($row['room_id']) . "</p>";
            echo "<p><strong>Check-in Date:</strong> " . htmlspecialchars($row['check_in_date']) . "</p>";
            echo "<p><strong>Check-out Date:</strong> " . htmlspecialchars($row['check_out_date']) . "</p>";
            echo "<p><strong>Total Price:</strong> " . htmlspecialchars($row['total_price']) . " BHD</p>";
            echo "<button class='cancel-btn' onclick=\"cancelBooking(" . htmlspecialchars($row['booking_id']) . ")\">Cancel Booking</button>";
            echo "</div>";
        }
    } else {
        echo "<p>No active bookings found.</p>";
    }
    ?>
</div>
<script src="js/scripts.js"></script> <!-- Link to external JS file -->

</body>
</html>
