<?php 
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
   
    require('Connection.php'); 
    $email = trim($_POST['email']);
    $Password = trim($_POST['password']);
    $Fname = trim($_POST['fname']);
    $Lname = trim($_POST ['lname']);
    $Phone = trim($_POST ['phone']);
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // make it js validation later 
    $UOBstuemail = "/^(20[0-9]{2}|201[0-9]|202[0-4])\d{4,5}+@stu\.uob\.edu\.bh$/";
    $UOBFemail = "/[a-z]+@uob\.edu\.bh/";
    $bhPhone = "/^((\+|00)973)?\s?\d{8}$/";
    $pass ="/^\w{6,12}$/";
    $name = "/^[a-z][a-z\s]{3,15}$/i";

    if (!preg_match($UOBstuemail,$email)|| !preg_match($UOBFemail,$email)) {
       die("Invalid email, must be UOB email");
       
    }









}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
<div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            <form action="Register.php" method="POST">
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
                    <input type="tel" name="phoneNumber" id="phonetNumber" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Sign Up" required>
                </div>
                <div class="links">
                    Already a member? <a href="index.html" >Sign In</a> 
                </div>
            </form> 
        </div>
    </div>

    
</body>
</html>