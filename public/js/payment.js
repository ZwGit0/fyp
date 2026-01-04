document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        if (document.getElementById('card').checked) {
            document.getElementById('card-details').style.display = 'block';
        } else {
            document.getElementById('card-details').style.display = 'none';
        }
    });
});

document.querySelector('.order-btn').addEventListener('click', function (event) {
    event.preventDefault(); // Prevent form submission

    // Form validation first
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('firstNameError').textContent = '';
    document.getElementById('lastNameError').textContent = '';
    document.getElementById('addressError').textContent = '';
    document.getElementById('cityError').textContent = '';
    document.getElementById('stateError').textContent = '';
    document.getElementById('zipCodeError').textContent = '';
    document.getElementById('countryError').textContent = '';
    document.getElementById('cardNumberError').textContent = '';
    document.getElementById('expirationDateError').textContent = '';
    document.getElementById('securityCodeError').textContent = '';
    document.getElementById('cardHolderNameError').textContent = '';

    // Get form field values
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    const first_name = document.getElementById('first_name').value.trim();
    const last_name = document.getElementById('last_name').value.trim();

    const address = document.getElementById('address').value.trim();
    const city = document.getElementById('city').value.trim();
    const state = document.getElementById('state').value.trim();
    const zipCode = document.getElementById('zipcode').value.trim();
    const country = document.getElementById('country').value.trim();
    
    const cardNumber = document.getElementById('card_number').value.trim();
    const expirationDate = document.getElementById('expiration_date').value.trim();
    const securityCode = document.getElementById('security_code').value.trim();
    const cardHolderName = document.getElementById('card_holder_name').value.trim();

    let hasError = false;
    
    // Email Validation
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (email === '') {
        document.getElementById('emailError').textContent = 'Email cannot be empty.';
        hasError = true;
    } else {
        if (!emailPattern.test(email)) {
            document.getElementById('emailError').textContent = 'Please enter a valid email address.';
            hasError = true;
        }
    }

    // Phone validation (must be at least 10 digits)
    if (phone === '') {
        document.getElementById('phoneError').textContent = 'Phone number cannot be empty.';
        hasError = true;
    } else {
        const phonePattern = /^[0-9]{10,}$/;
        if (!phonePattern.test(phone)) {
            document.getElementById('phoneError').textContent = 'Phone number must be at least 10 digits long and only contain numbers.';
            hasError = true;
        }
    }

    // First name cannot be empty
    if (first_name === '') {
        document.getElementById('firstNameError').textContent = 'First name cannot be empty.';
        hasError = true;
    }
    // last name cannot be empty
    if (last_name === '') {
        document.getElementById('lastNameError').textContent = 'Last name cannot be empty.';
        hasError = true;
    }

    // Address, City, State, and Country (no numbers allowed)
    if (address === '') {
        document.getElementById('addressError').textContent = 'Address cannot be empty.';
        hasError = true;
    } 

    const addressPattern = /\d/;
    if (city === '') {
        document.getElementById('cityError').textContent = 'City cannot be empty.';
        hasError = true;
    } else {
        if (addressPattern.test(city)) {
            document.getElementById('cityError').textContent = 'City cannot contain numbers.';
            hasError = true;
        }
    }

    if (state === '') {
        document.getElementById('stateError').textContent = 'State cannot be empty.';
        hasError = true;
    } else {
        if (addressPattern.test(state)) {
            document.getElementById('stateError').textContent = 'State cannot contain numbers.';
            hasError = true;
        }
    }

    if (country === '') {
        document.getElementById('countryError').textContent = 'Country cannot be empty.';
        hasError = true;
    } else {
        if (addressPattern.test(country)) {
            document.getElementById('countryError').textContent = 'Country cannot contain numbers.';
            hasError = true;
        }
    }

    // Zip Code validation (US format)
    if (zipCode === '') {
        document.getElementById('zipCodeError').textContent = 'Zip code cannot be empty.';
        hasError = true;
    } else {
        const zipCodePattern = /^\d{5}(-\d{4})?$/; // Matches 5 digits or 9 digits (e.g., 12345 or 12345-6789)
        if (!zipCodePattern.test(zipCode)) {
            document.getElementById('zipCodeError').textContent = 'Please enter a valid zip code (5 or 9 digits).';
            hasError = true;
        }
    }

    // Card Number Validation (16 digits)
    if (document.getElementById('card').checked) {
        if (cardNumber === '') {
            document.getElementById('cardNumberError').textContent = 'Card number cannot be empty.';
            hasError = true;
        } else {
            const cardNumberPattern = /^[0-9]{16}$/;
            if (!cardNumberPattern.test(cardNumber)) {
                document.getElementById('cardNumberError').textContent = 'Please enter a valid 16 digit card number.';
                hasError = true;
            }
        }

        // Expiration Date validation (MM/YY format)
        if (expirationDate === '') {
            document.getElementById('expirationDateError').textContent = 'Expiration date cannot be empty.';
            hasError = true;
        } else {
            const expirationDatePattern = /^(0[1-9]|1[0-2])\/(\d{2})$/;
            if (!expirationDatePattern.test(expirationDate)) {
                document.getElementById('expirationDateError').textContent = 'Expiration date must be in MM/YY format.';
                hasError = true;
            }
        }

        // Security Code (CVV) validation (3 digits)
        if (securityCode === '') {
            document.getElementById('securityCodeError').textContent = 'Security code (CVV) cannot be empty.';
            hasError = true;
        } else {
            const securityCodePattern = /^[0-9]{3}$/;
            if (!securityCodePattern.test(securityCode)) {
                document.getElementById('securityCodeError').textContent = 'Security code (CVV) must be 3 digits.';
                hasError = true;
            }
        }

        // Card Holder Name validation (no numbers allowed)
        if (cardHolderName === '') {
            document.getElementById('cardHolderNameError').textContent = 'Card holder name cannot be empty.';
            hasError = true;
        } else {
            const cardHolderNamePattern = /^[A-Za-z\s]+$/;
            if (!cardHolderNamePattern.test(cardHolderName)) {
                document.getElementById('cardHolderNameError').textContent = 'Card holder name can only contain letters and spaces.';
                hasError = true;
            }
        }
    }

    // If there are any validation errors, prevent form submission
    if (hasError) {
        event.preventDefault(); // Stops form submission
        return; // Exit the function early if validation fails
    }

    // If validation passes, proceed with the calculations and form submission
    const orderValue = calculateOrderValue(); // Calculate order value dynamically
    const discount = calculateDiscount(orderValue); // Calculate discount dynamically
    const subtotalAfterDiscount = orderValue - discount; // Subtotal after discount
    const tax = calculateTax(subtotalAfterDiscount); // Calculate tax
    const totalPrice = subtotalAfterDiscount + tax; // Total price including tax

    // Update the hidden inputs with the calculated values
    document.getElementById('order-value').value = orderValue;
    document.getElementById('discount').value = discount;
    document.getElementById('subtotal_after_discount').value = subtotalAfterDiscount;
    document.getElementById('tax').value = tax;
    document.getElementById('total-price').value = totalPrice;

    // Submit the form after updating the hidden inputs
    document.getElementById('order').submit();
});

// Example calculation functions (replace with your actual logic)
function calculateOrderValue() {
    // Add your logic to calculate the order value based on cart items
    return 100; // Placeholder value
}

function calculateDiscount(orderValue) {
    return orderValue * 0.1; // 10% discount
}

function calculateTax(subtotalAfterDiscount) {
    return subtotalAfterDiscount * 0.06; // 6% tax
}



