<?php
    session_start();
    require('Connection.php');
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location:login.php');
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] !== "POST"){
        header ("Location: AdminPanel.php");
        exit();
    }
    if (!isset($_POST['roomNumber']) || empty($_POST['roomNumber'])) {
        header("Location: AdminPanel.php?deletError=NoRoomSelected");
        exit();
    }
    try {
        $selectedRoom = $_POST['roomNumber'];

        $pdo->beginTransaction();

        $query = "SELECT RoomID FROM room WHERE RoomNumber = :roomNumber";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':roomNumber' => $selectedRoom]);
        $room= $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            throw new Exception("Room not found.");
        }

        $roomID = $room['RoomID'];

        $deleteAvailability = "DELETE FROM availability WHERE RoomID = :roomID";
        $availabilityStmt = $pdo->prepare($deleteAvailability);
        $availabilityStmt->execute([':roomID' => $roomID]);

        $query = "DELETE FROM room  WHERE RoomID = :roomID";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':roomID' => $roomID]);
        
        $pdo->commit();
        header("Location: AdminPanel.php?deleteSuccess=RoomDeleted");
        exit();   
    } catch(Exception $e){
        $pdo->rollBack;
        error_log("Error deleting room: " . $e->getMessage());
        header("Location: AdminPanel.php?deletError=DeleteFailed");
        exit();
        
    }

?>
