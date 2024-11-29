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

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<p>Booking ID: " . htmlspecialchars($row['booking_id']) . "</p>";
        echo "<p>Room ID: " . htmlspecialchars($row['room_id']) . "</p>";
        echo "<p>Check-in Date: " . htmlspecialchars($row['check_in_date']) . "</p>";
        echo "<p>Check-out Date: " . htmlspecialchars($row['check_out_date']) . "</p>";
        echo "<p>Total Price: " . htmlspecialchars($row['total_price']) . " BHD</p>";
        echo "<button onclick=\"cancelBooking(" . htmlspecialchars($row['booking_id']) . ")\">Cancel Booking</button>";
        echo "</div>";
    }
} else {
    echo "<p>No active bookings found.</p>";
}
?>
<script>
function cancelBooking(bookingId) {
    if (confirm("Are you sure you want to cancel this booking?")) {
        fetch('cancelBooking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `booking_id=${bookingId}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload to refresh booking list
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>
