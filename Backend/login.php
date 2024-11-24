<?php
session_start();
if (isset($_SESSION['user_id'])){
    header ("Location: homepage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Frontend/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Log in</header>
            <?php
            if (isset($_SESSION['login_error'])){
                echo "<p style='color: red;'>" . $_SESSION['login_error'] . "</p>";
                unset($_SESSION['login_error']);
            }
            if (isset($_SESSION['registration_success'])){
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

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have an account? <a href="register.php" >Sign Up Now!</a> 
                </div>
            </form> 
        </div>
    </div>
</body>
</html>