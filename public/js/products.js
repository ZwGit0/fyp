const minPriceSlider = document.getElementById('minPrice');
const maxPriceSlider = document.getElementById('maxPrice');
const minPriceText = document.getElementById('minPriceText');
const maxPriceText = document.getElementById('maxPriceText');
const minPriceLabel = document.getElementById('minPriceLabel');
const maxPriceLabel = document.getElementById('maxPriceLabel');
const productItems = document.querySelectorAll('.product-item');

minPriceSlider.addEventListener('input', updateFilters);
maxPriceSlider.addEventListener('input', updateFilters);

// Update price values when slider is moved
minPriceSlider.addEventListener('input', function() {
    if (parseInt(minPriceSlider.value) > parseInt(maxPriceSlider.value)) {
        minPriceSlider.value = maxPriceSlider.value;  
    }
    minPriceText.value = minPriceSlider.value;
    minPriceLabel.textContent = minPriceSlider.value;
});

maxPriceSlider.addEventListener('input', function() {
    if (parseInt(maxPriceSlider.value) < parseInt(minPriceSlider.value)) {
        maxPriceSlider.value = minPriceSlider.value;  
    }
    maxPriceText.value = maxPriceSlider.value;
    maxPriceLabel.textContent = maxPriceSlider.value;
});

// Update sliders when user types in text input fields
minPriceText.addEventListener('input', function() {
    if (parseInt(minPriceText.value) > parseInt(maxPriceSlider.value)) {
        minPriceText.value = maxPriceSlider.value;  
    }
    minPriceSlider.value = minPriceText.value;
    minPriceLabel.textContent = minPriceText.value;
    updateFilters();
});

maxPriceText.addEventListener('input', function() {
    if (parseInt(maxPriceText.value) < parseInt(minPriceSlider.value)) {
        maxPriceText.value = minPriceSlider.value;  
    }
    maxPriceSlider.value = maxPriceText.value;
    maxPriceLabel.textContent = maxPriceText.value;
    updateFilters();
});

// Filter products
function updateFilters() {
    const minPrice = parseInt(minPriceSlider.value);
    const maxPrice = parseInt(maxPriceSlider.value);

    document.getElementById('minPriceLabel').textContent = minPrice;
    document.getElementById('maxPriceLabel').textContent = maxPrice;

    productItems.forEach(product => {
        const productPrice = parseInt(product.getAttribute('data-price'));

        // Show or hide products based on price range
        if (productPrice >= minPrice && productPrice <= maxPrice) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Sort products
document.addEventListener('DOMContentLoaded', function () {
    const sortOptions = document.getElementById('sortOptions');
    const productGrid = document.querySelector('.product-grid');
    const productItems = Array.from(productGrid.getElementsByClassName('product-item'));

    sortOptions.addEventListener('change', function () {
        const selectedOption = sortOptions.value;
        let sortedProducts;

        switch (selectedOption) {
            case 'low-to-high':
                sortedProducts = productItems.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceA - priceB;
                });
                break;
            case 'high-to-low':
                sortedProducts = productItems.sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceB - priceA;
                });
                break;
            case 'alphabet-asc':
                sortedProducts = productItems.sort((a, b) => {
                    const nameA = a.querySelector('p').textContent.trim();
                    const nameB = b.querySelector('p').textContent.trim();
                    return nameA.localeCompare(nameB);
                });
                break;
            case 'alphabet-desc':
                sortedProducts = productItems.sort((a, b) => {
                    const nameA = a.querySelector('p').textContent.trim();
                    const nameB = b.querySelector('p').textContent.trim();
                    return nameB.localeCompare(nameA);
                });
                break;
            default:
                sortedProducts = productItems;
        }

        // Clear current grid and append sorted products
        productGrid.innerHTML = '';
        sortedProducts.forEach(product => productGrid.appendChild(product));
    });
});

document.getElementById('sortOptions').addEventListener('focus', function() {
    // Remove 'Sort' option when the dropdown is opened
    const sortOption = document.getElementById('sortOption');
    if (sortOption) {
        sortOption.style.display = 'none';
    }
});
