<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'vendor') {
    header('Location: vendor_login.php');
    exit;
}
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Main Content Wrapper -->
  <main class="main-content container">

    <!-- VENDOR DASHBOARD CONSOLE VIEW -->
    <div id="view-vendor" class="view-section">
      <!-- Vendor Header Banner -->
      <div style="background: var(--bg-card); border: 1px solid var(--border-light); border-radius: var(--radius-lg); padding: 1.5rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 1.25rem;">
          <div class="vendor-avatar" style="width: 60px; height: 60px; font-size: 1.5rem; background: var(--primary-500); color: #FFF; font-weight: 700; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
            <?php echo strtoupper(substr($_SESSION['user_name'], 0, 2)); ?>
          </div>
          <div>
            <div style="display: flex; align-items: center; gap: 0.6rem;">
              <h2 style="font-size: 1.5rem;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
              <span class="status-pill status-approved"><i class="fa-solid fa-circle-check"></i> Verified SME Store</span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.2rem;">
              Email: <code><?php echo htmlspecialchars($_SESSION['user_email']); ?></code> &nbsp;|&nbsp; Commission Tier: <strong>10% Platform Fee</strong> &nbsp;|&nbsp; Seller Score: <strong>4.9 / 5.0</strong>
            </p>
          </div>
        </div>
        <div>
          <button class="btn-primary" onclick="openAddProductModal()"><i class="fa-solid fa-plus"></i> Add New Product</button>
          <a href="api/auth.php?action=logout" class="btn-secondary" style="margin-left: 0.5rem;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
      </div>

      <!-- Dashboard Layout -->
      <div class="dashboard-layout">
        <!-- Sidebar Menu -->
        <aside class="dash-sidebar">
          <ul class="dash-menu">
            <li class="dash-menu-item active" onclick="switchVendorTab('overview')"><i class="fa-solid fa-chart-line"></i> Store Overview</li>
            <li class="dash-menu-item" onclick="switchVendorTab('products')"><i class="fa-solid fa-box-archive"></i> Product Listings</li>
            <li class="dash-menu-item" onclick="switchVendorTab('orders')"><i class="fa-solid fa-receipt"></i> Customer Orders</li>
            <li class="dash-menu-item" onclick="switchVendorTab('reviews')"><i class="fa-solid fa-star"></i> Buyer Feedback</li>
          </ul>
        </aside>

        <!-- Main Dashboard Tab Content -->
        <div>
          <!-- Tab 1: Overview & Analytics -->
          <div id="vendor-tab-overview">
            <!-- Stats Grid -->
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon orange"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                <div>
                  <div class="stat-val" id="vendor-stat-revenue">₹1,48,900</div>
                  <div class="stat-label">Total Store Revenue</div>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon blue"><i class="fa-solid fa-cart-flatbed"></i></div>
                <div>
                  <div class="stat-val" id="vendor-stat-orders">18</div>
                  <div class="stat-label">Orders Received</div>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon emerald"><i class="fa-solid fa-boxes-stacked"></i></div>
                <div>
                  <div class="stat-val" id="vendor-stat-products">70</div>
                  <div class="stat-label">Active Products</div>
                </div>
              </div>

              <div class="stat-card">
                <div class="stat-icon purple"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                  <div class="stat-val" style="color: var(--accent-rose);">3</div>
                  <div class="stat-label">Low Stock Warning</div>
                </div>
              </div>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
              <div class="chart-card">
                <div class="chart-header">
                  <h3><i class="fa-solid fa-chart-area text-primary"></i> Monthly Sales & Revenue Metric</h3>
                  <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">FY 2025-2026</span>
                </div>
                <div class="chart-canvas-wrap flex-center">
                  <svg width="100%" height="200" viewBox="0 0 500 200" style="overflow: visible;">
                    <line x1="40" y1="160" x2="480" y2="160" stroke="#E2E8F0" stroke-width="1"/>
                    <line x1="40" y1="110" x2="480" y2="110" stroke="#E2E8F0" stroke-dasharray="4" stroke-width="1"/>
                    <line x1="40" y1="60" x2="480" y2="60" stroke="#E2E8F0" stroke-dasharray="4" stroke-width="1"/>
                    
                    <defs>
                      <linearGradient id="salesGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#F97316" stop-opacity="0.4"/>
                        <stop offset="100%" stop-color="#F97316" stop-opacity="0.0"/>
                      </linearGradient>
                    </defs>
                    <path d="M 50 140 Q 120 120 190 90 T 330 50 T 470 30 L 470 160 L 50 160 Z" fill="url(#salesGrad)"/>
                    <path d="M 50 140 Q 120 120 190 90 T 330 50 T 470 30" fill="none" stroke="#F97316" stroke-width="3.5" stroke-linecap="round"/>
                    
                    <circle cx="50" cy="140" r="5" fill="#F97316"/>
                    <circle cx="190" cy="90" r="5" fill="#F97316"/>
                    <circle cx="330" cy="50" r="5" fill="#F97316"/>
                    <circle cx="470" cy="30" r="5" fill="#F97316"/>
                    
                    <text x="50" y="180" font-size="11" fill="#64748B" text-anchor="middle">Apr</text>
                    <text x="190" y="180" font-size="11" fill="#64748B" text-anchor="middle">May</text>
                    <text x="330" y="180" font-size="11" fill="#64748B" text-anchor="middle">Jun</text>
                    <text x="470" y="180" font-size="11" fill="#64748B" text-anchor="middle">Jul</text>
                  </svg>
                </div>
              </div>

              <div class="chart-card">
                <div class="chart-header">
                  <h3><i class="fa-solid fa-chart-pie text-primary"></i> Regional Distribution</h3>
                </div>
                <div class="chart-canvas-wrap flex-center flex-direction-column">
                  <svg width="140" height="140" viewBox="0 0 36 36">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#E2E8F0" stroke-width="3.8"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#F97316" stroke-width="3.8" stroke-dasharray="55, 100"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#6366F1" stroke-width="3.8" stroke-dasharray="30, 100" stroke-dashoffset="-55"/>
                  </svg>
                  <div style="font-size: 0.78rem; display: flex; gap: 0.75rem; margin-top: 1rem;">
                    <span><i class="fa-solid fa-circle" style="color: #F97316;"></i> West (55%)</span>
                    <span><i class="fa-solid fa-circle" style="color: #6366F1;"></i> North (30%)</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tab 2: Products Management -->
          <div id="vendor-tab-products" style="display: none;" class="data-table-card">
            <div class="flex-between" style="margin-bottom: 1rem;">
              <h3>My Product Inventory</h3>
              <button class="btn-primary" onclick="openAddProductModal()"><i class="fa-solid fa-plus"></i> Add Item</button>
            </div>
            <div class="table-responsive">
              <table class="custom-table">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Rating</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="vendor-products-tbody"></tbody>
              </table>
            </div>
          </div>

          <!-- Tab 3: Orders Fulfillment -->
          <div id="vendor-tab-orders" style="display: none;" class="data-table-card">
            <h3>Incoming Sales Orders</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Fulfill customer orders and update shipment status in real time.</p>
            <div class="table-responsive">
              <table class="custom-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Items Purchased</th>
                    <th>Total Price</th>
                    <th>Current Status</th>
                    <th>Fulfillment Actions</th>
                  </tr>
                </thead>
                <tbody id="vendor-orders-tbody"></tbody>
              </table>
            </div>
          </div>

          <!-- Tab 4: Reviews & Feedback -->
          <div id="vendor-tab-reviews" style="display: none;" class="data-table-card">
            <h3>Post-Purchase Buyer Feedback</h3>
            <div id="vendor-reviews-container" style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;"></div>
          </div>
        </div>
      </div>
    </div>

  </main>

  <!-- Add/Edit Product Modal for Vendors -->
  <div class="modal-overlay" id="modal-product-crud">
    <div class="modal-card">
      <button class="modal-close-btn" onclick="closeModal('modal-product-crud')"><i class="fa-solid fa-xmark"></i></button>
      <h2 id="crud-modal-title"><i class="fa-solid fa-box text-primary"></i> Add New Product Listing</h2>
      
      <form id="product-crud-form" onsubmit="handleSaveProduct(event)" style="margin-top: 1.25rem;">
        <input type="hidden" id="crud-p-id">
        
        <div style="margin-bottom: 1rem;">
          <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Product Title</label>
          <input type="text" id="crud-p-title" required placeholder="e.g. Wireless Noise Cancelling Headphones" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
          <div>
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Category</label>
            <select id="crud-p-cat" class="sort-select" style="width: 100%;">
              <option value="Electronics">Electronics</option>
              <option value="Fashion">Fashion</option>
              <option value="Handicrafts">Handicrafts</option>
              <option value="Spices & Organics">Spices & Organics</option>
              <option value="Home & Kitchen">Home & Kitchen</option>
              <option value="Beauty & Herbal Wellness">Beauty & Herbal Wellness</option>
              <option value="Footwear & Leather Goods">Footwear & Leather Goods</option>
              <option value="Books & Stationery">Books & Stationery</option>
            </select>
          </div>

          <div>
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Price (₹)</label>
            <input type="number" id="crud-p-price" required min="1" placeholder="2499" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
          </div>

          <div>
            <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Stock Inventory</label>
            <input type="number" id="crud-p-stock" required min="0" placeholder="50" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
          </div>
        </div>

        <div style="margin-bottom: 1rem;">
          <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Product Image URL</label>
          <input type="url" id="crud-p-image" required placeholder="https://images.unsplash.com/photo-..." style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);">
        </div>

        <div style="margin-bottom: 1.5rem;">
          <label style="font-size: 0.82rem; font-weight: 700; color: var(--navy-800); display: block; margin-bottom: 0.3rem;">Product Description & Specs</label>
          <textarea id="crud-p-desc" required rows="3" placeholder="Enter key features, material, warranty..." style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-light); border-radius: var(--radius-md);"></textarea>
        </div>

        <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem;">Save Product Listing <i class="fa-solid fa-check"></i></button>
      </form>
    </div>
  </div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
