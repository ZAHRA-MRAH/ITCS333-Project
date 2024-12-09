<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="pictures\uob-logo.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/profilestyle.css">
    <title>Login</title>
</head>
<body>

    <div class="header">
    <img src="../pictures/uob-logo.svg" alt="Image" class="left-image">
    <span class="text-span">IT College Room Booking</span>
</div>

    <div class="container">
        <div class="box form-box">
            <header>Sign in</header>
            <?php
            if (isset($_SESSION['login_error'])) {
                echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
            if (isset($_SESSION['registration_success'])) {
                echo "<p style='color: green;'>" . $_SESSION['registration_success'] . "</p>";
                unset($_SESSION['registration_success']);
            }
            ?>
            <form action="login_process.php" method="POST">
                 <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <div class="field input">
                        <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                        Don't have an account? <a href="register.php">Sign Up Now!</a> 
                </div>
            </form> 
        </div>               
    </div>
    <footer>© ITCS333 Project Copyright 2024 All Rights Reserved</footer>
</body>
</html>