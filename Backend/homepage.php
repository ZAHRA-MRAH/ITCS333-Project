
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../Frontend/homestyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
</head>
<body>

    <header>
        
<nav class="navbar">
    <a class="navbar-logo" href="#">
      <img src="https://placehold.co/30x30" width="30" height="30" class="d-inline-block align-top" alt="">
      UOB Booking
    </a>
    
  
  <nav class="leftbar">

    <div class="navbutton1">
        <a href="#Home" id="navlink">Home</a>
    </div>

    <div class="navbutton2">
        <a href="#ViewBooking" id="navlink">View Booking</a>

    </div>
</nav>

<nav class="rightbar">
    <div class="profile">
        <img src="https://placehold.co/30x30" alt="Profile">
    </div>
</nav>
</nav>
    </header>
        <main>
            <h2 id="welcome-messege">Welcome username!</h2>
              <div class="container">
                  <div class="box form-box">
                    <header>Search for Available Rooms:</header>
                    <form action="" method="post">
              <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class"RoomType>Room Type</span>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Classroom</a>
                    <a class="dropdown-item" href="#">Lab</a>
                    <a class="dropdown-item" href="#">Meeting Room</a>
                  </div>
                </div>
                
  
                <div clas="Date">
                  <span class"Date>Pick a Date</span>
                  <input type="date" name="Date" value="yyyy-mm-dd">
                </div>
  
                <input type="submit" value="Search">
            </form>
          </div>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6SpejpU02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>