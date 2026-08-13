/* Vyapar Setu - Multi-Vendor Marketplace Application Logic */

// Default Seed Data
const INITIAL_DATA = {
  vendors: [
    { id: 'v1', name: 'TechCraft Electronics', owner: 'Rahul Verma', gst: '27AABCT1234H1Z5', email: 'contact@techcraft.in', category: 'Electronics', rating: 4.9, reviewsCount: 1240, status: 'APPROVED', commission: '10%' },
    { id: 'v2', name: 'Bharat Handloom Textiles', owner: 'Priya Sharma', gst: '27AABCB5678K1Z2', email: 'sales@bharattextiles.in', category: 'Fashion', rating: 4.8, reviewsCount: 850, status: 'APPROVED', commission: '10%' },
    { id: 'v3', name: 'Swadeshi Organic Spices', owner: 'Amrit Patel', gst: '27AABCS9912M1Z9', email: 'info@swadeshispices.org', category: 'Spices & Organics', rating: 4.9, reviewsCount: 2410, status: 'APPROVED', commission: '10%' },
    { id: 'v4', name: 'Jaipur Royal Crafts', owner: 'Vikram Singh', gst: '08AAACJ9981K1Z3', email: 'raj@jaipurcrafts.com', category: 'Handicrafts', rating: 4.7, reviewsCount: 320, status: 'PENDING', commission: '10%' },
    { id: 'v5', name: 'GreenTerra Organics', owner: 'Kavita Rao', gst: '27AABCG7712L1Z8', email: 'care@greenterra.in', category: 'Beauty & Herbal Wellness', rating: 4.9, reviewsCount: 680, status: 'APPROVED', commission: '10%' },
    { id: 'v6', name: 'Kashmir Valley Crafts', owner: 'Tariq Ahmed', gst: '01AAACK4419K1Z4', email: 'tariq@kashmircrafts.org', category: 'Home & Kitchen', rating: 4.8, reviewsCount: 510, status: 'APPROVED', commission: '10%' },
    { id: 'v7', name: 'DesiLeather Crafters', owner: 'Suresh Kumar', gst: '09AAACD3312M1Z1', email: 'suresh@desileather.in', category: 'Footwear & Leather Goods', rating: 4.9, reviewsCount: 940, status: 'APPROVED', commission: '10%' },
    { id: 'v8', name: 'Nalanda Academic Press', owner: 'Dr. Arindam Sen', gst: '10AAACN8821P1Z9', email: 'editor@nalandapress.org', category: 'Books & Stationery', rating: 4.8, reviewsCount: 190, status: 'PENDING', commission: '10%' }
  ],
  products: [
    {
      id: 'p1',
      title: 'SonicPods Pro Active Noise Cancelling Earbuds',
      vendorId: 'v1',
      vendorName: 'TechCraft Electronics',
      category: 'Electronics',
      price: 2499,
      originalPrice: 4999,
      stock: 42,
      sku: 'TC-WLE-01',
      rating: 4.8,
      ratingCount: 312,
      spotlight: 'BESTSELLER',
      image: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
      description: 'Ultra-low latency wireless earbuds featuring Hybrid Active Noise Cancellation (ANC), 30-hour playback with fast charge case, dual ENC mics for crystal clear calls, and IPX5 splash resistance.',
      specs: { 'Battery': '30 Hours', 'Connectivity': 'Bluetooth 5.3', 'Warranty': '1 Year Brand Warranty', 'Driver': '12mm Dynamic' }
    },
    {
      id: 'p2',
      title: 'SmartFit OLED Fitness & SpO2 Smartwatch',
      vendorId: 'v1',
      vendorName: 'TechCraft Electronics',
      category: 'Electronics',
      price: 3999,
      originalPrice: 6999,
      stock: 15,
      sku: 'TC-SMW-04',
      rating: 4.9,
      ratingCount: 184,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=600&q=80',
      description: 'Sleek aluminum smartwatch with 1.43-inch Always-On AMOLED Display, continuous Heart Rate & SpO2 tracker, 100+ sports modes, and integrated Bluetooth phone calling.',
      specs: { 'Display': '1.43" AMOLED', 'Battery': '10 Days Normal Use', 'Waterproof': '5 ATM', 'Sensors': 'Optical HR, SpO2' }
    },
    {
      id: 'p3',
      title: 'Handcrafted Pure Mulberry Silk Banarasi Saree',
      vendorId: 'v2',
      vendorName: 'Bharat Handloom Textiles',
      category: 'Fashion',
      price: 6800,
      originalPrice: 9500,
      stock: 8,
      sku: 'BT-SLK-09',
      rating: 4.9,
      ratingCount: 96,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
      description: 'Woven by master artisans in Varanasi, this pure silk saree features intricate gold zari brocade work and comes with an unstitched matching blouse piece.',
      specs: { 'Fabric': '100% Pure Mulberry Silk', 'Weave': 'Banarasi Zari Handloom', 'Length': '6.3 Meters', 'Care': 'Dry Clean Only' }
    },
    {
      id: 'p4',
      title: 'Organic Malabar Black Pepper Whole (500g)',
      vendorId: 'v3',
      vendorName: 'Swadeshi Organic Spices',
      category: 'Spices & Organics',
      price: 650,
      originalPrice: 850,
      stock: 60,
      sku: 'SS-PEP-02',
      rating: 5.0,
      ratingCount: 540,
      spotlight: 'BESTSELLER',
      image: 'https://images.unsplash.com/photo-1599940824399-b87987ceb72a?auto=format&fit=crop&w=600&q=80',
      description: 'Directly sourced from organic certified farms in Western Ghats, Kerala. High essential oil content delivering rich aroma and bold pungency.',
      specs: { 'Certifications': 'USDA Organic, FSSAI Certified', 'Origin': 'Wayanad, Kerala', 'Shelf Life': '12 Months' }
    },
    {
      id: 'p5',
      title: 'Kashmiri Grade-1 Mongra Saffron Kesar (2g)',
      vendorId: 'v3',
      vendorName: 'Swadeshi Organic Spices',
      category: 'Spices & Organics',
      price: 990,
      originalPrice: 1290,
      stock: 30,
      sku: 'SS-KSR-01',
      rating: 4.9,
      ratingCount: 210,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1601004890684-d8cbf643f5f2?auto=format&fit=crop&w=600&q=80',
      description: 'Original Pampore Kashmiri Saffron strands known for deep crimson color, exquisite floral fragrance, and natural medicinal properties.',
      specs: { 'Grade': 'Grade-1 Mongra', 'Purity': '100% Natural Single Harvest', 'Weight': '2 Grams Glass Jar' }
    },
    {
      id: 'p6',
      title: 'Hand-Carved Solid Brass Peacock Diya Idol',
      vendorId: 'v4',
      vendorName: 'Jaipur Royal Crafts',
      category: 'Handicrafts',
      price: 1499,
      originalPrice: 1999,
      stock: 12,
      sku: 'JR-BRS-05',
      rating: 4.8,
      ratingCount: 74,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1605648916361-9bc12ad6a569?auto=format&fit=crop&w=600&q=80',
      description: 'Exquisite traditional oil lamp handcrafted by Rajasthan brass artisans. Perfect for festive decor, home altars, and heritage gifting.',
      specs: { 'Material': 'Pure Heavy Solid Brass', 'Height': '8 Inches', 'Weight': '850 Grams' }
    },
    {
      id: 'p7',
      title: 'Ultra HD 4K Pro Streaming Webcam (60fps)',
      vendorId: 'v1',
      vendorName: 'TechCraft Electronics',
      category: 'Electronics',
      price: 4500,
      originalPrice: 6500,
      stock: 3,
      sku: 'TC-WBC-08',
      rating: 4.6,
      ratingCount: 42,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
      description: 'Professional 4K USB camera featuring autofocus, dual noise-reducing stereo microphones, tripod thread, and magnetic privacy cover.',
      specs: { 'Resolution': '4K UHD @ 60fps', 'Microphone': 'Dual Noise-Canceling', 'Mount': 'Universal Clip & Tripod' }
    },
    {
      id: 'p8',
      title: 'Breathable Organic Cotton Chanderi Kurta Set',
      vendorId: 'v2',
      vendorName: 'Bharat Handloom Textiles',
      category: 'Fashion',
      price: 1850,
      originalPrice: 2450,
      stock: 25,
      sku: 'BT-KRT-12',
      rating: 4.7,
      ratingCount: 120,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80',
      description: 'Comfortable summer ethnic wear with subtle hand-block printing and sheer Chanderi dupatta. Ideal for daily formal and casual wear.',
      specs: { 'Material': '100% Breathable Cotton', 'Set Includes': 'Kurta, Pants & Dupatta', 'Fit': 'Regular Fit' }
    },
    {
      id: 'p9',
      title: 'Herbal Neem & Kumkumadi Saffron Face Cleanser (200ml)',
      vendorId: 'v5',
      vendorName: 'GreenTerra Organics',
      category: 'Beauty & Herbal Wellness',
      price: 450,
      originalPrice: 650,
      stock: 45,
      sku: 'GT-FAC-01',
      rating: 4.9,
      ratingCount: 380,
      spotlight: 'BESTSELLER',
      image: 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
      description: 'Ayurvedic sulphate-free facial cleanser infused with organic neem, Kashmiri saffron, and pure aloevera extract for radiant skin.',
      specs: { 'Volume': '200 ml', 'Skin Type': 'All Skin Types', 'Paraben Free': 'Yes 100%' }
    },
    {
      id: 'p10',
      title: 'Organic Cold-Pressed Extra Virgin Coconut Oil (500ml)',
      vendorId: 'v5',
      vendorName: 'GreenTerra Organics',
      category: 'Beauty & Herbal Wellness',
      price: 380,
      originalPrice: 520,
      stock: 80,
      sku: 'GT-OIL-05',
      rating: 4.8,
      ratingCount: 290,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1608248597261-26c7e3fef57c?auto=format&fit=crop&w=600&q=80',
      description: 'Pure raw unrefined coconut oil extracted from fresh coconut milk using traditional wood-press methods. Ideal for hair care and cooking.',
      specs: { 'Extraction': 'Cold-Pressed Wood Mill', 'Volume': '500 ml', 'Certifications': 'Organic India Certified' }
    },
    {
      id: 'p11',
      title: 'Hand-Carved Kashmir Walnut Wood Dry Fruit Bowl',
      vendorId: 'v6',
      vendorName: 'Kashmir Valley Crafts',
      category: 'Home & Kitchen',
      price: 1890,
      originalPrice: 2490,
      stock: 14,
      sku: 'KV-WOD-03',
      rating: 4.9,
      ratingCount: 175,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
      description: 'Intricately hand-carved decorative wooden serving bowl crafted from seasoned Kashmiri walnut wood by Srinagar master artisans.',
      specs: { 'Material': 'Seasoned Walnut Wood', 'Finish': 'Natural Wax Polish', 'Diameter': '10 Inches' }
    },
    {
      id: 'p12',
      title: 'Pure Ayurvedic Copper Water Jug Set (1.5L + 2 Tumblers)',
      vendorId: 'v6',
      vendorName: 'Kashmir Valley Crafts',
      category: 'Home & Kitchen',
      price: 1650,
      originalPrice: 2200,
      stock: 20,
      sku: 'KV-CPR-08',
      rating: 4.7,
      ratingCount: 142,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1610701596007-11502861dcfa?auto=format&fit=crop&w=600&q=80',
      description: 'Hammered pure copper water storage pitcher set. Drinking copper-charged water promotes healthy digestion and natural detox.',
      specs: { 'Material': '99.5% Pure Heavy Copper', 'Capacity': '1.5 Liters Pitcher', 'Includes': '1 Jug + 2 Glasses' }
    },
    {
      id: 'p13',
      title: 'Handcrafted Genuine Leather Kolhapuri Chappals',
      vendorId: 'v7',
      vendorName: 'DesiLeather Crafters',
      category: 'Footwear & Leather Goods',
      price: 1499,
      originalPrice: 1999,
      stock: 35,
      sku: 'DL-KLP-02',
      rating: 4.8,
      ratingCount: 410,
      spotlight: 'BESTSELLER',
      image: 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=600&q=80',
      description: 'Traditional Kolhapuri leather sandals featuring hand-stitched leather braiding and cushioned footbed for ethnic elegance.',
      specs: { 'Material': '100% Genuine Full Grain Leather', 'Sole': 'Leather Sole', 'Origin': 'Kolhapur, Maharashtra' }
    },
    {
      id: 'p14',
      title: 'Vintage Handcrafted Leather Messenger Laptop Bag (15.6")',
      vendorId: 'v7',
      vendorName: 'DesiLeather Crafters',
      category: 'Footwear & Leather Goods',
      price: 3450,
      originalPrice: 4950,
      stock: 10,
      sku: 'DL-BAG-09',
      rating: 4.9,
      ratingCount: 220,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=600&q=80',
      description: 'Full-grain buffalo leather laptop briefcase featuring padded laptop sleeve, antique brass hardware, and detachable shoulder strap.',
      specs: { 'Material': 'Full Grain Buffalo Leather', 'Fits Laptop': 'Up to 15.6 Inches', 'Warranty': '2 Years' }
    },
    {
      id: 'p15',
      title: 'Handmade Recycled Cotton Paper Journal & Quill Pen Set',
      vendorId: 'v8',
      vendorName: 'Nalanda Academic Press',
      category: 'Books & Stationery',
      price: 799,
      originalPrice: 1199,
      stock: 25,
      sku: 'NA-JRN-01',
      rating: 4.8,
      ratingCount: 88,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
      description: 'Eco-friendly diary made with 200 pages of tree-free handmade deckle-edge rag paper, complete with brass lock clasp and calligraphy pen.',
      specs: { 'Pages': '200 Unruled Deckle Pages', 'Binding': 'Hand-stitched Leather Coptic', 'Paper Type': '100% Recycled Cotton' }
    },
    {
      id: 'p16',
      title: 'Ergonomic Wireless Mechanical RGB Keyboard',
      vendorId: 'v1',
      vendorName: 'TechCraft Electronics',
      category: 'Electronics',
      price: 3299,
      originalPrice: 4500,
      stock: 18,
      sku: 'TC-KBD-06',
      rating: 4.8,
      ratingCount: 165,
      spotlight: 'FEATURED',
      image: 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=600&q=80',
      description: 'Compact 75% mechanical keyboard with hot-swappable tactile switches, Bluetooth 5.1 / 2.4Ghz dual wireless modes, and customizable RGB lighting.',
      specs: { 'Switches': 'Tactile Brown Switches', 'Connectivity': 'Tri-mode Wireless & Type-C', 'Battery': '4000mAh' }
    },
    {
      id: 'p17',
      title: 'Handblocked Indigo Dabu Cotton Dupatta',
      vendorId: 'v2',
      vendorName: 'Bharat Handloom Textiles',
      category: 'Fashion',
      price: 950,
      originalPrice: 1350,
      stock: 40,
      sku: 'BT-DUP-07',
      rating: 4.7,
      ratingCount: 95,
      spotlight: 'REGULAR',
      image: 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=600&q=80',
      description: 'Traditional mud-resist Dabu block printed dupatta dyed in natural organic Indigo. Lightweight, soft, and breathable.',
      specs: { 'Fabric': '100% Pure Mulmul Cotton', 'Dye': 'Natural Indigo Dye', 'Length': '2.5 Meters' }
    },
    {
      id: 'p18',
      title: 'Handpainted Terracotta Chai Kulhad Set (Set of 6)',
      vendorId: 'v4',
      vendorName: 'Jaipur Royal Crafts',
      category: 'Handicrafts',
      price: 590,
      originalPrice: 850,
      stock: 50,
      sku: 'JR-KUL-11',
      rating: 4.9,
      ratingCount: 310,
      spotlight: 'BESTSELLER',
      image: 'https://images.unsplash.com/photo-1578749556568-bc2c40e68b61?auto=format&fit=crop&w=600&q=80',
      description: 'Traditional earthen clay kulhad cups handcrafted by terracotta artisans. Enhances the earthy flavor of Indian masala chai.',
      specs: { 'Material': 'Natural Terracotta Clay', 'Quantity': 'Set of 6 Cups', 'Capacity': '180 ml per cup' }
    }
  ],
  orders: [
    {
      id: 'ORD-99012',
      customerName: 'Rishiraj Sharma',
      address: 'Flat 402, G.V. Acharya Campus Road, Shelu, Maharashtra - 410101',
      items: [
        { productId: 'p1', title: 'SonicPods Pro Active Noise Cancelling Earbuds', price: 2499, qty: 1, vendorId: 'v1' }
      ],
      totalAmount: 2249, // after 10% coupon
      discount: 250,
      paymentMethod: 'UPI / Razorpay',
      status: 'Shipped', // Options: Placed, Processing, Shipped, Out for Delivery, Delivered
      date: '2026-07-30T14:20:00'
    },
    {
      id: 'ORD-99013',
      customerName: 'Vishal Yadav',
      address: 'Suite 12, Tech Park Avenue, Pune, Maharashtra',
      items: [
        { productId: 'p4', title: 'Organic Malabar Black Pepper Whole (500g)', price: 650, qty: 2, vendorId: 'v3' }
      ],
      totalAmount: 1300,
      discount: 0,
      paymentMethod: 'Cash on Delivery',
      status: 'Delivered',
      date: '2026-07-28T10:15:00'
    }
  ],
  reviews: [
    {
      id: 'r1',
      productId: 'p1',
      customerName: 'Rishiraj S.',
      rating: 5,
      comment: 'Exceptional ANC quality and bass response! Delivered in 2 days.',
      vendorResponse: 'Thank you Rishiraj! We are thrilled you love the sound signature.',
      date: '2026-07-29'
    }
  ],
  auditLogs: [
    'System expanded with 8 SME vendor storefronts and 18 products.',
    'Vendor "TechCraft Electronics" received order #ORD-99012.',
    'Shipment status for #ORD-99012 updated to "Shipped".'
  ]
};

