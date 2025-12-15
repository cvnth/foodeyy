@extends('admin.AdminDashboard')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('page-content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="printable-area">

        <div class="print-header">
            <div class="header-content">
                <div class="logo-section">
                    <h1>SUROY SURIGAO</h1>
                    <p>Regional Sales Management Dashboard</p>
                </div>
                <div class="meta-section">
                    <div class="meta-box">
                        <span class="label">Generated On:</span>
                        <span class="value" id="print-date"></span>
                    </div>
                    <div class="meta-box">
                        <span class="label">Period:</span>
                        <span class="value" id="print-range">Last 7 Days</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sales-header no-print">
            <h2>Sales Overview</h2>
            <div style="display: flex; gap: 15px; align-items: center;">
                <div class="date-filter">
                    <input type="date" class="date-input" id="start-date">
                    <span>to</span>
                    <input type="date" class="date-input" id="end-date">
                    <button class="filter-btn" onclick="applyCustomFilter()">
                        <i class="material-icons">filter_list</i> Apply
                    </button>
                </div>
                <button class="export-btn" onclick="prepareAndPrint()">
                    <i class="material-icons">print</i> Print Report
                </button>
            </div>
        </div>

        <div class="time-filters no-print">
            <button class="time-filter-btn active" onclick="filterRange('7', this)">Last 7 Days</button>
            <button class="time-filter-btn" onclick="filterRange('30', this)">Last 30 Days</button>
            <button class="time-filter-btn" onclick="filterRange('90', this)">Last 90 Days</button>
            <button class="time-filter-btn" onclick="filterRange('365', this)">Last Year</button>
        </div>

        <div class="sales-overview">
            <div class="sales-card revenue">
                <div class="card-content">
                    <p class="card-title">Total Revenue</p>
                    <h3 id="stat-revenue">Loading...</h3>
                    <div class="trend up no-print">
                        <i class="material-icons">monetization_on</i> <span>Completed orders</span>
                    </div>
                    <div class="print-subtext">Sales Target Progress</div>
                </div>
                <div class="card-icon no-print"><i class="material-icons">payments</i></div>
            </div>

            <div class="sales-card orders">
                 <div class="card-content">
                    <p class="card-title">Total Orders</p>
                    <h3 id="stat-orders">Loading...</h3>
                    <div class="trend up no-print">
                        <i class="material-icons">shopping_cart</i> <span>All statuses</span>
                    </div>
                    <div class="print-subtext">Volume Analysis</div>
                 </div>
                 <div class="card-icon no-print"><i class="material-icons">shopping_bag</i></div>
            </div>

            <div class="sales-card average">
                 <div class="card-content">
                    <p class="card-title">Avg. Order Value</p>
                    <h3 id="stat-average">Loading...</h3>
                    <div class="trend up no-print">
                        <i class="material-icons">analytics</i> <span>Per transaction</span>
                    </div>
                    <div class="print-subtext">Transaction Value</div>
                 </div>
                 <div class="card-icon no-print"><i class="material-icons">trending_up</i></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="chart-card main-chart">
                <h3>Revenue Trend Analysis</h3>
                <div class="chart-placeholder">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
            <div class="chart-card side-chart">
                <h3>Order Status Distribution</h3>
                <div class="chart-placeholder pie-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="top-items">
            <h3>Top Selling Items Detail</h3>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity Sold</th>
                        <th>Total Earnings</th>
                    </tr>
                </thead>
                <tbody id="top-items-list"></tbody>
            </table>
        </div>


    <script src="{{ asset('js/admin-sales.js') }}"></script>
@endsection