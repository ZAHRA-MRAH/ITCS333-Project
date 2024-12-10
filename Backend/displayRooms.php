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
        <h1 style="text-align : center; margin-top: 50px; color: #2F5175;"> Browse Rooms</h1>
        <div class="room-container">
            <hr>
            <h2>Classrooms</h2>
            <div class="room-list">
                <?php foreach ($rooms as $room) { ?>
                    <?php
                    // Fetch the number of reviews for the current room
                    $room_id = $room['RoomID']; // Match the column name in the database
                    $count_reviews = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE RoomID = ?");
                    $count_reviews->execute([$room_id]);
                    $total_reviews = $count_reviews->fetchColumn() ?: 0; // Default to 0 if no reviews
                    ?>
                    <div class="room-card">
                        <img src="<?php echo $room['imgURL']; ?>" alt="Room Image" class="room-img expandable-image" id="image<?php echo $room['RoomID']; ?>" data-room-number="<?php echo $room['RoomNumber']; ?>">
                        <p class="total-reviews">⭐ Reviews: <span><?php echo $total_reviews; ?></span></p>


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

                                    </div>
                                    <div class="modal-body">
                                        <div class="capacity">
                                            <img src="../pictures/people.png" alt="Capacity Icon" class="icon">
                                            <h4>Capacity:</h4>
                                        </div>
                                        <p> <?php echo $room['Capacity']; ?> people</p>

                                        <div class="equipment">
                                            <img src="../pictures/blackboard.png" alt="Equipment Icon" class="icon">
                                            <h4>Equipment:</h4>
                                        </div>
                                        <ul>
                                            <?php
                                            $equipment = explode("\n", $room['Equipment']);
                                            foreach ($equipment as $item) {

                                                echo '<li>' . trim($item) . '</li>';
                                            }
                                            ?>
                                        </ul>


                                    </div>
                                    <div class="modal-footer">
                                        <!-- Change the color of the Close button using a custom class or inline style -->
                                        <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>

                                        <!-- Form to send room details to booking.php -->
                                        <form method="POST" action="booking.php">
                                            <input type="hidden" name="RoomID" value="<?php echo $room['RoomID']; ?>">
                                            <input type="hidden" name="RoomNumber" value="<?php echo $room['RoomNumber']; ?>">
                                            <input type="hidden" name="Capacity" value="<?php echo $room['Capacity']; ?>">
                                            <input type="hidden" name="Equipment" value="<?php echo $room['Equipment']; ?>">
                                            <button type="submit" class="btn custom-book-button">Book Room</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="room-container">
            <h2>Computer Labs</h2>
            <div class="room-list">
                <?php foreach ($labs as $lab) { ?>
                    <?php
                    // Get the number of reviews for the current lab
                    $lab_id = $lab['RoomID'];
                    $count_reviews = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE RoomID = ?");
                    $count_reviews->execute([$lab_id]);
                    $total_reviews = $count_reviews->fetchColumn() ?: 0; // Default to 0 if no reviews
                    ?>

                    <div class="room-card">
                        <img src="<?php echo $lab['imgURL']; ?>" alt="Room Image" class="room-img expandable-image" id="image<?php echo $lab['RoomID']; ?>" data-room-number="<?php echo $lab['RoomNumber']; ?>">
                        <p class="total-reviews">⭐ Reviews: <span><?php echo $total_reviews; ?></span></p>
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
                                        <div class="capacity">
                                            <img src="../pictures/people.png" alt="Capacity Icon" class="icon">
                                            <h4>Capacity:</h4>
                                        </div>
                                        <p> <?php echo $lab['Capacity']; ?> people</p>
                                        <div class="equipment">
                                            <img src="../pictures/blackboard.png" alt="Equipment Icon" class="icon">
                                            <h4>Equipment:</h4>
                                        </div>
                                        <ul>
                                            <?php
                                            $equipment = explode("\n", $lab['Equipment']);
                                            foreach ($equipment as $item) {

                                                echo '<li>' . trim($item) . '</li>';
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>

                                        <!-- Form to send room details to booking.php -->
                                        <form method="POST" action="booking.php">
                                            <input type="hidden" name="RoomID" value="<?php echo $lab['RoomID']; ?>">
                                            <input type="hidden" name="RoomNumber" value="<?php echo $lab['RoomNumber']; ?>">
                                            <input type="hidden" name="Capacity" value="<?php echo $lab['Capacity']; ?>">
                                            <input type="hidden" name="Equipment" value="<?php echo $lab['Equipment']; ?>">
                                            <button type="submit" class="btn custom-book-button">Book Room</button>
                                        </form>
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
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
        }



        .modal-title {
            font-size: 20px;
            font-weight: bold;
        }

        .modal-body {
            text-align: center;
        }

        .modal-body img {
            max-width: 100%;
            max-height: 500px;
            object-fit: contain;
            border-radius: 5px;
        }

        .expandable-image {
            transition: opacity 0.3s ease;
        }

        .expandable-image:hover {
            opacity: 0.7;
        }



        /* make the background of the image modal transparent */
        #imageModal .modal-content {
            background-color: transparent;
            border: none;
        }

        #imageModal .modal-header {
            background-color: transparent;
            border-bottom: none;
        }

        #imageModal .modal-body {
            background-color: transparent;
        }


        #imageModal .modal-header .btn-close {
            color: #fff !important;
            font-size: 30px;
            border: none;
        }

        #imageModal .modal-header .btn-close:hover {
            color: #ccc;
            cursor: pointer;
        }

        /* lighten the Room Number text */
        #imageModal #caption {
            color: #fff !important;
            font-size: 20px;
            font-weight: bold;
        }

        .modal-body h4 {
            font-size: 16px;
            color: #42413E;
            margin-bottom: 5px;
        }

        /* custom style for Close button */

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
            color: white;
        }


        /* custom style for Book Room button */
        .custom-book-button {
            background-color: #2F5175;
            color: #FEFEFE !important;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
        }

        .custom-book-button:hover {
            background-color: #1B61AC;
            cursor: pointer;
            text-decoration: none !important;
        }
    </style>


    <!-- The Modal for Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="expandedImg" class="img-fluid" src="" alt="Room Image" />
                    <p id="caption" class="text-center"></p>
                </div>
            </div>
        </div>
    </div>


    <script>
        window.onload = function() {
            // get all the images with class "expandable-image"
            var images = document.querySelectorAll('.expandable-image');

            images.forEach(function(image) {
                image.onclick = function() {
                    // open the Bootstrap modal
                    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
                    modal.show();

                    // set the image source and caption dynamically
                    var modalImg = document.getElementById("expandedImg");
                    var captionText = document.getElementById("caption");

                    // set the clicked image as the modal image
                    modalImg.src = this.src;

                    // get the room number from the data-room-number attribute
                    var roomNumber = this.getAttribute("data-room-number");

                    // set the caption to the room number
                    captionText.innerHTML = "Room Number: " + roomNumber;
                };
            });
        };
    </script>


</body>




</html>