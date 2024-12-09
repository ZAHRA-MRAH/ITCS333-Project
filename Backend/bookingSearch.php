<?php
session_start();
$user_id = $_SESSION['user_id'];

require('Connection.php'); // Assuming you have a PDO connection in this file

// Get the form data from the POST request
$RoomID = $_POST['RoomID'];
$RoomNumber = $_POST['RoomNumber'];
$Capacity = $_POST['Capacity'];
$Equipment = $_POST['Equipment'];
$date = $_POST['date'];
$time_slot = $_POST['time_slot'];

// Extract the start and end times from the time_slot
list($startTime, $endTime) = explode('-', $time_slot);

try {
    // Step 1: Check if the room is available for the given date and time
    $query = "SELECT * FROM `booking` 
              WHERE `RoomID` = :RoomID
              AND `BookingDate` = :BookingDate
              AND (
                  (`StartTime` < :EndTime AND `EndTime` > :StartTime) OR 
                  (`StartTime` < :StartTime AND `EndTime` > :EndTime)
              )";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':RoomID', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':BookingDate', $date, PDO::PARAM_STR);
    $stmt->bindParam(':StartTime', $startTime, PDO::PARAM_STR);
    $stmt->bindParam(':EndTime', $endTime, PDO::PARAM_STR);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If there is any booking conflict, return an error message
    if (count($result) > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'This room is already booked for the selected time slot.'
        ]);
        exit; // Stop further execution
    }

    // Step 2: If available, insert the booking into the database
    $query = "INSERT INTO `booking` (`userID`, `RoomID`, `BookingDate`, `StartTime`, `EndTime`, `Status`, `BookingTime`)
              VALUES (:userID, :RoomID, :BookingDate, :StartTime, :EndTime, 'Confirmed', NOW())";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userID', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':RoomID', $RoomID, PDO::PARAM_INT);
    $stmt->bindParam(':BookingDate', $date, PDO::PARAM_STR);
    $stmt->bindParam(':StartTime', $startTime, PDO::PARAM_STR);
    $stmt->bindParam(':EndTime', $endTime, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Room booked successfully!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to book the room. Please try again later.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
