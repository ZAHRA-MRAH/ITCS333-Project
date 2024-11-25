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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/style.css">
    <title>Login</title>
</head>
<body>
    <div class="container mt-5">
        <div class="box form-box border rounded-5 p-3 bg-white shadow">
            <div class="row g-0">
                <!-- Left Side: Image -->
                <div class="left col-md-6 d-flex justify-content-center align-items-center flex-column">
                    <div class="featured image">
                        <img src="../pictures/outside-image.jpg" class="img-fluid p-4" alt="Login Image" style="width: 250px;">
                    </div>
                </div>

                <!-- Right Side: Form -->
                <div class="right col-md-6 p-4">
                    <header class="mb-4 text-center">
                        <h3>Log in</h3>
                    </header>
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
                        <div class="field input mb-3">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" required>
                        </div>

                        <div class="field input mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" required>
                        </div>

                        <div class="field d-grid">
                            <input type="submit" class="btn" name="submit" value="Login" required>
                        </div>
                        <div class="links mt-3 text-center">
                            Don't have an account? <a href="register.php">Sign Up Now!</a> 
                        </div>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</body>
</html>