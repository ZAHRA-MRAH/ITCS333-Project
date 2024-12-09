document.getElementById('searchForm').addEventListener('submit', async function (e) {
    e.preventDefault(); // Prevent traditional form submission

    const formData = new FormData(e.target);
    const selectedDate = formData.get('date');
    const selectedTimeSlot = formData.get('time_slot');


    // Send fetch request to search.php
    const response = await fetch('search.php', {
        method: 'POST',
        body: formData,
    });

    const rooms = await response.json();

    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '<div class="room-list"></div>';
    const roomListDiv = resultsDiv.querySelector('.room-list');

    if (rooms.length > 0) {
        rooms.forEach((room) => {
            const roomCard = `
            <div class="room-card">
            
                <img class="room-img" src="${room.imgURL}" alt="Room Image">
                <h3>Room ${room.RoomNumber}</h3>
                <button type="button" class="btn btn-primary custom-book-button" data-bs-toggle="modal" data-bs-target="#modal${room.RoomID}">
                    View Details
                </button>

                <!-- Modal -->
                <div class="modal fade" id="modal${room.RoomID}" tabindex="-1" aria-labelledby="modalLabel${room.RoomID}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel${room.RoomID}">Room ${room.RoomNumber} Details</h5>
                            </div>
                            <div class="modal-body">
                                <div class="capacity">
                                    <img src="../pictures/people.png" alt="Capacity Icon" class="icon">
                                    <h4>Capacity:</h4>
                                </div>
                                <p>${room.Capacity} people</p>
                                <div class="equipment">
                                    <img src="../pictures/blackboard.png" alt="Equipment Icon" class="icon">
                                    <h4>Equipment:</h4>
                                </div>
                                <ul>
                                    ${room.Equipment.split('\n').map((item) => `<li>${item.trim()}</li>`).join('')}
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>
                                <form method="POST" id="bookingForm${room.RoomID}" class="booking-form">
                                    <input type="hidden" name="RoomID" value="${room.RoomID}">
                                    <input type="hidden" name="RoomNumber" value="${room.RoomNumber}">
                                    <input type="hidden" name="Capacity" value="${room.Capacity}">
                                    <input type="hidden" name="Equipment" value="${room.Equipment}">
                                    <input type="hidden" name="date" value="${selectedDate}">
                                    <input type="hidden" name="time_slot" value="${selectedTimeSlot}">
                                    <button type="button" class="btn custom-book-button" onclick="bookRoom(${room.RoomID})">Book Room</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `;
            roomListDiv.innerHTML += roomCard; // Append each room card with modal
        });
    } else {
        resultsDiv.innerHTML = '<div class="alert alert-danger custom-alert" role="alert" >No rooms available for the selected criteria.</div>';
    }
});

// Function to book room 
async function bookRoom(roomId) {
    const bookingForm = document.getElementById('bookingForm' + roomId);
    const formData = new FormData(bookingForm);

    // Send the booking data via fetch
    const response = await fetch('bookingSearch.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    // Select the modal and the message container
    const modal = document.getElementById('modal' + roomId);
    const messageDiv = document.getElementById('message');

    if (result.success) {
        // Hide the modal with Bootstrap's modal API
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide();

        // Display success message
        messageDiv.innerHTML = `<div class="alert alert-success col-12 col-md-6 mx-auto">${result.message}</div>`;
    } else {
        // Hide the modal with Bootstrap's modal API
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide();

        // Display error message
        messageDiv.innerHTML = `<div class="alert alert-danger col-12 col-md-6 mx-auto">${result.message}</div>`;
    }
}

