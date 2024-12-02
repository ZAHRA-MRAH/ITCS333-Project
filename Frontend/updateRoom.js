document.addEventListener('DOMContentLoaded', function () {
    const updateRoomFormContainer = document.getElementById('updateRoomFormContainer');

    // Event listener for dynamic form submission
    updateRoomFormContainer.addEventListener('submit', function (e) {
        if (e.target && e.target.id === 'updateRoomForm') {
            e.preventDefault(); // Prevent default form submission

            // Validate inputs before submitting the form
            if (validateInputs(e.target)) {
                console.log("Update form is valid");
                e.target.submit();
            } else {
                console.log("Update form validation failed");
            }
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

    // Validate Room Type
    function validateRoomType(type) {
        const validRoomTypes = ['Classroom', 'Computer Lab', 'Meeting Room'];
        return validRoomTypes.includes(type);
    }

    // Validate Capacity
    function validateCapacity(cap) {
        return Number.isInteger(parseInt(cap)) && parseInt(cap) > 0;
    }

    // Validate Equipment
    function validateEquipment(equip) {
        return equip.trim().length > 0;
    }

    // Validate Image Upload (optional)
    function validateImageUpload(fileInput) {
        if (fileInput.files.length === 0) {
            return { isValid: true }; // Image is optional, so it's valid if empty
        }

        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/png'];
        const maxSize = 5 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            return { isValid: false, message: "Only JPG and PNG files are allowed." };
        }

        if (file.size > maxSize) {
            return { isValid: false, message: "File size cannot exceed 5 MB." };
        }

        return { isValid: true };
    }

    // Validate all inputs dynamically
    function validateInputs(form) {
        let isValid = false;

        // Track if at least one field is updated
        let isFieldUpdated = false;

        // Validate Room Type
        const newRoomType = form.querySelector('#newRoomType');
        const originalRoomType = newRoomType.getAttribute('data-original');
        const roomTypeValue = newRoomType.value;
        if (roomTypeValue !== originalRoomType) {
            isFieldUpdated = true;
            if (!validateRoomType(roomTypeValue)) {
                showError(newRoomType, "Please select a valid room type.");
                isValid = false;
            } else {
                showSuccess(newRoomType);
            }
        }

        // Validate Capacity
        const newCapacity = form.querySelector('#newCapacity');
        const originalCapacity = newCapacity.getAttribute('data-original');
        const capacityValue = newCapacity.value.trim();
        if (capacityValue !== originalCapacity) {
            isFieldUpdated = true;
            if (!validateCapacity(capacityValue)) {
                showError(newCapacity, "Capacity must be a positive number.");
                isValid = false;
            } else {
                showSuccess(newCapacity);
            }
        }

        // Validate Equipment
        const newEquipment = form.querySelector('#newEquipment');
        const originalEquipment = newEquipment.getAttribute('data-original');
        const equipmentValue = newEquipment.value.trim();
        if (equipmentValue !== originalEquipment) {
            isFieldUpdated = true;
            if (!validateEquipment(equipmentValue)) {
                showError(newEquipment, "Equipment description cannot be empty.");
                isValid = false;
            } else {
                showSuccess(newEquipment);
            }
        }

        // Validate Image Upload
        const newImgURL = form.querySelector('#newImgURL');
        const imageValidationResult = validateImageUpload(newImgURL);
        if (newImgURL.files.length > 0) {
            isFieldUpdated = true;
            if (!imageValidationResult.isValid) {
                showError(newImgURL, imageValidationResult.message);
                isValid = false;
            } else {
                showSuccess(newImgURL);
            }
        }

        // Allow submission only if at least one field is updated
        if (!isFieldUpdated) {
            alert("Please update at least one field before submitting.");
        }

        return isFieldUpdated && isValid;
    }
});