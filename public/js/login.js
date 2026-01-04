document.getElementById('loginForm').addEventListener('submit', function(event) {
    
    document.getElementById('emailError').textContent = '';

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    let hasError = false;
    
    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === '') {
        document.getElementById('emailError').textContent = 'Email cannot be empty.';
        hasError = true;
    } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email address.';
        hasError = true;
    }

    // Password validation
    if (password.length < 8) {
        document.getElementById('passwordError').textContent = 'Password must be at least 8 characters long.';
        hasError = true;
    }
    
    if (hasError) {
        event.preventDefault(); // Prevent form submission if there are errors
    }

});
