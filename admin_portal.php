<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit;
}
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Main Content Wrapper -->
  <main class="main-content container">

    <!-- ADMIN GOVERNANCE PORTAL VIEW -->
    <div id="view-admin" class="view-section">
      <!-- Admin Header Banner -->
      <div style="background: linear-gradient(135deg, var(--navy-900), var(--navy-800)); color: #FFF; border-radius: var(--radius-lg); padding: 1.75rem 2rem; margin-bottom: 2rem; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-md);">
        <div>
          <h2 style="color: #FFF; font-size: 1.6rem;"><i class="fa-solid fa-shield-halved text-primary"></i> Platform Admin Governance Panel</h2>
          <p style="color: #94A3B8; font-size: 0.9rem; margin-top: 0.25rem;">Supervise multi-vendor store approvals, commission billing, product moderation, and audit compliance.</p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
          <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(8px); padding: 0.75rem 1.25rem; border-radius: var(--radius-md); border: 1px solid rgba(255,255,255,0.15); text-align: right;">
            <div style="font-size: 0.75rem; color: #CBD5E1; font-weight: 700; text-transform: uppercase;">Platform Commission Pool (10%)</div>
            <div style="font-size: 1.5rem; font-weight: 800; color: #F59E0B;" id="admin-commission-pool">₹84,850</div>
          </div>
          <a href="api/auth.php?action=logout" class="btn-secondary" style="background: rgba(255,255,255,0.15); color: #FFF; border-color: rgba(255,255,255,0.25);"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
      </div>

      <!-- Admin Stats Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-chart-gantt"></i></div>
          <div>
            <div class="stat-val" id="admin-total-gmv">₹8,48,500</div>
            <div class="stat-label">Total Marketplace GMV</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon emerald"><i class="fa-solid fa-building-circle-check"></i></div>
          <div>
            <div class="stat-val" id="admin-active-vendors">6 Active</div>
            <div class="stat-label">Verified SME Vendors</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-hourglass-half"></i></div>
          <div>
            <div class="stat-val" style="color: var(--primary-600);" id="admin-pending-vendors">2 Pending</div>
            <div class="stat-label">Vendor Onboarding Requests</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-boxes-stacked"></i></div>
          <div>
            <div class="stat-val" id="admin-total-products">560 Products</div>
            <div class="stat-label">Total Active Catalog Items</div>
          </div>
        </div>
      </div>

      <!-- Admin Navigation Tabs -->
      <div style="margin-bottom: 1.5rem; display: flex; gap: 1rem; border-bottom: 2px solid var(--border-light); padding-bottom: 0.5rem;">
        <button class="btn-primary" id="admin-btn-vendors" onclick="switchAdminTab('vendors')"><i class="fa-solid fa-store"></i> Vendor Onboarding Approvals</button>
        <button class="btn-secondary" id="admin-btn-products" onclick="switchAdminTab('products')"><i class="fa-solid fa-boxes-packing"></i> Product Moderation</button>
        <button class="btn-secondary" id="admin-btn-audit" onclick="switchAdminTab('audit')"><i class="fa-solid fa-list-check"></i> System Audit Logs</button>
      </div>

      <!-- Admin Tab 1: Vendor Approvals -->
      <div id="admin-tab-vendors" class="data-table-card">
        <h3>SME Vendor Onboarding Requests</h3>
        <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;">Validate GSTIN & business documents before granting storefront authorization.</p>
        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Store Name</th>
                <th>Owner Name</th>
                <th>Category Specialty</th>
                <th>GSTIN Document</th>
                <th>Application Date</th>
                <th>Status</th>
                <th>Governance Actions</th>
              </tr>
            </thead>
            <tbody id="admin-vendors-tbody"></tbody>
          </table>
        </div>
      </div>

      <!-- Admin Tab 2: Product Moderation -->
      <div id="admin-tab-products" style="display: none;" class="data-table-card">
        <h3>Platform Product Listings Moderation (560 Catalog Items)</h3>
        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Product Title</th>
                <th>SME Vendor</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Visibility Status</th>
                <th>Moderation Action</th>
              </tr>
            </thead>
            <tbody id="admin-products-tbody"></tbody>
          </table>
        </div>
      </div>

      <!-- Admin Tab 3: Audit Logs -->
      <div id="admin-tab-audit" style="display: none;" class="data-table-card">
        <h3>Real-time Activity Audit Logs</h3>
        <ul id="admin-audit-logs" style="list-style: none; display: flex; flex-direction: column; gap: 0.75rem; font-family: monospace; font-size: 0.85rem;"></ul>
      </div>
    </div>

  </main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
