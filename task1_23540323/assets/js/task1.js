function validateRegister(form) {
    if (form.name.value.trim() === "") { alert("Name is required."); return false; }
    if (!/^\S+@\S+\.\S+$/.test(form.email.value)) { alert("Enter a valid email."); return false; }
    if (form.password.value.length < 8) { alert("Password must be at least 8 characters."); return false; }
    if (form.address.value.trim() === "") { alert("Address is required."); return false; }
    if (form.phone.value.trim() === "") { alert("Phone is required."); return false; }
    return true;
}

function validateLogin(form) {
    if (!/^\S+@\S+\.\S+$/.test(form.email.value)) { alert("Enter a valid email."); return false; }
    if (form.password.value === "") { alert("Password is required."); return false; }
    return true;
}

function validateProfile(form) {
    if (form.name.value.trim() === "") { alert("Name is required."); return false; }
    if (!/^\S+@\S+\.\S+$/.test(form.email.value)) { alert("Enter a valid email."); return false; }
    if (form.address.value.trim() === "") { alert("Address is required."); return false; }
    if (form.phone.value.trim() === "") { alert("Phone is required."); return false; }
    return true;
}

function validatePasswordChange(form) {
    if (form.current_password.value === "") { alert("Enter current password."); return false; }
    if (form.new_password.value.length < 8) { alert("New password must be at least 8 characters."); return false; }
    return true;
}

var _searchTimer = null;

function runSearch() {
    var q = document.getElementById("searchInput").value;
    var vendor = document.getElementById("vendorInput").value;
    var genre = document.getElementById("genreSelect").value;

    var qs = "index.php?page=api_medicine_search"
        + "&q=" + encodeURIComponent(q)
        + "&vendor=" + encodeURIComponent(vendor)
        + "&genre=" + encodeURIComponent(genre);

    fetch(qs)
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) { return; }
            renderMedicines(data.medicines);
            setCount("searchCount", data.count, "medicine");
        })
        .catch(function () { });
}


function liveSearch() {
    clearTimeout(_searchTimer);
    _searchTimer = setTimeout(runSearch, 250);
}


document.addEventListener("DOMContentLoaded", function () {
    var s = document.getElementById("searchInput");
    var v = document.getElementById("vendorInput");
    var g = document.getElementById("genreSelect");
    if (s) { s.addEventListener("input", liveSearch); }
    if (v) { v.addEventListener("input", liveSearch); }
    if (g) { g.addEventListener("change", liveSearch); }

    initTableSearch();
    initCardSearch();
});

function initCardSearch() {
    var boxes = document.querySelectorAll(".card-search");
    boxes.forEach(function (box) {
        var run = function () {
            var grid = document.getElementById(box.getAttribute("data-grid"));
            if (!grid) { return; }
            var q = box.value.toLowerCase();
            var cards = grid.querySelectorAll(".med-card");
            var shown = 0;
            cards.forEach(function (card) {
                var match = card.textContent.toLowerCase().indexOf(q) !== -1;
                card.style.display = match ? "" : "none";
                if (match) { shown++; }
            });
            var c = document.getElementById(box.getAttribute("data-count"));
            if (c) { c.textContent = shown + " medicine" + (shown === 1 ? "" : "s") + " found"; }
        };
        box.addEventListener("input", run);
        run();
    });
}

function setCount(elId, n, noun) {
    var el = document.getElementById(elId);
    if (!el) { return; }
    el.textContent = n + " " + noun + (n === 1 ? "" : "s") + " found";
}

function initTableSearch() {
    var boxes = document.querySelectorAll(".table-search");
    boxes.forEach(function (box) {
        box.addEventListener("input", function () {
            filterTable(box.value,
                box.getAttribute("data-table"),
                box.getAttribute("data-count"),
                box.getAttribute("data-noun") || "result");
        });
        filterTable("", box.getAttribute("data-table"),
            box.getAttribute("data-count"),
            box.getAttribute("data-noun") || "result");
    });
}

function filterTable(query, tableId, countId, noun) {
    var table = document.getElementById(tableId);
    if (!table) { return; }
    query = query.toLowerCase();
    var rows = table.querySelectorAll("tbody tr");
    var shown = 0;
    rows.forEach(function (row) {
        if (row.hasAttribute("data-skip-filter")) { return; }
        var match = row.textContent.toLowerCase().indexOf(query) !== -1;
        row.style.display = match ? "" : "none";
        if (match) { shown++; }
    });
    var c = document.getElementById(countId);
    if (c) { c.textContent = shown + " " + noun + (shown === 1 ? "" : "s") + " found"; }
}

function renderMedicines(list) {
    var grid = document.getElementById("medicineGrid");
    if (!list.length) {
        grid.innerHTML = '<p class="muted">No medicines match your search.</p>';
        return;
    }
    var html = "";
    list.forEach(function (m) {
        var img = m.image_path
            ? '<img src="' + m.image_path + '" alt="">'
            : '<div class="img-placeholder">No image</div>';
        html +=
            '<div class="med-card card">' +
            '<div class="med-img">' + img + '</div>' +
            '<h4>' + escapeHtml(m.name) + '</h4>' +
            '<p class="muted">' + escapeHtml(m.vendor_name) + ' &middot; ' +
            escapeHtml(m.category_name) + '</p>' +
            '<p class="price">৳' + m.price + '</p>' +
            '<p class="muted">Stock: ' + m.availability + '</p>' +
            '<button class="btn btn-green" onclick="addToCart(' + m.id +
            ',1)">Add to Cart</button>' +
            '</div>';
    });
    grid.innerHTML = html;
}

function escapeHtml(s) {
    var d = document.createElement("div");
    d.textContent = s;
    return d.innerHTML;
}
