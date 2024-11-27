<?php 
session_start();
require('Connection.php');

// Initialize variables to store the search parameters
$roomType = '';
$date = '';
$rooms = [];
$message = ''; // For debug output

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomType = $_POST['roomType'] ?? '';
    $date = $_POST['date'] ?? '';

    if (empty($roomType) || empty($date)) {
        $message = "Please select both a room type and a date.";
    } else {
        try {
            $query = "SELECT * FROM rooms WHERE roomType = :roomType AND availableDate = :date";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':roomType', $roomType, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);
            $stmt->execute();

            $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($rooms)) {
                $message = "No rooms found for the selected type and date.";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="../Frontend/homestyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>
<body>

    <header>
        <nav class="navbar">
            <a class="navbar-logo" href="#">
                <img src="../pictures/uob-logo.svg" width="40" height="40" class="d-inline-block align-top" alt="">
                UOB IT College Room Booking System
            </a>
            <div class="navbar-nav">
                <a href="home.php" class="nav-item nav-link">Home</a>
                <a href="profile.php" class="nav-item nav-link">Profile</a>
                <a href="logout.php" class="nav-item nav-link">Log Out</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2>Search Results for <?php echo htmlspecialchars($roomType); ?> on <?php echo htmlspecialchars($date); ?></h2>
            
            <?php if (!empty($message)): ?>
            <div class="alert alert-warning"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if (!empty($rooms)): ?>
            <div class="row">
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <img src="<?php echo $room['photo']; ?>" class="card-img-top" alt="Room Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($room['roomName']); ?></h5>
                                <p class="card-text">Available on: <?php echo htmlspecialchars($room['availableDate']); ?></p>
                                <a href="viewRoom.php?roomId=<?php echo $room['id']; ?>" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No rooms available for the selected type and date.</p>
        <?php endif; ?>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>
</html>