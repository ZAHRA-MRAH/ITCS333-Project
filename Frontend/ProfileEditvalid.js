
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form');
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const phoneNumber = document.getElementById('phoneNumber');

    form.addEventListener('submit', e => {
        e.preventDefault(); // Prevent default submission
        const clickedButton = e.submitter;
        if (clickedButton && clickedButton.name === 'updatecnfrm'){
        if (validateInputs()) {
            
            form.submit(); // Submit form if valid
        } } else if (clickedButton && clickedButton.name === 'return') {
            
            
            window.location.href = '../Backend/profile.php'; 
    }
    });

    function showError(input, message) {
        const fieldDiv = input.closest('.field.input');
        fieldDiv.classList.remove('success');
        fieldDiv.classList.add('error');
        fieldDiv.querySelector('.error-message').textContent = message;
    }

    function showSuccess(input) {
        const fieldDiv = input.closest('.field.input');
        fieldDiv.classList.remove('error');
        fieldDiv.classList.add('success');
        fieldDiv.querySelector('.error-message').textContent = '';
    }

    function validateFirstName(firstName) {
        const fnameRegex = /^[a-z][a-z\s]{3,15}$/i;
        return fnameRegex.test(String(firstName));
    }

    function validateLastName(lastName) {
        const lnameRegex = /^[a-z][a-z\s]{3,15}$/i;
        return lnameRegex.test(String(lastName));
    }

    function validatePhoneNumber(phoneNumber) {
        const phoneRegex = /^((\+|00)973)?\s?\d{8}$/;
        return phoneRegex.test(String(phoneNumber));
    }

    function validateInputs() {
        let isValid = true; 

        const firstNameValue = firstName.value.trim();
        const lastNameValue = lastName.value.trim();
        const phoneNumberValue = phoneNumber.value.trim();

        // Validate First Name
        if (!validateFirstName(firstNameValue)) {
            showError(firstName, 'Invalid first name (3-15 characters, letters only)');
            isValid = false;
        } else {
            showSuccess(firstName);
        }

        // Validate Last Name
        if (!validateLastName(lastNameValue)) {
            showError(lastName, 'Invalid last name (3-15 characters, letters only)');
            isValid = false;
        } else {
            showSuccess(lastName);
        }

        // Validate Phone Number
        if (!validatePhoneNumber(phoneNumberValue)) {
            showError(phoneNumber, 'Invalid phone number');
            isValid = false;
        } else {
            showSuccess(phoneNumber);
        }

        return isValid; 
    }
});