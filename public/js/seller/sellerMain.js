document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebarCollapse");
    const mainContent = document.querySelector("main");

    if (sidebarToggle) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebar.style.width = "250px";
                mainContent.style.marginLeft = "250px";
            } else {
                sidebar.style.width = "0";
                mainContent.style.marginLeft = "0";
            }
        });
    }
    
    // Active class toggle for sidebar links
    const sidebarLinks = document.querySelectorAll('#sidebar ul li');

    sidebarLinks.forEach(function(item) {
        item.addEventListener('click', function() {
            // Remove 'active' class from all items
            sidebarLinks.forEach(function(link) {
                link.classList.remove('active');
            });

            // Add 'active' class to the clicked item
            item.classList.add('active');
        });
    });
});

window.addEventListener('load', function() {
    adjustSidebarHeight();
});

window.addEventListener('resize', adjustSidebarHeight);

function adjustSidebarHeight() {
    var sidebar = document.getElementById('sidebar');
    var viewportHeight = window.innerHeight; // Get the current viewport height
    sidebar.style.height = `${viewportHeight}px`; // Set sidebar height to match the viewport
}