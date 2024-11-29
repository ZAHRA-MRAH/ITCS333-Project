<?php
session_start();
require('Connection.php');

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: login.php");
  exit;
}

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];

  // Fetch the user's name
  $query = "SELECT * FROM users WHERE userID = :user_id";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    $fname = $user['FirstName'];
    $profilePic = $user['ProfilePic'];
  }
}


// get time slots from database
$query = "SELECT AvailabilityID, StartTime, EndTime FROM availability";
$stmt = $pdo->query($query);
$time_slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <link rel="stylesheet" href="../Frontend/homestyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="../Frontend/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
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
          <a href="homepage.php" id="navlink">Home</a>
        </div>

        <div class="navbutton2">
          <a href="#ViewBooking" id="navlink">View Booking</a>

        </div>

        <div class="navbutton2">
          <a href="profile.php" id="navlink">Profile</a>

        </div>

        <div class="navbutton2">
          <a href="logout.php" id="navlink">log out</a>

        </div>
      </nav>

      <nav class="rightbar">
        <div class="profile">
          <img src=<?php echo $profilePic ?> alt="Profile">
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
      <div class="box form-box">
        <header>Search for Available Rooms:</header>
        <form id="searchForm">
          <select class="form-select" name="room_type" aria-label="Default select example">
            <div id="options">
              <option selected>Room Type</option>
              <option value="Classroom">Classroom</option>
              <option value="Computer Lab">Computer Lab</option>

            </div>
          </select>


          <div class="Date">
            <span class="Datespan">Pick a Date</span><br>
            <input type="date" name="booking_date" required>
          </div>

          <div class="Time">
            <span class="Timespan">Select Time Slot</span><br>
            <select class="form-select" name="time_slot" required>
              <option selected disabled>Select a time slot</option>
              <option value="08:00-10:00">8:00 AM - 10:00 AM</option>
              <option value="10:00-12:00">10:00 AM - 12:00 PM</option>
              <option value="12:00-14:00">12:00 PM - 2:00 PM</option>
              <option value="14:00-16:00">2:00 PM - 4:00 PM</option>

            </select>
          </div>


          <input type="submit" id="searchbtn" value="Search" style="margin-top: 75px;">
        </form>
      </div>
    </div>

    <script>
      document.getElementById('searchForm').addEventListener('submit', async function(e) {
        e.preventDefault(); // Prevent traditional form submission

        const formData = new FormData(e.target);

        // Send fetch request
        const response = await fetch('search.php', {
          method: 'POST',
          body: formData,
        });

        const rooms = await response.json();

        const resultsDiv = document.getElementById('results');
        resultsDiv.innerHTML = '<div class="room-list"></div>'; // Add a container for the grid layout
        const roomListDiv = resultsDiv.querySelector('.room-list');

        if (rooms.length > 0) {
          rooms.forEach((room) => {
            const roomCard = `
                <div class="room-card">
                    <img class="room-img" src="${room.imgURL}" alt="Room Image">
                    <h3>Room ${room.RoomNumber}</h3>
                    <button type="button" class="btn btn-primary custom-book-button" data-bs-toggle="modal" data-bs-target="#modal${room.RoomID}">
                        View Details
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="modal${room.RoomID}" tabindex="-1" aria-labelledby="modalLabel${room.RoomID}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel${room.RoomID}">Room ${room.RoomNumber} Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h4>Capacity:</h4>
                                    <p>${room.Capacity} people</p>
                                    <h4>Equipment:</h4>
                                    <ul>
                                        ${room.Equipment.split('\n').map((item) => `<li>${item.trim()}</li>`).join('')}
                                    </ul>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>
                                    <form method="POST" action="booking.php">
                                        <input type="hidden" name="RoomID" value="${room.RoomID}">
                                        <input type="hidden" name="RoomNumber" value="${room.RoomNumber}">
                                        <input type="hidden" name="Capacity" value="${room.Capacity}">
                                        <input type="hidden" name="Equipment" value="${room.Equipment}">
                                        <button type="submit" class="btn custom-book-button">Book Room</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            roomListDiv.innerHTML += roomCard; // Append each room card with modal
          });
        } else {
          resultsDiv.innerHTML = '<div class="alert alert-danger custom-alert" role="alert" >No rooms available for the selected criteria.</div>';
        }
      });
    </script>

    <div id="results"></div>

    <style>
      /* Style for the results container */
      #results {
        margin-top: 35px;
      }


      .room-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }


      .room-card {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        text-align: center;
      }


      .room-card .room-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
      }


      .room-card h3 {
        margin: 10px 0 5px;
        font-size: 1.2em;
      }

      .room-card p {
        margin: 5px 0;
        color: #555;
      }

      .Time {
        padding-top: 10px;
        text-align: center;
      }

      .Timespan {
        color: #b393d3;
        font-weight: bold;
        padding-bottom: 20px;
      }

      .custom-alert {
        max-width: 600px;
        margin: 0 auto;
        /* Center horizontally */
        text-align: center;
        /* Center text */
      }
    </style>


  </main>

  <?php
  require('displayRooms.php');
  ?>

</body>

</html>