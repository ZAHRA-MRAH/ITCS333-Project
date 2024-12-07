<?php
session_start();
require('AdminHeader.php');
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
    <title>Delete Room</title>
</head>

<body>

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
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Delete</button>
            </form>
        </div>
    </main>

    <!-- Confirmation bootstrap modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this room? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const deleteRoomForm = document.getElementById('deleteRoomForm');

            confirmDeleteBtn.addEventListener('click', () => {
                deleteRoomForm.submit();
            });
        });
    </script>
</body>

</html> 