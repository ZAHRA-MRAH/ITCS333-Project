<?php
    session_start();
    require('Connection.php');

    // Check if the user is logged in
    if (!isset($$_SESSION['user_id'])) {
        header('location:login.php');
        exit();
    }
    $user_id = $_SESSION['user_id'];
    $message = ''; // Initialize variable to store error or success message
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve room data
        $roomNumber = $_POST['RoomNumber'];
        $roomType = $_POST['RoomType'];
        $capacity = $_POST['Capacity'];
        $equipment = $_POST['Equipment'];
        $roomImgURL = null;

        #handle img uplaod
        if (isset($_FILES['imgURL']) && $_FILES['imgURL']['error'] == UPLOAD_ERR_OK) {
            $target_dir = __DIR__ . "/../RoomUpload/";
            $allowed_types = ['image/jpeg', 'image/png'];
            $max_size = 5 * 1024 * 1024; // 5 MB
            $file_type = $_FILES['imgURL']['type'];
            $file_size = $_FILES['imgURL']['size'];
    
            try {
                // Validate file type
                if (!in_array($file_type, $allowed_types)) {
                    throw new Exception("Only JPG and PNG files are allowed.");
                }
    
                // Validate file size
                if ($file_size > $max_size) {
                    throw new Exception("File size cannot exceed 5 MB.");
                }
    
                // Generate a unique name for the file
                $file_ext = strtolower(pathinfo($_FILES['imgURL']['name'], PATHINFO_EXTENSION));
                $unique_name = uniqid() . '.' . $file_ext;
                $target_file = $target_dir . $unique_name;
    
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['imgURL']['tmp_name'], $target_file)) {
                    $imgURL = "../RoomUpload/" . $unique_name;
                } else {
                    throw new Exception("Failed to upload the image.");
                }
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
       
        if (!$message) { // Only proceed with database insertion if no errors occurred during file upload
            try {
                // Insert data into the `room` table
                $query = "INSERT INTO room (RoomNumber, RoomType, Capacity, Equipment, imgURL) 
                          VALUES (:roomNumber, :roomType, :capacity, :equipment, :imgURL)";
                $stmt = $pdo->prepare($query);
                $stmt->execute([
                    'roomNumber' => $roomNumber,
                    'roomType' => $roomType,
                    'capacity' => $capacity,
                    'equipment' => $equipment,
                    'imgURL' => $imgURL
                ]);
    
                $message = "Room added successfully!";
            } catch (Exception $e) {
                $message = "Error: " . $e->getMessage();
            }
        }
    }
?>