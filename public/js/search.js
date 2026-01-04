document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.querySelector(".search-bar input");
    let noResultsMessageElement = document.getElementById("no-results-message");
    let searchResults = document.getElementById("search-results");
    let searchTimeout; 

    searchInput.addEventListener("keydown", function (e) {
        if (e.key === "Enter") {
            e.preventDefault(); 
            let query = this.value.trim();
            if (query.length > 0) {
                fetchSearchResults(query); 
            }  else {
                searchResults.innerHTML = "<p class='no-results'>Enter a value.</p>";
                searchResults.style.display = "block";

                setTimeout(() => {
                    searchResults.style.display = "none";
                }, 1000); 
            }
        }
    });

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            let query = this.value.trim();
            clearTimeout(searchTimeout);
            if (query.length > 0) {
                searchTimeout = setTimeout(() => {
                    fetchSearchResults(query);
                }, 600);
            } else {
                searchResults.innerHTML = "";
                searchResults.style.display = "none";
                noResultsMessageElement.style.visibility = "hidden";
            }
        });
    }

    function fetchSearchResults(query) {
        fetch("/search?query=" + encodeURIComponent(query), {
            headers: { "X-Requested-With": "XMLHttpRequest" },
        })
            .then(response => response.json())
            .then(data => {
                searchResults.innerHTML = "";
                if (data.length > 0) {
                    searchResults.style.display = "block";
                    noResultsMessageElement.style.visibility = "hidden";

                    data.forEach(product => {
                        let item = document.createElement("div");
                        item.classList.add("search-item");
                        item.innerHTML = `<a href="/product/${product.id}">${product.name}</a>`;
                        searchResults.appendChild(item);
                    });
                } else {
                    searchResults.innerHTML = "<p class='no-results'>No products found.</p>";
                    searchResults.style.display = "block";
                }
            })
            .catch((error) => {
                console.error("Search error:", error);
                searchResults.innerHTML = "<p class='no-results'>Error fetching results.</p>";
                searchResults.style.display = "block";
            });
    }
});
