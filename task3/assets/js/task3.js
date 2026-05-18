
function postForm(page, body) {
    var status = 0;
    return fetch("index.php?page=" + page, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body
    }).then(function (r) {
        status = r.status;
        return r.json();
    }).then(function (data) {
        data._status = status;
        return data;
    });
}

/* Add to cart (used on home / category pages).
 * Guests / admins get redirected to login. */
function addToCart(medicineId, qty) {
    postForm("api_cart_add",
        "medicine_id=" + encodeURIComponent(medicineId) +
        "&quantity=" + encodeURIComponent(qty))
        .then(function (data) {
            if (data._status === 403 || (!data.success && /log in/i.test(data.message || ""))) {
                alert("Please log in as a customer to add items to the cart.");
                window.location = (window.BASE_URL || "/") + "index.php?page=login";
                return;
            }
            if (!data.success) { alert(data.message); return; }
            updateBadge(data.cart_count);
            alert("Added to cart!");
        })
        .catch(function () { alert("Network error."); });
}

/* Increase / decrease quantity on the cart page */
function changeQty(cartId, delta) {
    var row = document.getElementById("cart-row-" + cartId);
    var qtySpan = row.querySelector(".qty");
    var newQty = parseInt(qtySpan.textContent, 10) + delta;
    var stock = parseInt(row.getAttribute("data-stock"), 10);

    if (newQty < 1) { alert("Quantity must be at least 1."); return; }
    if (newQty > stock) { alert("Only " + stock + " in stock."); return; }

    postForm("api_cart_update",
        "cart_id=" + cartId + "&quantity=" + newQty)
        .then(function (data) {
            if (!data.success) { alert(data.message); return; }
            qtySpan.textContent = newQty;
            row.querySelector(".subtotal").textContent = "৳" + data.subtotal;
            document.getElementById("cartTotal").textContent = "৳" + data.total;
            updateBadge(data.cart_count);
        })
        .catch(function () { alert("Network error."); });
}

/* Remove a cart item */
function removeItem(cartId) {
    if (!confirm("Remove this item?")) { return; }
    postForm("api_cart_remove", "cart_id=" + cartId)
        .then(function (data) {
            if (!data.success) { alert(data.message); return; }
            var row = document.getElementById("cart-row-" + cartId);
            if (row) { row.parentNode.removeChild(row); }
            document.getElementById("cartTotal").textContent = "৳" + data.total;
            updateBadge(data.cart_count);
            if (data.empty) { location.reload(); }
        })
        .catch(function () { alert("Network error."); });
}

function updateBadge(count) {
    var badge = document.getElementById("cart-badge");
    if (badge) { badge.textContent = count; }
}

/* ---------------- Reusable real-time filters ---------------- */
document.addEventListener("DOMContentLoaded", function () {
    // Table row filter (cart page)
    document.querySelectorAll(".table-search").forEach(function (box) {
        var run = function () {
            var table = document.getElementById(box.getAttribute("data-table"));
            if (!table) { return; }
            var q = box.value.toLowerCase(), shown = 0;
            table.querySelectorAll("tbody tr").forEach(function (row) {
                if (row.hasAttribute("data-skip-filter")) { return; }
                var m = row.textContent.toLowerCase().indexOf(q) !== -1;
                row.style.display = m ? "" : "none";
                if (m) { shown++; }
            });
            var c = document.getElementById(box.getAttribute("data-count"));
            if (c) { c.textContent = shown + " item" + (shown === 1 ? "" : "s") + " found"; }
        };
        box.addEventListener("input", run);
        run();
    });

    // Order-card filter (my orders page)
    document.querySelectorAll(".order-search").forEach(function (box) {
        var run = function () {
            var wrap = document.getElementById(box.getAttribute("data-wrap"));
            if (!wrap) { return; }
            var q = box.value.toLowerCase(), shown = 0;
            wrap.querySelectorAll(".order-card").forEach(function (card) {
                var m = card.textContent.toLowerCase().indexOf(q) !== -1;
                card.style.display = m ? "" : "none";
                if (m) { shown++; }
            });
            var c = document.getElementById(box.getAttribute("data-count"));
            if (c) { c.textContent = shown + " order" + (shown === 1 ? "" : "s") + " found"; }
        };
        box.addEventListener("input", run);
        run();
    });
});

/* ---- Checkout validation ---- */
function validateCheckout(form) {
    if (form.shipping_address.value.trim() === "") {
        alert("Shipping address cannot be empty."); return false;
    }
    return true;
}

function validatePayment(form) {
    var chosen = false;
    for (var i = 0; i < form.payment_method.length; i++) {
        if (form.payment_method[i].checked) { chosen = true; break; }
    }
    if (!chosen) { alert("Please select a payment method."); return false; }
    return true;
}
