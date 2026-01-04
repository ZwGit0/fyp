document.getElementById('forgotPasswordForm').addEventListener('submit', function(e) {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let nameError = document.getElementById('nameError');
    let emailError = document.getElementById('emailError');
    let isValid = true;

    nameError.textContent = '';
    emailError.textContent = '';

    if (!name) {
        nameError.textContent = 'Name is required.';
        isValid = false;
    }

    if (!email) {
        emailError.textContent = 'Email is required.';
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailError.textContent = 'Invalid email format.';
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});