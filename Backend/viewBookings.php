<?php
session_start();
require('header.php');
require('Connection.php');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

try {
    // Fetch the user's bookings
    $query = "
        SELECT b.BookingID, b.BookingDate, b.StartTime, b.EndTime, b.Status, b.BookingTime, 
               r.RoomNumber, r.RoomType, r.Capacity, r.Equipment, r.imgURL
        FROM booking b
        JOIN room r ON b.RoomID = r.RoomID
        WHERE b.userID = :user_id
        ORDER BY b.BookingDate DESC, b.StartTime ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching bookings: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="icon" type="image/x-icon" href="pictures\uob-logo.svg">
    <link rel="stylesheet" href="../Frontend/style.css">
</head>
<body>


    <style>
        .booking-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-evenly;
            margin: 20px 0;
        }

        .booking-card {
            flex: 1 1 calc(25% - 20px);
            max-width: 300px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .booking-card img {
            max-width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
        }

        .booking-card h5 {
            margin: 10px 0;
        }

        .booking-card p {
            margin: 5px 0;
            color: #555;
            font-weight: bold;
        }

        .status-confirmed {
            color: green !important;
            font-weight: bold;
        }

        .status-cancelled {
            color: red !important;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #553c9a;
        }
    </style>
</head>

<body>
    <h2>My Bookings</h2>
    <div class="container mt-3">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
    </div>


    <main>
        <div class="container">

            <div class="booking-grid">
                <?php if (count($bookings) > 0): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <div class="booking-card">
                            <img src="<?php echo htmlspecialchars($booking['imgURL']); ?>" alt="Room Image">
                            <h5><?php echo htmlspecialchars($booking['RoomNumber']); ?> - <?php echo htmlspecialchars($booking['RoomType']); ?></h5>
                            <p>Capacity: <?php echo htmlspecialchars($booking['Capacity']); ?></p>
                            <p>Equipment: <?php echo htmlspecialchars($booking['Equipment']); ?></p>
                            <p>Date: <?php echo htmlspecialchars($booking['BookingDate']); ?></p>
                            <p>Time: <?php echo htmlspecialchars($booking['StartTime']); ?> - <?php echo htmlspecialchars($booking['EndTime']); ?></p>
                            <p>Time of Booking: <?php echo htmlspecialchars($booking['BookingTime']); ?></p>
                            <p class="<?php echo $booking['Status'] === 'Confirmed' ? 'status-confirmed' : 'status-cancelled'; ?>">
                                Status: <?php echo htmlspecialchars($booking['Status']); ?>
                            </p>
                            <?php if ($booking['Status'] === 'Confirmed'): ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal"
                                    data-booking-id="<?php echo $booking['BookingID']; ?>">
                                    Cancel Booking
                                </button>

                            <?php endif; ?>
                        </div>

                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">No Current Bookings</div>';
                <?php endif; ?>
            </div>

            <!-- bootstrap modal for cancel confirmation -->
            <div class="modal fade" id="cancelModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>

                        </div>
                        <div class="modal-body">
                            Are you sure you want to cancel this booking?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <form id="cancelForm" method="POST" action="cancelBooking.php" style="display: inline;">
                                <input type="hidden" name="booking_id" id="modalBookingID">
                                <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>





        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle the modal's show event
            var cancelModal = document.getElementById('cancelModal');
            cancelModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget; // button to trigger modal
                var bookingId = button.getAttribute('data-booking-id'); // booking ID
                var modalBookingIDInput = cancelModal.querySelector('#modalBookingID');
                modalBookingIDInput.value = bookingId; // Set the booking ID in the hidden input
            });
        });
    </script>
</body>

<?php 
require('footer.php')
?>

</html>