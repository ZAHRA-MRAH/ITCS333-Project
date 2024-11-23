<?php
session_start();
$_SESSION['errors'] = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require('Backend/Connection.php');
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $phoneNumber = trim($_POST['phoneNumber']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = strpos($email, '@stu.uob.edu.bh') !== false ? 'Student' : 'Faculty';

    // make it js validation later 
    $UOBstuemail = "/^(20[0-9]{2}|201[0-9]|202[0-4])\d{4,5}+@stu\.uob\.edu\.bh$/";
    $UOBFemail = "/[a-z]+@uob\.edu\.bh/";
    $bhPhone = "/^((\+|00)973)?\s?\d{8}$/";
    $pass = "/^\w{6,12}$/";
    $name = "/^[a-z][a-z\s]{3,15}$/i";

    if (!preg_match($UOBstuemail, $email) && !preg_match($UOBFemail, $email)) {
        $_SESSION['errors'][] = 'Invalid email address. Must be an Email with UOB domain';

    } else {
        // Check if email exists in database 
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
    
        if ($stmt->rowCount() > 0) {
            $_SESSION['errors'][] = 'This email is already registered.';
        }
    }
   
    if(!preg_match($pass, $password)) {
        $_SESSION['errors'][] = 'Password must be between 6 to 12 characters.';
    }

    if(!preg_match($bhPhone, $Phone)) {
        $_SESSION['errors'][] = 'Invalid Phone number.';  
    }

    if(!preg_match($name,$Fname)){
        $_SESSION['errors'][] = 'Invalid first name.';
    } 
    if(!preg_match($name, $Lname)) {
        $_SESSION['errors'][] = 'Invalid last name.';
    }


    if (!empty($_SESSION['errors'])) {
        header("Location: register.php");
        exit();
    }

    //insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (FirstName, LastName, Email, Password, Role, PhoneNo)
     VALUES (:firstName, :lastName, :email, :password, :role, :phoneNo)");
      $stmt->execute([
        ':firstName' => $firstName,
        ':lastName' => $lastName,
        ':email' => $email,
        ':password' => $hashed_password,
        ':role' => $role,
        ':phoneNo' => $phoneNumber
    ]);

    header("Location: index.php");
    exit();
    



}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Frontend/style.css">
    <title>Register</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            
            <?php
            // Display errors 
            if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                echo '<ul style="color: red;">';
                foreach ($_SESSION['errors'] as $error) {
                    echo '<li>' . $error . '</li>';
                }
                echo '</ul>';
                unset($_SESSION['errors']); // Clear errors after displaying
            }
            ?>
            
            <form action="register.php" method="POST">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" name="phoneNumber" id="phoneNumber" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Sign Up" required>
                </div>
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
    </div>


</body>

</html>