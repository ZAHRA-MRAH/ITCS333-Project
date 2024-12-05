<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
</head>
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
                    <a href="AdminPanel.php" id="navlink" class="nav-link ">Home</a>
                </div>
                <div class="navbutton1">
                    <a href="deleteRoom.php" id="navlink" class="nav-link ">Delete Room</a>
                </div>
                <div class="navbutton1">
                    <a href="UpdateRoom.php" id="navlink" class="nav-link ">Update Room</a>
                </div>
                <div class="navbutton1">
                    <a href="roomSchedules.php" id="navlink" class="nav-link">Manage Room Schedules</a>
                </div>
                <div class="navbutton2">
                    <a href="logout.php" id="navlink">log out</a>
                </div>
            </nav>
        </nav>
    </header>

    <script>
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>
</body>