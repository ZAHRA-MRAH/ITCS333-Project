<?php
// Database connection
require('Connection.php');

// Get the POST data
$date = $_POST['date']; 
$room_id = $_POST['room_id'];



// Query to fetch available time slots for the selected date and room
$query = "SELECT * FROM availability WHERE RoomID = :room_id AND Date = :date";
$stmt = $pdo->prepare($query);
$stmt->execute([
    ':room_id' => $room_id,
    ':date' => $date
]);

$time_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate the dropdown options
if (!empty($time_slots)) {
    foreach ($time_slots as $slot) {
        $start_time = date('H:i', strtotime($slot['StartTime']));
        $end_time = date('H:i', strtotime($slot['EndTime']));
        echo "<option value='{$slot['AvailabilityID']}'>{$start_time} - {$end_time}</option>";
    }
} else {
    echo "<option value=''>No available time slots</option>";
}
?>
