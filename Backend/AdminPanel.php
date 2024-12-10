<?php
session_start();
require('Connection.php');
require('AdminHeader.php');
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location:login.php');
    exit();
}

//Total Bookings per Room
$stmt = $pdo->prepare("
    SELECT room.RoomNumber, COUNT(booking.BookingID) AS TotalBookings
    FROM booking
    INNER JOIN room ON booking.RoomID = room.RoomID
    GROUP BY room.RoomNumber
    ORDER BY TotalBookings DESC
    LIMIT 5;
");
$stmt->execute();
$popularRooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Daily Booking Trends
$stmt = $pdo->prepare("
    SELECT DATE(BookingDate) AS BookingDay, COUNT(BookingID) AS TotalBookings
    FROM booking
    GROUP BY BookingDay
    ORDER BY BookingDay DESC
    LIMIT 7;
");
$stmt->execute();
$bookingTrends = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Total Usage Hours per Room
$stmt = $pdo->prepare("
    SELECT room.RoomNumber, SUM(TIMESTAMPDIFF(MINUTE, StartTime, EndTime)) AS TotalUsageMinutes
    FROM booking
    INNER JOIN room ON booking.RoomID = room.RoomID
    GROUP BY room.RoomNumber
    ORDER BY TotalUsageMinutes DESC;
");
$stmt->execute();
$roomUsage = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../Frontend/addRoom.js"> </script>
    <title>Admin Panel</title>
</head>

<body>

    <main id="main">
        <div class="container mt-5">
            <h2>Room Usage Reports</h2>

            <!-- Room Popularity -->
            <div class="card mb-4">
                <h3 class="card-header">Most Popular Rooms</h3>
                <div class="card-body d-flex justify-content-between align-items-start">
                    <!-- Table -->
                    <table class="table" style="width: 50%;">
                        <thead>
                            <tr>
                                <th>Room Number</th>
                                <th>Total Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($popularRooms as $room): ?>
                                <tr>
                                    <td><?= htmlspecialchars($room['RoomNumber']) ?></td>
                                    <td><?= htmlspecialchars($room['TotalBookings']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pie Chart -->
                    <canvas id="popularRoomsPieChart" style="max-width: 400px; max-height: 400px;"></canvas>
                </div>
            </div>


            <!-- Booking Trends -->
            <div class="card mb-4">
                <h3 class="card-header">Booking Trends (Last 7 Days)</h3>
                <div class="card-body">
                    <canvas id="bookingTrendsChart"></canvas>
                </div>
            </div>

            <!-- Total Usage Hours -->
            <div class="card mb-4">
                <h3 class="card-header">Room Usage (Hours)</h3>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Room Number</th>
                                <th>Total Usage (Minutes)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($roomUsage as $usage): ?>
                                <tr>
                                    <td><?= htmlspecialchars($usage['RoomNumber']) ?></td>
                                    <td><?= htmlspecialchars($usage['TotalUsageMinutes']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



        <div class="box">
            <h2>Add Room</h2>
            <?php
            if (isset($_GET['addSuccess'])) {
                echo "<p class='alert alert-success'>" . htmlspecialchars($_GET['addSuccess']) . "</p>";
            }

            if (isset($_GET['addError'])) {
                echo "<p class='alert alert-danger'>" . htmlspecialchars($_GET['addError']) . "</p>";
            }
            ?>
            <form id="roomForm" action="addRoom.php" method="POST" enctype="multipart/form-data">
                <div class="field input">
                    <label for="RoomNumber">Room Number:</label>
                    <input type="text" id="RoomNumber" name="RoomNumber">
                    <span class="error-message"></span>
                </div>
                <div class="field input">
                    <label for="RoomType">Room Type:</label>
                    <select id="RoomType" name="RoomType">
                        <option value=""></option>
                        <option value="Classroom">Classroom</option>
                        <option value="Computer Lab">Computer Lab</option>
                    </select>
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="Capacity">Capacity:</label>
                    <input type="number" id="Capacity" name="Capacity">
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="Equipment">Equipment:</label>
                    <textarea id="Equipment" name="Equipment"></textarea>
                    <span class="error-message"></span>
                </div>

                <div class="field input">
                    <label for="imgURL">Upload Image:</label>
                    <input type="file" id="imgURL" name="imgURL">
                    <span class="error-message"></span>
                </div>
                <button class="btn" type="submit">Add Room</button>
            </form>
        </div>
    </main>

    <script>
        const bookingTrends = <?= json_encode($bookingTrends) ?>;
        const labels = bookingTrends.map(item => item.BookingDay);
        const data = bookingTrends.map(item => item.TotalBookings);

        new Chart(document.getElementById('bookingTrendsChart'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Bookings',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Bookings'
                        }
                    }
                }
            }
        });

        const popularRooms = <?= json_encode($popularRooms) ?>;

        // Extract room numbers and their booking counts
        const roomLabels = popularRooms.map(room => room.RoomNumber);
        const roomData = popularRooms.map(room => room.TotalBookings);

        const pieData = {
            labels: roomLabels, // Room numbers as labels
            datasets: [{
                data: roomData, // Total bookings as values
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('popularRoomsPieChart'), {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Most Popular Rooms (Bookings)'
                    }
                }
            }
        });
    </script>

</body>
<?php require('footer.php'); ?>

</html>