<?php
session_start();
require('Connection.php');
require('header.php');

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

$message = ''; // Initialize variable to store error or success message
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
    /*if no file is uploaded, path is null (so it doesnt put the profile pic in the uploads folder
    in my server every time the page is refreshed)*/
    $profilePicPath = null;

    // Handle profile picture upload
    try {
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] == UPLOAD_ERR_OK) {
            $target_dir = __DIR__ . "/../uploads/";
            $allowed_types = ['image/jpeg', 'image/png'];
            $max_size = 5 * 1024 * 1024; // 5 MB

            $file_type = $_FILES['profilePic']['type'];
            $file_size = $_FILES['profilePic']['size'];

            if (!in_array($file_type, $allowed_types)) {
                $message = "Error: Only JPG and PNG files are allowed.";
            }

            if ($file_size > $max_size) {
                $message = "Error: File size cannot exceed 5 MB.";
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

                $message = "Profile updated successfully!";
            } else {
                $message = "Error: Failed to upload profile picture.";
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

            $message = "Profile updated successfully!";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="..\pictures\uob-logo.svg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/profilestyle.css">
    <script src="../Frontend/ProfileEditvalid.js"> </script>
    <title>Profile</title>
</head>

<body>
    <div class="container">
        <div class="box form-box" id="formbox">
            <header>Update Profile Information</header>
            <style>
                /* PHP Messages */
                .php-message {
                    margin: 10px 0;
                    padding: 10px;
                    border-radius: 5px;
                    font-weight: bold;
                }

                .php-success {
                    color: #155724;
                    background-color: #d4edda;
                    border: 1px solid #c3e6cb;
                }

                .php-error {
                    color: #721c24;
                    background-color: #f8d7da;
                    border: 1px solid #f5c6cb;
                }

                /* JS Validation Messages */
                /* Input Field Styles */
                .field.input.error input {
                    border: 2px solid #f5c6cb;
                }

                .field.input.success input {
                    border: 2px solid #c3e6cb;
                }

                /* Error Message Styles */
                .field.input .error-message {
                    color: #721c24;
                    font-size: 0.875rem;
                    margin-top: 5px;
                }

                /* Success/Error Field Container */
                .field.input.error {
                    margin-bottom: 1rem;
                }

                .field.input.success {
                    margin-bottom: 1rem;
                }
            </style>
            <?php if (!empty($message)): ?>
                <div class="php-message <?php echo strpos($message, 'Error') === 0 ? 'php-error' : 'php-success'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>


            <!-- Profile Picture Display -->
            <div class="profile-pic">
                <img src="<?php echo $profilePic; ?>" alt="Profile Picture" class="profile-pic2" width="100" height="100">
            </div>

            <style>
                .profile-pic2 {
                    width: 150px;
                    height: 150px;
                    border-radius: 50%;
                    border: 2px solid #CCC7B6;
                    object-fit: cover;
                }

                .profile-pic {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                #formbox {
                    width: 450px;
                    height: 975px;
                    margin: 0px 10px;
                }
            </style>

            <!-- Form to display user information -->
            <form id="form" action="" method="POST" enctype="multipart/form-data">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" value="<?php echo $email; ?>" disabled> <!-- Display email from the database -->
                </div>

                <div class="field input">
                    <label for="role">Role</label>
                    <input type="text" value="<?php echo $Role; ?>" disabled> <!-- Display email from the database -->
                </div>

                <div class="field input mb-3">
                    <label for="profilePic" class="form-label">Upload Profile Picture</label>
                    <input class="form-control" type="file" name="profilePic" id="profilePic" accept="image/*">
                </div>


                <div class="field input">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" id="firstName" value="<?php echo $firstName; ?>" autocomplete="off" required>
                    <div class="error-message"></div>
                </div>

                <div class="field input">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" id="lastName" value="<?php echo $lastName; ?>" autocomplete="off" required>
                    <div class="error-message"></div>
                </div>

                <div class="field input">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" name="phoneNumber" id="phoneNumber" value="<?php echo $phoneNumber; ?>" autocomplete="off" required>
                    <div class="error-message"></div>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="updatecnfrm" value="Confirm Update">
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="return" value="Return back">
                </div>

            </form>
        </div>
    </div>
</body>
<?php require('footer.php');?>
</html>