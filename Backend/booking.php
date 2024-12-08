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
    <link rel="icon" type="image/x-icon" href="..\pictures\uob-logo.svg">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <h1>Book Room <?php echo htmlspecialchars($room_number); ?></h1>
    <!-- confirmation message -->
    <div id="confirmation-message" style="margin-top: 20px; max-width: 500px; margin-left: 20px;" class="d-none  mx-auto"></div>
    <h2>Room Details</h2>
    <div class="main-container">
        <div class="left-container">

            <div class="details-container">
                <div class="capacity">
                    <img src="../pictures/people.png" alt="Capacity Icon" class="icon">
                    <p>Capacity: <?php echo htmlspecialchars($capacity); ?></p>
                </div>
                <div class="equipment">
                    <img src="../pictures/blackboard.png" alt="Equipment Icon" class="icon">
                    <p>Equipment: <?php echo htmlspecialchars($equipment); ?></p>
                </div>
            </div>

            <div class="bookingform-container">
                <form id="booking-form">
                    <input type="hidden" name="room_id" id="room-id" value="<?php echo htmlspecialchars($room_id); ?>">
                    <!-- Date Picker -->
                    <label for="booking-date" id="date-label">Select Date:</label>
                    <input type="date" name="date" id="booking-date" required><br>
                    <!-- Time Slots Dropdown -->
                    <label for="time-slot">Select Time Slot:</label>
                    <select name="time_slot" id="time-slot" required> <br>
                        <option value="" disabled selected>Select Time Slot</option>
                    </select>
                    <!-- Submit Button -->
                    <button type="button" id="confirm-booking-btn" class="btn custom-book-button">Confirm Booking</button>
                </form>
            </div>
        </div>
        <div class="img-container">
            <img src="https://placehold.co/500x400" alt="">
            <p>Room Image</p>
        </div>
    </div>
    </form>


    <style>
        h1 {
            color: #553c9a;
            text-indent: 2px;
        }

        h2 {
            color: #9B89CF;
            /*changelater*/
            text-indent: 25px;
        }

        .main-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .left-container {
            display: flex;
            flex-direction: column;
            width: 50%;
            margin-right: 20px;
        }

        .details-container {
            top: auto;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            height: fit-content;
            width: 99%;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            margin-left: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .img-container {
            position: relative;
            top: 0;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            margin-left: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .bookingform-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            position: relative;
            height: 200px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-left: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .capacity {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .equipment {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }

        .icon {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .capacity p,
        .equipment p {
            color: #9B89CF;
            margin: 0;
            font-size: 16px;
        }

        .booking-date {
            text-align: center;
            margin: 10px 0;
        }

        label {
            font-family: Arial, sans-serif;
            font-size: 18px;
            font-weight: normal;
            color: #b393d3;
            font-weight: bold;
            margin-top: 10px;

        }

        .Date input[type="date"] {
            width: 200px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            outline: none;
            box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
        }

        #time-slot {
            background-color: #9B89CF;
            border-radius: 5px;
            font-size: 17px;
            border: none;
            width: 175px;
            height: 35px;
            color: #fdfdfd;
            /* font-weight: bold; */
            text-align: center;
        }

        .custom-book-button {
            position: absolute;
            right: 20px;
            bottom: 10px;
            background-color: #9B89CF;
            color: white;
            margin-top: 10px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .custom-book-button:hover {
            background-color: #b393d3;
            color: white;
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
                    confirmationMessage.className = "alert alert-danger";
                    confirmationMessage.textContent = "Please select a date and time slot.";
                    confirmationMessage.classList.remove("d-none");
                    
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
                        confirmationMessage.className = "alert alert-success";
                        confirmationMessage.textContent = "Booking confirmed!";
                        
                    } else {
                        confirmationMessage.className = "alert alert-danger";
                        confirmationMessage.textContent = result.message || "Booking failed.";
                       
                    }
                    confirmationMessage.classList.remove("d-none");

                } catch (error) {
                    console.error('Error confirming booking:', error);
                    confirmationMessage.className = "alert alert-danger";
                    confirmationMessage.textContent = "An error occurred while processing your booking.";
                    confirmationMessage.classList.remove("d-none");
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