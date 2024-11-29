document.getElementById('searchForm').addEventListener('submit', async function(e) {
    e.preventDefault(); // Prevent traditional form submission

    const formData = new FormData(e.target);

    // Send fetch request
    const response = await fetch('search.php', {
      method: 'POST',
      body: formData,
    });

    const rooms = await response.json();

    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '<div class="room-list"></div>'; // Add a container for the grid layout
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
                                <h4>Capacity:</h4>
                                <p>${room.Capacity} people</p>
                                <h4>Equipment:</h4>
                                <ul>
                                    ${room.Equipment.split('\n').map((item) => `<li>${item.trim()}</li>`).join('')}
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn custom-close-color" data-bs-dismiss="modal">Close</button>
                                <form method="POST" action="booking.php">
                                    <input type="hidden" name="RoomID" value="${room.RoomID}">
                                    <input type="hidden" name="RoomNumber" value="${room.RoomNumber}">
                                    <input type="hidden" name="Capacity" value="${room.Capacity}">
                                    <input type="hidden" name="Equipment" value="${room.Equipment}">
                                    <button type="submit" class="btn custom-book-button">Book Room</button>
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