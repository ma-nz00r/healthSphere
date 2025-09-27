document.addEventListener('DOMContentLoaded', () => {
    const addItemBtn = document.getElementById('addItemBtn');
    const addItemModal = document.getElementById('addItemModal');
    const closeButton = document.querySelector('.close-button');
    const addItemForm = document.getElementById('addItemForm');
    const inventoryBody = document.getElementById('inventoryBody');

    // Sample initial inventory data (replace with your actual data fetching)
    let inventoryData = [
        { productName: 'Acetaminophen', itemNo: '', problem: 'Aches', manufacturer: 'Kimberly Clark Lever Ltd', category: 'Medication', storeBox: 'A1.2', price: 1.50, quantity: 190, expiryDate: '' },
        { productName: 'A1 Cream', itemNo: 'HK9299', problem: 'Acne', manufacturer: "Vickman's Laboratory", category: 'Cream', storeBox: 'A2.2', price: 1.00, quantity: 250, expiryDate: '2023-02-01' },
        { productName: 'Acacavir', itemNo: '', problem: '', manufacturer: 'Apatex Laboratory T & C', category: 'Medication', storeBox: 'A12', price: 2.00, quantity: 230, expiryDate: '' },
        { productName: 'A-Phine', itemNo: '', problem: 'Bacterial Infections', manufacturer: 'Kimberly Clark Lever Ltd', category: 'Medication', storeBox: 'B01', price: 1.50, quantity: 350, expiryDate: '' },
        { productName: 'Apple Syrup', itemNo: 'HK62937', problem: 'Cough', manufacturer: 'Kiran Laboratories', category: 'Syrup', storeBox: 'B2', price: 8.00, quantity: 300, expiryDate: '2025-05-01' },
        { productName: 'Acadine Tab', itemNo: '', problem: 'Bacterial Infections', manufacturer: 'Marching Ltd', category: 'Tablet', storeBox: 'T30', price: 10.00, quantity: 95, expiryDate: '' },
        { productName: 'Accurate', itemNo: '', problem: 'Bacterial Infections', manufacturer: "Vickman's Laboratory", category: 'Tablet', storeBox: 'A2.2', price: 2.00, quantity: 3, expiryDate: '' },
        { productName: 'Anastrazole', itemNo: '', problem: 'Breast cancer', manufacturer: 'Accord Health Care', category: 'Tablet', storeBox: 'A2.2', price: 2.00, quantity: 0, expiryDate: '' },
    ];

    // Function to render the inventory table
    function renderInventory() {
        inventoryBody.innerHTML = '';
        inventoryData.forEach(item => {
            const row = inventoryBody.insertRow();
            row.insertCell().textContent = item.productName;
            row.insertCell().textContent = item.itemNo;
            row.insertCell().textContent = item.problem;
            row.insertCell().textContent = item.manufacturer;
            row.insertCell().textContent = item.category;
            row.insertCell().textContent = item.storeBox;
            row.insertCell().textContent = item.price;
            row.insertCell().textContent = item.quantity;
            row.insertCell().textContent = item.expiryDate;
            row.insertCell().innerHTML = '<button class="edit-btn">Edit</button> <button class="delete-btn">Delete</button>'; // Basic action buttons
        });

        // Add event listeners for the new buttons (important for dynamically added elements)
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                // In a real application, you would send a request to the server to delete
                inventoryData.splice(index, 1);
                renderInventory();
            });
        });

        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                // Implement your edit functionality here (e.g., populate a modal with item details)
                console.log('Edit item at index:', index);
            });
        });
    }

    // Initial rendering of the inventory
    renderInventory();

    // Show the add item modal
    addItemBtn.addEventListener('click', () => {
        addItemModal.style.display = 'block';
    });

    // Close the add item modal
    closeButton.addEventListener('click', () => {
        addItemModal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target == addItemModal) {
            addItemModal.style.display = 'none';
        }
    });

    // Handle form submission for adding new items
    addItemForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent default form submission

        const newProductName = document.getElementById('newProductName').value;
        const newItemNumber = document.getElementById('newItemNumber').value;
        const newProblem = document.getElementById('newProblem').value;
        const newManufacturer = document.getElementById('newManufacturer').value;
        const newCategory = document.getElementById('newCategory').value;
        const newStoreBox = document.getElementById('newStoreBox').value;
        const newPrice = parseFloat(document.getElementById('newPrice').value);
        const newQuantity = parseInt(document.getElementById('newQuantity').value);
        const newExpiryDate = document.getElementById('newExpiryDate').value;

        const newItem = {
            productName: newProductName,
            itemNo: newItemNumber,
            problem: newProblem,
            manufacturer: newManufacturer,
            category: newCategory,
            storeBox: newStoreBox,
            price: newPrice,
            quantity: newQuantity,
            expiryDate: newExpiryDate,
        };

        // In a real application, you would send this 'newItem' data to your back-end server
        // to be stored in the database. For this front-end example, we'll just add it
        // to our local array and re-render the table.
        inventoryData.push(newItem);
        renderInventory();

        // Close the modal and reset the form
        addItemModal.style.display = 'none';
        addItemForm.reset();
    });

    // Basic filtering functionality
    const productNameFilter = document.getElementById('productNameFilter');
    const manufacturerFilter = document.getElementById('manufacturerFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const applyFilterBtn = document.querySelector('.apply-filter-btn');

    applyFilterBtn.addEventListener('click', () => {
        const productNameQuery = productNameFilter.value.toLowerCase();
        const manufacturerQuery = manufacturerFilter.value.toLowerCase();
        const categoryQuery = categoryFilter.value;

        const filteredData = inventoryData.filter(item => {
            const productNameMatch = item.productName.toLowerCase().includes(productNameQuery);
            const manufacturerMatch = item.manufacturer.toLowerCase().includes(manufacturerQuery);
            const categoryMatch = categoryQuery === '' || item.category === categoryQuery;
            return productNameMatch && manufacturerMatch && categoryMatch;
        });
        renderInventory(filteredData); // You'll need to modify renderInventory to accept data
    });

    // Modify renderInventory to accept data for filtering
    function renderInventory(data = inventoryData) {
        inventoryBody.innerHTML = '';
        data.forEach(item => {
            const row = inventoryBody.insertRow();
            row.insertCell().textContent = item.productName;
            row.insertCell().textContent = item.itemNo;
            row.insertCell().textContent = item.problem;
            row.insertCell().textContent = item.manufacturer;
            row.insertCell().textContent = item.category;
            row.insertCell().textContent = item.storeBox;
            row.insertCell().textContent = item.price;
            row.insertCell().textContent = item.quantity;
            row.insertCell().textContent = item.expiryDate;
            row.insertCell().innerHTML = '<button class="edit-btn">Edit</button> <button class="delete-btn">Delete</button>';
        });

        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const itemIndex = inventoryData.findIndex(item => item.productName === data[index].productName && item.itemNo === data[index].itemNo);
                if (itemIndex > -1) {
                    inventoryData.splice(itemIndex, 1);
                    renderInventory(inventoryData);
                }
            });
        });

        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                console.log('Edit item:', data[index]);
                // Implement your edit logic here
            });
        });
    }
});