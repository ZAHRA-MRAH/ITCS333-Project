<?php
require('Connection.php');

header('Content-Type: application/json');

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

if ($action === 'fetch') {
    $roomID = $input['roomID'] ?? '';
    if (!$roomID) {
        echo json_encode(['error' => ['Room ID is required.']]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM availability WHERE RoomID = ?");
    $stmt->execute([$roomID]);
    $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($slots);
    exit;
}

if ($action === 'add') {
    $roomID = $input['roomID'] ?? '';
    $date = $input['date'] ?? '';
    $startTime = $input['startTime'] ?? '';
    $endTime = $input['endTime'] ?? '';

    $errors = [];
    if (!$roomID) $errors[] = 'Room ID is required.';
    if (!$date) $errors[] = 'Date is required.';
    if (!$startTime) $errors[] = 'Start time is required.';
    if (!$endTime) $errors[] = 'End time is required.';
    if ($startTime >= $endTime) $errors[] = 'Start time must be before end time.';

    if (!empty($errors)) {
        echo json_encode(['error' => $errors]);
        exit;
    }

    // Check for overlapping slots
    $stmt = $pdo->prepare("
        SELECT * FROM availability 
        WHERE RoomID = ? AND Date = ? AND 
        (
            (StartTime <= ? AND EndTime > ?) OR
            (StartTime < ? AND EndTime >= ?) OR
            (StartTime >= ? AND StartTime < ?)
        )
    ");
    $stmt->execute([$roomID, $date, $startTime, $startTime, $endTime, $endTime, $startTime, $endTime]);
    $existingSlots = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($existingSlots)) {
        echo json_encode(['error' => ['An overlapping slot already exists.']]);
        exit;
    }

    // Insert new slot
    $stmt = $pdo->prepare("INSERT INTO availability (RoomID, Date, StartTime, EndTime) VALUES (?, ?, ?, ?)");
    $stmt->execute([$roomID, $date, $startTime, $endTime]);
    echo json_encode(['success' => 'Slot added successfully.']);
    exit;
}


if ($action === 'delete') {
    $id = $input['id'] ?? '';
    if (!$id) {
        echo json_encode(['error' => ['Slot ID is required to delete.']]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM availability WHERE AvailabilityID = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => 'Slot deleted successfully.']);
    exit;
}

if ($action === 'update') {
    $id = $input['id'] ?? '';
    $roomID = $input['roomID'] ?? '';
    $date = $input['date'] ?? '';
    $startTime = $input['startTime'] ?? '';
    $endTime = $input['endTime'] ?? '';

    $errors = [];
    if (!$id) $errors[] = 'Slot ID is required to update.';
    if (!$roomID) $errors[] = 'Room ID is required.';
    if (!$date) $errors[] = 'Date is required.';
    if (!$startTime) $errors[] = 'Start time is required.';
    if (!$endTime) $errors[] = 'End time is required.';
    if ($startTime >= $endTime) $errors[] = 'Start time must be before end time.';

    if (!empty($errors)) {
        echo json_encode(['error' => $errors]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE availability SET Date = ?, StartTime = ?, EndTime = ? WHERE AvailabilityID = ?");
    $stmt->execute([$date, $startTime, $endTime, $id]);
    echo json_encode(['success' => 'Slot updated successfully.']);
    exit;
}

// Default response for unrecognized actions
echo json_encode(['error' => ['Invalid action.']]);
exit;
