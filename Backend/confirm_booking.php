<?php
session_start();
require('Connection.php');

// check if the user is logged in and save user id 
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// retrieve POST data
$room_id = $_POST['room_id'];
$date = $_POST['date'];
$time_slot = $_POST['time_slot'];

// get StartTime and EndTime for the selected time slot
$query = "SELECT StartTime, EndTime FROM availability WHERE AvailabilityID = :time_slot";
$stmt = $pdo->prepare($query);
$stmt->execute([':time_slot' => $time_slot]);
$time = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$time) {
    echo json_encode(['success' => false, 'message' => 'Invalid time slot selected.']);
    exit;
}

$start_time = $time['StartTime'];
$end_time = $time['EndTime'];

// check for conflicts
$query = "
    SELECT COUNT(*) AS conflict_count 
    FROM booking 
    WHERE RoomID = :room_id 
    AND BookingDate = :date 
    AND ((StartTime < :end_time AND EndTime > :start_time))
";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':room_id' => $room_id,
    ':date' => $date,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

$conflict = $stmt->fetchColumn();

if ($conflict > 0) {
    echo json_encode(['success' => false, 'message' => 'The selected time slot is already booked.']);
    exit;
}

// no conflict, insert the booking
$query = "
    INSERT INTO booking (userID, RoomID, BookingDate, StartTime, EndTime, Status, BookingTime) 
    VALUES (:user_id, :room_id, :date, :start_time, :end_time, 'Confirmed', NOW())
";
$stmt = $pdo->prepare($query);
$success = $stmt->execute([
    ':user_id' => $user_id,
    ':room_id' => $room_id,
    ':date' => $date,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

if ($success) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to confirm booking.']);
}
