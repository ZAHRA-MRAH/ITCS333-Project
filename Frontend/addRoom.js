document.addEventListener('DOMContentLoaded', function () {
    const roomForm = document.getElementById('roomForm');
    const roomNumber = document.getElementById('RoomNumber');
    const roomType = document.getElementById('RoomType');
    const capacity = document.getElementById('Capacity');
    const equipment = document.getElementById('Equipment');
    const imgURL = document.getElementById('imgURL');

    // Form submission Event Listener
    roomForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        //Only Submit when inputs are validated
        if (validateInputs()) {
            console.log("Form is valid");
            roomForm.submit();
        } else {
            console.log("Form validation failed");
        }
    });
    // Display Error Message for Input Field
    function showError(input, message) {
        const fieldDiv = input.closest('.field.input');
        fieldDiv.classList.remove('success');
        fieldDiv.classList.add('error');
        fieldDiv.querySelector('.error-message').textContent = message;
    }
       // Mark an input field as valid
    function showSuccess(input) {
        const fieldDiv = input.closest('.field.input');
        fieldDiv.classList.remove('error');
        fieldDiv.classList.add('success');
        fieldDiv.querySelector('.error-message').textContent = '';
    }
    // Validate Room Number
    function validateRoomNumber(number) {
        const roomNumberRegex = /^(S40-(0\d{2}|10\d{2}|20\d{2}))$/;
        return roomNumberRegex.test(number);
    }
    function validateRoomType(type) {
        // List of valid room types
        const validRoomTypes = ['Classroom', 'Computer Lab'];
        // Check if the selected value matches one of the valid options
        return validRoomTypes.includes(type);
    }
    function validateCapacity(cap) {
        return Number.isInteger(parseInt(cap)) && parseInt(cap) > 0; // Positive integer
    }
    // Validate Equipment
    function validateEquipment(equip) {
        return equip.trim().length > 0; // Not empty
    }
    function validateImageUpload(fileInput) {
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png'];
        const maxSize = 5 * 1024 * 1024; // 5 MB
    
        // Check if a file is selected
        if (!file) {
            return { isValid: false, message: "Please upload an image." };
        }
    
        // Check file type
        if (!allowedTypes.includes(file.type)) {
            return { isValid: false, message: "Only JPG and PNG files are allowed." };
        }
    
        // Check file size
        if (file.size > maxSize) {
            return { isValid: false, message: "File size cannot exceed 5 MB." };
        }
    
        return { isValid: true }; // Return an object indicating success
    }
    // Validate all inputs
    function validateInputs() {
        let isValid = true;

        // Validate Room Number
        const roomNumberValue = roomNumber.value.trim();
        if (!validateRoomNumber(roomNumberValue)) {
            showError(roomNumber, "Invalid Room Number (must be following: S40-0XX, S40-10XX, or S40-20XX).");
            isValid = false;
        } else {
            showSuccess(roomNumber);
        }

        // Validate Room Type
        const roomTypeValue = roomType.value;
        if (!validateRoomType(roomTypeValue)) {
            showError(roomType, "Please select a valid room type.");
            isValid = false;
        } else {
            showSuccess(roomType);
        }

        // Validate Capacity
        const capacityValue = capacity.value.trim();
        if (!validateCapacity(capacityValue)) {
            showError(capacity, "Capacity must be a positive number.");
            isValid = false;
        } else {
            showSuccess(capacity);
        }

        // Validate Equipment
        const equipmentValue = equipment.value.trim();
        if (!validateEquipment(equipmentValue)) {
            showError(equipment, "Equipment description cannot be empty.");
            isValid = false;
        } else {
            showSuccess(equipment);
        }

        // Validate Image Upload
        const imageValidationResult = validateImageUpload(imgURL);
        if (!imageValidationResult.isValid) {
            showError(imgURL, imageValidationResult.message); // Show error message
            isValid = false;
        } else {
            showSuccess(imgURL); // Mark the field as valid
        }

        return isValid;
    }
});
