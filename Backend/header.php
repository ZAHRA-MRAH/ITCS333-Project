<?php
require('Connection.php');

// Check if the user is logged in
//if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
 // header("Location: login.php");
  //exit;
//}

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
  <link rel="icon" type="image/x-icon" href="..\pictures\uob-logo.svg">
 
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Frontend/homestyle.css">
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
        <a href="homepage.php" id="navlink" class="nav-linkk">Home</a>
    </div>

      <div class="navbutton2">
        <a href="viewBookings.php" id="navlink" class="nav-linkk">View Booking</a>
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
  <script>
    document.querySelectorAll('.nav-linkk').forEach(link => {
  if (link.href === window.location.href) {
    link.classList.add('active');
  }
});

</script>


</body>