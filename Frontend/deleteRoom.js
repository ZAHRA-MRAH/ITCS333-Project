document.addEventListener("DOMContentLoaded", function () {
    const deleteButton = document.getElementById("deleteButton");
    const confirmModal = new bootstrap.Modal(document.getElementById("confirmModal"));
    const confirmDelete = document.getElementById("confirmDelete");
    const deleteForm = document.getElementById("deleteRoomForm");

    deleteButton.addEventListener("click", function (event) {
        event.preventDefault(); // Prevent default form submission
        const selectElement = document.getElementById("roomNumber");
        const selectedRoom = selectElement.options[selectElement.selectedIndex].text;

        // Update the modal body with selected room details (optional)
        document.querySelector(".modal-body").innerHTML =
            `Are you sure you want to delete <br> ${selectedRoom}?`;

        // Show the modal
        confirmModal.show();
    });

    confirmDelete.addEventListener("click", function () {
        // Submit the form when the delete is confirmed
        deleteForm.submit();
    });
});
