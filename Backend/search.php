<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    require('Connection.php');
    
 

    $room_type = $_POST['room_type'];
    $booking_date = $_POST['date'];
    $time_slot = $_POST['time_slot'];

    // Split the time slot into start_time and end_time
    list($start_time, $end_time) = explode('-', $time_slot);

    $query = "
        SELECT r.RoomID, r.RoomNumber, r.RoomType, r.Capacity, r.Equipment, r.imgURL 
        FROM room r
        WHERE r.RoomType = :room_type
        AND r.RoomID NOT IN (
            SELECT b.RoomID 
            FROM booking b 
            WHERE b.BookingDate = :booking_date 
            AND (
                (b.StartTime <= :end_time AND b.EndTime >= :start_time)
            )
        )
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':room_type', $room_type, PDO::PARAM_STR);
    $stmt->bindParam(':booking_date', $booking_date, PDO::PARAM_STR);
    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);
    $stmt->execute();

    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($rooms);
    exit;
}


?>