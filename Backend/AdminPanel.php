<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <link rel="stylesheet" href="../Frontend/AdminStyle.css">
            <script src="../Frontend/addRoom.js"> </script>
            <title>Admin Panel</title>
        </head>
<body>
<header>

<nav class="navbar">
    <a class="navbar-logo" href="#">
        <img src="../pictures/uob-logo.svg" width="40" height="40" class="d-inline-block align-top" alt="">
        UOB IT College Room Booking System
    </a>
    <nav class="leftbar">
        <div class="navbutton1">
        <a href="#Home" id="navlink">Home</a>
        </div>
        <div class="navbutton2">
        <a href="logout.php" id="navlink">log out</a>
        </div>
    </nav>
</nav>
</header>
<main id="main">
                <div class="box-left">
                    <h2>Add Room</h2>
                    <?php
                        if (isset($_GET['success'])) {
                            echo "<p class='alert alert-success'>" . htmlspecialchars($_GET['success']) . "</p>";
                        }

                        if (isset($_GET['error'])) {
                            echo "<p class='alert alert-danger'>" . htmlspecialchars($_GET['error']) . "</p>";
                        }
                        ?>
                    <form id="roomForm" action="addRoom.php" method="POST" enctype="multipart/form-data">
                        <div class="field input">
                            <label for="RoomNumber">Room Number:</label>
                            <input type="text" id="RoomNumber" name="RoomNumber" required>
                            <span class="error-message"></span>
                        </div>
                        <div class="field input">
                            <label for="RoomType">Room Type:</label>
                            <select id="RoomType" name="RoomType" required>
                            <option value=""></option>
                                <option value="Classroom">Classroom</option>
                                <option value="Computer Lab">Computer Lab</option>
                                <option value="Meeting Room">Meeting Room</option>
                            </select>
                            <span class="error-message"></span>
                        </div>

                        <div class="field input">
                            <label for="Capacity">Capacity:</label>
                            <input type="number" id="Capacity" name="Capacity" required>
                            <span class="error-message"></span>
                        </div>

                        <div class="field input">
                            <label for="Equipment">Equipment:</label>
                            <textarea id="Equipment" name="Equipment" required></textarea>
                            <span class="error-message"></span>
                        </div>

                        <div class="field input">
                            <label for="imgURL">Upload Image:</label>
                            <input type="file" id="imgURL" name="imgURL" required>
                            <span class="error-message"></span>
                        </div>
                        <button class="btn" type="submit">Add Room</button>
                    </form>
                </div>
                <div class="box-right">
                    <h2>Update Room</h2>
                    Update
                </div>
                <div class="box-bottom">
                    <h2>Delete Room</h2>
                <?php
                    require("Connection.php");
                    $query = "SELECT * FROM `room` ";
                    $Roomstmt = $pdo->prepare($query);
                    $Roomstmt->execute();
                    if ($Roomstmt->rowCount()>0){
                    echo "<table>
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Room Type</th>
                            <th>Capacity</th>
                            <th>Equipment</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>";
                    while ( $rooms = $Roomstmt->fetch(PDO::FETCH_ASSOC)){
                        echo "<tr>
                        <td>{$rooms['RoomNumber']}</td>
                        <td>{$rooms['RoomType']}</td>
                        <td>{$rooms['Capacity']}</td>
                        <td>{$rooms['Equipment']}</td>
                        <td><img src='{$rooms['imgURL']}' alt='Room Image' width='100'></td>
                        </tr>";    
                    }
                    echo "</tbody></table>";    
                    
                    } else {echo "No rooms found";};
                ?>
              </div>
    </main>
</body>
</html>