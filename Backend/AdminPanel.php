<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
            <link rel="stylesheet" href="../Frontend/AdminStyle.css">
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
        <a href="profile.php" id="navlink">Profile</a>
        </div>
        <div class="navbutton2">
        <a href="logout.php" id="navlink">log out</a>
        </div>
    </nav>
</nav>
</header>
<main>
<h2 id="welcome-messege"><?php
                          try {
                            if (!isset($fname)) {
                              throw new Exception("Name not set");
                            }

                            echo "<h2 id='welcome-messege'>Welcome " . htmlspecialchars($fname) . "!</h2>";
                          } catch (Exception $e) {

              echo "<h2 id='welcome-messege'>Welcome Guest!</h2>";
          }
          ?></h2>
          <div class="container">
              <div class="box-left">
                <form action="" method="POST">
                    <label for="RoomNumber">Room Number:</label>
                    <input type="text" id="RoomNumber" name="RoomNumber" required>

                    <label for="RoomType">Room Type:</label>
                    <input type="text" id="RoomType" name="RoomType" required>

                    <label for="Capacity">Capacity:</label>
                    <input type="number" id="Capacity" name="Capacity" required>

                    <label for="Equipment">Equipment:</label>
                    <textarea id="Equipment" name="Equipment" required></textarea>

                    <label for="imgURL">Upload Image:</label>
                    <input type="file" id="imgURL" name="imgURL" required>

                    <button type="submit">Add Room</button>
                </form>
                <div class="box-right">
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

          </div>
    </main>
</body>
</html>