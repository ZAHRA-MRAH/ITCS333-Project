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
  <link rel="icon" type="image/x-icon" href="..\pictures\uob-logo.svg">
  <title>Home</title>
  <link rel="stylesheet" href="../Frontend/homestyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

  <header>

    <nav class="navbar">
      <a class="navbar-logo" href="homepage.php">
        <img src="../pictures/uob-logo.svg" width="40" height="40" class="d-inline-block align-top" alt="">
        IT College Room Booking
      </a>


      <nav class="leftbar">

        <div class="navbutton1">
          <a href="homepage.php" id="navlink">Home</a>
        </div>

        <div class="navbutton2">
          <a href="viewBookings.php" id="navlink">View Booking</a>
        </div>
      </nav>
      <nav class="rightbar">
        <div class="profile">
          <a class="profilebtn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo $profilePic; ?>" alt="Profile">
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="editprofile" href="profile.php"> Profile</a></li>
            <li><a class="logout" href="logout.php">Log out</a></li>
          </ul>
        </div>
      </nav>
    </nav>
  </header>


  <h2 id="welcome-messege">Welcome <?php echo htmlspecialchars($fname) ?>!</h2>
  <style>
#welcome-messege {
    text-align: left !important; 
    text-indent: 20px; 
    margin: 10px 30px; 
    background-image: linear-gradient(to right, #1B61AC, #2F5175) !important; 
    -webkit-background-clip: text; 
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent; 
}
  </style>

  <main>

    <div class="container">
      <div class="box form-box">
        <header>Search for Available Rooms:</header>
        <form id="searchForm" action="search.php">
          <select class="form-select" name="room_type" aria-label="Default select example">
            <div id="options">
              <option selected disabled>Room Type</option>
              <option value="Classroom">Classroom</option>
              <option value="Computer Lab">Computer Lab</option>

            </div>
          </select>


          <div class="Date">
            <span class="Datespan">Pick a Date</span><br>
            <input type="date" name="date" aria-label="Date" color:"#b393d3" required>
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


          <input type="submit" id="searchbtn" value="Search" style="margin-top: 50px;">
        </form>
      </div>
    </div>

    <script src="../Frontend/search.js"></script>
    <div id="message"></div>
    <div id="results"></div>

    <style>
      /* Style for the message container */
      #message {
        max-width: 550px;
        margin: 20px auto;
        padding: 10px;
        box-sizing: border-box;
        text-align: center;
        margin: 0 auto
      }

      /* Style for the results container */
      #results {
        margin-top: 30px;
      }


  .room-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .room-card {
    background-color: #FEFEFE;
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
    color: #42413E;
  }

  .room-card p {
    color: #6F6B45;
    margin: 0;
    font-size: 16px;
  }

  .Time {
    padding-top: 10px;
    text-align: center;
  }

  .Timespan {
    color: #2F5175;
    font-weight: bold;
    padding-bottom: 20px;
    text-align: center;
  }

  .custom-alert {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    color: #998F6E; 
  }
</style>



  </main>

  <?php
  require('displayRooms.php');
  ?>

</body>

<?php
require('footer.php')
?>

</html>