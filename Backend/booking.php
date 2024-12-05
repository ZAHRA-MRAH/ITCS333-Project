<?php
session_start();
require('header.php');
// Retrieve room details from POST (displayRooms.php)
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

</head>

<body>
    <h1>Book Room <?php echo htmlspecialchars($room_number); ?></h1>
    <p>Capacity: <?php echo htmlspecialchars($capacity); ?></p>
    <p>Equipment: <?php echo htmlspecialchars($equipment); ?></p>

    <!-- Booking Form -->
    <form id="booking-form">
        <input type="hidden" name="room_id" id="room-id" value="<?php echo htmlspecialchars($room_id); ?>">

        <!-- Date Picker -->
        <label for="booking-date">Select Date:</label>
        <input type="date" name="date" id="booking-date" required>

        <!-- Time Slots Dropdown -->
        <label for="time-slot">Select Time Slot:</label>
        <select name="time_slot" id="time-slot" required>
            <option value="" disabled selected>Select Time Slot</option>
        </select>

        <!-- Submit Button -->
        <button type="button" id="confirm-booking-btn" class="btn custom-book-button">Confirm Booking</button>
    </form>

    <!-- confirmation message -->
    <div id="confirmation-message" style="margin-top: 20px;"></div>

    <style>
          


        h1 {
            font-size: 24px;
            color: #553c9a;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
        
            color: #553c9a;
            margin-bottom: 5px;
        }
        h1 {
            font-size: 24px;
            color: #553c9a;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
        
            color: #553c9a;
            margin-bottom: 5px;
        }

        input,
        select {
            width: 30%;
            padding: 4px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
</style>

     

       
        
    

    <!--Javascirpt to fetch timeslots from db and display booking confirmation or error to the user in the same page -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const bookingDate = document.getElementById("booking-date");
            const timeSlotDropdown = document.getElementById("time-slot");
            const roomID = document.getElementById("room-id").value;
            const confirmBookingBtn = document.getElementById("confirm-booking-btn");
            const confirmationMessage = document.getElementById("confirmation-message");

            // async function to fetch time slots
            async function fetchTimeSlots(date) {
                try {
                    const response = await fetch('fetch_time_slots.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `date=${encodeURIComponent(date)}&room_id=${encodeURIComponent(roomID)}`
                    });
                    
                    // check if request was successful
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    
                    // .text because fetch_time_slots.php will return html (dropdwn populated with html option element  )
                    const data = await response.text();
                    // populate the dropdown with the fetched timeslots
                    timeSlotDropdown.innerHTML = data;

                } catch (error) {
                    console.error('Error fetching time slots:', error);
                    // error msg in dropdown 
                    timeSlotDropdown.innerHTML = "<option value=''>Failed to load time slots</option>";
                }
            }

            // confirm booking async func (js fetch)
            async function confirmBooking() {
                const selectedDate = bookingDate.value;
                const selectedTimeSlot = timeSlotDropdown.value;

                //  make sure user selected date and time slot
                if (!selectedDate || !selectedTimeSlot) {
                    confirmationMessage.textContent = "Please select a date and time slot.";
                    confirmationMessage.style.color = "red";
                    return;
                }

                try {
                    // send booking data to confirm_booking.php for processing
                    const response = await fetch('confirm_booking.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `room_id=${encodeURIComponent(roomID)}&date=${encodeURIComponent(selectedDate)}&time_slot=${encodeURIComponent(selectedTimeSlot)}`
                    });

                    // check if request was successful
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    // parse the JSON response
                    const result = await response.json();

                    // update confirmation message
                    if (result.success) {
                        confirmationMessage.textContent = "Booking confirmed!";
                        confirmationMessage.style.color = "green";
                    } else {
                        confirmationMessage.textContent = result.message || "Booking failed.";
                        confirmationMessage.style.color = "red";
                    }

                } catch (error) {
                    console.error('Error confirming booking:', error);
                    confirmationMessage.textContent = "An error occurred while processing your booking.";
                    confirmationMessage.style.color = "red";
                }
            }
            // event listener for selected date change
            bookingDate.addEventListener("change", (event) => {
                const selectedDate = event.target.value;
                if (selectedDate) {
                    fetchTimeSlots(selectedDate);
                }
            });
            
             // event listener for when confirm btn is clicked
            confirmBookingBtn.addEventListener("click", confirmBooking);
        });
    </script>
</body>

</html>