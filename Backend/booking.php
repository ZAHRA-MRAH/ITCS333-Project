<?php
session_start();
require('header.php');
require('Connection.php');
// Retrieve room details from POST (displayRooms.php)
$room_id = $_POST['RoomID'];
$room_number = $_POST['RoomNumber'];
$capacity = $_POST['Capacity'];
$equipment = $_POST['Equipment'];

$query = "SELECT imgURL FROM room WHERE RoomID = :room_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT); // Assuming RoomID is an integer
$stmt->execute();

$imgURL = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="icon" type="image/x-icon" href="..\pictures\uob-logo.svg">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Frontend/homestyle.css">
</head>

<body>
    <h1>Book Room <?php echo htmlspecialchars($room_number); ?></h1>
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
            <!-- confirmation message -->
    <div id="confirmation-message" style="margin-top: 20px; max-width: 500px; margin-left: 20px;" class="d-none  mx-auto"></div>
        </div>
        <div class="img-container">
            <img src=<?php echo $imgURL ?> alt="Room Image" width="300" height="300">
            
        </div>
    </div>
    </form>

    <style>
   
    h1 {
        color: #2F5175;
        margin: 30px 0;
        text-align: center;
        font-size: 2.5rem;
    }

    h2 {
        color: #1B61AC;
        margin: 20px 0;
        margin-left: 20px;
        margin-bottom: 0;
        font-size: 1.8rem;
    }

    .main-container {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        gap: 40px;
    }

    .left-container {
        flex: 1;
        display: flex;
        flex-direction: column;
        max-width: 60%;
    }

    .details-container {
        top: auto;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        height: fit-content;
        width: 99%;
        background-color: #FEFEFE; 
        border: 1px solid #D9D5C8; 
        border-radius: 8px;
        padding: 20px;
        margin-top: 10px;
        margin-left: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    
    .capacity {
        display: flex;
        align-items: center;
        margin-right: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        width: 100%;
    }

    .equipment {
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        width: 100%;
    }

    .icon {
        width: 24px;
        height: 24px;
        margin-right: 10px;
    }

    .capacity p,
    .equipment p {
        color: #44471C; 
        margin: 0;
        font-size: 16px;
    }

    .img-container {
        position: relative;
        top: 20px;
        width: 40%;
        height: fit-content;
        background-color: #FEFEFE;
        border: 1px solid #D9D5C8;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        align-self: flex-start;
    }

    .img-container img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 4px;
    }

    .bookingform-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
        height: auto;
        min-height: 250px;
        width: 100%;
        background-color: #FEFEFE; 
        border: 1px solid #D9D5C8; 
        border-radius: 8px;
        padding: 20px;
        margin-left: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }


    .booking-date {
        text-align: center;
        margin: 10px 0;
    }

    label {
        font-family: Arial, sans-serif;
        font-size: 18px;
        font-weight: bold;
        color: #CCC7B6; 
        margin-top: 10px;
    }

    .Date input[type="date"] {
        width: 200px;
        border: 2px solid #CCC7B6; 
        border-radius: 5px;
        font-size: 16px;
        text-align: center;
        outline: none;
        box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);
    }

    #time-slot {
        background-color: #1B61AC; 
        border-radius: 5px;
        color: #FEFEFE;
        padding: 10px;
        font-size: 16px;
        cursor: pointer;
    }

    #time-slot option {
        color: #000;
    }

    .custom-book-button {
        position: absolute;
        right: 20px;
        bottom: 10px;
        background-color: #2F5175; 
        color: #FEFEFE; 
        margin-top: 20px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .custom-book-button:hover {
        background-color: #1B61AC; 
        color: #FEFEFE; 
    }

    #booking-form {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }


    label {
        color: #2F5175;
        margin-bottom: 5px;
    }

    .custom-book-button {
        position: static;
        align-self: flex-end;
        margin-top: 20px;
    }

    #confirmation-message {
        max-width: 800px;
        margin: 20px auto;
        text-align: center;
    }

    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            gap: 20px;
            padding: 10px;
        }
        
        .left-container {
            max-width: 100%;
        }
        
        .img-container {
            position: static;
            width: 100%;
        }
        
        h1 {
            font-size: 2rem;
            margin: 20px 0;
        }
        
        h2 {
            font-size: 1.5rem;
            margin-left: 10px;
        }

        .details-container,
        .bookingform-container {
            margin-left: 0;
            width: 100%;
        }

        .capacity,
        .equipment {
            padding: 8px;
        }

        .capacity p,
        .equipment p {
            font-size: 14px;
        }

        #time-slot {
            width: 100%;
        }

        #booking-form {
            gap: 10px;
        }

        .custom-book-button {
            width: 100%;
            margin-top: 15px;
        }
    }

    @media (max-width: 480px) {
        h1 {
            font-size: 1.8rem;
        }

        h2 {
            font-size: 1.3rem;
        }

        .details-container,
        .bookingform-container {
            padding: 15px;
        }

        .img-container {
            padding: 15px;
        }

        .capacity p,
        .equipment p {
            font-size: 12px;
        }

        label {
            font-size: 16px;
        }

        .Date input[type="date"],
        #time-slot {
            font-size: 14px;
            width: 100%;
        }

        .custom-book-button {
            font-size: 14px;
            padding: 8px 15px;
        }
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
<?php require('footer.php');?>

</html>