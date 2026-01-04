function confirmDelete() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone!')) {
        // If user confirms, show the password field and form
        document.getElementById('delete-form').style.display = 'block';
        document.getElementById('delete-button').style.display = 'none'; // Hide the delete button
    }
}