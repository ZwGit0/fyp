document.querySelectorAll('.accordion').forEach(button => {
    button.addEventListener('click', () => {
        let panel = button.nextElementSibling;
        panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
    });
});

// Toggle accordion panels and rotate icon
document.querySelectorAll('.accordion').forEach(button => {
    button.addEventListener('click', () => {
        // Toggle the active class on the clicked button (accordion)
        button.classList.toggle('active');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const loginRequiredElements = document.querySelectorAll('.needs-login');
    
    loginRequiredElements.forEach(function (element) {

        // If the element is a link, handle the click event
        if (element.tagName.toLowerCase() === 'a') {
            element.addEventListener('click', function (e) {
                const isNotLoggedIn = element.classList.contains('not-logged-in');
                
                if (isNotLoggedIn) {
                    e.preventDefault();  // Prevent the link from navigating
                    
                    const userConfirmed = confirm("You must log in to view your cart. Do you want to log in?");
                    
                    if (userConfirmed) {
                        window.location.href = element.href; // Navigate to the link after confirmation
                    }
                }
            });
        }

        // If the element is a form, handle the submit event
        if (element.tagName.toLowerCase() === 'form') {
            element.addEventListener('submit', function (e) {
                const isNotLoggedIn = element.classList.contains('not-logged-in');
                
                if (isNotLoggedIn) {
                    e.preventDefault();  // Prevent form submission
                    
                    const userConfirmed = confirm("You must log in to add items to your cart. Do you want to log in?");
                    
                    if (userConfirmed) {
                        element.submit(); // Submit the form after confirmation
                    }
                }
            });
        }
    });
});
