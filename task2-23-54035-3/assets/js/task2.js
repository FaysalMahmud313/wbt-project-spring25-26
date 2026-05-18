document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".table-search").forEach(function (box) {
        var run = function () {
            filterTable(box.value,
                        box.getAttribute("data-table"),
                        box.getAttribute("data-count"),
                        box.getAttribute("data-noun") || "result");
        };
        box.addEventListener("input", run);
        run();
    });
});

function filterTable(query, tableId, countId, noun) {
    var table = document.getElementById(tableId);
    if (!table) { return; }
    query = query.toLowerCase();
    var shown = 0;
    table.querySelectorAll("tbody tr").forEach(function (row) {
        if (row.hasAttribute("data-skip-filter")) { return; }
        var match = row.textContent.toLowerCase().indexOf(query) !== -1;
        row.style.display = match ? "" : "none";
        if (match) { shown++; }
    });
    var c = document.getElementById(countId);
    if (c) { c.textContent = shown + " " + noun + (shown === 1 ? "" : "s") + " found"; }
}

function validateCategory(form) {
    if (form.name.value.trim() === "") { alert("Category name is required."); return false; }
    return true;
}

function validateAdmin(form) {
    if (form.name.value.trim() === "")        { alert("Name is required."); return false; }
    if (!/^\S+@\S+\.\S+$/.test(form.email.value)) { alert("Enter a valid email."); return false; }
    if (form.password.value.length < 8)       { alert("Password must be at least 8 characters."); return false; }
    if (form.address.value.trim() === "")     { alert("Address is required."); return false; }
    if (form.phone.value.trim() === "")       { alert("Phone is required."); return false; }
    return true;
}

function validateMedicine(form) {
    if (form.name.value.trim() === "")        { alert("Name is required."); return false; }
    if (form.category_id.value === "")        { alert("Select a category."); return false; }
    if (form.vendor_name.value.trim() === "") { alert("Vendor is required."); return false; }
    if (parseFloat(form.price.value) <= 0)    { alert("Price must be greater than 0."); return false; }
    if (parseInt(form.availability.value, 10) < 0) { alert("Stock cannot be negative."); return false; }
    return true;
}


function updateOrderStatus(orderId, status) {
    if (!confirm("Set order #" + orderId + " to " + status + "?")) { return; }

    var body = "order_id=" + encodeURIComponent(orderId) +
               "&status="  + encodeURIComponent(status);

    fetch("index.php?page=api_order_status", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: body
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (!data.success) { alert(data.message || "Update failed."); return; }
        var row = document.getElementById("order-row-" + orderId);
        if (row) {
            row.querySelector(".status").className = "status status-" + data.status;
            row.querySelector(".status").textContent = data.status;
            row.querySelector("td:last-child").innerHTML = "&mdash;";
        }
    })
    .catch(function () { alert("Network error."); });
}
