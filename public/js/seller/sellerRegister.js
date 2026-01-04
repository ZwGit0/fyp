document.getElementById('phone').addEventListener('input', function(event) {
    // Allow only numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
});

document.getElementById('registerForm').addEventListener('submit', function(event) {
    // Clear previous error messages
    document.getElementById('nameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('addressError').textContent = '';
    document.getElementById('cityError').textContent = '';
    document.getElementById('stateError').textContent = '';
    document.getElementById('zipCodeError').textContent = '';
    document.getElementById('countryError').textContent = '';
    document.getElementById('passwordError').textContent = '';
    document.getElementById('confirmPasswordError').textContent = '';

    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    const state = document.getElementById('state').value.trim();
    const zipCode = document.getElementById('zip_code').value.trim();
    const country = document.getElementById('country').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

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

    // Password validation
    if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long.';
        hasError = true;
    }

    // Confirm password validation
    if (password !== confirmPassword) {
        document.getElementById('confirmPasswordError').textContent = 'Passwords do not match.';
        hasError = true;
    }

    if (hasError) {
        event.preventDefault(); // Prevent form submission if there are errors
    }
});
