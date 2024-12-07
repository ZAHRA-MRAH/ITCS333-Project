document.addEventListener('DOMContentLoaded', function () {
    const updateRoomFormContainer = document.getElementById('updateRoomFormContainer');

    // Event listener for dynamic form submission
    updateRoomFormContainer.addEventListener('submit', function (e) {
        if (e.target && e.target.id === 'updateRoomForm') {
            e.preventDefault(); // Prevent default form submission

            // Validate inputs before submitting the form
            if (validateInputs(e.target)) {
                console.log("Update form is valid");
                e.target.submit(); // Submit form if validation passes
            } else {
                console.log("Update form validation failed");
            }
        }
    });

    function showError(input, message) {
        const fieldDiv = input.closest('.field.input');
        if (!fieldDiv) {
            console.error("Field container not found for input:", input);
            return; // Exit if the container is missing
        }
    
        fieldDiv.classList.remove('success');
        fieldDiv.classList.add('error');
    
        const errorMessage = fieldDiv.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.textContent = message;
        } else {
            console.error("Error message container not found for input:", input);
        }
    }
    
    function showSuccess(input) {
        const fieldDiv = input.closest('.field.input');
        if (!fieldDiv) {
            console.error("Field container not found for input:", input);
            return; // Exit if the container is missing
        }
    
        fieldDiv.classList.remove('error');
        fieldDiv.classList.add('success');
    
        const errorMessage = fieldDiv.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.textContent = '';
        } else {
            console.error("Error message container not found for input:", input);
        }
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
        let isValid = true;

        // Validate Room Type
        const newRoomType = form.querySelector('#newRoomType');
        if (!validateRoomType(newRoomType.value)) {
            showError(newRoomType, "Please select a valid room type.");
            isValid = false;
        } else {
            showSuccess(newRoomType);
        }

        // Validate Capacity
        const newCapacity = form.querySelector('#newCapacity');
        if (!validateCapacity(newCapacity.value.trim())) {
            showError(newCapacity, "Capacity must be a positive number.");
            isValid = false;
        } else {
            showSuccess(newCapacity);
        }

        // Validate Equipment
        const newEquipment = form.querySelector('#newEquipment');
        if (!validateEquipment(newEquipment.value.trim())) {
            showError(newEquipment, "Equipment description cannot be empty.");
            isValid = false;
        } else {
            showSuccess(newEquipment);
        }

        // Validate Image Upload (optional)
        const newImgURL = form.querySelector('#newImgURL');
        if (!validateImageUpload(newImgURL)) {
            isValid = false;
        }

        return isValid;
    }
});