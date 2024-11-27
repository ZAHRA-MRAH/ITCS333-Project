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
  <script src="index.js"></script>
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
          <a href="#Home" id="navlink">Home</a>
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
                    <form action="" method="post">
                    <select class="form-select" aria-label="Default select example">
                      <div id="options">
                      <option selected>Room Type</option>
                      <option value="1">Classroom</option>
                      <option value="2">Computer Lab</option>
                      <option value="3">Meeting Room</option>
                    </div>
                    </select>

               
                <div class="Date">
                  <span class ="Datespan">Pick a Date</span><br>
                  <input type="date" name="Date" value="2024-12-07" >
                </div>
  
                <input type="submit" id="searchbtn" value="Search">
            </form>
          </div>
            </div>
        </main>

  <?php 
   require('displayRooms.php');
  ?>

</body>

</html>