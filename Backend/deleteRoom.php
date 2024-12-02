<?php
session_start();
require('Connection.php');

// Check if the user is logged in
$user_id = $_SESSION['user_id'];
if (!isset($user_id)) {
    header('location: login.php');
    exit();
}

// Handle POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['roomNumber']) || empty($_POST['roomNumber'])) {
        header("Location: deleteRoom.php?deletError=No Room is Selected!");
        exit();
    }

    try {
        $selectedRoom = $_POST['roomNumber'];

        $pdo->beginTransaction();

        // Fetch the room
        $query = "SELECT RoomID FROM room WHERE RoomNumber = :roomNumber";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':roomNumber' => $selectedRoom]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$room) {
            throw new Exception("Room not found.");
        }

        $roomID = $room['RoomID'];

        // Delete associated availability
        $deleteAvailability = "DELETE FROM availability WHERE RoomID = :roomID";
        $availabilityStmt = $pdo->prepare($deleteAvailability);
        $availabilityStmt->execute([':roomID' => $roomID]);

        // Delete the room
        $deleteRoom = "DELETE FROM room WHERE RoomID = :roomID";
        $stmt = $pdo->prepare($deleteRoom);
        $stmt->execute([':roomID' => $roomID]);

        $pdo->commit();
        header("Location: deleteRoom.php?deleteSuccess=Room Deleted Successfully!");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Error deleting room: " . $e->getMessage());
        header("Location: deleteRoom.php?deletError=Delete Failed");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
    <script src="../Frontend/deleteRoom.js"> </script>
    <title>Delete Room</title>
</head>
<body>
<header>
    <nav class="navbar">
        <a class="navbar-logo" href="AdminPanel.php">
            <img src="../pictures/uob-logo.svg" width="40" height="40" alt="">
            UOB IT College Room Booking System
        </a>
        <div class="leftbar">
            <a href="AdminPanel.php" id="navlink">Add Room</a>
            <a href="deleteRoom.php" id="navlink">Delete Room</a>
            <a href="updateRoom.php" id="navlink">Update Room</a>
            <a href="logout.php" id="navlink">Log Out</a>
        </div>
    </nav>
</header>
<main id="main">
    <div class="box">
        <h2>Delete Room</h2>
        <?php
        if (isset($_GET['deletError'])) {
            echo "<div class='alert alert-danger'>" . htmlspecialchars($_GET['deletError']) . "</div>";
        }
        if (isset($_GET['deleteSuccess'])) {
            echo "<div class='alert alert-success'>" . htmlspecialchars($_GET['deleteSuccess']) . "</div>";
        }
        ?>
        <form id="deleteRoomForm" action="deleteRoom.php" method="post">
            <label for="roomNumber">Select Room:</label>
            <select name="roomNumber" id="roomNumber" required>
                <option value="">-- Select Room --</option>
                <?php
                $query = "SELECT * FROM room";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$room['RoomNumber']}'>Room Number: {$room['RoomNumber']} | Type: {$room['RoomType']}</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>
</main>
</body>
</html>