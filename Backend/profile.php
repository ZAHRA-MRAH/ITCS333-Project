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
    <link rel="stylesheet" href="../Frontend/style.css">
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
                <label for="email">Email</label>
                <p><?php echo $email; ?></p> <!-- Display email from the database -->
            </div>

            <div class="field input">
                <label for="firstName">First Name</label> <br>
                <?php echo $firstName; ?>
            </div>

            <div class="field input">
                <label for="lastName">Last Name</label> <br>
                <?php echo $lastName; ?>
            </div>

            <div class="field input">
                <label for="phoneNumber">Phone Number</label> <br>
                <?php echo $phoneNumber; ?>
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