// Global App State
let state = {
  role: 'customer', // customer, vendor, admin
  data: JSON.parse(localStorage.getItem('vyapar_setu_db')) || INITIAL_DATA,
  cart: JSON.parse(localStorage.getItem('vyapar_setu_cart')) || [],
  wishlist: JSON.parse(localStorage.getItem('vyapar_setu_wishlist')) || [],
  compareTray: JSON.parse(localStorage.getItem('vyapar_setu_compare')) || [],
  appliedCouponRate: 0,
  activeCategory: 'ALL',
  searchQuery: '',
  priceMax: 50000,
  minRating: 0,
  selectedVendorFilter: 'ALL',
  sortBy: 'spotlight'
};

// Auto-sync data upgrade if products count is lower than expanded dataset
if (!state.data.products || state.data.products.length < 18) {
  state.data = JSON.parse(JSON.stringify(INITIAL_DATA));
  localStorage.setItem('vyapar_setu_db', JSON.stringify(state.data));
}

// Initialize Application
document.addEventListener('DOMContentLoaded', () => {
  saveState();
  renderCategoryPills();
  filterProducts();
  updateBadgeCounts();
  renderVendorDashboard();
  renderAdminPortal();
});

// Save state to LocalStorage
function saveState() {
  localStorage.setItem('vyapar_setu_db', JSON.stringify(state.data));
  localStorage.setItem('vyapar_setu_cart', JSON.stringify(state.cart));
  localStorage.setItem('vyapar_setu_wishlist', JSON.stringify(state.wishlist));
  localStorage.setItem('vyapar_setu_compare', JSON.stringify(state.compareTray));
}

