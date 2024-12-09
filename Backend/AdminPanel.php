<?php
session_start();
require('Connection.php');
require('AdminHeader.php');
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location:login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
    <script src="../Frontend/addRoom.js"> </script>
    <title>Admin Panel</title>
</head>

<body>

    <main id="main">
        <div class="box">
            <h2>Add Room</h2>
            <?php
            if (isset($_GET['addSuccess'])) {
                echo "<p class='alert alert-success'>" . htmlspecialchars($_GET['addSuccess']) . "</p>";
            }

            if (isset($_GET['addError'])) {
                echo "<p class='alert alert-danger'>" . htmlspecialchars($_GET['addError']) . "</p>";
            }
            ?>
            <form id="roomForm" action="addRoom.php" method="POST" enctype="multipart/form-data">
                <div class="field input">
                    <label for="RoomNumber">Room Number:</label>
                    <input type="text" id="RoomNumber" name="RoomNumber">
                    <span class="error-message"></span>
                </div>
                <div class="field input">
                    <label for="RoomType">Room Type:</label>
                    <select id="RoomType" name="RoomType" >
                        <option value=""></option>
                        <option value="Classroom">Classroom</option>
                        <option value="Computer Lab">Computer Lab</option>
                    </select>
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="Capacity">Capacity:</label>
                    <input type="number" id="Capacity" name="Capacity">
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="Equipment">Equipment:</label>
                    <textarea id="Equipment" name="Equipment"></textarea>
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="imgURL">Upload Image:</label>
                    <input type="file" id="imgURL" name="imgURL">
                    <span class="error-message"></span>
                </div>
                <button class="btn" type="submit">Add Room</button>
            </form>
        </div>
    </main>


</body>

</html>