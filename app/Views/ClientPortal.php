  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>LARGA Client Portal | Unified Shipment Dashboard</title>
    <link rel="shortcut icon" type="image/png" href="<?=base_url('assets/images/logos/largaicon.png')?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
  <style>
      * { margin: 0; padding: 0; box-sizing: border-box; }
      
      :root {
        --bg-primary: #F5F7FA;
        --bg-white: #FFFFFF;
        --card-white: #FFFFFF;
        --blue-light: #E8F0FE;
        --blue-primary: #2A7DE1;
        --blue-dark: #1A5BBF;
        --blue-soft: #D6E6F9;
        --gray-50: #F8F9FC;
        --gray-100: #F0F2F5;
        --gray-200: #E4E7EC;
        --gray-300: #D0D5DD;
        --gray-500: #667085;
        --gray-700: #344054;
        --gray-900: #1A2C3E;
        --success: #10B981;
        --warning: #F59E0B;
        --danger: #EF4444;
        --info: #3B82F6;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        --border: #E4E7EC;
      }
      
      body {
        font-family: 'Inter', sans-serif;
        background: var(--bg-primary);
        color: var(--gray-900);
        min-height: 100vh;
      }
      
      .dashboard-container { position: relative; z-index: 2; min-height: 100vh; }
      
      .dashboard-header {
        background: var(--bg-white);
        padding: 1rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
        flex-wrap: wrap;
        gap: 1rem;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: var(--shadow-sm);
      }
      
      .logo-area { display: flex; align-items: center; gap: 12px; }
      .logo-area i { font-size: 28px; color: var(--blue-primary); }
      .logo-area h2 { font-size: 1.3rem; font-weight: 700; color: var(--gray-900); }
      .logo-area span { color: var(--blue-primary); }
      
      .client-info-area { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; }
      .client-badge { background: var(--blue-light); padding: 0.4rem 1.2rem; border-radius: 40px; font-size: 0.8rem; color: var(--blue-dark); font-weight: 500; }
      .trust-badge { background: var(--gray-100); padding: 0.4rem 1rem; border-radius: 40px; font-size: 0.7rem; color: var(--gray-700); }
      .logout-btn {
        background: var(--gray-100);
        border: 1px solid var(--border);
        padding: 0.5rem 1.2rem;
        border-radius: 40px;
        cursor: pointer;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--danger);
      }
      .logout-btn:hover { background: var(--gray-200); }
      
      .main-content { padding: 1.8rem 2rem; max-width: 1600px; margin: 0 auto; }
      
      .welcome-section {
        background: linear-gradient(135deg, var(--blue-primary) 0%, var(--blue-dark) 100%);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        color: white;
      }
      .welcome-section h1 { font-size: 1.6rem; margin-bottom: 0.3rem; color: white; }
      .welcome-section h1 i { margin-right: 10px; }
      .welcome-section p { opacity: 0.9; font-size: 0.9rem; }
      
      .workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
      }
      
      .workflow-card {
        background: var(--card-white);
        border-radius: 16px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
      }
      .workflow-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
      
      .card-header {
        padding: 1rem 1.2rem;
        background: var(--gray-50);
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
      }
      .card-header h3 {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gray-900);
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .card-header h3 i { color: var(--blue-primary); font-size: 1rem; }
      .badge-count {
        background: var(--blue-light);
        color: var(--blue-primary);
        padding: 2px 10px;
        border-radius: 30px;
        font-size: 0.65rem;
        font-weight: 600;
      }
      
      .card-body { padding: 0.8rem 1.2rem; }
      
      .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.75rem;
      }
      .data-table th {
        text-align: left;
        padding: 0.75rem 0.5rem;
        color: var(--gray-500);
        font-weight: 600;
        border-bottom: 1px solid var(--border);
        background: var(--gray-50);
      }
      .data-table td {
        padding: 0.7rem 0.5rem;
        border-bottom: 1px solid var(--gray-100);
        color: var(--gray-700);
      }
      .data-table tr:hover td { background-color: var(--blue-light); }
      .data-table tr.missing-doc-row { background-color: rgba(239, 68, 68, 0.05); }
      
      .status-badge-sm {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 0.65rem;
        font-weight: 600;
      }
      .status-in-transit { background: var(--blue-light); color: var(--blue-primary); }
      .status-delivered { background: #D1FAE5; color: var(--success); }
      .status-pending { background: #FEF3C7; color: var(--warning); }
      .payment-paid { background: #D1FAE5; color: var(--success); }
      .payment-unpaid { background: #FEE2E2; color: var(--danger); }
      .payment-pending { background: #FEF3C7; color: var(--warning); }
      .missing-doc-badge { background: #FEE2E2; color: var(--danger); padding: 2px 6px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; display: inline-block; }
      .doc-ok-badge { background: #D1FAE5; color: var(--success); padding: 2px 6px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; display: inline-block; }
      .paid-badge { background: #D1FAE5; color: var(--success); padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; display: inline-block; }
      .unpaid-badge { background: #FEE2E2; color: var(--danger); padding: 2px 8px; border-radius: 20px; font-size: 0.6rem; font-weight: 600; display: inline-block; }
      
      .filter-row {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
        align-items: center;
      }
      .filter-select, .filter-input {
        padding: 0.4rem 0.8rem;
        border: 1px solid var(--border);
        border-radius: 30px;
        font-size: 0.7rem;
        background: var(--bg-white);
        color: var(--gray-700);
        cursor: pointer;
      }
      .filter-input { cursor: text; }
      .btn-primary-sm {
        background: var(--blue-primary);
        color: white;
        border: none;
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        cursor: pointer;
        font-weight: 500;
      }
      .btn-icon {
        background: var(--gray-100);
        border: 1px solid var(--border);
        padding: 0.4rem 1rem;
        border-radius: 30px;
        font-size: 0.7rem;
        cursor: pointer;
        font-weight: 500;
      }
      
      .map-container {
        background: var(--gray-100);
        border-radius: 12px;
        height: 180px;
        position: relative;
        overflow: hidden;
        margin-bottom: 0.8rem;
      }
      .mock-map {
        width: 100%;
        height: 100%;
        background: linear-gradient(145deg, #E8F0FE, #D6E6F9);
        position: relative;
      }
      .route-line {
        position: absolute;
        top: 50%;
        left: 10%;
        width: 80%;
        height: 2px;
        background: var(--blue-primary);
        transform: translateY(-50%);
      }
      .gps-marker {
        position: absolute;
        width: 20px;
        height: 20px;
        background: var(--blue-primary);
        border-radius: 50%;
        border: 2px solid white;
        transform: translate(-50%, -50%);
        animation: pulse 1.5s infinite;
      }
      @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(42, 125, 225, 0.5); }
        50% { box-shadow: 0 0 0 10px rgba(42, 125, 225, 0); }
      }
      
      .chat-messages {
        max-height: 180px;
        overflow-y: auto;
        margin-bottom: 0.8rem;
      }
      .chat-message {
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.75rem;
      }
      .chat-message strong { color: var(--blue-primary); }
      .chat-input {
        display: flex;
        gap: 8px;
      }
      .chat-input input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid var(--border);
        border-radius: 30px;
        font-size: 0.7rem;
        outline: none;
      }
      .chat-input button {
        background: var(--blue-primary);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 30px;
        color: white;
        cursor: pointer;
      }
      
      .audit-item {
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.7rem;
        display: flex;
        justify-content: space-between;
      }
      .audit-time { color: var(--gray-500); font-size: 0.65rem; }
      
      .notification-item {
        padding: 0.6rem;
        margin-bottom: 0.5rem;
        border-radius: 10px;
        background: var(--gray-50);
        border-left: 3px solid var(--blue-primary);
        font-size: 0.7rem;
      }
      .notification-delivery { border-left-color: var(--success); }
      .notification-delay { border-left-color: var(--danger); }
      .notification-shipment { border-left-color: var(--info); }
      
      .payable-badge { background-color: #FEF3C7; color: var(--warning); padding: 2px 8px; border-radius: 20px; font-size: 0.65rem; font-weight: 600; }
      .doc-ok { background-color: #D1FAE5; color: var(--success); padding: 2px 6px; border-radius: 4px; font-size: 0.65rem; display: inline-block; }
      .missing-doc { background-color: #FEE2E2; color: var(--danger); padding: 2px 6px; border-radius: 4px; font-size: 0.65rem; display: inline-block; }
      
      .table-wrapper { overflow-x: auto; }
      .filter-info { font-size: 0.7rem; color: var(--gray-500); margin-left: auto; }
      
      @media (max-width: 800px) { .workflow-grid { grid-template-columns: 1fr; } .main-content { padding: 1rem; } }
      
      ::-webkit-scrollbar { width: 6px; height: 6px; }
      ::-webkit-scrollbar-track { background: var(--gray-100); border-radius: 4px; }
      ::-webkit-scrollbar-thumb { background: var(--gray-300); border-radius: 4px; }
  </style>
  </head>
  <body>

  <div class="dashboard-container">
    <div class="dashboard-header">
      <div class="logo-area">
        <i class="fas fa-boxes"></i>
        <h2>LARGA <span>Client Portal</span></h2>
      </div>
      <div class="client-info-area">
        <div class="trust-badge"><i class="fas fa-brain"></i> AI Trust Score: 96% | <span id="liveTime"></span></div>
        <div class="client-badge"><i class="fas fa-building"></i> TransGlobal Logistics Ltd.</div>
        <div class="client-badge"><i class="fas fa-user"></i> Michael Chen</div>
        <form action="<?=site_url();?>mylogout" method="post" id="logoutForm" style="display: inline;">
          <button type="submit" class="logout-btn">
              <i class="fas fa-sign-out-alt"></i> Logout
          </button>
      </form>
      </div>
    </div>

    <div class="main-content">
      <div class="welcome-section">
        <h1><i class="fas fa-chart-line"></i> Logistics Command Center</h1>
        <p>Real-time visibility | Predictive analytics | Document compliance | End-to-end tracking</p>
      </div>

      <!-- Unified Single Data Table - 1st 10 Shipments -->
      <div class="workflow-card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
          <h3><i class="fas fa-table-list"></i> 1st 10 Shipments | Complete View (ETA • Status • Payment • Docs • Delivery • Paid)</h3>
          <span class="badge-count">Unified Dashboard</span>
        </div>
        <div class="card-body">
          <div class="filter-row">
            <select id="unifiedFilterSelect" class="filter-select">
              <option value="all">📋 All Shipments</option>
              <option value="status_eta">🚢 ETA & Status</option>
              <option value="payment">💰 Payment Status</option>
              <option value="missing_docs">⚠️ Missing Documents</option>
              <option value="delivery">📦 Delivery Schedule</option>
              <option value="already_paid">✅ Already Paid</option>
            </select>
            <button class="btn-primary-sm" onclick="applyUnifiedFilter()"><i class="fas fa-filter"></i> Apply Filter</button>
            <button class="btn-icon" onclick="exportUnifiedToCSV()"><i class="fas fa-file-excel"></i> Export CSV</button>
            <button class="btn-icon" onclick="alert('PDF Export - Unified Report')"><i class="fas fa-file-pdf"></i> PDF</button>
            <div class="filter-info" id="filterIndicator">Showing: All Shipments</div>
          </div>
          
          <div class="table-wrapper">
            <table class="data-table" id="unifiedShipmentTable" style="width: 100%; min-width: 1000px;">
              <thead>
                <tr><th>Shipment ID</th><th>ETA (Earliest → Current)</th><th>Status</th><th>Payment Status</th><th>Documents</th><th>Expected Delivery</th><th>Already Paid?</th><th>Route</th></tr>
              </thead>
              <tbody id="unifiedTableBody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- GPS + Audit Trail Row -->
      <div class="workflow-grid">
        <div class="workflow-card">
          <div class="card-header">
            <h3><i class="fas fa-map-marker-alt"></i> Live GPS Tracking | Shipment Visibility</h3>
            <span class="badge-count"><i class="fas fa-satellite-dish"></i> Real-time</span>
          </div>
          <div class="card-body">
            <div class="map-container">
              <div class="mock-map">
                <div class="route-line"></div>
                <div id="gpsMarker" class="gps-marker" style="left: 35%; top: 50%;"></div>
              </div>
            </div>
            <div style="font-size: 0.7rem; text-align: center; color: var(--gray-500);">
              <i class="fas fa-location-dot"></i> Current: Indian Ocean | ETA: Apr 18, 2026
            </div>
          </div>
        </div>
        
        <div class="workflow-card">
          <div class="card-header">
            <h3><i class="fas fa-history"></i> Audit Trail | Shipment Events</h3>
            <span class="badge-count">Live Feed</span>
          </div>
          <div class="card-body">
            <div id="shipmentAuditTrail"></div>
          </div>
        </div>
      </div>

      <!-- Instant Chat per Transaction -->
      <div class="workflow-grid">
        <div class="workflow-card">
          <div class="card-header">
            <h3><i class="fas fa-comments"></i> Instant Chat | Per Transaction</h3>
            <span class="badge-count">LGA-4219</span>
          </div>
          <div class="card-body">
            <div class="chat-messages" id="chatMessages">
              <div class="chat-message"><strong>Support:</strong> Your shipment is on schedule</div>
              <div class="chat-message"><strong>You:</strong> Any update on docs?</div>
              <div class="chat-message"><strong>Support:</strong> Docs received, processing clearance</div>
            </div>
            <div class="chat-input">
              <input type="text" placeholder="Type message..." id="chatInput">
              <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
            </div>
          </div>
        </div>
      </div>

      <!-- Transactions Job Order Filter - Complete Table -->
      <div class="workflow-card" style="margin-bottom: 1.5rem;">
        <div class="card-header">
          <h3><i class="fas fa-table-list"></i> Transactions | Job Order Filter (ETA / Transaction / Goods / Routing)</h3>
          <span class="badge-count">Latest to Oldest</span>
        </div>
        <div class="card-body">
          <div class="filter-row">
            <select id="joFilterType" class="filter-select">
              <option value="all">All Transactions</option>
              <option value="eta">Filter by ETA</option>
              <option value="transaction">Filter by Transaction ID</option>
              <option value="goods">Filter by Goods Type</option>
              <option value="routing">Filter by Routing</option>
            </select>
            <input type="text" id="joFilterValue" placeholder="Enter value..." class="filter-input">
            <select id="dateFilter" class="filter-select">
              <option value="all">All Dates</option>
              <option value="today">Today</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
            </select>
            <button class="btn-primary-sm" onclick="applyJOFilters()"><i class="fas fa-filter"></i> Apply Filters</button>
            <button class="btn-icon" onclick="exportTransactionsCSV()"><i class="fas fa-file-excel"></i> Export CSV</button>
          </div>
          <div class="table-wrapper">
            <table class="data-table" id="transactionsTable" style="width: 100%; min-width: 1200px;">
              <thead>
                <tr><th>Date</th><th>Job Order</th><th>Transaction ID</th><th>Goods Type</th><th>Routing</th><th>ETA</th><th>Amount</th><th>Payment Status</th><th>Quotation</th><th>OR</th><th>Docs Status</th><th>Payables</th></tr>
              </thead>
              <tbody id="transactionsBody"></tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Reports & Analysis + Transaction Comparison -->
      <div class="workflow-grid">
        <div class="workflow-card">
          <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> Reports & Analysis</h3>
            <span class="badge-count">Flexible Conditions</span>
          </div>
          <div class="card-body">
            <div class="filter-row" style="margin-bottom: 1rem;">
              <select class="filter-select" id="reportCondition">
                <option value="eta">By ETA</option>
                <option value="transaction">By Transaction</option>
                <option value="goods">By Goods</option>
                <option value="routing">By Routing</option>
                <option value="payment">By Payment Status</option>
              </select>
              <button class="btn-primary-sm" onclick="generateReport()">Generate Report</button>
            </div>
            <div id="reportPreview" style="font-size: 0.75rem; color: var(--gray-700);">
              OTIF: 94% | Avg Delay: 1.2 days | Top Issue: Port Congestion
            </div>
          </div>
        </div>
        
        <div class="workflow-card">
          <div class="card-header">
            <h3><i class="fas fa-chart-line"></i> Transaction Comparison</h3>
            <span class="badge-count">Period-over-Period</span>
          </div>
          <div class="card-body">
            <div style="display: flex; gap: 1rem; justify-content: space-between; font-size: 0.75rem;">
              <div>This Month: <strong>$4.28M</strong></div>
              <div>Last Month: <strong>$3.95M</strong></div>
              <div style="color: var(--success);">+8.3%</div>
            </div>
            <div style="margin-top: 0.8rem; background: var(--gray-100); border-radius: 8px; height: 4px;">
              <div style="width: 68%; background: var(--blue-primary); height: 4px; border-radius: 8px;"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Notifications Section -->
      <div class="workflow-card">
        <div class="card-header">
          <h3><i class="fas fa-bell"></i> Notifications | Audit Trail Alerts</h3>
          <span class="badge-count">Filterable</span>
        </div>
        <div class="card-body">
          <div class="filter-row">
            <select id="notificationFilter" class="filter-select">
              <option value="all">All Notifications</option>
              <option value="shipment">Shipment Notifications</option>
              <option value="delivery">Delivery Alerts</option>
              <option value="delay">Delay Alerts</option>
            </select>
            <input type="date" id="notificationDateFilter" class="filter-input">
            <input type="text" id="notificationTransactionFilter" placeholder="Filter by Transaction ID..." class="filter-input">
            <button class="btn-primary-sm" onclick="applyNotificationFilters()"><i class="fas fa-filter"></i> Filter</button>
          </div>
          <div id="notificationsList" style="max-height: 250px; overflow-y: auto;"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
  // Unified Shipment Data
  const unifiedShipments = [
    { id: "LGA-101", eta: "2026-05-14", etaDisplay: "May 14, 2026 (Today)", status: "in_transit", paymentStatus: "Pending", docs: "missing", deliveryDate: "May 14, 2026", paid: false, route: "Shanghai → Singapore" },
    { id: "LGA-102", eta: "2026-05-14", etaDisplay: "May 14, 2026 (Today)", status: "in_transit", paymentStatus: "Pending", docs: "complete", deliveryDate: "May 15, 2026", paid: false, route: "Ningbo → Hamburg" },
    { id: "LGA-103", eta: "2026-05-15", etaDisplay: "May 15, 2026", status: "pending", paymentStatus: "Unpaid", docs: "missing", deliveryDate: "May 16, 2026", paid: false, route: "Busan → Los Angeles" },
    { id: "LGA-104", eta: "2026-05-15", etaDisplay: "May 15, 2026", status: "delivered", paymentStatus: "Paid", docs: "complete", deliveryDate: "May 13, 2026", paid: true, route: "Rotterdam → New York" },
    { id: "LGA-105", eta: "2026-05-16", etaDisplay: "May 16, 2026", status: "in_transit", paymentStatus: "Pending", docs: "missing", deliveryDate: "May 17, 2026", paid: false, route: "Jebel Ali → Felixstowe" },
    { id: "LGA-106", eta: "2026-05-16", etaDisplay: "May 16, 2026", status: "in_transit", paymentStatus: "Paid", docs: "complete", deliveryDate: "May 16, 2026", paid: true, route: "Tokyo → Sydney" },
    { id: "LGA-107", eta: "2026-05-17", etaDisplay: "May 17, 2026", status: "pending", paymentStatus: "Unpaid", docs: "missing", deliveryDate: "May 19, 2026", paid: false, route: "Colombo → Rotterdam" },
    { id: "LGA-108", eta: "2026-05-17", etaDisplay: "May 17, 2026", status: "delivered", paymentStatus: "Paid", docs: "complete", deliveryDate: "May 16, 2026", paid: true, route: "Santos → Miami" },
    { id: "LGA-109", eta: "2026-05-18", etaDisplay: "May 18, 2026", status: "in_transit", paymentStatus: "Pending", docs: "complete", deliveryDate: "May 19, 2026", paid: false, route: "Singapore → Brisbane" },
    { id: "LGA-110", eta: "2026-05-18", etaDisplay: "May 18, 2026", status: "in_transit", paymentStatus: "Unpaid", docs: "missing", deliveryDate: "May 20, 2026", paid: false, route: "Manila → Vancouver" }
  ];

  const sortedUnified = [...unifiedShipments].sort((a,b) => new Date(a.eta) - new Date(b.eta));

  function getStatusBadge(status) {
    if (status === 'in_transit') return '<span class="status-badge-sm status-in-transit"><i class="fas fa-ship"></i> In Transit</span>';
    if (status === 'delivered') return '<span class="status-badge-sm status-delivered"><i class="fas fa-check-circle"></i> Delivered</span>';
    return '<span class="status-badge-sm status-pending"><i class="fas fa-clock"></i> Pending</span>';
  }

  function getPaymentBadge(paymentStatus, paid) {
    if (paid === true || paymentStatus === 'Paid') return '<span class="status-badge-sm payment-paid"><i class="fas fa-check-circle"></i> Paid</span>';
    if (paymentStatus === 'Unpaid') return '<span class="status-badge-sm payment-unpaid"><i class="fas fa-exclamation-circle"></i> Unpaid</span>';
    return '<span class="status-badge-sm payment-pending"><i class="fas fa-hourglass-half"></i> Pending</span>';
  }

  function getDocsBadge(docs) {
    if (docs === 'missing') return '<span class="missing-doc-badge"><i class="fas fa-exclamation-triangle"></i> Missing</span>';
    return '<span class="doc-ok-badge"><i class="fas fa-check-circle"></i> Complete</span>';
  }

  function getPaidBadge(paid) {
    if (paid) return '<span class="paid-badge"><i class="fas fa-check-circle"></i> Yes</span>';
    return '<span class="unpaid-badge"><i class="fas fa-hourglass-half"></i> No</span>';
  }

  let currentFilter = 'all';

  function renderUnifiedTable() {
    const tbody = document.getElementById('unifiedTableBody');
    let filtered = [...sortedUnified];
    let filterLabel = 'All Shipments';
    
    switch(currentFilter) {
      case 'status_eta': filterLabel = 'ETA & Status View'; break;
      case 'payment': filtered = filtered.filter(s => s.paymentStatus === 'Unpaid' || s.paymentStatus === 'Pending'); filterLabel = 'Payment Status (Unpaid/Pending)'; break;
      case 'missing_docs': filtered = filtered.filter(s => s.docs === 'missing'); filterLabel = '⚠️ Missing Documents'; break;
      case 'delivery': filterLabel = 'Delivery Schedule (All)'; break;
      case 'already_paid': filtered = filtered.filter(s => s.paid === true); filterLabel = '✅ Already Paid'; break;
      default: filterLabel = 'All Shipments';
    }
    
    document.getElementById('filterIndicator').innerHTML = `Showing: ${filterLabel} (${filtered.length} of ${sortedUnified.length})`;
    
    if (filtered.length === 0) {
      tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:2rem;">No shipments match this filter.</td></tr>';
      return;
    }
    
    let html = '';
    for (let i = 0; i < filtered.length; i++) {
      const ship = filtered[i];
      const rowClass = ship.docs === 'missing' ? 'class="missing-doc-row"' : '';
      html += '<tr ' + rowClass + '>' +
        '<td><strong>' + ship.id + '</strong></td>' +
        '<td><i class="far fa-calendar-alt"></i> ' + ship.etaDisplay + '</td>' +
        '<td>' + getStatusBadge(ship.status) + '</td>' +
        '<td>' + getPaymentBadge(ship.paymentStatus, ship.paid) + '</td>' +
        '<td>' + getDocsBadge(ship.docs) + '</td>' +
        '<td><i class="fas fa-truck"></i> ' + ship.deliveryDate + '</td>' +
        '<td>' + getPaidBadge(ship.paid) + '</td>' +
        '<td><i class="fas fa-route"></i> ' + ship.route + '</td>' +
        '</tr>';
    }
    tbody.innerHTML = html;
  }

  function applyUnifiedFilter() {
    const select = document.getElementById('unifiedFilterSelect');
    currentFilter = select.value;
    renderUnifiedTable();
  }

  function exportUnifiedToCSV() {
    let filtered = [...sortedUnified];
    switch(currentFilter) {
      case 'payment': filtered = filtered.filter(s => s.paymentStatus === 'Unpaid' || s.paymentStatus === 'Pending'); break;
      case 'missing_docs': filtered = filtered.filter(s => s.docs === 'missing'); break;
      case 'already_paid': filtered = filtered.filter(s => s.paid === true); break;
      default: break;
    }
    const headers = ['Shipment ID', 'ETA', 'Status', 'Payment Status', 'Documents', 'Expected Delivery', 'Already Paid', 'Route'];
    const rows = filtered.map(s => [s.id, s.etaDisplay, s.status, s.paid ? 'Paid' : (s.paymentStatus === 'Paid' ? 'Paid' : s.paymentStatus), s.docs === 'missing' ? 'Missing' : 'Complete', s.deliveryDate, s.paid ? 'Yes' : 'No', s.route]);
    let csv = headers.join(',') + '\n';
    rows.forEach(r => { csv += r.map(cell => '"' + String(cell).replace(/"/g, '""') + '"').join(',') + '\n'; });
    const blob = new Blob([csv], {type: 'text/csv'});
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'unified_shipment_report.csv';
    link.click();
    URL.revokeObjectURL(link.href);
  }

  // Audit Trail
  var auditEvents = [
    { time: "10:32 AM", event: "📦 Shipment LGA-4219 arrived at Indian Ocean" },
    { time: "09:15 AM", event: "⚠️ Delay alert: LGA-0023 weather delay +6hrs" },
    { time: "08:00 AM", event: "✅ Delivery alert: LGA-8472 completed" },
    { time: "Yesterday", event: "📄 Document missing: LGA-3391 requires Bill of Lading" }
  ];

  function renderShipmentAudit() {
    var container = document.getElementById('shipmentAuditTrail');
    var html = '';
    for (var i = 0; i < auditEvents.length; i++) {
      html += '<div class="audit-item"><span>' + auditEvents[i].event + '</span><span class="audit-time">' + auditEvents[i].time + '</span></div>';
    }
    container.innerHTML = html;
  }

  // Transactions Data
  var allTransactions = [
    { date: "May 18, 2026", jobOrder: "JO-2341", transId: "LGA-2341", goods: "Electronics", routing: "Singapore → Brisbane", eta: "May 19, 2026", amount: 7600, payment: "Pending", quotation: "—", or: "—", docs: "complete", payables: 0 },
    { date: "May 17, 2026", jobOrder: "JO-9923", transId: "LGA-9923", goods: "Machinery", routing: "Jebel Ali → Felixstowe", eta: "May 17, 2026", amount: 20300, payment: "Pending", quotation: "—", or: "OR-9923", docs: "complete", payables: 20300 },
    { date: "May 16, 2026", jobOrder: "JO-4456", transId: "LGA-4456", goods: "Textiles", routing: "Busan → Los Angeles", eta: "May 25, 2026", amount: 9450, payment: "Unpaid", quotation: "—", or: "—", docs: "missing", payables: 9450 },
    { date: "May 15, 2026", jobOrder: "JO-7783", transId: "LGA-7783", goods: "Auto Parts", routing: "Rotterdam → New York", eta: "May 20, 2026", amount: 11200, payment: "Pending", quotation: "QTA-7783", or: "—", docs: "complete", payables: 11200 },
    { date: "May 14, 2026", jobOrder: "JO-5621", transId: "LGA-5621", goods: "Medical", routing: "Shanghai → Hamburg", eta: "May 18, 2026", amount: 18900, payment: "Pending", quotation: "—", or: "—", docs: "complete", payables: 18900 },
    { date: "May 13, 2026", jobOrder: "JO-9074", transId: "LGA-9074", goods: "Food", routing: "Lima → Miami", eta: "Apr 09, 2026", amount: 6700, payment: "Paid", quotation: "—", or: "OR-9074", docs: "complete", payables: 0 },
    { date: "May 12, 2026", jobOrder: "JO-3391", transId: "LGA-3391", goods: "Chemicals", routing: "Gothenburg → Chicago", eta: "Pending", amount: 22100, payment: "Unpaid", quotation: "—", or: "—", docs: "missing", payables: 22100 },
    { date: "May 11, 2026", jobOrder: "JO-0023", transId: "LGA-0023", goods: "Pharma", routing: "Alexandria → Valencia", eta: "Apr 15, 2026", amount: 15400, payment: "Pending", quotation: "QTA-0023", or: "—", docs: "missing", payables: 15400 },
    { date: "May 10, 2026", jobOrder: "JO-8472", transId: "LGA-8472", goods: "Retail", routing: "Mumbai → Rotterdam", eta: "Apr 08, 2026", amount: 8900, payment: "Paid", quotation: "—", or: "—", docs: "complete", payables: 0 },
    { date: "May 9, 2026", jobOrder: "JO-4219", transId: "LGA-4219", goods: "Electronics", routing: "Shanghai → Singapore", eta: "Apr 16, 2026", amount: 12500, payment: "Pending", quotation: "—", or: "—", docs: "missing", payables: 12500 }
  ];

  function renderTransactions() {
    var tbody = document.getElementById('transactionsBody');
    var sorted = allTransactions.slice().sort(function(a,b) { return new Date(b.date) - new Date(a.date); });
    var html = '';
    for (var i = 0; i < sorted.length; i++) {
      var t = sorted[i];
      var paymentClass = t.payment === 'Paid' ? 'payment-paid' : (t.payment === 'Unpaid' ? 'payment-unpaid' : 'payment-pending');
      var docsHtml = t.docs === 'complete' ? '<span class="doc-ok">✓ Available</span>' : '<span class="missing-doc">⚠️ Missing</span>';
      var payablesHtml = t.payables > 0 ? '<span class="payable-badge">₱' + t.payables.toLocaleString() + '</span>' : '—';
      html += '<tr>' +
        '<td>' + t.date + '</td>' +
        '<td><strong>' + t.jobOrder + '</strong></td>' +
        '<td>' + t.transId + '</td>' +
        '<td>' + t.goods + '</td>' +
        '<td>' + t.routing + '</td>' +
        '<td>' + t.eta + '</td>' +
        '<td>₱' + t.amount.toLocaleString() + '</td>' +
        '<td><span class="status-badge-sm ' + paymentClass + '">' + t.payment + '</span></td>' +
        '<td>' + t.quotation + '</td>' +
        '<td>' + t.or + '</td>' +
        '<td>' + docsHtml + '</td>' +
        '<td>' + payablesHtml + '</td>' +
        '</tr>';
    }
    tbody.innerHTML = html;
  }

  function applyJOFilters() {
    var filterType = document.getElementById('joFilterType').value;
    var filterValue = document.getElementById('joFilterValue').value.toLowerCase();
    var dateFilter = document.getElementById('dateFilter').value;
    var filtered = allTransactions.slice();
    
    if (filterValue) {
      if (filterType === 'eta') {
        filtered = filtered.filter(function(t) { return t.eta.toLowerCase().indexOf(filterValue) !== -1; });
      } else if (filterType === 'transaction') {
        filtered = filtered.filter(function(t) { return t.transId.toLowerCase().indexOf(filterValue) !== -1; });
      } else if (filterType === 'goods') {
        filtered = filtered.filter(function(t) { return t.goods.toLowerCase().indexOf(filterValue) !== -1; });
      } else if (filterType === 'routing') {
        filtered = filtered.filter(function(t) { return t.routing.toLowerCase().indexOf(filterValue) !== -1; });
      }
    }
    
    if (dateFilter === 'today') {
      filtered = filtered.filter(function(t) { return t.date.indexOf('May 18') !== -1; });
    } else if (dateFilter === 'week') {
      filtered = filtered.filter(function(t) { return parseInt(t.date.split(' ')[1]) >= 12; });
    } else if (dateFilter === 'month') {
      filtered = filtered.filter(function(t) { return t.date.indexOf('May') !== -1; });
    }
    
    var tbody = document.getElementById('transactionsBody');
    var html = '';
    for (var i = 0; i < filtered.length; i++) {
      var t = filtered[i];
      var paymentClass = t.payment === 'Paid' ? 'payment-paid' : (t.payment === 'Unpaid' ? 'payment-unpaid' : 'payment-pending');
      var docsHtml = t.docs === 'complete' ? '<span class="doc-ok">✓ Available</span>' : '<span class="missing-doc">⚠️ Missing</span>';
      var payablesHtml = t.payables > 0 ? '<span class="payable-badge">₱' + t.payables.toLocaleString() + '</span>' : '—';
      html += '<tr>' +
        '<td>' + t.date + '</td>' +
        '<td><strong>' + t.jobOrder + '</strong></td>' +
        '<td>' + t.transId + '</td>' +
        '<td>' + t.goods + '</td>' +
        '<td>' + t.routing + '</td>' +
        '<td>' + t.eta + '</td>' +
        '<td>₱' + t.amount.toLocaleString() + '</td>' +
        '<td><span class="status-badge-sm ' + paymentClass + '">' + t.payment + '</span></td>' +
        '<td>' + t.quotation + '</td>' +
        '<td>' + t.or + '</td>' +
        '<td>' + docsHtml + '</td>' +
        '<td>' + payablesHtml + '</td>' +
        '</tr>';
    }
    tbody.innerHTML = html;
  }

  // Notifications
  var notifications = [
    { id: 1, date: "May 18, 2026", transactionId: "LGA-4219", type: "shipment", message: "Shipment arrived at Indian Ocean - on schedule" },
    { id: 2, date: "May 18, 2026", transactionId: "LGA-0023", type: "delay", message: "Weather delay detected +6 hours" },
    { id: 3, date: "May 17, 2026", transactionId: "LGA-8472", type: "delivery", message: "Delivery completed successfully" },
    { id: 4, date: "May 16, 2026", transactionId: "LGA-3391", type: "shipment", message: "Document missing - Bill of Lading required" },
    { id: 5, date: "May 15, 2026", transactionId: "LGA-5621", type: "shipment", message: "Vessel departed from Shanghai" },
    { id: 6, date: "May 14, 2026", transactionId: "LGA-7783", type: "delay", message: "Port congestion - ETA may be delayed" }
  ];

  function renderNotifications() {
    var filterType = document.getElementById('notificationFilter').value;
    var dateFilter = document.getElementById('notificationDateFilter').value;
    var transFilter = document.getElementById('notificationTransactionFilter').value.toLowerCase();
    var filtered = notifications.slice();
    
    if (filterType !== 'all') {
      filtered = filtered.filter(function(n) { return n.type === filterType; });
    }
    if (dateFilter) {
      filtered = filtered.filter(function(n) { return n.date === dateFilter; });
    }
    if (transFilter) {
      filtered = filtered.filter(function(n) { return n.transactionId.toLowerCase().indexOf(transFilter) !== -1; });
    }
    
    var container = document.getElementById('notificationsList');
    var html = '';
    for (var i = 0; i < filtered.length; i++) {
      var n = filtered[i];
      var typeClass = n.type === 'delivery' ? 'notification-delivery' : (n.type === 'delay' ? 'notification-delay' : 'notification-shipment');
      var iconClass = n.type === 'delivery' ? 'fa-check-circle' : (n.type === 'delay' ? 'fa-exclamation-triangle' : 'fa-ship');
      var typeText = n.type === 'delivery' ? 'Delivery Alert' : (n.type === 'delay' ? 'Delay Alert' : 'Shipment Notification');
      html += '<div class="notification-item ' + typeClass + '">' +
        '<div style="display: flex; justify-content: space-between;"><strong>' + n.transactionId + '</strong><span style="font-size:0.6rem; color:var(--gray-500);">' + n.date + '</span></div>' +
        '<div style="margin-top: 4px;">' + n.message + '</div>' +
        '<div style="margin-top: 4px; font-size:0.6rem;"><i class="fas ' + iconClass + '"></i> ' + typeText + '</div>' +
        '</div>';
    }
    container.innerHTML = html;
  }

  function applyNotificationFilters() { renderNotifications(); }
  function exportTransactionsCSV() { alert('CSV Export would download all transaction data'); }
  function generateReport() { alert('Report generated with condition: ' + document.getElementById('reportCondition').value); }

  function sendMessage() {
    var input = document.getElementById('chatInput');
    if (input.value.trim()) {
      var chatDiv = document.getElementById('chatMessages');
      chatDiv.innerHTML += '<div class="chat-message"><strong>You:</strong> ' + input.value + '</div>';
      input.value = '';
      chatDiv.scrollTop = chatDiv.scrollHeight;
    }
  }

  function updateLiveTime() {
    var now = new Date();
    document.getElementById('liveTime').innerText = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  }
  setInterval(updateLiveTime, 1000);
  updateLiveTime();

  var gpsProgress = 0.35;
  setInterval(function() {
    gpsProgress += 0.005;
    if (gpsProgress > 0.92) gpsProgress = 0.35;
    var marker = document.getElementById('gpsMarker');
    if (marker) marker.style.left = (10 + gpsProgress * 80) + '%';
  }, 2000);

  // Initialize all
  renderUnifiedTable();
  renderShipmentAudit();
  renderTransactions();
  renderNotifications();
  </script>

  </body>
  </html>