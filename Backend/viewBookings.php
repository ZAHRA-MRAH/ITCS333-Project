<?php
session_start();
require('header.php');
require('Connection.php');

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

// Separate bookings into categories
$upcomingBookings = [];
$previousBookings = [];
$cancelledBookings = [];

$currentDate = date('Y-m-d');  // Get current date in 'YYYY-MM-DD' format

foreach ($bookings as $booking) {
    if ($booking['Status'] === 'Cancelled') {
        $cancelledBookings[] = $booking;
    } else if ($booking['BookingDate'] >= $currentDate) {
        // Upcoming bookings (BookingDate is today or in the future)
        $upcomingBookings[] = $booking;
    } else {
        // Previous bookings (BookingDate is in the past)
        $previousBookings[] = $booking;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="icon" type="image/x-icon" href="pictures\uob-logo.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-k6RqeWeci5ZR/Lv4MR0sA0FfDOM2zJ8n13s7Zx83EGolzoggsCg5h4vfsKyd3aS" crossorigin="anonymous">

    <style>
.booking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); /* Creates flexible columns */
    gap: 20px; /* Space between grid items */
    justify-content: start; /* Align items to the start of the container */
    margin: 20px 0;
}

.booking-card {
    background: #F2F1EC;
    border: 1px solid #F2F1EC;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.booking-card img {
    max-width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #F2F1EC;
}

.booking-card h5 {
    margin: 10px 0;
    color: #2F5175;
    font-weight: 600;
}

.booking-card p {
    margin: 5px 0;
    color: #44471C;
    font-weight: bold;
}

.status-confirmed {
    color: green !important;
    font-weight: bold;
}

.status-cancelled {
    color: #E63946 !important;
    font-weight: bold;
}

h2 {
    text-align: center;
    margin-top: 20px;
    color: #2F5175;
    font-weight: bold;
}

.booking-category-header {
    padding: 10px;
    background-color: #2F5175;
    color: white;
    text-align: center;
    border-radius: 5px;
    margin-bottom: 15px;
}

.btn-danger {
    background-color: #E63946;
    border-color: transparent;
    color: #FEFEFE;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-danger:hover {
    background-color: #C9253B;
}

