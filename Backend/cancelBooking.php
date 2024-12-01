<?php
session_start();
require('Connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    try {
        // update booking status to 'Cancelled'
        $query = "UPDATE booking SET Status = 'Cancelled' WHERE BookingID = :booking_id AND userID = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success_message'] = "Booking cancelled successfully!";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Failed to cancel booking: " . $e->getMessage();
    }

    header("Location: viewBookings.php");
    exit;
}
?>
