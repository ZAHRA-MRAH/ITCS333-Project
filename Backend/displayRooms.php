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


<body>

    <div class="display-rooms-section">
        <div class="room-container">
            <h1>Classrooms</h1>
            <div class="room-list">
                <?php foreach ($rooms as $room) { ?>
                    <div class="room-card">
                        <img src="<?php echo $room['imgURL']; ?>" alt="Room Image" class="room-img">
                        <h3><?php echo $room['RoomNumber']; ?> </h3>


                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary custom-book-button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $room['RoomID']; ?>">
                            View Details
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modal<?php echo $room['RoomID']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $room['RoomID']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?php echo $room['RoomID']; ?>">Room <?php echo $room['RoomNumber']; ?> Details</h5>
                                        <!-- Removed the close (X) button here -->
                                    </div>
                                    <div class="modal-body">
                                        <h4>Capacity:</h4><p> <?php echo $room['Capacity']; ?> people</p>
                                        <h4>Equipment:</h4>
                                        <ul>
                                            <?php
                                            $equipment = explode("\n", $room['Equipment']); 
                                            foreach ($equipment as $item) {
                                                
                                                echo '<li>'.trim($item).'</li>';
                                            }
                                            ?>
                                        </ul>

                                    </div>
                                    <div class="modal-footer">
                                        <!-- Change the color of the Close button using a custom class or inline style -->
                                        <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>

                                        <!-- Book button that redirects to the booking page -->
                                        <a href="bookingPage.php?room_id=<?php echo $room['RoomID']; ?>" class="btn custom-book-button">Book Room</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="room-container">
            <h1>Computer Labs</h1>
            <div class="room-list">
                <?php foreach ($labs as $lab) { ?>
                    <div class="room-card">
                        <img src="<?php echo $lab['imgURL']; ?>" alt="Room Image" class="room-img">
                        <h3><?php echo $lab['RoomNumber']; ?> </h3>

                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary custom-book-button" data-bs-toggle="modal" data-bs-target="#modal<?php echo $lab['RoomID']; ?>">
                            View Details
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="modal<?php echo $lab['RoomID']; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $lab['RoomID']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel<?php echo $lab['RoomID']; ?>">Room <?php echo $lab['RoomNumber']; ?> Details</h5>

                                    </div>
                                    <div class="modal-body">
                                        <h4>Capacity:</h4><p> <?php echo $lab['Capacity']; ?> people</p>
                                        <h4>Equipment:</h4>
                                        <ul>
                                            <?php
                                           $equipment = explode("\n", $lab['Equipment']); 
                                            foreach ($equipment as $item) {
                                               
                                                echo '<li>'.trim($item).'</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>

                                        <!-- Book button that redirects to the booking page -->
                                        <a href="bookingPage.php?room_id=<?php echo $lab['RoomID']; ?>" class="btn custom-book-button">Book Room</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <style>
        /* Custom style for Close button */
        .custom-close-color {
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
        }


        .custom-close-color:hover {
            background-color: #c82333;
            cursor: pointer;
        }

        /* Custom style for Book Room button */
        .custom-book-button {
            background-color: #b393d3;
            color: white !important;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
        }


        .custom-book-button:hover {
            background-color: #553c9a;
            cursor: pointer;
            text-decoration: none !important;
        }
    </style>



</body>

</html>