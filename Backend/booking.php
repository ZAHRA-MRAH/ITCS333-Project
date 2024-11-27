<?php
require('header.php');
// Retrieve room details from POST
$room_id = $_POST['RoomID'];
$room_number = $_POST['RoomNumber'];
$capacity = $_POST['Capacity'];
$equipment = $_POST['Equipment'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h1>Book Room <?php echo htmlspecialchars($room_number); ?></h1>
    <p>Capacity: <?php echo htmlspecialchars($capacity); ?></p>
    <p>Equipment: <?php echo htmlspecialchars($equipment); ?></p>

    <!-- Booking Form -->
    <form method="POST" action="confirm_booking.php">
        <!-- Hidden input for Room ID -->
        <input type="hidden" name="RoomID" value="<?php echo htmlspecialchars($room_id); ?>">

        <!-- Date Picker -->
        <label for="booking-date">Select Date:</label>
        <input type="date" id="booking-date" name="date" required>

        <!-- Time Slots Dropdown -->
        <label for="time-slot">Select Time Slot:</label>
        <select id="time-slot" name="time_slot" required>
            <option value="" disabled selected>Select Time Slot</option>
            <!-- Time slots populated here using ajax function -->
        </select>

        <!-- Submit Button -->
        <button type="submit" class="btn custom-book-button">Confirm Booking</button>
    </form>

    <!-- ajax script to fetch available time slots from db-->
    <script>
        $(document).ready(function() {
            // Trigger when a date is selected
            $('#booking-date').change(function() {
                var selectedDate = $(this).val(); 
                var roomID = <?php echo json_encode($room_id); ?>; // Pass the room ID from PHP

                if (selectedDate) {
                    // ajax request to fetch time slots for the selected date
                    $.ajax({
                        url: 'fetch_time_slots.php',
                        type: 'POST',
                        data: {
                            date: selectedDate, 
                            room_id: roomID
                        },
                        success: function(response) {
                            // populate the time slot dropdown
                            $('#time-slot').html(response);
                        }
                    });
                }
            });


        });
    </script>
</body>

</html>