document.getElementById('phone').addEventListener('input', function(event) {
    // Allow only numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('editForm').addEventListener('submit', function(event) {
    // Clear previous error messages
    document.getElementById('nameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';

    document.getElementById('firstNameError').textContent = '';
    document.getElementById('lastNameError').textContent = '';
    document.getElementById('addressError').textContent = '';
    document.getElementById('cityError').textContent = '';
    document.getElementById('stateError').textContent = '';
    document.getElementById('zipCodeError').textContent = '';
    document.getElementById('countryError').textContent = '';

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    const firstName = document.getElementById('address_first_name').value.trim();
    const lastName = document.getElementById('address_last_name').value.trim();
    const address = document.getElementById('address_address').value.trim();
    const city = document.getElementById('address_city').value.trim();
    const state = document.getElementById('address_state').value.trim();
    const zipCode = document.getElementById('address_zip_code').value.trim();
    const country = document.getElementById('address_country').value.trim();

    let hasError = false;

    // Name validation
    if (name === '') {
        document.getElementById('nameError').textContent = 'Name cannot be empty.';
        hasError = true;
    }

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('emailError').textContent = 'Email cannot be empty.';
        hasError = true;
    } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address.';
        hasError = true;
    }

    // Phone number validation (must be numeric)
    const minPhoneLength = 10;
    if (phone === '') {
        document.getElementById('phoneError').textContent = 'Phone number cannot be empty.';
        hasError = true;
    } else if (phone.length < minPhoneLength) {
        document.getElementById('phoneError').textContent = `Phone number must be at least ${minPhoneLength} digits.`;
        hasError = true;
    }

    // First name validation
    if (firstName === '') {
        document.getElementById('firstNameError').textContent = 'First name cannot be empty.';
        hasError = true;
    }

    // Last name validation
    if (lastName === '') {
        document.getElementById('lastNameError').textContent = 'Last name cannot be empty.';
        hasError = true;
    }


    // Address validation (cannot contain numbers)
    const addressPattern = /\d/;
    if (address === '') {
        document.getElementById('addressError').textContent = 'Address cannot be empty.';
        hasError = true;
    }

    // City validation (cannot contain numbers)
    if (city === '') {
        document.getElementById('cityError').textContent = 'City cannot be empty.';
        hasError = true;
    } else if (addressPattern.test(city)) {
        document.getElementById('cityError').textContent = 'City cannot contain numbers.';
        hasError = true;
    }

    // State validation (cannot contain numbers)
    if (state === '') {
        document.getElementById('stateError').textContent = 'State cannot be empty.';
        hasError = true;
    } else if (addressPattern.test(state)) {
        document.getElementById('stateError').textContent = 'State cannot contain numbers.';
        hasError = true;
    }

    // Country validation (cannot contain numbers)
    if (country === '') {
        document.getElementById('countryError').textContent = 'Country cannot be empty.';
        hasError = true;
    } else if (addressPattern.test(country)) {
        document.getElementById('countryError').textContent = 'Country cannot contain numbers.';
        hasError = true;
    }

    // Zip Code validation (valid 5-digit or 9-digit format for US zip code)
    const zipCodePattern = /^\d{5}(-\d{4})?$/;
    if (zipCode === '') {
        document.getElementById('zipCodeError').textContent = 'Zip code cannot be empty.';
        hasError = true;
    } else if (!zipCodePattern.test(zipCode)) {
        document.getElementById('zipCodeError').textContent = 'Please enter a valid zip code.';
        hasError = true;
    }

    if (hasError) {
        event.preventDefault(); // Prevent form submission if there are errors
    }
});
