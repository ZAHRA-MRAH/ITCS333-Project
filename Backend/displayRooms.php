<?php
require('Connection.php');

//get classrooms from the database
$query = "SELECT * FROM `room` WHERE RoomType LIKE 'Classroom' ";
$Roomstmt = $pdo->prepare($query);
$Roomstmt->execute();
$rooms = $Roomstmt->fetchAll(PDO::FETCH_ASSOC);


//get labs from the database
$query = "SELECT * FROM `room` WHERE RoomType LIKE 'Computer lab' ";
$Labstmt = $pdo->prepare($query);
$Labstmt->execute();
$labs = $Labstmt->fetchAll(PDO::FETCH_ASSOC);





?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/displaystyle.css">

</head>
<div class="display-rooms-section">

    <body>


        <div class="room-container">
            <h1> Classrooms</h1>
            <div class="room-list">
                <?php foreach ($rooms as $room) { ?>
                    <div class="room-card">
                        <img src="<?php echo $room['imgURL']; ?>" alt="Room Image" class="room-img">
                        <h3><?php echo $room['RoomNumber']; ?> </h3>
                        <p>Capacity: <?php echo $room['Capacity']; ?> people</p>
                        <a href="roomViewDetails.php?room_id=<?php echo $room['RoomID']; ?>">View Details</a>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="room-container">
            <h1> Computer labs</h1>
            <div class="room-list">
                <?php foreach ($labs as $lab) { ?>
                    <div class="room-card">
                        <img src="<?php echo $lab['imgURL']; ?>" alt="Room Image" class="room-img">
                        <h3><?php echo $lab['RoomNumber']; ?> </h3>
                        <p>Capacity: <?php echo $lab['Capacity']; ?> people</p>
                        <a href="roomViewDetails.php?room_id=<?php echo $lab['RoomID']; ?>">View Details</a>
                    </div>
                <?php } ?>
            </div>
        </div>
</div>
</body>

</html>