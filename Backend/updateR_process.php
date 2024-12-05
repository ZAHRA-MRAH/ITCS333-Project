<?php
session_start();
require('Connection.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['roomNumber']) || empty($_POST['roomNumber'])) {
        header("Location: UpdateRoom.php?deletError=No Room is Selected!");
        exit();
    }
}

try {
    $roomNumber = $_POST['roomNumber'];
    $newRoomType = $_POST['newRoomType'];
    $newCapacity = $_POST['newCapacity'];
    $newEquipment = $_POST['newEquipment'];
    $newImgURL = null;

    // Handle file upload if a new image is provided
    if (isset($_FILES['newImgURL']) && $_FILES['newImgURL']['error'] == UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../RoomUpload/";
        $allowed_types = ['image/jpeg', 'image/png'];
        $max_size = 5 * 1024 * 1024;
        $file_type = $_FILES['newImgURL']['type'];
        $file_size = $_FILES['newImgURL']['size'];

        if (!in_array($file_type, $allowed_types)) {
            throw new Exception("Only JPG and PNG files are allowed.");
        }

        if ($file_size > $max_size) {
            throw new Exception("File size cannot exceed 5 MB.");
        }

        $file_ext = strtolower(pathinfo($_FILES['newImgURL']['name'], PATHINFO_EXTENSION));
        $unique_name = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $unique_name;

        if (move_uploaded_file($_FILES['newImgURL']['tmp_name'], $target_file)) {
            $newImgURL = "../RoomUpload/" . $unique_name;
        } else {
            throw new Exception("Failed to upload the new image.");
        }
    }

    // Update the room in the database
    $query = "UPDATE room SET 
              RoomType = :newRoomType, 
              Capacity = :newCapacity, 
              Equipment = :newEquipment";

    if ($newImgURL) {
        $query .= ", imgURL = :newImgURL";
    }

    $query .= " WHERE RoomNumber = :roomNumber";
    $stmt = $pdo->prepare($query);

    $params = [
        'newRoomType' => $newRoomType,
        'newCapacity' => $newCapacity,
        'newEquipment' => $newEquipment,
        'roomNumber' => $roomNumber
    ];

    if ($newImgURL) {
        $params['newImgURL'] = $newImgURL;
    }

    $stmt->execute($params);

    $message = "Room updated successfully!";
    header("Location: UpdateRoom.php?updateSuccess=" . urlencode($message));
    exit();
} catch (Exception $e) {
    $message = "Error: " . $e->getMessage();
    header("Location: UpdateRoom.php?updateError=" . urlencode($message));
    exit();
}
?>