.btn-secondary {
    background-color: #2F5175;
    border-color: transparent;
    color: #FEFEFE;
    font-weight: bold;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-secondary:hover {
    background-color: #1B61AC;
}

.modal-content {
    background-color: #FEFEFE;
    border: 1px solid #F2F1EC;
}

.modal-header, .modal-footer {
    border-bottom: 1px solid #F2F1EC;
    border-top: 1px solid #F2F1EC;
}

.modal-title {
    color: #2F5175;
    font-weight: bold;
}

.alert {
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
    font-weight: bold;
}

.alert-success {
    background-color: #DFF2BF;
    color: #4F8A10;
}

.alert-danger {
    background-color: #FFBABA;
    color: #D8000C;
}

.alert-warning {
    background-color: #FFF4CC;
    color: #9F6000;
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
        
            <!-- Upcoming Bookings Section -->
            <div class="booking-category-header">
                <h3>Upcoming Bookings</h3>
            </div>
            <div class="booking-grid">
                <?php if (count($upcomingBookings) > 0): ?>
                    <?php foreach ($upcomingBookings as $booking): ?>
                        <div class="booking-card">
                            <img src="<?php echo htmlspecialchars($booking['imgURL']); ?>" alt="Room Image">
                            <h5><?php echo htmlspecialchars($booking['RoomNumber']); ?> - <?php echo htmlspecialchars($booking['RoomType']); ?></h5>
                            <p> <i class="fa-solid fa-person"></i> Capacity: <?php echo htmlspecialchars($booking['Capacity']); ?></p>
                            <p> <i class="fa-solid fa-chalkboard"></i> Equipment: <?php echo htmlspecialchars($booking['Equipment']); ?></p>
                            <p> <i class="fa-regular fa-calendar-days"></i> Date: <?php echo htmlspecialchars($booking['BookingDate']); ?></p>
                            <p> <i class="fa-regular fa-clock"></i> Time: <?php echo htmlspecialchars($booking['StartTime']); ?> - <?php echo htmlspecialchars($booking['EndTime']); ?></p>
                            <p>Time of Booking: <?php echo htmlspecialchars($booking['BookingTime']); ?></p>
                            <p class="status-confirmed">Status: <?php echo htmlspecialchars($booking['Status']); ?></p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal"
                                    data-booking-id="<?php echo $booking['BookingID']; ?>">
                                Cancel Booking
                            </button>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">No Upcoming Bookings</div>
                <?php endif; ?>
            </div>

            <!-- Previous Bookings Section -->
            <div class="booking-category-header">
                <h3>Previous Bookings</h3>
            </div>
            <div class="booking-grid">
                <?php if (count($previousBookings) > 0): ?>
                    <?php foreach ($previousBookings as $booking): ?>
                        <div class="booking-card">
                            <img src="<?php echo htmlspecialchars($booking['imgURL']); ?>" alt="Room Image">
                            <h5><?php echo htmlspecialchars($booking['RoomNumber']); ?> - <?php echo htmlspecialchars($booking['RoomType']); ?></h5>
                            <p> <i class="fa-solid fa-person"></i>  Capacity: <?php echo htmlspecialchars($booking['Capacity']); ?></p>
                            <p> <i class="fa-solid fa-chalkboard"> </i> Equipment: <?php echo htmlspecialchars($booking['Equipment']); ?></p>
                            <p> <i class="fa-regular fa-calendar-days"></i> Date: <?php echo htmlspecialchars($booking['BookingDate']); ?></p>
                            <p> <i class="fa-regular fa-clock"></i> Time: <?php echo htmlspecialchars($booking['StartTime']); ?> - <?php echo htmlspecialchars($booking['EndTime']); ?></p>
                            <p>Time of Booking: <?php echo htmlspecialchars($booking['BookingTime']); ?></p>
                            <p class="status-confirmed">Status: <?php echo htmlspecialchars($booking['Status']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">No Previous Bookings</div>
                <?php endif; ?>
            </div>

            <!-- Cancelled Bookings Section -->
            <div class="booking-category-header">
                <h3>Cancelled Bookings</h3>
            </div>
            <div class="booking-grid">
                <?php if (count($cancelledBookings) > 0): ?>
                    <?php foreach ($cancelledBookings as $booking): ?>
                        <div class="booking-card">
                            <img src="<?php echo htmlspecialchars($booking['imgURL']); ?>" alt="Room Image">
                            <h5><?php echo htmlspecialchars($booking['RoomNumber']); ?> - <?php echo htmlspecialchars($booking['RoomType']); ?></h5>
                            <p><i class="fa-solid fa-person"></i> Capacity: <?php echo htmlspecialchars($booking['Capacity']); ?></p>
                            <p><i class="fa-solid fa-chalkboard"></i> Equipment: <?php echo htmlspecialchars($booking['Equipment']); ?></p>
                            <p><i class="fa-regular fa-calendar-days"></i> Date: <?php echo htmlspecialchars($booking['BookingDate']); ?></p>
                            <p><i class="fa-regular fa-clock"></i> Time: <?php echo htmlspecialchars($booking['StartTime']); ?> - <?php echo htmlspecialchars($booking['EndTime']); ?></p>
                            <p>Time of Booking: <?php echo htmlspecialchars($booking['BookingTime']); ?></p>
                            <p class="status-cancelled">Status: <?php echo htmlspecialchars($booking['Status']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning" role="alert">No Cancelled Bookings</div>
                <?php endif; ?>
            </div>
       
    </main>

    <!-- Bootstrap modal for cancel confirmation -->
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

<?php require('footer.php') ?>

</html>
