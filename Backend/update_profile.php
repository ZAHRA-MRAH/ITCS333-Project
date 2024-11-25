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
        // Redirect to profile.php when "Return" button is clicked
        header('Location: profile.php');
        exit();
    }
    // Update user data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phoneNumber = $_POST['phoneNumber'];


    // Handle profile picture upload
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../uploads/";
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 5 * 1024 * 1024; // 5 MB

        $file_type = $_FILES['profilePic']['type'];
        $file_size = $_FILES['profilePic']['size'];

        if (!in_array($file_type, $allowed_types)) {
            exit("Error: Only JPG and PNG files are allowed.");
        }

        if ($file_size > $max_size) {
            exit("Error: File size cannot exceed 5 MB.");
        }

        $file_ext = strtolower(pathinfo($_FILES['profilePic']['name'], PATHINFO_EXTENSION));
        $unique_name = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $target_file)) {
            // Update the database with the new profile picture path
            $profilePicPath = "../uploads/" . $unique_name;

            $query = "UPDATE users SET FirstName = :firstName, LastName = :lastName, PhoneNo = :phoneNumber, ProfilePic = :profilePic WHERE userID = :user_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'phoneNumber' => $phoneNumber,
                'profilePic' => $profilePicPath,
                'user_id' => $user_id
            ]);

            echo "Profile updated successfully!";
        } else {
            exit("Error: Failed to upload profile picture.");
        }
    } else {
        // Update only the text fields if no file is uploaded
        $query = "UPDATE users SET FirstName = :firstName, LastName = :lastName, PhoneNo = :phoneNumber WHERE userID = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'firstName' => $firstName,
            'lastName' => $lastName,
            'phoneNumber' => $phoneNumber,
            'user_id' => $user_id
        ]);

        echo "Profile updated successfully!";
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
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="field input">
                    <label for="email">Email</label>
                    <p><?php echo $email; ?></p> <!-- Display email from the database -->
                </div>

                <div class="field input">
                    <label for="profilePic">Upload Profile Picture</label>
                    <input type="file" name="profilePic" id="profilePic" accept="image/*">
                </div>


                <div class="field input">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Confirm Update">
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="return" value="Return back">
                </div>
            </form>
        </div>
    </div>
</body>

</html>