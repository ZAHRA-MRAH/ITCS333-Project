<?php
session_start();
require('AdminHeader.php');
require('Connection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Schedule Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../Frontend/AdminStyle.css">
    <style>
        @media (max-width: 768px) {
            #main {
                height: 1024px;
            }
        }


    </style>
</head>

<body>
    <main id="main">
        <div class="box">
            <div class="container mt-5">
                <h2 class="mb-4">Room Schedule Management</h2>

                <!-- Error/Success message container -->
                <div id="errorContainer" class="alert d-none" role="alert"></div>

                <!-- Room selector -->
                <div class="mb-4">
                    <label for="roomSelect" class="form-label">Select Room:</label>
                    <select id="roomSelect" class="form-select">
                        <option value="">-- Select a Room --</option>
                        <?php
                        $stmt = $pdo->query("SELECT RoomID, RoomNumber FROM room");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='{$row['RoomID']}'>Room {$row['RoomNumber']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Action buttons -->
                <div class="mb-4">
                    <button class="btn btn-primary" id="addAction">Add Availability Slot</button>
                    <button class="btn btn-warning" id="updateAction">Update Availability Slot</button>
                    <button class="btn btn-danger" id="deleteAction">Delete Availability Slot</button>
                </div>

                <!-- Add/Update slot form -->
                <div id="slotForm" class="d-none">
                    <form id="slotFormDetails">
                        <div class="mb-3">
                            <label for="date" class="form-label">Select Date:</label>
                            <input type="date" id="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="startTime" class="form-label">Start Time:</label>
                            <input type="time" id="startTime" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="endTime" class="form-label">End Time:</label>
                            <input type="time" id="endTime" class="form-control">
                        </div>
                        <button type="button" id="submitButton" class="btn btn-success">Submit</button>
                    </form>
                </div>

                <!-- Slots list -->
                <div id="slotsList" class="mt-4 d-none">
                    <p class="text-muted">Select a slot to update/delete:</p>
                    <h4>Availability Slots </h4>
                    <ul id="slotsUl" class="list-group"></ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete confirmation bootstrap modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this availability slot?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Active state for add button */
        .btn-primary.active,
        .btn-primary:hover {
            background-color: #0b71de;
            color: white;
        }

        /* Active state for update button */
        .btn-warning.active,
        .btn-warning:hover {
            background-color: #d39e00;
            color: white;
        }

        /* Active state for delete button */
        .btn-danger.active,
        .btn-danger:hover {
            background-color: #bd2130;
            color: white;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentAction = '';

            const errorContainer = document.getElementById('errorContainer');
            const roomSelect = document.getElementById('roomSelect');
            const slotForm = document.getElementById('slotForm');
            const slotsList = document.getElementById('slotsList');
            const slotsUl = document.getElementById('slotsUl');
            const submitButton = document.getElementById('submitButton');
            const addAction = document.getElementById('addAction');
            const updateAction = document.getElementById('updateAction');
            const deleteAction = document.getElementById('deleteAction');


            // Modal Elements
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            const buttons = document.querySelectorAll('.btn');

            // click event listeners for all buttons
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove the active class from all buttons
                    buttons.forEach(btn => btn.classList.remove('active'));

                    // Add the active class to the clicked button
                    button.classList.add('active');
                });
            });



            function showError(messages) {
                if (Array.isArray(messages)) {
                    errorContainer.innerHTML = messages.map(msg => `<p>${msg}</p>`).join('');
                } else {
                    errorContainer.textContent = messages;
                }
                errorContainer.className = 'alert alert-danger';
                errorContainer.classList.remove('d-none');
            }

            function showSuccess(message) {
                errorContainer.textContent = message;
                errorContainer.className = 'alert alert-success';
                errorContainer.classList.remove('d-none');
            }

            function clearError() {
                errorContainer.textContent = '';
                errorContainer.classList.add('d-none');
            }


            // Add action event listener
            addAction.addEventListener('click', () => {
                clearError();
                currentAction = 'add';
                slotForm.classList.remove('d-none');
                slotsList.classList.add('d-none');
                clearError();
                submitButton.disabled = false; // Enable submit button for adding
            });

            // Update action event listener
            updateAction.addEventListener('click', async () => {
                clearError();
                const roomID = roomSelect.value;
                if (!roomID) {
                    showError('Please select a room first!');
                    return;
                }

                try {
                    const response = await fetch('roomAvailability_mgt.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            action: 'fetch',
                            roomID
                        }),
                    });

                    if (!response.ok) throw new Error('Failed to fetch slots.');

                    const slots = await response.json();
                    if (slots.length === 0) {
                        showError('No slots available to update.');
                        return;
                    }

                    slotsUl.innerHTML = '';
                    slots.forEach(slot => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.innerHTML = `
                    <input type="radio" name="updateSlot" value="${slot.AvailabilityID}">
                    ${slot.Date} | ${slot.StartTime} - ${slot.EndTime}
                `;
                        slotsUl.appendChild(listItem);
                    });

                    slotsList.classList.remove('d-none');
                    slotForm.classList.add('d-none');
                    currentAction = 'update';
                    submitButton.disabled = true; // Disable submit until a slot is selected
                } catch (error) {
                    showError(error.message || 'An unexpected error occurred while fetching slots.');
                }
            });

            // Delete action event listener
            deleteAction.addEventListener('click', async () => {
                clearError();
                const roomID = roomSelect.value;
                if (!roomID) {
                    showError('Please select a room first!');
                    return;
                }

                try {
                    const response = await fetch('roomAvailability_mgt.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'fetch',
                            roomID,
                        }),
                    });

                    if (!response.ok) throw new Error('Failed to fetch slots.');

                    const slots = await response.json();
                    slotsUl.innerHTML = '';
                    slots.forEach((slot) => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.innerHTML = `
                    ${slot.Date} | ${slot.StartTime} - ${slot.EndTime}
                    <button class="btn btn-sm btn-danger float-end delete-slot" data-id="${slot.AvailabilityID}">Delete</button>
                `;
                        slotsUl.appendChild(listItem);
                    });

                    slotsList.classList.remove('d-none');
                    slotForm.classList.add('d-none');
                } catch (error) {
                    showError(error.message || 'An unexpected error occurred while fetching slots.');
                }
            });

            // Event listener for Slot deletion (show the modal)
            slotsUl.addEventListener('click', (event) => {
                if (event.target.classList.contains('delete-slot')) {
                    selectedSlotID = event.target.getAttribute('data-id');
                    if (!selectedSlotID) {
                        showError('Slot ID is missing.');
                        return;
                    }

                    // Show confirmation modal
                    deleteModal.show();
                }
            });

            // Confirm deletion event
            confirmDeleteBtn.addEventListener('click', async () => {
                clearError();
                if (!selectedSlotID) return;

                try {
                    const response = await fetch('roomAvailability_mgt.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'delete',
                            id: selectedSlotID,
                        }),
                    });

                    const result = await response.json();
                    if (result.error) {
                        showError(result.error.join(', '));
                    } else {
                        showSuccess(result.success || 'Slot deleted successfully.');
                        const slotToDelete = document.querySelector(`button[data-id="${selectedSlotID}"]`).closest('li');
                        if (slotToDelete) {
                            slotToDelete.remove(); // Remove the deleted slot from the UI
                        }
                    }

                    // Hide modal after deletion
                    deleteModal.hide();
                    selectedSlotID = null; // Reset the selected slot ID
                } catch (error) {
                    showError(error.message || 'An error occurred while deleting the slot.');
                    deleteModal.hide(); // Hide modal on error
                }
            });

            // Slot selection (trigger update form)
            slotsUl.addEventListener('change', () => {
                const selectedSlot = document.querySelector('input[name="updateSlot"]:checked');
                if (selectedSlot) {
                    submitButton.disabled = false; // Enable submit button when a slot is selected

                    // Populate the form with the selected slot's details
                    const slotDetails = selectedSlot.closest('li').textContent.trim();
                    const [slotDate, slotTimes] = slotDetails.split(' | ');
                    const [startTime, endTime] = slotTimes.split(' - ');

                    document.getElementById('date').value = slotDate;
                    document.getElementById('startTime').value = startTime;
                    document.getElementById('endTime').value = endTime;

                    // Show the form
                    slotForm.classList.remove('d-none');
                    slotsList.classList.add('d-none');
                } else {
                    submitButton.disabled = true; // Disable submit button if no slot is selected
                }
            });


            // Submit button event listener
            submitButton.addEventListener('click', async () => {
                clearError();

                const roomID = roomSelect.value;
                const date = document.getElementById('date').value;
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;

                if (!roomID || !date || !startTime || !endTime) {
                    showError('All fields are required!');
                    return;
                }

                const payload = {
                    roomID,
                    date,
                    startTime,
                    endTime,
                    action: currentAction
                };

                if (currentAction === 'update') {
                    const selectedSlot = document.querySelector('input[name="updateSlot"]:checked');
                    if (!selectedSlot) {
                        showError('Please select a slot to update!');
                        return;
                    }
                    payload.id = selectedSlot.value; // Include the slot ID in the payload
                }

                try {
                    const response = await fetch('roomAvailability_mgt.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    const result = await response.json();
                    if (result.error) {
                        showError(result.error.join(', '));
                    } else {
                        showSuccess(result.success || 'Operation completed successfully.');
                        slotForm.classList.add('d-none'); // Hide form after successful update
                        slotsList.classList.add('d-none'); // Hide slots list after update
                    }
                } catch (error) {
                    showError(error.message || 'An unexpected error occurred while submitting the form.');
                }
            });

            document.querySelectorAll('#slotFormDetails input').forEach(input => {
                input.addEventListener('input', clearError); // Clear errors on user input
            });
        });
    </script>

<?php require('footer.php');?>
</body>

</html>