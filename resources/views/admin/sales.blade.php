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

    </div>

    <style>
        .sales-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .date-input { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .filter-btn, .export-btn { padding: 8px 15px; border: none; cursor: pointer; border-radius: 4px; display: flex; align-items: center; gap: 5px; font-weight: bold; }
        .filter-btn { background: #333; color: white; }
        .export-btn { background: #fff; border: 1px solid #ddd; color: #333; }
        .time-filters { margin-bottom: 25px; display: flex; gap: 10px; }
        .time-filter-btn { padding: 8px 16px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 20px; transition: 0.2s; }
        .time-filter-btn.active { background: #e67e22; color: white; border-color: #e67e22; }

        .sales-overview { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 30px; }
        .sales-card { 
            background: white; padding: 20px; border-radius: 8px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee;
            display: flex; justify-content: space-between; align-items: start;
        }
        .sales-card h3 { font-size: 1.8rem; margin: 5px 0; color: #333; }
        .sales-card p { color: #666; margin: 0; font-size: 0.9rem; }
        .card-icon i { font-size: 40px; color: #eee; }
        .trend { display: flex; align-items: center; gap: 5px; margin-top: 10px; font-size: 0.85rem; color: #10b981; }

        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px; }
        .chart-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .chart-placeholder { position: relative; height: 300px; width: 100%; }
        .pie-container { display: flex; justify-content: center; }

        .top-items { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .orders-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .orders-table th { text-align: left; padding: 12px; background: #f8f9fa; border-bottom: 2px solid #eee; color: #444; }
        .orders-table td { padding: 12px; border-bottom: 1px solid #eee; }

        .print-header, .print-subtext { display: none; }

        @media print {
            @page { margin: 0.5cm; size: A4 landscape; }
            body * { visibility: hidden; }
            .printable-area, .printable-area * { visibility: visible; }
            .printable-area {
                position: absolute; left: 0; top: 0; width: 100%;
                background-color: #f0f2f5; padding: 10px;
                -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
            }
            .no-print { display: none !important; }
            .print-header { 
                display: block !important; background: white; padding: 15px 25px; 
                margin-bottom: 20px; border-bottom: 1px solid #ddd;
            }
            .header-content { display: flex; justify-content: space-between; align-items: center; }
            .logo-section h1 { margin: 0; color: #1a237e; font-size: 24px; font-weight: 800; letter-spacing: 1px; }
            .logo-section p { margin: 0; color: #666; font-size: 14px; }
            .meta-section { display: flex; gap: 20px; }
            .meta-box { background: #f8f9fa; padding: 8px 15px; border-radius: 6px; border: 1px solid #eee; }
            .meta-box .label { display: block; font-size: 10px; color: #888; text-transform: uppercase; }
            .meta-box .value { color: #333; font-weight: bold; font-size: 14px; }

            .sales-overview { display: grid !important; grid-template-columns: 1fr 1fr 1fr !important; gap: 15px !important; margin-bottom: 20px !important; }
            .sales-card { border: none !important; border-radius: 8px !important; padding: 20px !important; color: white !important; box-shadow: none !important; }
            .sales-card.revenue { background: linear-gradient(135deg, #1565C0 0%, #1E88E5 100%) !important; }
            .sales-card.orders { background: linear-gradient(135deg, #E65100 0%, #F57C00 100%) !important; }
            .sales-card.average { background: linear-gradient(135deg, #F9A825 0%, #FBC02D 100%) !important; }
            .sales-card h3 { color: white !important; font-size: 2.2rem !important; margin: 10px 0 !important; }
            .sales-card p.card-title { color: rgba(255,255,255,0.9) !important; font-size: 1rem !important; font-weight: bold; }
            .print-subtext { display: block !important; font-size: 0.8rem; opacity: 0.8; margin-top: 5px; }

            .dashboard-grid { display: grid !important; grid-template-columns: 2fr 1fr !important; gap: 15px !important; margin-bottom: 20px !important; }
            .chart-card { background: white !important; border: 1px solid #ddd !important; border-top: 4px solid #1565C0 !important; padding: 15px !important; page-break-inside: avoid; }
            .chart-card h3 { font-size: 14px !important; color: #333 !important; margin-top: 0; margin-bottom: 15px; padding-left: 10px; border-left: 4px solid #1565C0; }
            .chart-placeholder { height: 250px !important; }

            .top-items { background: white !important; border: 1px solid #ddd !important; border-top: 4px solid #1565C0 !important; padding: 0 !important; page-break-inside: avoid; }
            .top-items h3 { font-size: 14px !important; margin: 15px; padding-left: 10px; border-left: 4px solid #1565C0; }
            .orders-table th { background-color: #f1f5f9 !important; color: #333 !important; font-size: 12px; font-weight: bold; }
            .orders-table td { font-size: 12px; border-bottom: 1px solid #eee !important; padding: 8px !important; }
            .orders-table tr:nth-child(even) { background-color: #fafafa !important; }
        }
    </style>

    <script src="{{ asset('js/admin-sales.js') }}"></script>
@endsection