// Reset Demo Data
function resetSystemData() {
  if (confirm('Reset Vyapar Setu marketplace back to original demo data?')) {
    state.data = JSON.parse(JSON.stringify(INITIAL_DATA));
    state.cart = [];
    state.wishlist = [];
    state.compareTray = [];
    state.appliedCouponRate = 0;
    saveState();
    location.reload();
  }
}

// Persona & Role Switcher
function switchRole(role) {
  state.role = role;
  
  // Highlight Pill
  document.querySelectorAll('.role-pill').forEach(btn => {
    btn.classList.toggle('active', btn.dataset.role === role);
  });

  const badgeText = document.getElementById('role-badge-text');
  const viewCustomer = document.getElementById('view-customer');
  const viewVendor = document.getElementById('view-vendor');
  const viewAdmin = document.getElementById('view-admin');
  const catNav = document.getElementById('cat-nav-bar');

  viewCustomer.style.display = 'none';
  viewVendor.style.display = 'none';
  viewAdmin.style.display = 'none';

  if (role === 'customer') {
    viewCustomer.style.display = 'block';
    catNav.style.display = 'block';
    badgeText.innerText = 'Mode: Customer Storefront';
  } else if (role === 'vendor') {
    viewVendor.style.display = 'block';
    catNav.style.display = 'none';
    badgeText.innerText = 'Mode: Vendor Management Console (TechCraft)';
    renderVendorDashboard();
  } else if (role === 'admin') {
    viewAdmin.style.display = 'block';
    catNav.style.display = 'none';
    badgeText.innerText = 'Mode: Admin Governance Portal';
    renderAdminPortal();
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Render Category Navigation Pills & Sidebar Checklist
function renderCategoryPills() {
  const categories = [
    'ALL',
    'Electronics',
    'Fashion',
    'Handicrafts',
    'Spices & Organics',
    'Home & Kitchen',
    'Beauty & Herbal Wellness',
    'Footwear & Leather Goods',
    'Books & Stationery'
  ];
  
  // Top nav bar pills
  const navList = document.getElementById('category-pills-list');
  navList.innerHTML = categories.map(cat => `
    <li class="cat-nav-item ${state.activeCategory === cat ? 'active' : ''}" onclick="selectCategory('${cat}')">
      ${cat === 'ALL' ? '<i class="fa-solid fa-grid-2"></i> All Catalog' : cat}
    </li>
  `).join('');

  // Sidebar list
  const sidebarList = document.getElementById('sidebar-category-list');
  sidebarList.innerHTML = categories.map(cat => `
    <label class="filter-item-label">
      <span>${cat === 'ALL' ? 'All Categories' : cat}</span>
      <input type="radio" name="sidebar-cat" value="${cat}" ${state.activeCategory === cat ? 'checked' : ''} onchange="selectCategory('${cat}')">
    </label>
  `).join('');

  // Vendor Filter Dropdown
  const vendorSelect = document.getElementById('vendor-filter-select');
  const approvedVendors = state.data.vendors.filter(v => v.status === 'APPROVED');
  vendorSelect.innerHTML = `<option value="ALL">All SME Stores</option>` + 
    approvedVendors.map(v => `<option value="${v.id}">${v.name}</option>`).join('');
}

function selectCategory(cat) {
  state.activeCategory = cat;
  renderCategoryPills();
  filterProducts();
}

function updatePriceFilter(val) {
  state.priceMax = parseInt(val);
  document.getElementById('price-max-display').innerText = `₹${state.priceMax.toLocaleString('en-IN')}`;
  filterProducts();
}

function handleSearchInput(val) {
  state.searchQuery = val.trim().toLowerCase();
  filterProducts();
}

function resetFilters() {
  state.activeCategory = 'ALL';
  state.searchQuery = '';
  state.priceMax = 50000;
  state.minRating = 0;
  state.selectedVendorFilter = 'ALL';
  document.getElementById('global-search-input').value = '';
  document.getElementById('price-range-slider').value = 50000;
  document.getElementById('price-max-display').innerText = '₹50,000';
  document.getElementById('vendor-filter-select').value = 'ALL';
  renderCategoryPills();
  filterProducts();
}

// Filter and Render Products Grid (API-driven with Pagination)
let currentPage = 1;

async function filterProducts(page = 1) {
  currentPage = page;
  const vendorFilter = document.getElementById('vendor-filter-select') ? document.getElementById('vendor-filter-select').value : 'ALL';
  const ratingRadios = document.getElementsByName('rating-filter');
  let selectedRating = 0;
  for (let r of ratingRadios) {
    if (r.checked) selectedRating = parseFloat(r.value);
  }
  const sortVal = document.getElementById('sort-select') ? document.getElementById('sort-select').value : 'spotlight';

  const params = new URLSearchParams({
    action: 'list',
    category: state.activeCategory,
    vendor_id: vendorFilter,
    search: state.searchQuery,
    max_price: state.priceMax,
    min_rating: selectedRating,
    sort: sortVal,
    page: currentPage,
    limit: 24
  });

  try {
    const res = await fetch(`api/products.php?${params.toString()}`);
    const json = await res.json();
    
    if (json.status !== 'success') return;

    const products = json.data;
    const pagination = json.pagination;

    // Update Count
    const resultsElem = document.getElementById('results-count-text');
    if (resultsElem) {
      resultsElem.innerText = `Showing ${products.length} of ${pagination.total} catalog items (Page ${pagination.page} of ${pagination.total_pages})`;
    }

    // Render Grid
    const grid = document.getElementById('products-grid-container');
    if (!grid) return;

    if (products.length === 0) {
      grid.innerHTML = `
        <div style="grid-column: 1/-1; text-align: center; padding: 4rem 1rem;">
          <i class="fa-solid fa-box-open" style="font-size: 3rem; color: #CBD5E1; margin-bottom: 1rem;"></i>
          <h3>No matching products found</h3>
          <p style="color: var(--text-muted); margin-top: 0.5rem;">Try adjusting your filters or search keywords.</p>
          <button class="btn-outline" style="margin-top: 1rem;" onclick="resetFilters()">Reset All Filters</button>
        </div>
      `;
      document.getElementById('pagination-toolbar').innerHTML = '';
      return;
    }

    grid.innerHTML = products.map(p => {
      const isWishlisted = state.wishlist.includes(p.id);
      const isCompared = state.compareTray.includes(p.id);

      return `
        <div class="product-card">
          <div class="product-thumb">
            <img src="${p.image_url || p.image}" alt="${p.title}" loading="lazy">
            
            <div class="badge-tag-wrap">
              ${p.spotlight === 'BESTSELLER' ? '<span class="badge-spotlight">Best Seller</span>' : ''}
              <span class="badge-vendor">${p.vendorName || 'SME Store'}</span>
            </div>

            <button class="wishlist-btn ${isWishlisted ? 'active' : ''}" onclick="toggleWishlist('${p.id}')" title="Wishlist">
              <i class="${isWishlisted ? 'fa-solid' : 'fa-regular'} fa-heart"></i>
            </button>

            <label class="compare-checkbox-label">
              <input type="checkbox" ${isCompared ? 'checked' : ''} onchange="toggleCompareTray('${p.id}')"> Compare
            </label>
          </div>

          <div class="product-body">
            <div class="product-cat">${p.category_name || p.category}</div>
            <h3 class="product-title" onclick="openProductDetail('${p.id}')">${p.title}</h3>
            
            <div class="product-rating">
              <i class="fa-solid fa-star"></i>
              <span style="font-weight: 700; color: var(--navy-900);">${p.rating}</span>
              <span class="rating-count">(${p.rating_count || p.ratingCount || 45})</span>
            </div>

            <div class="product-footer">
              <div class="price-wrap">
                <span class="price-current">₹${parseFloat(p.price).toLocaleString('en-IN')}</span>
                <span class="price-original">₹${parseFloat(p.original_price || p.originalPrice || (p.price*1.3)).toLocaleString('en-IN')}</span>
              </div>

              <button class="add-cart-btn" onclick="addToCart('${p.id}')">
                <i class="fa-solid fa-cart-plus"></i> Add
              </button>
            </div>
          </div>
        </div>
      `;
    }).join('');

    // Render Pagination Toolbar
    renderPaginationBar(pagination);

  } catch (err) {
    console.error('Error fetching products:', err);
  }
}

function renderPaginationBar(p) {
  const toolbar = document.getElementById('pagination-toolbar');
  if (!toolbar || p.total_pages <= 1) {
    if (toolbar) toolbar.innerHTML = '';
    return;
  }

  let html = '';
  if (p.page > 1) {
    html += `<button class="btn-secondary" style="padding: 0.4rem 0.85rem;" onclick="filterProducts(${p.page - 1})"><i class="fa-solid fa-chevron-left"></i> Prev</button>`;
  }

  for (let i = 1; i <= p.total_pages; i++) {
    if (i === 1 || i === p.total_pages || (i >= p.page - 2 && i <= p.page + 2)) {
      html += `<button class="btn-${i === p.page ? 'primary' : 'secondary'}" style="padding: 0.4rem 0.85rem; font-weight: 700;" onclick="filterProducts(${i})">${i}</button>`;
    } else if (i === p.page - 3 || i === p.page + 3) {
      html += `<span style="padding: 0.4rem; color: var(--text-muted);">...</span>`;
    }
  }

  if (p.page < p.total_pages) {
    html += `<button class="btn-secondary" style="padding: 0.4rem 0.85rem;" onclick="filterProducts(${p.page + 1})">Next <i class="fa-solid fa-chevron-right"></i></button>`;
  }

  toolbar.innerHTML = html;
}

// Wishlist & Cart Actions
function toggleWishlist(id) {
  const index = state.wishlist.indexOf(id);
  if (index > -1) {
    state.wishlist.splice(index, 1);
  } else {
    state.wishlist.push(id);
  }
  saveState();
  updateBadgeCounts();
  filterProducts();
}

function addToCart(id) {
  const existing = state.cart.find(item => item.productId === id);
  if (existing) {
    existing.qty += 1;
  } else {
    state.cart.push({ productId: id, qty: 1 });
  }
  saveState();
  updateBadgeCounts();
  openCartDrawer();
}

function updateBadgeCounts() {
  document.getElementById('wishlist-count').innerText = state.wishlist.length;
  const totalCartQty = state.cart.reduce((sum, i) => sum + i.qty, 0);
  document.getElementById('cart-count').innerText = totalCartQty;
  renderCompareTray();
}

// Product Compare Tray Management (up to 4 items)
function toggleCompareTray(id) {
  const index = state.compareTray.indexOf(id);
  if (index > -1) {
    state.compareTray.splice(index, 1);
  } else {
    if (state.compareTray.length >= 4) {
      alert('You can compare a maximum of 4 products side-by-side!');
      filterProducts();
      return;
    }
    state.compareTray.push(id);
  }
  saveState();
  renderCompareTray();
}

function clearCompareTray() {
  state.compareTray = [];
  saveState();
  renderCompareTray();
  filterProducts();
}

function renderCompareTray() {
  const bar = document.getElementById('compare-float-bar');
  const countText = document.getElementById('compare-count-text');
  const thumbsContainer = document.getElementById('compare-thumbs-container');

  if (state.compareTray.length === 0) {
    bar.classList.remove('show');
    return;
  }

  bar.classList.add('show');
  countText.innerText = `${state.compareTray.length} of 4 items selected`;

  thumbsContainer.innerHTML = state.compareTray.map(id => {
    const p = state.data.products.find(item => item.id === id);
    if (!p) return '';
    return `<div class="compare-thumb-item"><img src="${p.image}" title="${p.title}"></div>`;
  }).join('');
}

function openCompareModal() {
  if (state.compareTray.length === 0) {
    alert('Please select at least one product using the "Compare" checkbox on product cards!');
    return;
  }

  const container = document.getElementById('compare-matrix-content');
  const productsToCompare = state.compareTray.map(id => state.data.products.find(p => p.id === id)).filter(Boolean);

  container.innerHTML = productsToCompare.map(p => `
    <div class="compare-col">
      <img src="${p.image}" alt="${p.title}">
      <div class="compare-item-title">${p.title}</div>
      <div style="font-size: 1.2rem; font-weight: 800; color: var(--navy-900);">₹${p.price.toLocaleString('en-IN')}</div>
      
      <div class="compare-spec-row">
        <span class="compare-spec-label">SME Vendor</span>
        <strong>${p.vendorName}</strong>
      </div>

      <div class="compare-spec-row">
        <span class="compare-spec-label">Category</span>
        ${p.category}
      </div>

      <div class="compare-spec-row">
        <span class="compare-spec-label">Rating</span>
        <i class="fa-solid fa-star" style="color: var(--accent-gold);"></i> ${p.rating} (${p.ratingCount} reviews)
      </div>

      <div class="compare-spec-row">
        <span class="compare-spec-label">Stock Status</span>
        <span style="color: ${p.stock > 5 ? 'var(--accent-emerald)' : 'var(--accent-rose)'}; font-weight: 700;">
          ${p.stock > 0 ? `${p.stock} units available` : 'Out of Stock'}
        </span>
      </div>

      <div class="compare-spec-row">
        <span class="compare-spec-label">SKU Identifier</span>
        <code>${p.sku}</code>
      </div>

      <div class="compare-spec-row">
        <span class="compare-spec-label">Key Specifications</span>
        <ul style="padding-left: 1.1rem; margin-top: 0.25rem;">
          ${Object.entries(p.specs || {}).map(([k, v]) => `<li><strong>${k}:</strong> ${v}</li>`).join('')}
        </ul>
      </div>

      <button class="btn-primary" style="margin-top: 0.85rem; width: 100%; justify-content: center;" onclick="addToCart('${p.id}')">Add to Cart</button>
    </div>
  `).join('');

  openModal('modal-compare');
}

// Product Details Modal
function openProductDetail(id) {
  const p = state.data.products.find(item => item.id === id);
  if (!p) return;

  const content = document.getElementById('product-detail-content');
  const reviews = state.data.reviews.filter(r => r.productId === id);

  content.innerHTML = `
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
      <div>
        <img src="${p.image}" alt="${p.title}" style="width: 100%; height: 320px; object-fit: contain; background: #f8fafc; padding: 10px; border-radius: var(--radius-lg); border: 1px solid var(--border-light);">
      </div>

      <div>
        <span class="status-pill status-approved" style="margin-bottom: 0.5rem;"><i class="fa-solid fa-store"></i> Sold by ${p.vendorName}</span>
        <h2>${p.title}</h2>
        
        <div style="display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0 1rem;">
          <i class="fa-solid fa-star" style="color: var(--accent-gold);"></i>
          <span style="font-weight: 700;">${p.rating}</span>
          <span style="color: var(--text-muted);">(${p.ratingCount} customer ratings)</span>
          <span style="color: var(--border-focus);">|</span>
          <span style="font-size: 0.82rem; font-weight: 600; color: var(--navy-700);">SKU: <code>${p.sku}</code></span>
        </div>

        <div style="font-size: 2rem; font-weight: 800; color: var(--navy-900); margin-bottom: 1rem;">
          ₹${p.price.toLocaleString('en-IN')}
          <span style="font-size: 1rem; color: var(--text-muted); text-decoration: line-through; font-weight: 500;">₹${p.originalPrice.toLocaleString('en-IN')}</span>
        </div>

        <p style="color: var(--navy-700); font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.5rem;">${p.description}</p>

        <div style="background: #F8FAFC; border: 1px solid var(--border-light); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
          <h4 style="font-size: 0.88rem; margin-bottom: 0.5rem;">Specifications</h4>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.82rem;">
            ${Object.entries(p.specs || {}).map(([k, v]) => `<div><strong style="color: var(--navy-800);">${k}:</strong> ${v}</div>`).join('')}
          </div>
        </div>

        <div style="display: flex; gap: 1rem;">
          <button class="btn-primary" style="flex: 1; justify-content: center; padding: 0.85rem;" onclick="addToCart('${p.id}'); closeModal('modal-product-detail');">
            <i class="fa-solid fa-cart-plus"></i> Add to Basket
          </button>
        </div>
      </div>
    </div>

    <!-- Product Reviews Section -->
    <div style="margin-top: 2rem; border-top: 1px solid var(--border-light); padding-top: 1.5rem;">
      <h3><i class="fa-solid fa-comments text-primary"></i> Customer Reviews & Verified Ratings</h3>
      
      <div style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.85rem;">
        ${reviews.length === 0 ? '<p style="color: var(--text-muted); font-size: 0.88rem;">No reviews yet. Be the first verified buyer to leave feedback!</p>' : ''}
        ${reviews.map(r => `
          <div style="background: #F8FAFC; border: 1px solid var(--border-light); padding: 1rem; border-radius: var(--radius-md);">
            <div class="flex-between">
              <strong style="font-size: 0.9rem;">${r.customerName} <span class="status-pill status-approved" style="font-size: 0.68rem; margin-left: 0.4rem;">Verified Purchase</span></strong>
              <span style="font-size: 0.8rem; color: var(--accent-gold);"><i class="fa-solid fa-star"></i> ${r.rating}/5</span>
            </div>
            <p style="font-size: 0.88rem; margin-top: 0.35rem; color: var(--navy-800);">${r.comment}</p>
            ${r.vendorResponse ? `
              <div style="margin-top: 0.5rem; padding: 0.5rem 0.85rem; background: #FFF; border-left: 3px solid var(--primary-500); font-size: 0.8rem; color: var(--navy-900);">
                <strong>Vendor Response:</strong> ${r.vendorResponse}
              </div>
            ` : ''}
          </div>
        `).join('')}
      </div>
    </div>
  `;

  openModal('modal-product-detail');
}

// Cart Drawer & Checkout Engine
function openCartDrawer() {
  renderCartDrawer();
  openModal('modal-cart');
}

function renderCartDrawer() {
  const container = document.getElementById('cart-items-list');
  if (state.cart.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 2rem;">
        <i class="fa-solid fa-basket-shopping" style="font-size: 2.5rem; color: #CBD5E1;"></i>
        <p style="margin-top: 0.75rem; color: var(--text-muted); font-weight: 600;">Your shopping basket is currently empty.</p>
      </div>
    `;
    document.getElementById('cart-subtotal-display').innerText = '₹0';
    document.getElementById('cart-discount-display').innerText = '-₹0';
    document.getElementById('cart-total-display').innerText = '₹0';
    return;
  }

  let subtotal = 0;

  container.innerHTML = state.cart.map(item => {
    const p = state.data.products.find(x => x.id === item.productId);
    if (!p) return '';
    const itemTotal = p.price * item.qty;
    subtotal += itemTotal;

    return `
      <div style="display: flex; align-items: center; justify-content: space-between; background: #F8FAFC; border: 1px solid var(--border-light); padding: 0.85rem; border-radius: var(--radius-md);">
        <div style="display: flex; align-items: center; gap: 0.85rem;">
          <img src="${p.image}" alt="${p.title}" style="width: 50px; height: 50px; object-fit: cover; border-radius: var(--radius-sm);">
          <div>
            <div style="font-weight: 700; font-size: 0.9rem;">${p.title}</div>
            <div style="font-size: 0.78rem; color: var(--text-muted);">₹${p.price.toLocaleString('en-IN')} × ${item.qty}</div>
          </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <div style="display: flex; align-items: center; border: 1px solid var(--border-light); border-radius: var(--radius-sm); background: #FFF;">
            <button onclick="changeCartQty('${p.id}', -1)" style="padding: 0.2rem 0.5rem; font-weight: 700;">-</button>
            <span style="padding: 0.2rem 0.5rem; font-size: 0.85rem; font-weight: 700;">${item.qty}</span>
            <button onclick="changeCartQty('${p.id}', 1)" style="padding: 0.2rem 0.5rem; font-weight: 700;">+</button>
          </div>
          <span style="font-weight: 800; font-size: 0.95rem; width: 70px; text-align: right;">₹${itemTotal.toLocaleString('en-IN')}</span>
          <button onclick="removeCartItem('${p.id}')" style="color: var(--accent-rose); font-size: 0.9rem;"><i class="fa-solid fa-trash-can"></i></button>
        </div>
      </div>
    `;
  }).join('');

  const discountAmount = subtotal * state.appliedCouponRate;
  const grandTotal = subtotal - discountAmount;

  document.getElementById('cart-subtotal-display').innerText = `₹${subtotal.toLocaleString('en-IN')}`;
  document.getElementById('cart-discount-display').innerText = `-₹${discountAmount.toLocaleString('en-IN')}`;
  document.getElementById('cart-total-display').innerText = `₹${grandTotal.toLocaleString('en-IN')}`;
}

function changeCartQty(id, delta) {
  const item = state.cart.find(i => i.productId === id);
  if (!item) return;
  item.qty += delta;
  if (item.qty <= 0) {
    state.cart = state.cart.filter(i => i.productId !== id);
  }
  saveState();
  updateBadgeCounts();
  renderCartDrawer();
}

function removeCartItem(id) {
  state.cart = state.cart.filter(i => i.productId !== id);
  saveState();
  updateBadgeCounts();
  renderCartDrawer();
}

function applyCouponCode() {
  const input = document.getElementById('coupon-code-input').value.trim().toUpperCase();
  if (input === 'VYAPAR10') {
    state.appliedCouponRate = 0.10;
    alert('Coupon VYAPAR10 Applied! 10% Discount unlocked.');
  } else if (input === 'FESTIVE20') {
    state.appliedCouponRate = 0.20;
    alert('Coupon FESTIVE20 Applied! 20% Discount unlocked.');
  } else {
    alert('Invalid Coupon Code. Try "VYAPAR10" or "FESTIVE20".');
    state.appliedCouponRate = 0;
  }
  renderCartDrawer();
}

function openCheckoutModal() {
  if (state.cart.length === 0) {
    alert('Cart is empty!');
    return;
  }
  closeModal('modal-cart');
  openModal('modal-checkout');
}

// Order Placement & PDF Invoice Generator
function handlePlaceOrder(e) {
  e.preventDefault();
  
  const form = e.target;
  const name = form.querySelectorAll('input')[0].value;
  const address = form.querySelector('textarea').value;

  let subtotal = 0;
  const orderItems = state.cart.map(i => {
    const p = state.data.products.find(x => x.id === i.productId);
    const cost = p.price * i.qty;
    subtotal += cost;

    // Inventory Deduction
    p.stock = Math.max(0, p.stock - i.qty);

    return {
      productId: p.id,
      title: p.title,
      price: p.price,
      qty: i.qty,
      vendorId: p.vendorId,
      vendorName: p.vendorName
    };
  });

  const discount = subtotal * state.appliedCouponRate;
  const finalTotal = subtotal - discount;
  const newOrderId = `ORD-${Math.floor(10000 + Math.random() * 90000)}`;

  const newOrder = {
    id: newOrderId,
    customerName: name,
    address: address,
    items: orderItems,
    totalAmount: finalTotal,
    discount: discount,
    paymentMethod: 'UPI / Razorpay Instant',
    status: 'Order Placed',
    date: new Date().toISOString()
  };

  state.data.orders.unshift(newOrder);
  state.data.auditLogs.unshift(`Customer "${name}" placed order #${newOrderId} totaling ₹${finalTotal}.`);
  
  // Clear cart
  state.cart = [];
  state.appliedCouponRate = 0;
  saveState();
  updateBadgeCounts();
  filterProducts();

  closeModal('modal-checkout');
  renderInvoice(newOrder);
  openModal('modal-invoice');
}

// Render Printable PDF Invoice HTML
function renderInvoice(order) {
  const container = document.getElementById('invoice-render-box');
  
  container.innerHTML = `
    <div class="invoice-box">
      <div class="invoice-header">
        <div>
          <h1 style="color: var(--primary-600); font-size: 1.8rem; line-height: 1;">Vyapar Setu</h1>
          <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Centralized SME Marketplace Platform</p>
          <p style="font-size: 0.8rem; color: var(--navy-800); margin-top: 0.5rem;">GSTIN: 27AABCV9901M1Z4</p>
        </div>

        <div class="invoice-meta">
          <h2 style="font-size: 1.25rem; color: var(--navy-900);">TAX INVOICE</h2>
          <p style="font-size: 0.85rem; font-weight: 700; color: var(--primary-600);">Invoice ID: ${order.id}</p>
          <p style="font-size: 0.78rem; color: var(--text-muted);">Date: ${new Date(order.date).toLocaleDateString('en-IN')}</p>
        </div>
      </div>

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; font-size: 0.85rem; margin-bottom: 1.5rem;">
        <div style="background: #F8FAFC; padding: 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--border-light);">
          <strong style="display: block; color: var(--navy-900); margin-bottom: 0.25rem;">Billed To (Customer):</strong>
          <div>${order.customerName}</div>
          <div style="color: var(--text-muted); margin-top: 0.2rem;">${order.address}</div>
        </div>

        <div style="background: #F8FAFC; padding: 0.85rem; border-radius: var(--radius-md); border: 1px solid var(--border-light);">
          <strong style="display: block; color: var(--navy-900); margin-bottom: 0.25rem;">Fulfillment & Payment:</strong>
          <div>Payment Status: <span style="color: var(--accent-emerald); font-weight: 700;">PAID (${order.paymentMethod})</span></div>
          <div>Shipment Stage: <span class="status-pill status-processing" style="margin-top: 0.25rem;">${order.status}</span></div>
        </div>
      </div>

      <table class="invoice-table">
        <thead>
          <tr style="background: #F1F5F9; font-size: 0.82rem;">
            <th>Product Description</th>
            <th>Vendor Store</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th style="text-align: right;">Total (INR)</th>
          </tr>
        </thead>
        <tbody style="font-size: 0.85rem;">
          ${order.items.map(item => `
            <tr>
              <td><strong>${item.title}</strong></td>
              <td>${item.vendorName || 'SME Store'}</td>
              <td>${item.qty}</td>
              <td>₹${item.price.toLocaleString('en-IN')}</td>
              <td style="text-align: right; font-weight: 700;">₹${(item.price * item.qty).toLocaleString('en-IN')}</td>
            </tr>
          `).join('')}
        </tbody>
      </table>

      <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
        <div style="width: 250px; font-size: 0.88rem; display: flex; flex-direction: column; gap: 0.4rem;">
          <div class="flex-between"><span>Subtotal:</span><span>₹${(order.totalAmount + order.discount).toLocaleString('en-IN')}</span></div>
          ${order.discount > 0 ? `<div class="flex-between" style="color: var(--accent-emerald);"><span>Discount:</span><span>-₹${order.discount.toLocaleString('en-IN')}</span></div>` : ''}
          <div class="flex-between"><span>GST Tax (Included 18%):</span><span>₹${Math.round(order.totalAmount * 0.18).toLocaleString('en-IN')}</span></div>
          <div class="flex-between" style="font-size: 1.1rem; font-weight: 800; border-top: 2px solid var(--navy-900); padding-top: 0.5rem; color: var(--navy-900);">
            <span>Total Paid:</span><span>₹${order.totalAmount.toLocaleString('en-IN')}</span>
          </div>
        </div>
      </div>

      <div style="margin-top: 2rem; border-top: 1px dashed var(--border-light); padding-top: 1rem; text-align: center; font-size: 0.75rem; color: var(--text-muted);">
        This is a computer-generated invoice issued under Vyapar Setu Multi-Vendor E-Commerce Platform.
      </div>
    </div>
  `;
}

// Visual Order Shipment Tracker View
function openOrderTracker() {
  const container = document.getElementById('tracker-order-selector');
  if (state.data.orders.length === 0) {
    container.innerHTML = `<p style="color: var(--text-muted);">No orders found to track.</p>`;
    openModal('modal-order-tracker');
    return;
  }

  const latestOrder = state.data.orders[0];
  renderOrderTrackerDetails(latestOrder.id);
  openModal('modal-order-tracker');
}

function renderOrderTrackerDetails(orderId) {
  const order = state.data.orders.find(o => o.id === orderId);
  const container = document.getElementById('tracker-order-selector');
  if (!order) return;

  const stages = ['Order Placed', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered'];
  const currentIndex = stages.indexOf(order.status) !== -1 ? stages.indexOf(order.status) : 1;
  const progressPercent = (currentIndex / (stages.length - 1)) * 100;

  container.innerHTML = `
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem;">
      <label style="font-weight: 700; font-size: 0.88rem;">Select Order to Track:</label>
      <select class="sort-select" onchange="renderOrderTrackerDetails(this.value)">
        ${state.data.orders.map(o => `<option value="${o.id}" ${o.id === orderId ? 'selected' : ''}>${o.id} - ₹${o.totalAmount.toLocaleString('en-IN')} (${o.status})</option>`).join('')}
      </select>
    </div>

    <div style="background: #F8FAFC; border: 1px solid var(--border-light); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 1.5rem;">
      <!-- Pipeline Steps -->
      <div class="tracking-pipeline">
        <div class="tracking-pipeline-progress" style="width: ${progressPercent}%;"></div>
        
        ${stages.map((stg, idx) => {
          const isCompleted = idx < currentIndex;
          const isActive = idx === currentIndex;
          return `
            <div class="tracking-step ${isCompleted ? 'completed' : ''} ${isActive ? 'active' : ''}">
              <div class="step-node">
                <i class="fa-solid ${idx === 0 ? 'fa-receipt' : idx === 1 ? 'fa-box' : idx === 2 ? 'fa-truck' : idx === 3 ? 'fa-motorcycle' : 'fa-house-circle-check'}"></i>
              </div>
              <div class="step-title">${stg}</div>
            </div>
          `;
        }).join('')}
      </div>
    </div>

    <div style="background: #FFF; border: 1px solid var(--border-light); border-radius: var(--radius-md); padding: 1rem;">
      <h4 style="margin-bottom: 0.5rem;">Order Shipment Itemization</h4>
      <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.4rem; font-size: 0.85rem;">
        ${order.items.map(i => `<li><i class="fa-solid fa-angle-right text-primary"></i> ${i.title} (Qty: ${i.qty}) - Vendor: <strong>${i.vendorName || 'SME Seller'}</strong></li>`).join('')}
      </ul>
      <div style="margin-top: 0.75rem; font-size: 0.82rem; color: var(--text-muted);">
        Shipping Address: <strong>${order.address}</strong>
      </div>
    </div>
  `;
}

// Vendor Dashboard Management Console
function switchVendorTab(tab) {
  document.querySelectorAll('.dash-menu-item').forEach(el => el.classList.remove('active'));
  event.currentTarget.classList.add('active');

  document.getElementById('vendor-tab-overview').style.display = tab === 'overview' ? 'block' : 'none';
  document.getElementById('vendor-tab-products').style.display = tab === 'products' ? 'block' : 'none';
  document.getElementById('vendor-tab-orders').style.display = tab === 'orders' ? 'block' : 'none';
  document.getElementById('vendor-tab-reviews').style.display = tab === 'reviews' ? 'block' : 'none';

  renderVendorDashboard();
}

function renderVendorDashboard() {
  const vendorId = 'v1'; // TechCraft Electronics Persona
  const myProducts = state.data.products.filter(p => p.vendorId === vendorId);
  
  // Stats
  document.getElementById('vendor-stat-products').innerText = myProducts.length;

  // Products Table
  const pTable = document.getElementById('vendor-products-tbody');
  pTable.innerHTML = myProducts.map(p => `
    <tr>
      <td>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
          <img src="${p.image}" alt="" style="width: 36px; height: 36px; object-fit: cover; border-radius: 4px;">
          <strong>${p.title}</strong>
        </div>
      </td>
      <td><code>${p.sku}</code></td>
      <td>${p.category}</td>
      <td>₹${p.price.toLocaleString('en-IN')}</td>
      <td>
        <span style="color: ${p.stock < 5 ? 'var(--accent-rose)' : 'var(--navy-900)'}; font-weight: 700;">
          ${p.stock} units
        </span>
      </td>
      <td><i class="fa-solid fa-star" style="color: var(--accent-gold);"></i> ${p.rating}</td>
      <td>
        <button class="btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" onclick="openEditProductModal('${p.id}')"><i class="fa-solid fa-pen"></i> Edit</button>
        <button style="color: var(--accent-rose); font-size: 0.85rem; margin-left: 0.4rem;" onclick="deleteProduct('${p.id}')"><i class="fa-solid fa-trash"></i></button>
      </td>
    </tr>
  `).join('');

  // Orders Table for Vendor
  const vendorOrders = state.data.orders.filter(o => o.items.some(i => i.vendorId === vendorId));
  const oTable = document.getElementById('vendor-orders-tbody');
  oTable.innerHTML = vendorOrders.map(o => `
    <tr>
      <td><strong>${o.id}</strong></td>
      <td>${o.customerName}</td>
      <td>${o.items.map(i => i.title).join(', ')}</td>
      <td>₹${o.totalAmount.toLocaleString('en-IN')}</td>
      <td><span class="status-pill status-processing">${o.status}</span></td>
      <td>
        <select class="sort-select" style="padding: 0.25rem 0.5rem; font-size: 0.78rem;" onchange="updateOrderStatus('${o.id}', this.value)">
          <option value="Order Placed" ${o.status === 'Order Placed' ? 'selected' : ''}>Order Placed</option>
          <option value="Processing" ${o.status === 'Processing' ? 'selected' : ''}>Processing</option>
          <option value="Shipped" ${o.status === 'Shipped' ? 'selected' : ''}>Shipped</option>
          <option value="Out for Delivery" ${o.status === 'Out for Delivery' ? 'selected' : ''}>Out for Delivery</option>
          <option value="Delivered" ${o.status === 'Delivered' ? 'selected' : ''}>Delivered</option>
        </select>
      </td>
    </tr>
  `).join('');

  // Reviews
  const reviewsContainer = document.getElementById('vendor-reviews-container');
  const myReviews = state.data.reviews;
  reviewsContainer.innerHTML = myReviews.map(r => `
    <div style="background: #F8FAFC; border: 1px solid var(--border-light); padding: 1rem; border-radius: var(--radius-md);">
      <div class="flex-between">
        <strong>${r.customerName} - Rated ${r.rating}/5 <i class="fa-solid fa-star" style="color: var(--accent-gold);"></i></strong>
        <span style="font-size: 0.78rem; color: var(--text-muted);">${r.date}</span>
      </div>
      <p style="font-size: 0.88rem; margin: 0.4rem 0;">"${r.comment}"</p>
      ${r.vendorResponse ? `
        <div style="font-size: 0.8rem; background: #FFF; padding: 0.5rem; border-radius: 4px; border-left: 3px solid var(--primary-500);">
          Your Store Reply: ${r.vendorResponse}
        </div>
      ` : `
        <button class="btn-outline" style="font-size: 0.75rem; padding: 0.2rem 0.5rem;" onclick="replyToReview('${r.id}')">Reply to Buyer</button>
      `}
    </div>
  `).join('');
}

function updateOrderStatus(orderId, newStatus) {
  const order = state.data.orders.find(o => o.id === orderId);
  if (order) {
    order.status = newStatus;
    state.data.auditLogs.unshift(`Vendor updated order #${orderId} status to "${newStatus}".`);
    saveState();
    renderVendorDashboard();
    alert(`Order status updated to "${newStatus}"!`);
  }
}

function replyToReview(reviewId) {
  const response = prompt('Enter your vendor response to the customer feedback:');
  if (response) {
    const rev = state.data.reviews.find(r => r.id === reviewId);
    if (rev) {
      rev.vendorResponse = response;
      saveState();
      renderVendorDashboard();
    }
  }
}

// Vendor Product CRUD
function openAddProductModal() {
  document.getElementById('crud-modal-title').innerText = 'Add New Product Listing';
  document.getElementById('crud-p-id').value = '';
  document.getElementById('product-crud-form').reset();
  openModal('modal-product-crud');
}

function openEditProductModal(id) {
  const p = state.data.products.find(x => x.id === id);
  if (!p) return;

  document.getElementById('crud-modal-title').innerText = 'Edit Product Listing';
  document.getElementById('crud-p-id').value = p.id;
  document.getElementById('crud-p-title').value = p.title;
  document.getElementById('crud-p-cat').value = p.category;
  document.getElementById('crud-p-price').value = p.price;
  document.getElementById('crud-p-stock').value = p.stock;
  document.getElementById('crud-p-image').value = p.image;
  document.getElementById('crud-p-desc').value = p.description;

  openModal('modal-product-crud');
}

function handleSaveProduct(e) {
  e.preventDefault();
  const id = document.getElementById('crud-p-id').value;
  const title = document.getElementById('crud-p-title').value;
  const cat = document.getElementById('crud-p-cat').value;
  const price = parseFloat(document.getElementById('crud-p-price').value);
  const stock = parseInt(document.getElementById('crud-p-stock').value);
  const image = document.getElementById('crud-p-image').value;
  const desc = document.getElementById('crud-p-desc').value;

  if (id) {
    // Edit existing
    const p = state.data.products.find(x => x.id === id);
    if (p) {
      p.title = title;
      p.category = cat;
      p.price = price;
      p.stock = stock;
      p.image = image;
      p.description = desc;
    }
  } else {
    // Create new
    const newId = `p${Date.now()}`;
    state.data.products.unshift({
      id: newId,
      title: title,
      vendorId: 'v1',
      vendorName: 'TechCraft Electronics',
      category: cat,
      price: price,
      originalPrice: price * 1.3,
      stock: stock,
      sku: `TC-NEW-${Math.floor(10 + Math.random() * 90)}`,
      rating: 5.0,
      ratingCount: 1,
      spotlight: 'REGULAR',
      image: image,
      description: desc,
      specs: { 'Status': 'Fresh Listing', 'Warranty': '1 Year' }
    });
    state.data.auditLogs.unshift(`Vendor "TechCraft Electronics" added new product listing "${title}".`);
  }

  saveState();
  closeModal('modal-product-crud');
  renderVendorDashboard();
  filterProducts();
  alert('Product catalog successfully saved!');
}

function deleteProduct(id) {
  if (confirm('Delete this product from catalog?')) {
    state.data.products = state.data.products.filter(p => p.id !== id);
    saveState();
    renderVendorDashboard();
    filterProducts();
  }
}

// Vendor Registration Application
function openVendorRegistrationModal() {
  openModal('modal-vendor-reg');
}

function handleVendorRegistration(e) {
  e.preventDefault();
  const name = document.getElementById('v-reg-name').value;
  const cat = document.getElementById('v-reg-cat').value;
  const gst = document.getElementById('v-reg-gst').value;
  const email = document.getElementById('v-reg-email').value;

  const newVendor = {
    id: `v${Date.now()}`,
    name: name,
    owner: 'Applicant',
    gst: gst,
    email: email,
    category: cat,
    rating: 5.0,
    reviewsCount: 0,
    status: 'PENDING',
    commission: '10%'
  };

  state.data.vendors.push(newVendor);
  state.data.auditLogs.unshift(`New vendor registration submitted by "${name}" (GSTIN: ${gst}).`);
  saveState();
  closeModal('modal-vendor-reg');
  alert('Your SME Vendor Registration application has been submitted to Admin Governance for document verification!');
}

// Admin Governance Portal
function switchAdminTab(tab) {
  document.getElementById('admin-tab-vendors').style.display = tab === 'vendors' ? 'block' : 'none';
  document.getElementById('admin-tab-products').style.display = tab === 'products' ? 'block' : 'none';
  document.getElementById('admin-tab-audit').style.display = tab === 'audit' ? 'block' : 'none';
  renderAdminPortal();
}

function renderAdminPortal() {
  // GMV & Commission Calculation
  let gmv = 0;
  state.data.orders.forEach(o => gmv += o.totalAmount);
  const commission = gmv * 0.10;

  document.getElementById('admin-total-gmv').innerText = `₹${gmv.toLocaleString('en-IN')}`;
  document.getElementById('admin-commission-pool').innerText = `₹${commission.toLocaleString('en-IN')}`;
  
  const activeCount = state.data.vendors.filter(v => v.status === 'APPROVED').length;
  const pendingCount = state.data.vendors.filter(v => v.status === 'PENDING').length;

  document.getElementById('admin-active-vendors').innerText = `${activeCount} Active`;
  document.getElementById('admin-pending-vendors').innerText = `${pendingCount} Pending`;
  document.getElementById('admin-total-orders').innerText = state.data.orders.length;

  // Vendors Table
  const vTable = document.getElementById('admin-vendors-tbody');
  vTable.innerHTML = state.data.vendors.map(v => `
    <tr>
      <td><strong>${v.name}</strong></td>
      <td>${v.owner}</td>
      <td>${v.category}</td>
      <td><code>${v.gst}</code></td>
      <td>${v.status === 'APPROVED' ? '2026-07-25' : 'Just Now'}</td>
      <td><span class="status-pill ${v.status === 'APPROVED' ? 'status-approved' : 'status-pending'}">${v.status}</span></td>
      <td>
        ${v.status === 'PENDING' ? `
          <button class="btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" onclick="approveVendor('${v.id}')"><i class="fa-solid fa-check"></i> Approve Store</button>
          <button style="color: var(--accent-rose); font-size: 0.8rem; margin-left: 0.4rem;" onclick="rejectVendor('${v.id}')">Reject</button>
        ` : `
          <span style="font-size: 0.8rem; color: var(--accent-emerald); font-weight: 700;"><i class="fa-solid fa-shield-check"></i> Verified</span>
        `}
      </td>
    </tr>
  `).join('');

  // Products Moderation Table
  const pTable = document.getElementById('admin-products-tbody');
  pTable.innerHTML = state.data.products.map(p => `
    <tr>
      <td><strong>${p.title}</strong></td>
      <td>${p.vendorName}</td>
      <td>₹${p.price.toLocaleString('en-IN')}</td>
      <td>${p.stock}</td>
      <td><span class="status-pill ${p.hidden ? 'status-rejected' : 'status-approved'}">${p.hidden ? 'Hidden / Overruled' : 'Active Public'}</span></td>
      <td>
        <button class="btn-secondary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;" onclick="toggleProductVisibility('${p.id}')">
          <i class="fa-solid ${p.hidden ? 'fa-eye' : 'fa-eye-slash'}"></i> ${p.hidden ? 'Unhide' : 'Overrule / Hide'}
        </button>
      </td>
    </tr>
  `).join('');

  // Audit Logs
  const auditList = document.getElementById('admin-audit-logs');
  auditList.innerHTML = state.data.auditLogs.map(log => `
    <li style="background: #F8FAFC; border-left: 3px solid var(--navy-900); padding: 0.5rem 0.85rem; border-radius: 4px;">
      <i class="fa-solid fa-terminal text-primary"></i> ${log}
    </li>
  `).join('');
}

function approveVendor(id) {
  const v = state.data.vendors.find(x => x.id === id);
  if (v) {
    v.status = 'APPROVED';
    state.data.auditLogs.unshift(`Admin approved SME vendor store "${v.name}".`);
    saveState();
    renderAdminPortal();
    renderCategoryPills();
    alert(`Vendor "${v.name}" has been approved!`);
  }
}

function rejectVendor(id) {
  state.data.vendors = state.data.vendors.filter(x => x.id !== id);
  saveState();
  renderAdminPortal();
}

function toggleProductVisibility(id) {
  const p = state.data.products.find(x => x.id === id);
  if (p) {
    p.hidden = !p.hidden;
    state.data.auditLogs.unshift(`Admin ${p.hidden ? 'hidden' : 'unhidden'} product listing "${p.title}".`);
    saveState();
    renderAdminPortal();
    filterProducts();
  }
}

// Modal Helpers
function openModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.add('open');
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (el) el.classList.remove('open');
}

function scrollToProducts() {
  const el = document.getElementById('products-section');
  if (el) el.scrollIntoView({ behavior: 'smooth' });
}
