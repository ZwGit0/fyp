document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
    let password = document.getElementById('password').value;
    let passwordConfirmation = document.getElementById('password_confirmation').value;
    let passwordError = document.getElementById('passwordError');
    let passwordConfirmationError = document.getElementById('passwordConfirmationError');
    let isValid = true;

    passwordError.textContent = '';
    passwordConfirmationError.textContent = '';

    if (!password) {
        passwordError.textContent = 'Password is required.';
        isValid = false;
    } else if (password.length < 8) {
        passwordError.textContent = 'Password must be at least 8 characters.';
        isValid = false;
    }

    if (password !== passwordConfirmation) {
        passwordConfirmationError.textContent = 'Passwords do not match.';
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});