<?php
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
  <link rel="stylesheet" href="../Frontend/homestyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
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
      <a href="homepage.php" id="navlink" class="nav-linkk">Home</a>
    </div>

    <div class="navbutton2">
      <a href="viewBookings.php" id="navlink" class="nav-linkk">View Booking</a>

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
  <script>
    document.querySelectorAll('.nav-linkk').forEach(link => {
  if (link.href === window.location.href) {
    link.classList.add('active');
  }
});

</script>
</body>