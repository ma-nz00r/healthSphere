<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "inventory_db";
$TABLE   = "inventory_items";

/* ---------- Connect and bootstrap DB ---------- */
$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS);
if ($mysqli->connect_error) {
    http_response_code(500);
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->query("CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($DB_NAME);

/* Create table if not exists */
$mysqli->query("
    CREATE TABLE IF NOT EXISTS `$TABLE` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        productName VARCHAR(200) NOT NULL,
        itemNo VARCHAR(100) NULL,
        problem VARCHAR(255) NULL,
        manufacturer VARCHAR(200) NULL,
        category VARCHAR(100) NULL,
        storeBox VARCHAR(100) NULL,
        price DECIMAL(10,2) NULL,
        quantity INT NULL,
        expiryDate DATE NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

/* ---------- Simple router for AJAX ---------- */
$action = $_GET['action'] ?? $_POST['action'] ?? null;

if ($action === 'load') {
    header('Content-Type: application/json; charset=utf-8');

    // Filters (all optional)
    $productName = trim($_GET['productName'] ?? '');
    $manufacturer = trim($_GET['manufacturer'] ?? '');
    $itemNo = trim($_GET['itemNo'] ?? '');
    $problem = trim($_GET['problem'] ?? '');
    $category = trim($_GET['category'] ?? '');

    $sql = "SELECT id, productName, itemNo, problem, manufacturer, category, storeBox, 
                   price, quantity, DATE_FORMAT(expiryDate, '%Y-%m-%d') AS expiryDate
            FROM `$TABLE`";
    $conds = [];
    $params = [];
    $types  = '';

    if ($productName !== '') { $conds[] = "productName LIKE ?"; $params[] = "%$productName%"; $types .= 's'; }
    if ($manufacturer !== '') { $conds[] = "manufacturer LIKE ?"; $params[] = "%$manufacturer%"; $types .= 's'; }
    if ($itemNo !== '') { $conds[] = "itemNo LIKE ?"; $params[] = "%$itemNo%"; $types .= 's'; }
    if ($problem !== '') { $conds[] = "problem LIKE ?"; $params[] = "%$problem%"; $types .= 's'; }
    if ($category !== '') { $conds[] = "category = ?"; $params[] = $category; $types .= 's'; }

    if ($conds) {
        $sql .= " WHERE " . implode(" AND ", $conds);
    }
    $sql .= " ORDER BY id DESC";

    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        echo json_encode([]);
        exit;
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    $rows = [];
    while ($row = $res->fetch_assoc()) {
        // ensure numeric formatting for front-end (optional)
        if ($row['price'] !== null) $row['price'] = (string)$row['price'];
        if ($row['quantity'] !== null) $row['quantity'] = (int)$row['quantity'];
        $rows[] = $row;
    }
    $stmt->close();
    echo json_encode($rows);
    exit;
}

if ($action === 'add') {
    header('Content-Type: application/json; charset=utf-8');

    // Read & normalize inputs
    $productName = trim($_POST['productName'] ?? '');
    $itemNo      = trim($_POST['itemNo'] ?? '');
    $problem     = trim($_POST['problem'] ?? '');
    $manufacturer= trim($_POST['manufacturer'] ?? '');
    $category    = trim($_POST['category'] ?? '');
    $storeBox    = trim($_POST['storeBox'] ?? '');
    $price       = $_POST['price'] ?? null;      // allow null
    $quantity    = $_POST['quantity'] ?? null;   // allow null
    $expiryDate  = trim($_POST['expiryDate'] ?? '');

    if ($productName === '') {
        echo json_encode(['status' => 'error', 'message' => 'Product Name is required']);
        exit;
    }

    // Convert empty strings to NULL to avoid invalid date/number inserts
    $price = ($price === '' ? null : $price);
    $quantity = ($quantity === '' ? null : $quantity);
    $expiryDate = ($expiryDate === '' ? null : $expiryDate);

    $sql = "INSERT INTO `$TABLE`
            (productName, itemNo, problem, manufacturer, category, storeBox, price, quantity, expiryDate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Use 's' for all; MySQL will coerce numeric strings; NULL stays NULL
    $stmt = $mysqli->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed']);
        exit;
    }
    $stmt->bind_param(
        'sssssssss',
        $productName,
        $itemNo === '' ? null : $itemNo,
        $problem === '' ? null : $problem,
        $manufacturer === '' ? null : $manufacturer,
        $category === '' ? null : $category,
        $storeBox === '' ? null : $storeBox,
        $price,
        $quantity,
        $expiryDate
    );

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

if ($action === 'delete') {
    header('Content-Type: application/json; charset=utf-8');

    $id = $_POST['id'] ?? '';
    if (!ctype_digit((string)$id)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid ID']);
        exit;
    }

    $stmt = $mysqli->prepare("DELETE FROM `$TABLE` WHERE id = ?");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare failed']);
        exit;
    }
    $id = (int)$id;
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    exit;
}

/* ---------- If no AJAX action, render the page ---------- */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory List</title>
    <link rel="stylesheet" href="inventory.css">
    <style>
        /* Minimal safe defaults in case CSS missing */
        body { font-family: system-ui, Arial, sans-serif; margin: 0; background:#f7f7f7; }
        .container { max-width: 1100px; margin: 24px auto; background:#fff; padding: 20px; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,.06); }
        header { display:flex; align-items:center; justify-content:space-between; margin-bottom: 14px; }
        #addItemBtn { padding:10px 14px; border:0; border-radius:8px; cursor:pointer; }
        .filters { display:grid; grid-template-columns: repeat(5, 1fr) auto; gap:10px; margin: 12px 0 18px; }
        .filter-group { display:flex; flex-direction:column; font-size:14px; }
        table { width:100%; border-collapse: collapse; }
        th, td { border-bottom:1px solid #ececec; padding:10px; text-align:left; }
        th { background:#fafafa; }
        .modal { display:none; position:fixed; z-index:10; left:0; top:0; width:100%; height:100%; overflow:auto; background:rgba(0,0,0,.35); }
        .modal-content { background:#fff; margin: 60px auto; padding:20px; border-radius:12px; max-width:520px; position:relative; }
        .close-button { position:absolute; right:12px; top:8px; cursor:pointer; font-size:22px; }
        button { cursor:pointer; }
        .apply-filter-btn { padding:10px 12px; border-radius:8px; border:1px solid #ddd; background:#fff; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Inventory List</h1>
            <div class="header-actions">
                <button id="addItemBtn">+ Add Item</button>
            </div>
        </header>

        <!-- Filters section -->
        <div class="filters">
            <div class="filter-group">
                <label for="productNameFilter">Product Name:</label>
                <input type="text" id="productNameFilter" placeholder="Search...">
            </div>
            <div class="filter-group">
                <label for="manufacturerFilter">Manufacturer:</label>
                <input type="text" id="manufacturerFilter" placeholder="Search...">
            </div>
            <div class="filter-group">
                <label for="itemNumberFilter">Item Number:</label>
                <input type="text" id="itemNumberFilter" placeholder="Search...">
            </div>
            <div class="filter-group">
                <label for="problemConditionFilter">Problem/Condition:</label>
                <input type="text" id="problemConditionFilter" placeholder="Search...">
            </div>
            <div class="filter-group">
                <label for="categoryFilter">Category:</label>
                <select id="categoryFilter">
                    <option value="">All</option>
                    <option value="Medication">Medication</option>
                    <option value="Cream">Cream</option>
                    <option value="Syrup">Syrup</option>
                    <option value="Tablet">Tablet</option>
                </select>
            </div>
            <button class="apply-filter-btn" id="applyFilterBtn">Apply Filter</button>
        </div>

        <!-- Inventory Table -->
        <table id="inventoryTable">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Item No.</th>
                    <th>Problem</th>
                    <th>Manufacturer</th>
                    <th>Category</th>
                    <th>Store Box</th>
                    <th>Price (per piece)</th>
                    <th>Quantity</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryBody"></tbody>
        </table>

        <!-- Add Item Modal -->
        <div id="addItemModal" class="modal">
            <div class="modal-content">
                <span class="close-button">&times;</span>
                <h2>Add New Item</h2>
                <form id="addItemForm">
                    <label for="newProductName">Product Name:</label>
                    <input type="text" id="newProductName" name="productName" required><br><br>

                    <label for="newItemNumber">Item No.:</label>
                    <input type="text" id="newItemNumber" name="itemNo"><br><br>

                    <label for="newProblem">Problem/Condition:</label>
                    <input type="text" id="newProblem" name="problem"><br><br>

                    <label for="newManufacturer">Manufacturer:</label>
                    <input type="text" id="newManufacturer" name="manufacturer"><br><br>

                    <label for="newCategory">Category:</label>
                    <select id="newCategory" name="category">
                        <option value="">-- Select --</option>
                        <option value="Medication">Medication</option>
                        <option value="Cream">Cream</option>
                        <option value="Syrup">Syrup</option>
                        <option value="Tablet">Tablet</option>
                    </select><br><br>

                    <label for="newStoreBox">Store Box:</label>
                    <input type="text" id="newStoreBox" name="storeBox"><br><br>

                    <label for="newPrice">Price (per piece):</label>
                    <input type="number" id="newPrice" min="0" step="0.01" name="price"><br><br>

                    <label for="newQuantity">Quantity:</label>
                    <input type="number" id="newQuantity" min="0" name="quantity"><br><br>

                    <label for="newExpiryDate">Expiry Date:</label>
                    <input type="date" id="newExpiryDate" name="expiryDate"><br><br>

                    <button type="submit" name="register">Save Item</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Load items on page ready
        window.onload = () => loadInventory();

        function loadInventory(query = '') {
            const xhr = new XMLHttpRequest();
            const url = 'inventory.php?action=load' + (query ? '&' + query : '');
            xhr.open('GET', url, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    let items = [];
                    try { items = JSON.parse(xhr.responseText); } catch(e) { items = []; }
                    const inventoryBody = document.getElementById('inventoryBody');
                    inventoryBody.innerHTML = '';

                    if (!Array.isArray(items) || items.length === 0) {
                        inventoryBody.innerHTML = '<tr><td colspan="10">No items found</td></tr>';
                        return;
                    }
                    items.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${escapeHtml(item.productName ?? '')}</td>
                            <td>${escapeHtml(item.itemNo ?? '')}</td>
                            <td>${escapeHtml(item.problem ?? '')}</td>
                            <td>${escapeHtml(item.manufacturer ?? '')}</td>
                            <td>${escapeHtml(item.category ?? '')}</td>
                            <td>${escapeHtml(item.storeBox ?? '')}</td>
                            <td>${item.price ?? ''}</td>
                            <td>${item.quantity ?? ''}</td>
                            <td>${escapeHtml(item.expiryDate ?? '')}</td>
                            <td><button onclick="deleteItem(${Number(item.id)})">Delete</button></td>
                        `;
                        inventoryBody.appendChild(row);
                    });
                }
            };
            xhr.send();
        }

        // Add new item
        document.getElementById('addItemForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'inventory.php?action=add', true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    let res = {};
                    try { res = JSON.parse(xhr.responseText); } catch(e) {}
                    if (res.status === 'success') {
                        loadInventory();
                        alert('Item added successfully');
                        closeModal();
                        document.getElementById('addItemForm').reset();
                    } else {
                        alert('Error: ' + (res.message || 'Unknown error'));
                    }
                }
            };
            xhr.send(formData);
        });

        // Filters
        document.getElementById('applyFilterBtn').addEventListener('click', function () {
            const filters = {
                productName: document.getElementById('productNameFilter').value,
                manufacturer: document.getElementById('manufacturerFilter').value,
                itemNo: document.getElementById('itemNumberFilter').value,
                problem: document.getElementById('problemConditionFilter').value,
                category: document.getElementById('categoryFilter').value
            };
            loadInventory(new URLSearchParams(filters).toString());
        });

        // Delete
        function deleteItem(id) {
            if (!confirm("Are you sure you want to delete this item?")) return;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'inventory.php?action=delete', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    let res = {};
                    try { res = JSON.parse(xhr.responseText); } catch(e) {}
                    if (res.status === 'success') {
                        loadInventory();
                        alert('Item deleted successfully');
                    } else {
                        alert('Error: ' + (res.message || 'Unknown error'));
                    }
                }
            };
            xhr.send('id=' + encodeURIComponent(id));
        }

        // Modal controls
        document.getElementById('addItemBtn').addEventListener('click', function () {
            document.getElementById('addItemModal').style.display = 'block';
        });
        document.querySelector('.close-button').addEventListener('click', closeModal);
        window.onclick = function (e) {
            const modal = document.getElementById('addItemModal');
            if (e.target === modal) closeModal();
        };
        function closeModal() {
            document.getElementById('addItemModal').style.display = 'none';
        }

        // Basic HTML escaping for safety
        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>
