document.addEventListener("DOMContentLoaded", function () {
    let productTypeDropdown = document.getElementById("product_type_id");
    let categoriesWrapper = document.getElementById("categories");
    let attributesWrapper = document.getElementById("attributes");

    productTypeDropdown.addEventListener("change", function () {
        let productTypeId = this.value;

        fetch(`/seller/products/getCategoriesByProductType?product_type_id=${productTypeId}`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
        })
        .then(response => response.json())
        .then(data => {
            
            // Clear previous categories
            categoriesWrapper.innerHTML = "";

            // Append checkboxes for each category
            data.categories.forEach(category => {
                let checkboxDiv = document.createElement("div");
                checkboxDiv.classList.add("form-check");

                let checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.name = "categories[]";
                checkbox.value = category.id;
                checkbox.classList.add("form-check-input");
                checkbox.id = `category-${category.id}`;

                let label = document.createElement("label");
                label.classList.add("form-check-label");
                label.htmlFor = `category-${category.id}`;
                label.textContent = category.name;

                checkboxDiv.appendChild(checkbox);
                checkboxDiv.appendChild(label);
                categoriesWrapper.appendChild(checkboxDiv);
            });

            if (Object.keys(data.attributes).length === 0) {
                console.log("No attributes found for this product type.");
            } else {
                attributesWrapper.innerHTML = "";
                Object.entries(data.attributes).forEach(([key, value]) => {
                    let inputDiv = document.createElement("div");
                    inputDiv.classList.add("form-group");
            
                    let label = document.createElement("label");
                    label.setAttribute("for", key);
                    label.textContent = key;
            
                    let input = document.createElement("input");
                    input.type = "text";
                    input.name = `attributes[${key}]`;
                    input.id = key;
                    input.classList.add("form-control");

                    input.value = value || "";
            
                    inputDiv.appendChild(label);
                    inputDiv.appendChild(input);
                    attributesWrapper.appendChild(inputDiv);
                });
            }
            
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });
});
