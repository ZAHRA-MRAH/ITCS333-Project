<?php
session_start();
require('AdminHeader.php');
require('Connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
    <script src="../Frontend/updateRoom.js"> </script>
    <title>Update Room</title>
    <style>
        
        #main {
            height: 530px;
        }
        
        @media (max-width: 768px) {
            #main {
                height: 1024px;
            }
        }

        /* Media query for phones (up to 576px wide) */
        @media (max-width: 576px) {
            #main {
                height: 700px;
            }
        }
    </style>
</head>
<body>

<main id="main">
    <div class="box">
        <h2>Update Room</h2>
        <?php
        if (isset($_GET['updateSuccess'])) {
            echo "<p class='alert alert-success'>" . htmlspecialchars($_GET['updateSuccess']) . "</p>";
        }
        if (isset($_GET['updateError'])) {
            echo "<p class='alert alert-danger'>" . htmlspecialchars($_GET['updateError']) . "</p>";
        }
        ?>

        <label for="roomNumber">Select Room to Update:</label>
        <select name="roomNumber" id="roomNumber" class="form-control" onchange="fetchRoomDetails(this.value)">
            <option value="">-- Select Room --</option>
            <?php
            $query = "SELECT * FROM room";
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='" . htmlspecialchars($room['RoomNumber']) . "'>
                        Room Number: " . htmlspecialchars($room['RoomNumber']) . "
                      </option>";
            }
            ?>
        </select>
        <div id="updateRoomFormContainer" class="mt-4"></div>
    </div>
</main>
<script>
    const rooms = <?php
        $query = "SELECT * FROM room";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    ?>;

    function fetchRoomDetails(roomNumber) {
        if (roomNumber === "") {
            document.getElementById('updateRoomFormContainer').innerHTML = "";
            return;
        }

        const room = rooms.find(r => r.RoomNumber === roomNumber);
        if (!room) {
            document.getElementById('updateRoomFormContainer').innerHTML = "<p class='alert alert-danger'>Room not found.</p>";
            return;
        }

    const formHTML = `
            <form id="updateRoomForm" action="updateR_process.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="roomNumber" value="${room.RoomNumber}">
                <div class="field input">
                    <label for="newRoomType">New Room Type:</label>
                    <select id="newRoomType" name="newRoomType" class="form-control">
                        <option value="Classroom" ${room.RoomType === 'Classroom' ? 'selected' : ''}>Classroom</option>
                        <option value="Computer Lab" ${room.RoomType === 'Computer Lab' ? 'selected' : ''}>Computer Lab</option>
                    </select>
                      <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="newCapacity">New Capacity:</label>
                    <input type="number" id="newCapacity" name="newCapacity" value="${room.Capacity}" class="form-control">
                      <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="newEquipment">New Equipment:</label>
                    <textarea id="newEquipment" name="newEquipment" class="form-control" >${room.Equipment}</textarea>
                    <span class="error-message"></span>
                </div>
                <div class="field input">
                    <label for="newImgURL">Upload New Image (Optional):</label>
                    <input type="file" id="newImgURL" name="newImgURL" class="form-control">
                      <span class="error-message"></span>
                </div>

                <button class="btn btn-primary mt-3" type="submit">Update Room</button>
            </form>
        `;
        document.getElementById('updateRoomFormContainer').innerHTML = formHTML;
    }
</script>
</body>
<?php require('footer.php');?>
</html>