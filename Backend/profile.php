<?php
session_start();
require('Connection.php');

// Check if the user is logged in
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

// Fetch user data from the database based on the logged-in user_id
$query = "SELECT * FROM users WHERE userID = :user_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// If no user data is found, redirect to login page
if (!$user) {
    header('location:login.php');
    exit();
}


$email = $user['Email'];
$firstName = $user['FirstName'];
$lastName = $user['LastName'];
$phoneNumber = $user['PhoneNo'];
$profilePic = $user['ProfilePic'];
$Role = $user['Role'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['return'])) {
        // Redirect to homepage.php when "Return" button is clicked
        header('Location: homepage.php');
        exit();
    }

    if (isset($_POST['update'])) {
        // Redirect to homepage.php when "Return" button is clicked
        header('Location: update_profile.php');
        exit();
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/style.css">
    <link rel="icon" type="image/x-icon" href="pictures\uob-logo.svg">
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <div class="box form-box">
            <header>Profile Information</header>

            <!-- Profile Picture Display -->
            <div class="profile-pic">
                <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic2" width="100" height="100">
            </div>
            <style>
                .profile-pic2 {
                    width: 150px;
                    height: 150px;
                    border-radius: 50%;
                    border: 2px solid #553c9a;
                    object-fit: cover;
                }

                .profile-pic {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
            </style>
            <!-- Form to display user information -->

            <div class="field input">
                <label for="email">Email</label> <br>
                <input  type="text" value="<?php echo $email; ?>" disabled >
            </div>

            <div class="field input">
                <label for="email">Role</label> <br>
                <input  type="text" value="<?php echo $Role; ?>" disabled >
            </div>

            <div class="field input">
                <label for="firstName">First Name</label> <br>
                <input  type="text" value="<?php echo $firstName; ?>" disabled >
                
            </div>

            <div class="field input">
                <label for="lastName">Last Name</label> <br>
                <input  type="text" value="<?php echo $lastName; ?>" disabled >
                
            </div>

            <div class="field input">
                <label for="phoneNumber">Phone Number</label> <br>
                <input  type="text" value="<?php echo $phoneNumber; ?>" disabled >
                
            </div>

            <form action="" method="POST">
                <div class="field">
                    <input type="submit" class="btn" name="update" value="Update profile information">
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="return" value="Return to home page">
                </div>
            </form>
        </div>
    </div>
</body>

</html>