<?php
session_start();
require('Connection.php'); // Ensure database connection

// Check if booking_id is provided
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];
    
    // Prepare SQL to cancel the booking
    $sql = "UPDATE bookings SET status = 'canceled' WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    
    if ($stmt->execute()) {
        echo "Booking canceled successfully.";
    } else {
        echo "Error: Unable to cancel the booking.";
    }
} else {
    echo "No booking ID provided.";
}
?>
