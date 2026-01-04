document.addEventListener("DOMContentLoaded", function () {
    recalculateTotal();

    const addToCartButtons = document.querySelectorAll(".add-to-cart");
    
    addToCartButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            
            const productId = this.getAttribute("data-product-id");
            
            fetch(`/cart/add/${productId}`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            })
            .then(data => {
                if (data.success) {
                    alert(data.success); 
                } else {
                    alert("Failed to add product to the cart.");
                }
            })
            .catch(error => {
                console.error("Error adding product to cart:", error);
                alert("An error occurred while adding the product to the cart.");
            });
        });
    });

    // Handling Increase/Decrease in Cart Quantity
    document.querySelectorAll(".decrease, .increase").forEach((button) => {
        button.addEventListener("click", function () {
            let cartId = this.getAttribute("data-id");
            let quantityField = this.classList.contains("decrease") ? this.nextElementSibling : this.previousElementSibling;
            let quantity = parseInt(quantityField.value);
            let stock = parseInt(this.getAttribute("data-stock")) || Infinity;
            let price = parseFloat(this.closest("tr").querySelector(".total").getAttribute("data-price"));
            let reservedStock = getReservedStockForItem(cartId); // Get reserved stock for this specific item

            let availableStock = stock - reservedStock;

            // Handle Decrease Quantity
            if (this.classList.contains("decrease") && quantity > 1) {
                quantity -= 1;
                reservedStock -= 1; // Release 1 unit of reserved stock when decreasing
            }
            // Handle Increase Quantity
            else if (this.classList.contains("increase")) {
                // Disable increase if stock is already fully reserved
                if (quantity + 1 > availableStock) {
                    alert("Item max quantity reached!");
                    return;
                }
                quantity += 1;
                reservedStock += 1; // Reserve 1 more unit of stock when increasing
            }
            else if (this.classList.contains("decrease") && quantity === 1) {
                if (!confirm("Are you sure you want to remove this item from the cart?")) {
                    return;
                }
                removeFromCart(cartId, this.closest("tr"));
                return;
            }

            quantityField.value = quantity;
            let subtotalCell = this.closest("tr").querySelector(".total");
            let newSubtotal = price * quantity;
            subtotalCell.textContent = `RM ${newSubtotal.toFixed(2)}`;

            updateCart(cartId, quantity, reservedStock);
            recalculateTotal();
        });
    });

    document.querySelectorAll(".remove-btn").forEach((button) => {
        button.addEventListener("click", function () {
            let cartId = this.getAttribute("data-id");
            removeFromCart(cartId, this.closest("tr"));
        });
    });

    function removeFromCart(cartId, rowElement) {
        fetch(`/cart/remove/${cartId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    rowElement.remove();
                    recalculateTotal();
                    alert(data.success); 
                } else {
                    alert("Failed to remove item from cart");
                }
            })
            .catch((error) => {
                console.error("Error removing item:", error);
                alert("An error occurred while removing the item from the cart.");
            });
    }

    function updateCart(cartId, quantity, reservedStock) {
        fetch(`/cart/update/${cartId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            },
            body: JSON.stringify({ quantity: quantity, reservedStock: reservedStock }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    alert("Due to another customer's reservation. Please adjust your quantity.");
                    location.reload();
                }
            })
            .catch((error) => {
                console.error("Error updating cart:", error);
                alert("An error occurred while updating the cart.");
            });
    }

    function recalculateTotal() {
        let subtotal = 0;
        document.querySelectorAll(".total").forEach((cell) => {
            subtotal += parseFloat(cell.textContent.replace("RM", "").replace(/,/g, ''));
        });

        let discount = subtotal * 0.1; // 10% discount
        let subtotalAfterDiscount = subtotal - discount;
        let tax = subtotalAfterDiscount * 0.06; // 6% tax
        let total = subtotalAfterDiscount + tax;

        document.querySelector(".order-value").textContent = subtotal.toFixed(2);
        document.querySelector(".discount").textContent = discount.toFixed(2);
        document.querySelector(".subtotal-after-discount").textContent = subtotalAfterDiscount.toFixed(2);
        document.querySelector(".tax").textContent = tax.toFixed(2);
        document.querySelector(".total-price").textContent = total.toFixed(2);
    }

    // Helper: Get Reserved Stock for a specific cart item
    function getReservedStockForItem(cartId) {
        let reservedStock = 0;

        document.querySelectorAll('.cart-item').forEach(item => {
            if (item.getAttribute('data-cart-id') == cartId) {
                reservedStock += parseInt(item.querySelector('.quantity').value);
            }
        });

        return reservedStock;
    }
});
