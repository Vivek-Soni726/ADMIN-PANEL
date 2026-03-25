<?php
include 'adminHeader.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
    integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="style.css">
  <title>Admin Panel</title>
  <style>
    /* Special hover effect for the logout button */
    .logout-btn:hover {
      background-color: #d32f2f !important;
      /* Professional Red */
      color: white !important;
      padding-left: 20px !important;
      /* Matches your other buttons' shift effect */
      box-shadow: 0px 0px 1.6px 0.5px rgb(88, 88, 88);
    }

    /* Keeps the red color active if the button is clicked/focused */
    .logout-btn:focus {
      background-color: #b71c1c !important;
      /* Darker Red */
      color: white !important;
      outline: none;
    }
  </style>
</head>

<body>

  <!--------------------------------------- HEADER ---------------------------------->
  <div class="header">
    <div class="header-left">
      <i class="fa-solid fa-warehouse"></i> STOCK MANAGEMENT
    </div>

    <div class="header-right">
      <div class="header-datetime">
        <div id="header-date" style="font-size: 0.9rem;"></div>
        <div class="time-wrapper">
          <span id="header-time"></span>
          <span id="header-period" style="font-size: 1.6rem; font-family: 'Courier New', Courier, monospace;"></span>
        </div>
      </div>

      <div class="user-profile">
        <div class="user-info">
          <span class="user-name">
            <?php echo $_SESSION['user_name']; ?>
          </span>

          <span class="user-role">
            <?php echo $_SESSION['role_name']; ?>
          </span>
        </div>
        <div class="user-avatar">
          <i class="fa-solid fa-circle-user"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="layout">

    <!--------------------------------------- SIDEBAR BUTTONS ---------------------------------->
    <div class="sidebar">
      <button id="dashboard" data-target="dashboard" class="active-btn"
        onclick="inventoryValue() ; dashboardCharts() ; product_category_count() ; shopAlert()">
        <i class="fa-solid fa-house"></i>Dashboard
      </button>

      <button id="user" data-target="user" onclick="loadShops(); loadUsers()">
        <i class="fa-solid fa-user-group"></i>User Management
      </button>

      <button id="shop" data-target="shop" onclick="loadAllShops()">
        <i class="fa-solid fa-shop"></i>Shop Management
      </button>

      <button id="category" data-target="category" onclick="refreshCategoryList()">
        <i class="fa-solid fa-layer-group"></i>Categories
      </button>

      <button id="product" data-target="product" onclick="loadCategories()">
        <i class="fa-solid fa-box-open"></i>Product
      </button>

      <button id="report" data-target="report" onclick="refreshDashboard()">
        <i class="fa-solid fa-chart-line"></i>Reports
      </button>

      <button class="logout-btn" onclick="confirmLogout()"
        style="display: block; width: 100%; max-width: 217px; background-color: transparent; border: none; height: 7rem; cursor: pointer; color: #d32f2f; font-size: 1.6rem; letter-spacing: 0.12rem; text-align: left; border-radius: 5px; margin-bottom: 3px; margin-top: 20px; padding-left: 10px; transition: all 250ms ease;">
        <i class="fa-solid fa-right-from-bracket" style="margin-right: 10px;"></i> Logout
      </button>
    </div>

    <!--------------------------------------- MAIN CONTENTS ---------------------------------->
    <div style="align-items: center; justify-content: center; width: 98%;" class="panel">

      <!-- -----------------DASHBOARD PANEL---------------- -->
      <div class="dashboard visible">
        <div class="div1">
          <div>
            <div>
              <div style="display: flex; flex-direction: column; width: 50%; height: 4rem;">
                <p style="font-size: rem;">Total Products</p>
                <p id="total_product"></p>
              </div>
              <i class="fa-solid fa-box-open"></i>
            </div>
          </div>

          <div>
            <div>
              <div style="display: flex; flex-direction: column; width: 50%; height: 4rem;">
                <p style="font-size: rem;">Total Categories</p>
                <p id="total_category"></p>
              </div>
              <i class="fa-solid fa-layer-group"></i>
            </div>
          </div>

          <div>
            <div>
              <div style="display: flex; flex-direction: column; width: 50%; height: 4rem;">
                <p style="font-size: rem;">Shop Alerts</p>
                <p id="stock_alert"></p>
              </div>
              <i class="fa-solid fa-triangle-exclamation" style="color:red;"></i>
            </div>
          </div>

          <div>
            <div>
              <div style="display: flex; flex-direction: column; width: 50%; height: 4rem;">
                <p style="font-size: rem;">Inventory Value</p>
                <p id="inventory_value"></p>
              </div>
              <i class="fa-solid fa-coins"></i>
            </div>
          </div>

        </div>
        <!-- -----------------DASHBOARD CHARTS---------------- -->
        <div class="Chart">
          <div class="barchart"><canvas id="myChart"></canvas></div>
          <div class="piechart"><canvas id="myChart2"></canvas></div>
        </div>

      </div>

      <!-- -----------------USER PANEL---------------- -->
      <div class="user hidden">
        <div class="filter">
          <select id="shopselect">
          </select>
        </div>

        <div class="user-card">
        </div>
      </div>

      <!-- -----------------SHOP PANEL---------------- -->
      <div class="shop hidden">
        <div class="shop-card">
        </div>
      </div>

      <!-- -----------------CATEGORY PANEL---------------- -->
      <div class="category hidden">
        <div class="category-card"
          style="width:98%; padding:2rem; display: grid; grid-template-columns: repeat(5, 1fr); overflow-y: auto; gap: 20px;">
        </div>
      </div>

      <!-- -----------------PRODUCT PANEL---------------- -->
      <div class="product hidden">
        <div class="filter">
          <select id="categorySelect"></select>
        </div>

        <div class="product-card"
          style="width:98%; padding:2rem; display: grid; grid-template-columns: repeat(5, 1fr); overflow-y: auto; gap: 20px;">
        </div>
      </div>

      <!-- -----------------REPORT PANEL---------------- -->
      <div class="report hidden">
        <div class="report-content-wrapper">

          <div class="report-box"
            style="margin-bottom: 25px; display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
              <label style="font-weight: 600; font-size: 14px; color: #4A5568;">From:</label>
              <input type="date" id="date-from"
                style="padding: 8px; border: 1px solid #E2E8F0; border-radius: 8px; outline: none;">
            </div>
            <div style="display: flex; align-items: center; gap: 10px;">
              <label style="font-weight: 600; font-size: 14px; color: #4A5568;">To:</label>
              <input type="date" id="date-to"
                style="padding: 8px; border: 1px solid #E2E8F0; border-radius: 8px; outline: none;">
            </div>
            <button class="btn-export-purple" onclick="applyDateFilter()" style="padding: 8px 20px;">Apply
              Filter</button>
            <button onclick="resetFilter()"
              style="background: none; border: none; color: #718096; cursor: pointer; font-size: 14px;">Reset</button>
          </div>

          <div class="report-row">
            <div class="kpi-card">
              <p>Net Profit</p>
              <h2 id="display-profit">₹0</h2>
            </div>
            <div class="kpi-card">
              <p>Total Shops</p>
              <h2 id="display-shops">0</h2>
            </div>
            <div class="kpi-card">
              <p>Total Users (With Admin)</p>
              <h2 id="display-users">0</h2>
            </div>
          </div>

          <div class="report-row">
            <div class="report-box" style="flex: 1;">
              <h4>Shop Revenue Performance</h4>
              <canvas id="barChart" height="150"></canvas>
            </div>
            <div class="report-box" style="flex: 1;">
              <h4>Sales Velocity Trend</h4>
              <canvas id="lineChart" height="150"></canvas>
            </div>
          </div>

          <div style="display: flex; flex-direction: column; gap: 25px;">
            <div style="max-height: 500px; overflow-y:auto;" class="report-box">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h4 style="margin:0;">Product Inventory Report</h4>
                <button class="btn-export-purple" onclick="exportCSV('prodTable')">Export CSV</button>
              </div>
              <table id="prodTable" class="report-table">
                <thead>
                  <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Revenue</th>
                    <th>Shop Location</th>
                  </tr>
                </thead>
                <tbody id="prodTableBody">
                </tbody>
              </table>
            </div>

            <div style="max-height: 500px; overflow-y:auto;" class="report-box">
              <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h4 style="margin:0;">Shop Management Directory</h4>
                <button class="btn-export-green" onclick="exportCSV('shopTable')">Export CSV</button>
              </div>
              <table id="shopTable" class="report-table">
                <thead>
                  <tr>
                    <th>Shop Name</th>
                    <th>Owner Name</th>
                    <th>Contact</th>
                    <th style="text-align: right;">Total Revenue</th>
                  </tr>
                </thead>
                <tbody id="shopTableBody">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>




  <!--------------------------------------------USER UPDATE FORM ----------------------------------- -->
  <div id="editUserModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div
      style="background:white; padding:2rem; border-radius:20px; width:450px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
      <h2 style="color:#4C1D95; margin-bottom: 20px;">Update User Details</h2>
      <form id="editUserForm">
        <input type="hidden" id="edit_user_id" name="user_id">

        <div style="margin-bottom:15px;">
          <label style="font-weight:bold; color:grey;">Full Name</label><br>
          <input type="text" id="edit_user_name" name="user_name"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:15px;">
          <label style="font-weight:bold; color:grey;">Address</label><br>
          <textarea id="edit_user_address" name="user_address"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" rows="3"
            required></textarea>
        </div>

        <div style="margin-bottom:20px;">
          <label style="font-weight:bold; color:grey;">Contact Number</label><br>
          <input type="text" id="edit_user_contact" name="user_contact"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeUserModal()"
            style="padding:10px 20px; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
          <button type="submit"
            style="padding:10px 25px; background:#4C1D95; color:white; border:none; border-radius:8px; cursor:pointer;">
            Update User
          </button>
        </div>
      </form>
    </div>
  </div>

  <!--------------------------------------------ADD USER FORM ----------------------------------- -->
  <div id="addUserModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div
      style="background:white; padding:2rem; border-radius:20px; width:480px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto;">
      <h2 style="color:#4C1D95; margin-bottom: 20px;">Register New User</h2>
      <form id="addUserForm">

        <p
          style="font-size: 1.4rem;font-weight: bold; color: #4C1D95; border-bottom: 1px solid #eee; margin-bottom: 10px;">
          Personal Details</p>

        <div style="margin-bottom:12px;">
          <label style="font-weight:bold; color:grey;">Full Name</label>
          <input type="text" id="add_user_name" name="user_name"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:12px;">
          <label style="font-weight:bold; color:grey;">Address</label>
          <textarea id="add_user_address" name="user_address"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" rows="2"
            required></textarea>
        </div>

        <div style="margin-bottom:12px;">
          <label style="font-weight:bold; color:grey;">Contact Number</label>
          <input type="text" id="add_user_contact" name="user_contact"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <p
          style="font-size: 1.4rem; font-weight: bold; color: #4C1D95; border-bottom: 1px solid #eee; margin: 20px 0 10px 0;">
          Account Credentials</p>

        <div style="margin-bottom:12px;">
          <label style="font-weight:bold; color:grey;">Email (Username)</label>
          <input type="email" id="add_login_email" name="login_email"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:12px;">
          <label style="font-weight:bold; color:grey;">Login Password</label>
          <input type="password" id="add_login_password" name="login_password"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:12px; display:flex; gap:10px;">
          <div style="flex:1;">
            <label style="font-weight:bold; color:grey;">Role</label>
            <select id="add_user_role" name="role_id"
              style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
            </select>
          </div>
          <div style="flex:1;">
            <label style="font-weight:bold; color:grey;">Assign Shop</label>
            <select id="add_user_shop" name="shop_id"
              style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
            </select>
          </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:20px;">
          <button type="button" onclick="closeAddModal()"
            style="padding:10px 20px; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
          <button type="submit"
            style="padding:10px 25px; background:#4C1D95; color:white; border:none; border-radius:8px; cursor:pointer;">
            Save User
          </button>
        </div>
      </form>
    </div>
  </div>

  <!--------------------------------------------ADD SHOP FORM ----------------------------------- -->
  <div id="shopModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div
      style="background:white; padding:2rem; border-radius:20px; width:400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
      <h2 id="modalTitle" style="color:#4C1D95; margin-bottom: 20px;">Add New Shop</h2>

      <form id="shopForm">
        <input type="hidden" id="modal_shop_id" name="shop_id">

        <div style="margin-bottom:15px;">
          <label style="font-weight:bold; color:grey;">Shop Name</label>
          <input type="text" id="modal_shop_name" name="shop_name"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:20px;">
          <label style="font-weight:bold; color:grey;">Shop Address</label>
          <textarea id="modal_shop_address" name="shop_address"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" rows="3"
            required></textarea>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeShopModal()"
            style="padding:10px 20px; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
          <button type="submit"
            style="padding:10px 25px; background:#4C1D95; color:white; border:none; border-radius:8px; cursor:pointer;">
            Save Shop
          </button>
        </div>
      </form>
    </div>
  </div>

  <!--------------------------------------------ADD and UPDATE CATEGORY FORM ----------------------------------- -->
  <div id="categoryModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div
      style="background:white; padding:2rem; border-radius:20px; width:400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
      <h2 id="catModalTitle" style="color:#4C1D95; margin-bottom: 20px;">Add Category</h2>
      <form id="categoryForm">
        <input type="hidden" id="modal_cat_id">

        <div style="margin-bottom:20px;">
          <label style="font-weight:bold; color:grey;">Category Name</label>
          <input type="text" id="modal_cat_name"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;"
            placeholder="e.g. Beverages, Snacks" required>
        </div>

        <div style="margin-bottom:20px;">
          <label style="font-weight:bold; color:grey;">Description</label>
          <textarea id="modal_cat_description"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd; height: 80px; resize: none;"
            placeholder="Brief description of category..."></textarea>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeCatModal()"
            style="padding:10px 20px; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
          <button type="submit"
            style="padding:10px 25px; background:#4C1D95; color:white; border:none; border-radius:8px; cursor:pointer;">Save</button>
        </div>

      </form>
    </div>
  </div>

  <!----------------------------------------------ADD and UPDATE PRODUCTS FORM ------------------------------- -->
  <div id="productModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999; align-items:center; justify-content:center; backdrop-filter: blur(4px);">
    <div
      style="background:white; padding:2rem; border-radius:20px; width:400px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
      <h2 id="prodModalTitle" style="color:#4C1D95; margin-bottom: 20px;">Add New Product</h2>

      <form id="productForm">
        <input type="hidden" id="modal_prod_id" name="product_id">

        <div style="margin-bottom:15px;">
          <label style="font-weight:bold; color:grey;">Product Name</label>
          <input type="text" id="modal_prod_name" name="Product_name"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:15px;">
          <label style="font-weight:bold; color:grey;">Cost Price (₹)</label>
          <input type="number" step="0.01" id="modal_prod_price" name="Cost_price"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
        </div>

        <div style="margin-bottom:20px;">
          <label style="font-weight:bold; color:grey;">Category</label>
          <select id="modal_prod_cat" name="Cat_id"
            style="width:100%; padding:10px; margin-top:5px; border-radius:8px; border:1px solid #ddd;" required>
          </select>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
          <button type="button" onclick="closeProductModal()"
            style="padding:10px 20px; background:#f3f4f6; border:none; border-radius:8px; cursor:pointer;">Cancel</button>
          <button type="submit"
            style="padding:10px 25px; background:#4C1D95; color:white; border:none; border-radius:8px; cursor:pointer;">
            Save Product
          </button>
        </div>
      </form>
    </div>
  </div>

  <!----------------------------------------------Product Refill form ------------------------------- -->
  <div id="shopRefillModal" class="modal"
    style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
    <div class="modal-content"
      style="background: white; padding: 20px; border-radius: 10px; width: 400px; position: relative;">
      <span class="close" onclick="closeRefillModal()"
        style="position: absolute; right: 20px; cursor: pointer; font-size: 1.5rem;">&times;</span>
      <h2 id="refillModalTitle" style="margin-bottom: 20px;">Refill Shop Stock</h2>

      <form id="refillForm">
        <input type="hidden" id="refill_shop_id" name="shop_id">

        <div style="margin-bottom: 15px;">
          <label style="display: block;">Select Product:</label>
          <select id="refill_product_select" name="product_id" required
            style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
            <option value="">-- Loading Products --</option>
          </select>
        </div>

        <div style="margin-bottom: 15px;">
          <label style="display: block;">Quantity:</label>
          <input type="number" name="quantity" min="1" required
            style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <div style="margin-bottom: 20px;">
          <label style="display: block;">Selling Price (per unit):</label>
          <input type="number" name="selling_price" step="0.01" required
            style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
        </div>

        <button type="submit"
          style="width: 100%; padding: 10px; background: #4c1d95; color: white; border: none; border-radius: 5px; cursor: pointer;">
          Update Stock
        </button>
      </form>
    </div>
  </div>





  <!----------------------------------------------LOGOUT ------------------------------- -->
  <script>
    function confirmLogout() {
      if (confirm("Are you sure you want to log out?")) {
        // Point to your root logout.php file
        window.location.href = "../logout.php";
      }
    }
  </script>

  <!--------------------------- DATE AND TIME ------------------->
  <script>
    function updateClock() {
      const now = new Date();

      // Date: SAT, FEB 21, 2026
      const dateOptions = {
        weekday: 'short',
        month: 'short',
        day: 'numeric',
        year: 'numeric'
      };
      document.getElementById('header-date').textContent = now.toLocaleDateString('en-US', dateOptions).toUpperCase();

      // Time: 07:41:18 (12-hour format)
      const timeString = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      });

      // Split the time "07:41:18 PM" into "07:41:18" and "PM"
      const [time, period] = timeString.split(' ');

      document.getElementById('header-time').textContent = time;
      document.getElementById('header-period').textContent = period;
    }

    setInterval(updateClock, 1000);
    updateClock();
  </script>
  <!-------------------------- DASHBOARD CHARTS ------------------------->
  <script>
    // 1. Declare instances outside the function to track them across refreshes
    let salesBarChart = null;
    let stockDoughnutChart = null;

    function dashboardCharts() {
      // 2. Get canvas contexts
      const ctx = document.getElementById('myChart').getContext('2d');
      const cx = document.getElementById('myChart2').getContext('2d');

      // 3. Destroy existing charts if they exist (prevents "Canvas in use" error)
      if (salesBarChart) salesBarChart.destroy();
      if (stockDoughnutChart) stockDoughnutChart.destroy();

      // 4. Fetch data from PHP backend
      fetch('getCharts.php')
        .then(response => response.json())
        .then(data => {

          // --- CREATE BAR CHART ---
          salesBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
              // row.Product_name matches your PHP $sql query
              labels: data.sales.map(row => row.Product_name),
              datasets: [{
                label: 'Total Quantity Sold',
                // row.TotalQuantityMoved matches your PHP $sql query
                data: data.sales.map(row => row.TotalQuantityMoved),
                backgroundColor: '#6EB8E1',
                borderColor: '#1F2937',
                borderWidth: 1,
                hoverBackgroundColor: '#4A90B5',
                hoverBorderColor: '#000000',
                hoverBorderWidth: 2,
                borderRadius: 5,
              }]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              animation: {
                duration: 1500,
                easing: 'easeOutBounce',
                delay: (context) => {
                  let delay = 0;
                  if (context.type === 'data' && context.mode === 'default') {
                    delay = context.dataIndex * 150;
                  }
                  return delay;
                }
              },
              plugins: {
                title: {
                  display: true,
                  text: 'Product Wise Sales Performance',
                  font: {
                    size: 18
                  }
                },
                tooltip: {
                  backgroundColor: 'rgba(31, 41, 55, 0.9)',
                  padding: 10,
                  displayColors: false,
                  bodyFont: {
                    size: 13
                  }
                }
              },
              interaction: {
                mode: 'index',
                intersect: false
              },
              scales: {
                y: {
                  beginAtZero: true,
                  grid: {
                    color: 'rgba(0,0,0,0.05)'
                  }
                },
                x: {
                  grid: {
                    display: false
                  }
                }
              }
            }
          });

          // --- CREATE DOUGHNUT CHART ---
          const getDynamicColors = (count) => {
            const colors = [];
            for (let i = 0; i < count; i++) {
              const hue = i * 137.508;
              colors.push(`hsl(${hue % 360}, 70%, 60%)`);
            }
            return colors;
          };

          // 2. Your existing chart variable with the dynamic color function integrated
          stockDoughnutChart = new Chart(cx, {
            type: 'doughnut',
            data: {
              // UPDATED: Now using Category Name from your $stmt
              labels: data.stock.map(row => row.Cat_name),
              datasets: [{
                label: 'Total Category Stock',
                // UPDATED: Now using the calculated CurrentStock per category
                data: data.stock.map(row => row.CurrentStock),

                backgroundColor: getDynamicColors(data.stock.length),
                borderColor: '#ffffff',
                borderWidth: 2,
                hoverOffset: 20
              }]
            },
            options: {
              cutout: '65%',
              maintainAspectRatio: false,
              animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1500,
                easing: 'easeOutQuart'
              },
              plugins: {
                legend: {
                  labels: {
                    font: {
                      family: 'Arial',
                      size: 12
                    },
                    color: '#111827'
                  }
                },
                title: {
                  display: true,
                  // UPDATED: Changed text to reflect Category view
                  text: 'Category Wise Stock Distribution',
                  color: '#4C1D95',
                  font: {
                    size: 18,
                    weight: 'bold'
                  }
                },
                // ADDED: Custom tooltip to show the number of products in this category
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      const index = context.dataIndex;
                      const stockVal = data.stock[index].CurrentStock;
                      const prodCount = data.stock[index].TotalProductsInCategory;
                      return ` Stock: ${stockVal} (${prodCount} Products)`;
                    }
                  }
                }
              }
            }
          });
        })
        .catch(error => console.error('Error loading charts:', error));
    }

    // Initial call on page load
    document.addEventListener('DOMContentLoaded', dashboardCharts);
  </script>
  <!-- ----------------- SIDEBAR ---------------------------->
  <script>
    // Select all buttons inside the sidebar
    const sidebarBtns = document.querySelectorAll('.sidebar button');
    // Select all main panels
    const allPanels = document.querySelectorAll('.panel > div');

    sidebarBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const target = btn.getAttribute('data-target');

        // 1. Remove active class from all buttons and add to clicked one
        sidebarBtns.forEach(b => b.classList.remove('active-btn'));
        btn.classList.add('active-btn');

        // 2. Hide all panels and show the targeted one
        allPanels.forEach(panel => {
          if (panel.classList.contains(target)) {
            panel.classList.remove('hidden');
            panel.classList.add('visible');
          } else {
            panel.classList.add('hidden');
            panel.classList.remove('visible');
          }
        });
      });
    });
  </script>




  <!------------------- Product and Category Count ---------------------->
  <script>
    function product_category_count() {
      let count_product = document.getElementById('total_product');
      let count_category = document.getElementById('total_category');

      // Fetch Total Products
      fetch('count_product.php')
        .then(response => response.json())
        .then(data => {
          if (count_product) {
            count_product.textContent = data['product_count'] || 0;
          }
        })
        .catch(err => console.error("Error fetching product count:", err));

      // Fetch Total Categories
      fetch('count_category.php')
        .then(response => response.json())
        .then(data => {
          if (count_category) {
            count_category.textContent = data['total'] || 0;
          }
        })
        .catch(err => console.error("Error fetching category count:", err));
    }

    // Call on page load
    document.addEventListener('DOMContentLoaded', product_category_count);
  </script>

  <!---------------- Shop Alerts ---------------->
  <script>
    function shopAlert() {
      fetch('getShopAlert.php')
        .then(response => response.json())
        .then(data => {
          const stock_alerts = data['count'] || 0;
          const stock_alert_element = document.getElementById('stock_alert');

          if (stock_alert_element) {
            stock_alert_element.textContent = stock_alerts;
          }
        })
        .catch(err => console.error("Error fetching shop alerts:", err));
    }

    // Call on page load
    document.addEventListener('DOMContentLoaded', shopAlert);
  </script>

  <!-------------- Inventory Value ------------------->
  <script>
    function inventoryValue() {
      fetch('getInventory.php')
        .then(response => response.json())
        .then(data => {
          let inventory_values = data['inventory_value'];
          let inventory_value = document.getElementById('inventory_value');
          inventory_value.textContent = `\u20B9${inventory_values}`;
        });
    }

    document.addEventListener('DOMContentLoaded', inventoryValue);
  </script>

  <!-------------- Product Refill ------------------->
  <script>
    /**
     * 5. REFILL MODAL LOGIC
     */

    // Function to fetch products and open the modal
    const openShopRefillModal = (shopId, shopName) => {
      // 1. Set basic info
      document.getElementById('refillModalTitle').innerText = `Refill: ${shopName}`;
      document.getElementById('refill_shop_id').value = shopId;

      // 2. Fetch Products for the dropdown
      fetch('getProduct.php')
        .then(res => res.json())
        .then(products => {
          let options = '<option value="">-- Choose Product --</option>';
          products.forEach(p => {
            options += `<option value="${p.Product_id}">${p.Product_name} (₹${p.Cost_price})</option>`;
          });
          document.getElementById('refill_product_select').innerHTML = options;
        })
        .catch(err => console.error("Error fetching products:", err));

      // 3. Show Modal
      document.getElementById('shopRefillModal').style.display = 'flex';
    };

    const closeRefillModal = () => {
      document.getElementById('shopRefillModal').style.display = 'none';
      document.getElementById('refillForm').reset();
    };

    // Handle Refill Form Submission
    document.getElementById('refillForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch('processRefill.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Shop stock updated successfully!");
            closeRefillModal();
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch(err => console.error("Refill submission error:", err));
    });
  </script>

  <!-- -------------- USER PANEL ------------------->
  <script>
    /**
     * USER MANAGEMENT SYSTEM - REFACTORED
     * Includes: Manager validation, Shop filtering, and UI transitions
     */

    const shopSelect = document.getElementById('shopselect');
    const userCardContainer = document.querySelector('.user-card');

    // --- 1. DATA LOADING FUNCTIONS ---

    const loadShops = () => {
      fetch('getShop.php')
        .then(res => res.json())
        .then(data => {
          let options = '<option value=""> ---All Shops---</option>';

          // FIX: Check if data is the array, or if the array is inside data.shops
          const shopArray = Array.isArray(data) ? data : (data.shops || data.data);

          if (shopArray && Array.isArray(shopArray)) {
            shopArray.forEach(shop => {
              options += `<option value="${shop.Shop_id}">${shop.Shop_name}</option>`;
            });
          } else {
            console.error('Expected an array but received:', data);
          }

          shopSelect.innerHTML = options;
        })
        .catch(err => console.error('Error loading shops:', err));
    };

    const loadUsers = (shopId = null) => {
      let url = 'getUser.php';
      if (shopId) url += `?shop_id=${shopId}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          // Start with the Add User card
          let html = `
                <div class="card userbtn">
                    <button class="add-user light-tooltip" data-tooltip="Add New User" onclick="addUser()">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>`;

          if (Array.isArray(data)) {
            data.forEach(user => {
              html += `
                        <div class="card" id="user-card-${user.User_id}">
                            <div style="display: flex; align-items: center; justify-content: center;">
                                <i style="font-size: 12rem; border-radius: 50%; width: 15rem; height:15rem; color: rgba(0, 0, 0, 0.3);" class="fa-solid fa-user"></i>
                            </div>
                            <div class="user-info" style="transform: translateY(-9px); font-weight: 600; font-size: 1.3rem; color: grey; text-align: left; width: 100%;">
                                <p style= "margin-bottom: 2.5px;">Name : ${user.User_name}</p>
                                <p style= "margin-bottom: 2.5px;">Contact No. : ${user.User_contact}</p>
                                <p style= "margin-bottom: 2.5px;">User ID : ${user.User_id}</p>
                                <p style= "margin-bottom: 2.5px;">Shop : ${user.Shop_name}</p>
                                <p>Address : ${user.User_address}</p>
                            </div>
                            <div style="display: flex; flex-direction: row; justify-content: space-around; gap: 10px; margin-top: 10px;">
                                <button style="translate: 0px -3px" onclick="editItem(${user.User_id})" class="edit light-tooltip" data-tooltip="Edit Personal Details">
                                    <i class="fa-solid fa-user-pen"></i>
                                </button>
                                <button style="translate: 0px -3px" onclick="deleteItem(${user.User_id})" class="delete light-tooltip" data-tooltip="Delete User">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>`;
            });
          }
          userCardContainer.innerHTML = html;
        })
        .catch(err => console.error('Error loading users:', err));
    };

    // --- 2. MODAL CONTROLS ---

    const addUser = () => {
      // Load Roles
      fetch('getRoles.php')
        .then(res => res.json())
        .then(roles => {
          let roleHtml = '<option value="">--Select Role--</option>';
          roles.forEach(role => {
            roleHtml += `<option value="${role.Role_id}">${role.Role_name}</option>`;
          });
          document.getElementById('add_user_role').innerHTML = roleHtml;
        });

      // Sync Shop options from the main filter to the modal
      document.getElementById('add_user_shop').innerHTML = shopSelect.innerHTML;
      document.getElementById('addUserModal').style.display = 'flex';
    };

    const closeAddModal = () => {
      document.getElementById('addUserModal').style.display = 'none';
      document.getElementById('addUserForm').reset();
    };

    const closeUserModal = () => {
      document.getElementById('editUserModal').style.display = 'none';
    };

    // --- 3. CRUD OPERATIONS ---

    // DELETE USER
    function deleteItem(id) {
      if (confirm(`Are you sure you want to delete this user?`)) {
        fetch(`deleteUser.php?user_id=${id}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              const card = document.getElementById(`user-card-${id}`);
              if (card) {
                card.style.opacity = "0";
                card.style.transform = "scale(0.8)";
                setTimeout(() => card.remove(), 400);
              }
            } else {
              alert("Delete Error: " + data.message);
            }
          });
      }
    }

    // EDIT USER (Populate Modal)
    const editItem = (userId) => {
      const card = document.getElementById(`user-card-${userId}`);
      if (!card) return;

      const getValue = (label) => {
        const paragraphs = Array.from(card.querySelectorAll('p'));
        const target = paragraphs.find(p => p.innerText.includes(label));
        return target ? target.innerText.split(' : ')[1].trim() : '';
      };

      document.getElementById('edit_user_id').value = userId;
      document.getElementById('edit_user_name').value = getValue('Name');
      document.getElementById('edit_user_address').value = getValue('Address');
      document.getElementById('edit_user_contact').value = getValue('Contact No.');
      document.getElementById('editUserModal').style.display = 'flex';
    };

    // --- 4. FORM SUBMISSIONS WITH VALIDATION ---

    // ADD NEW USER
    document.getElementById('addUserForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch('addUser.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("User successfully registered!");
            closeAddModal();
            loadUsers(shopSelect.value);
          } else {
            // This displays the "Manager already assigned" alert from PHP
            alert("Validation Failed: " + data.message);
          }
        })
        .catch(err => alert("Server connection error."));
    });

    // UPDATE EXISTING USER
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
      e.preventDefault();
      fetch('updateUser.php', {
          method: 'POST',
          body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert("Profile updated!");
            closeUserModal();
            loadUsers(shopSelect.value);
          } else {
            alert("Update failed: " + data.message);
          }
        });
    });

    // --- 5. INITIALIZATION ---

    shopSelect.addEventListener('change', (e) => loadUsers(e.target.value));

    document.addEventListener('DOMContentLoaded', () => {
      loadShops();
      loadUsers();
    });
  </script>

  <!-------------- SHOP PANEL ----------------------->
  <script>
    /**
     * SHOP MANAGEMENT MODULE
     * Includes: Fetching, Rendering, Deletion (Vanish Effect), and Modal handling.
     */

    const shopCardContainer = document.querySelector('.shop-card');

    /**
     * 1. LOAD ALL SHOPS
     * Renders the "Add" button first, then appends shops from the database.
     */
    const loadAllShops = () => {
      fetch('allShop.php')
        .then(response => response.json())
        .then(data => {
          // Initial HTML with the "Add New Shop" card
          let html = `
                  <div class="card userbtn">
                      <button class="add-user light-tooltip" data-tooltip="Add New Shop" onclick="addShop()">
                          <i class="fa-solid fa-plus"></i>
                      </button>
                  </div>`;

          // If data is an array and has shops, loop through them
          if (Array.isArray(data) && data.length > 0) {
            data.forEach(shop => {
              html += `
                          <div class="card" id="shop-card-${shop.Shop_id}">
                              <div style="display: flex; align-items: center; justify-content: center;">
                                  <i style="font-size: 12rem; border-radius: 50%; width: 15rem; height:15rem; color: rgba(0, 0, 0, 0.3);" class="fa-solid fa-shop"></i>
                              </div>
                              
                              <div class="shop-info" style="transform: translateY(-9px); font-weight: 600; font-size: 1.3rem; color: grey; display: flex; flex-direction: column; justify-content: space-evenly; text-align: left; width: 100%; height: 40%;">
                                  <p>Shop ID : ${shop.Shop_id}</p>
                                  <p>Name : ${shop.Shop_name}</p>
                                  <p>Shop Address : ${shop.Shop_address}</p>
                                  <p>Owner Name : ${shop.User_name || 'Not Assigned'}</p>
                                  <p>Contact No. : ${shop.User_contact || 'N/A'}</p>
                              </div>

                              <div class="shop-actions" style="display: flex; flex-direction: row; justify-content: space-evenly; gap: 9px;">
                                  <button onclick="editShop(${shop.Shop_id})" class="edit light-tooltip" data-tooltip="Edit Shop Details">
                                      <i class="fa-solid fa-pen-to-square"></i>
                                  </button>

                                  <button onclick="deleteShop(${shop.Shop_id})" class="delete light-tooltip" data-tooltip="Delete Shop">
                                      <i class="fa-solid fa-trash-can"></i>
                                  </button>

                                  <button style="border: 1px solid rgba(76, 29, 149, 0.7); background-color: transparent; width: 9rem; translate: 0px -3px; border-radius: 50px" 
                                          onclick="openShopRefillModal(${shop.Shop_id}, '${shop.Shop_name}')" class="refill light-tooltip" data-tooltip="Refill Product">
                                      <i style="width: 4rem; color : rgba(76, 29, 149, 0.7); translate: 1px" class="fa-solid fa-plus"></i>
                                  </button>
                              </div>
                          </div>`;
            });
          }
          shopCardContainer.innerHTML = html;
        })
        .catch(err => {
          console.error("Error loading shops:", err);
          // Fallback: Ensure Add button is visible even on network error
          shopCardContainer.innerHTML = `
                  <div class="card userbtn">
                      <button class="add-user" onclick="addShop()">
                          <i class="fa-solid fa-plus"></i>
                      </button>
                      <p style="color:red; font-size:0.8rem; margin-top:10px;">Load Failed</p>
                  </div>`;
        });
    };

    /**
     * 2. DELETE SHOP (With Vanish Animation)
     */
    const deleteShop = (shopId) => {
      if (confirm("Are you sure you want to delete this shop?")) {
        fetch(`deleteShop.php?shop_id=${shopId}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              const element = document.getElementById(`shop-card-${shopId}`);
              if (element) {
                // Apply the "Vanish" CSS effect
                element.style.pointerEvents = "none";
                element.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                element.style.transform = "scale(0.3) translateY(30px)";
                element.style.opacity = "0";
                element.style.filter = "blur(10px)";

                // Remove from DOM after animation completes
                setTimeout(() => element.remove(), 500);
              }
            } else {
              alert(data.message); // Displays error if shop has users assigned
            }
          })
          .catch(err => console.error("Delete failed:", err));
      }
    };

    /**
     * 3. MODAL CONTROLS (Add/Edit)
     */
    const addShop = () => {
      document.getElementById('modalTitle').innerText = "Add New Shop";
      document.getElementById('modal_shop_id').value = ""; // Clear ID for new entry
      document.getElementById('shopForm').reset();
      document.getElementById('shopModal').style.display = 'flex';
    };

    const editShop = (shopId) => {
      document.getElementById('modalTitle').innerText = "Edit Shop Details";
      const card = document.getElementById(`shop-card-${shopId}`);

      // Helper function to extract text from the <p> tags
      const getText = (label) => {
        const paragraphs = Array.from(card.querySelectorAll('p'));
        const target = paragraphs.find(p => p.innerText.includes(label));
        return target ? target.innerText.split(' : ')[1].trim() : '';
      };

      // Populate Modal Fields
      document.getElementById('modal_shop_id').value = shopId;
      document.getElementById('modal_shop_name').value = getText('Name');
      document.getElementById('modal_shop_address').value = getText('Shop Address');

      document.getElementById('shopModal').style.display = 'flex';
    };

    const closeShopModal = () => {
      document.getElementById('shopModal').style.display = 'none';
    };

    /**
     * 4. FORM SUBMISSION (Save or Update)
     */
    document.getElementById('shopForm').addEventListener('submit', function(e) {
      e.preventDefault();

      const shopId = document.getElementById('modal_shop_id').value;
      const formData = new FormData(this);
      const url = 'saveShop.php';

      fetch(url, {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(shopId ? "Shop details updated!" : "New shop added successfully!");
            closeShopModal();
            loadAllShops(); // Refresh the list
          } else {
            alert("Error: " + data.message);
          }
        })
        .catch(err => {
          console.error("Submission failed:", err);
          alert("System error. Check server connection.");
        });
    });

    /**
     * INITIALIZE
     */
    document.addEventListener('DOMContentLoaded', loadAllShops);
  </script>

  <!-------------- CATEGORY PANEL  ------------------->
  <script>
    /**
     * 1. LOAD & DISPLAY CATEGORIES
     */
    const refreshCategoryList = () => {
      const container = document.querySelector('.category-card');
      if (!container) return;

      fetch('getCategory.php')
        .then(res => res.json())
        .then(data => {
          // 1. "Add" button card (Matches exactly)
          let html = `
            <div class="card userbtn">
	            <button class="add-user light-tooltip" data-tooltip="Add new Category" onclick="openCategoryModal()">
		            <i class="fa-solid fa-plus"></i>
	            </button>
            </div>`;

          data.forEach(cat => {
            // 2. Category Card (Exact structure as Shop/User)
            html += `
          <div class="card" id="cat-card-${cat.Cat_id}">
              <div style="display: flex; align-items: center; justify-content: center;">
 
                  <i style="font-size: 12rem; border-radius: 50%; width: 15rem; height:15rem; color: rgba(0, 0, 0, 0.3);" class="fa-solid fa-layer-group"></i>
              </div>
              
              <div style="transform: translateY(-9px); font-weight: 600; font-size: 1.3rem; color: grey; display: flex; flex-direction: column; justify-content: space-evenly; text-align: left; width: 100%; height: 40%;">
                  <p>Category ID : ${cat.Cat_id}</p>
                  <p>Name : ${cat.Cat_name}</p>
                  <p>No. of Products : ${cat.no_of_product}</p>
              </div>

              <div  style="display: flex; flex-direction: row; justify-content: space-around; gap: 10px;">
                  <button onclick="prepareCategoryEdit(${cat.Cat_id}, '${cat.Cat_name}')" class="edit light-tooltip" data-tooltip="Edit Category Details">
                      <i class="fa-solid fa-pen-to-square"></i>
                  </button>
                  <button onclick="handleCategoryDelete(${cat.Cat_id})" class="delete light-tooltip" data-tooltip="Delete Category">
                <i class="fa-solid fa-trash-can"></i>
            </button>
              </div>
          </div>`;
          });
          container.innerHTML = html;
        })
        .catch(err => console.error("Error fetching categories:", err));
    };

    /**
     * 2. DELETE LOGIC (With Vanish Animation)
     */
    const handleCategoryDelete = (id) => {
      const confirmMsg = "Are you sure? You must delete every product related to this category first.";

      if (confirm(confirmMsg)) {
        fetch(`deleteCategory.php?cat_id=${id}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              const element = document.getElementById(`cat-card-${id}`);
              if (element) {
                // Apply the smooth Vanish effect to match other panels
                element.style.pointerEvents = "none";
                element.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                element.style.transform = "scale(0.3) translateY(30px)";
                element.style.opacity = "0";
                element.style.filter = "blur(10px)";

                setTimeout(() => {
                  element.remove();
                }, 500);
              }
            } else {
              alert(data.message); // Shows "Cannot delete! This category has X products..."
            }
          })
          .catch(err => console.error("Delete operation failed:", err));
      }
    };

    /**
     * 3. MODAL & FORM LOGIC
     */
    const openCategoryModal = () => {
      document.getElementById('catModalTitle').innerText = "Add Category";
      document.getElementById('modal_cat_id').value = "";
      document.getElementById('categoryForm').reset();
      document.getElementById('modal_cat_description').value = ""; // Clear description
      document.getElementById('categoryModal').style.display = 'flex';
    };

    const closeCatModal = () => {
      document.getElementById('categoryModal').style.display = 'none';
    };

    const prepareCategoryEdit = (id, name, desc) => {
      document.getElementById('catModalTitle').innerText = "Update Category";
      document.getElementById('modal_cat_id').value = id;
      document.getElementById('modal_cat_name').value = name;
      document.getElementById('modal_cat_description').value = desc; // Set description
      document.getElementById('categoryModal').style.display = 'flex';
    };

    document.getElementById('categoryForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const catId = document.getElementById('modal_cat_id').value;
      const formData = new FormData();
      formData.append('cat_name', document.getElementById('modal_cat_name').value);
      formData.append('cat_description', document.getElementById('modal_cat_description').value);

      if (catId) formData.append('cat_id', catId);

      fetch('saveCategory.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            closeCatModal();
            refreshCategoryList();
          } else {
            alert(data.message);
          }
        });
    });

    document.addEventListener('DOMContentLoaded', refreshCategoryList);
  </script>

  <!------------------ PRODUCT PANEL ------------------>
  <script>
    // Global references
    const categorySelect = document.getElementById('categorySelect');
    const productCardContainer = document.querySelector('.product-card');

    /**
     * 1. LOAD CATEGORIES (Dropdown Sync)
     * Fetches categories for both the filter and the modal dropdown.
     */
    const loadCategories = () => {
      fetch('getCategory.php')
        .then(res => res.json())
        .then(data => {
          let options = '<option value="">---Select Category---</option>';
          data.forEach(category => {
            options += `<option value="${category.Cat_id}">${category.Cat_name}</option>`;
          });
          // Fill the main filter
          categorySelect.innerHTML = options;
          // Fill the modal dropdown
          document.getElementById('modal_prod_cat').innerHTML = options;
        })
        .catch(err => console.error("Category Load Error:", err));
    };

    /**
     * 2. SHOW PRODUCTS
     * Fetches products and renders the cards with the exact CSS layout.
     */
    const loadProducts = (Cat_id = null) => {
      let url = 'getProduct.php';
      if (Cat_id) url += `?Cat_id=${Cat_id}`;

      fetch(url)
        .then(res => res.json())
        .then(data => {
          // "Add Product" card
          let html = `
                  <div class="card userbtn">
	                  <button class="add-user light-tooltip" data-tooltip="Add New Product" data-tooltip="Add New Product" onclick="openProductModal()">
		                  <i class="fa-solid fa-plus"></i>
	                  </button>
                  </div>`;

          data.forEach(product => {
            html += `
                <div class="card" id="prod-card-${product.Product_id}">
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <i style="font-size: 12rem; border-radius: 50%; width: 15rem; height:15rem; color: rgba(0, 0, 0, 0.3);" class="fa-solid fa-box-open"></i>
                    </div>
                    
                    <div style="transform: translateY(-9px); font-weight: 600; font-size: 1.3rem; color: grey; display: flex; flex-direction: column; justify-content: space-evenly; text-align: left; width: 100%; height: 40%;">
                        <p>Product ID : ${product.Product_id}</p>
                        <p>Name : ${product.Product_name}</p>
                        <p>Cost Price : ₹${product.Cost_price}</p>
                        <p>Category : ${product.Cat_name}</p>
                    </div>

                    <div style="display: flex; flex-direction: row; justify-content: space-around; gap: 10px;">
                        <button onclick="prepareProductEdit(${product.Product_id}, '${product.Product_name}', ${product.Cost_price}, ${product.Cat_id})" class="edit light-tooltip" data-tooltip="Edit Product Details">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button onclick="handleProductDelete(${product.Product_id})" class="delete light-tooltip" data-tooltip="Delete Product">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </div>`;
          });
          productCardContainer.innerHTML = html;
        })
        .catch(err => {
          console.error(err);
          productCardContainer.innerHTML = "<p>Failed to load products.</p>";
        });
    };

    /**
     * 3. MODAL CONTROLS (ADD)
     */
    const openProductModal = () => {
      document.getElementById('prodModalTitle').innerText = "Add New Product";
      document.getElementById('modal_prod_id').value = ""; // Reset ID
      document.getElementById('productForm').reset();

      // Ensure "Select Category" isn't an option in the Modal
      const modalCat = document.getElementById('modal_prod_cat');
      if (modalCat.options[0] && modalCat.options[0].value === "") {
        modalCat.remove(0);
      }

      document.getElementById('productModal').style.display = 'flex';
    };

    const closeProductModal = () => {
      document.getElementById('productModal').style.display = 'none';
    };

    /**
     * 4. PREPARE UPDATE (EDIT MAPPING)
     */
    const prepareProductEdit = (id, name, price, catId) => {
      document.getElementById('prodModalTitle').innerText = "Update Product";
      document.getElementById('modal_prod_id').value = id;
      document.getElementById('modal_prod_name').value = name;
      document.getElementById('modal_prod_price').value = price;
      document.getElementById('modal_prod_cat').value = catId;

      document.getElementById('productModal').style.display = 'flex';
    };

    /**
     * 5. SAVE / UPDATE LOGIC (With Duplicate Check)
     */
    document.getElementById('productForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);

      fetch('saveProduct.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            closeProductModal();
            loadProducts(categorySelect.value); // Refresh current view
          } else {
            // This handles the Duplicate Product Alert from PHP
            alert(data.message);
          }
        })
        .catch(err => alert("Error connecting to server."));
    });

    /**
     * 6. DELETE LOGIC (With Animation)
     */
    const handleProductDelete = (id) => {
      if (confirm("Delete this product permanently?")) {
        fetch(`deleteProduct.php?product_id=${id}`)
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              const card = document.getElementById(`prod-card-${id}`);
              if (card) {
                card.style.pointerEvents = "none";
                card.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                card.style.transform = "scale(0.3) translateY(30px)";
                card.style.opacity = "0";
                card.style.filter = "blur(10px)";
                setTimeout(() => card.remove(), 500);
              }
            } else {
              alert(data.message);
            }
          });
      }
    };

    /**
     * INITIALIZE
     */
    categorySelect.addEventListener('change', (e) => loadProducts(e.target.value));

    document.addEventListener('DOMContentLoaded', () => {
      loadCategories();
      loadProducts();
    });
  </script>

  <!------------------ REPORT PANEL ------------------>
  <script>
    let barChartInstance = null;
    let lineChartInstance = null;

    function updateKPICards(stats) {
      if (stats.error) {
        console.error("Stats Error:", stats.error);
        return;
      }
      document.getElementById('display-profit').innerText = stats.net_profit;
      document.getElementById('display-shops').innerText = stats.active_shops;
      document.getElementById('display-users').innerText = stats.active_users;
    }

    function renderBarChart(chartData) {
      const ctx = document.getElementById('barChart').getContext('2d');
      if (barChartInstance) barChartInstance.destroy();

      barChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: chartData.labels,
          datasets: [{
            label: 'Revenue (₹)',
            data: chartData.data,
            backgroundColor: '#4C1D95',
            borderRadius: 6
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    }

    function renderLineChart(chartData) {
      const ctx = document.getElementById('lineChart').getContext('2d');
      if (lineChartInstance) lineChartInstance.destroy();

      lineChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: chartData.labels,
          datasets: [{
            label: 'Sales Velocity',
            data: chartData.data,
            borderColor: '#01B574',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(1, 181, 116, 0.05)'
          }]
        },
        options: {
          responsive: true
        }
      });
    }

    function renderTables(tablesData) {
      // Update Product Table
      const prodBody = document.getElementById('prodTableBody');
      prodBody.innerHTML = tablesData.inventory.map(item => `
        <tr>
            <td style="font-weight: 600;">${item.Product_name}</td>
            <td>${item.qty} Units</td>
            <td style="font-weight: 700;">₹${Number(item.rev).toLocaleString()}</td>
            <td><span class="badge-location">${item.Shop_name}</span></td>
        </tr>
    `).join('');

      // Update Shop Table
      const shopBody = document.getElementById('shopTableBody');
      shopBody.innerHTML = tablesData.directory.map(shop => `
        <tr>
            <td style="font-weight: 600;">${shop.Shop_name}</td>
            <td>${shop.Owner_name}</td>
            <td>${shop.Contact}</td>
            <td style="text-align: right; color: #01B574; font-weight: 700;">
                ₹${Number(shop.rev).toLocaleString()}
            </td>
        </tr>
    `).join('');
    }

    async function refreshDashboard(fromDate = '', toDate = '') {
      try {
        const queryParams = `?from=${fromDate}&to=${toDate}`;

        const [statsRes, chartsRes, tablesRes] = await Promise.all([
          fetch('getReport.php' + queryParams).then(res => res.json()),
          fetch('getReportCharts.php' + queryParams).then(res => res.json()),
          fetch('getReportTable.php' + queryParams).then(res => res.json())
        ]);

        updateKPICards(statsRes);
        renderBarChart(chartsRes.barChart);
        renderLineChart(chartsRes.lineChart);
        renderTables(tablesRes);

      } catch (err) {
        console.error("Dashboard Orchestration Error:", err);
      }
    }

    function logReportAudit(type, filename) {
      const formData = new FormData();
      formData.append('type', type);
      formData.append('filename', filename);

      fetch('saveReport.php', {
          method: 'POST',
          body: formData
        })
        .then(res => res.json())
        .then(data => console.log("Audit Log:", data.status))
        .catch(err => console.error("Logging Error:", err));
    }

    function exportCSV(tableId) {
      const table = document.getElementById(tableId);
      if (!table) return;

      const reportType = (tableId === 'prodTable') ? "Product Inventory" : "Shop Performance";
      const baseName = (tableId === 'prodTable') ? "Product_Inventory" : "Shop_Performance";
      const filename = `${baseName}_${new Date().toISOString().slice(0, 10)}.csv`;

      logReportAudit(reportType, filename);

      let csv = [];
      const rows = table.querySelectorAll("tr");
      rows.forEach(tr => {
        const row = [];
        tr.querySelectorAll("td, th").forEach(td => {
          let cleanData = td.innerText.replace(/,/g, '').replace('₹', '').trim();
          row.push(`"${cleanData}"`);
        });
        csv.push(row.join(","));
      });

      const csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
      const link = document.createElement("a");
      link.href = encodeURI(csvContent);
      link.download = filename;
      link.click();
    }

    function applyDateFilter() {
      const from = document.getElementById('date-from').value;
      const to = document.getElementById('date-to').value;

      if (!from || !to) {
        alert("Please select both 'From' and 'To' dates.");
        return;
      }

      refreshDashboard(from, to);
    }

    function resetFilter() {
      document.getElementById('date-from').value = '';
      document.getElementById('date-to').value = '';
      refreshDashboard();
    }

    // Initialize the dashboard on page load
    window.onload = () => refreshDashboard();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>