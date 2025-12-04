@extends('admin.AdminDashboard')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('page-content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="sales-header">
        <h2>Sales Report</h2>
        <div style="display: flex; gap: 15px; align-items: center;">
            <div class="date-filter">
                <input type="date" class="date-input" id="start-date">
                <span>to</span>
                <input type="date" class="date-input" id="end-date">
                <button class="filter-btn" onclick="applyCustomFilter()">
                    <i class="material-icons">filter_list</i> Apply
                </button>
            </div>
            <button class="export-btn" onclick="window.print()">
                <i class="material-icons">print</i> Print Report
            </button>
        </div>
    </div>

    <div class="time-filters">
        <button class="time-filter-btn active" onclick="filterRange('7', this)">Last 7 Days</button>
        <button class="time-filter-btn" onclick="filterRange('30', this)">Last 30 Days</button>
        <button class="time-filter-btn" onclick="filterRange('90', this)">Last 90 Days</button>
        <button class="time-filter-btn" onclick="filterRange('365', this)">Last Year</button>
    </div>

    <div class="sales-overview">
        <div class="sales-card revenue">
            <h3 id="stat-revenue">Loading...</h3>
            <p>Total Revenue</p>
            <div class="trend up">
                <i class="material-icons">monetization_on</i>
                <span>Completed orders</span>
            </div>
        </div>
        <div class="sales-card orders">
            <h3 id="stat-orders">Loading...</h3>
            <p>Total Orders</p>
            <div class="trend up">
                <i class="material-icons">shopping_cart</i>
                <span>All statuses</span>
            </div>
        </div>
        <div class="sales-card average">
            <h3 id="stat-average">Loading...</h3>
            <p>Average Order Value</p>
            <div class="trend up">
                <i class="material-icons">analytics</i>
                <span>Per transaction</span>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-card">
            <h3>Revenue Trend</h3>
            <div class="chart-placeholder">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="chart-card">
            <h3>Order Status Distribution</h3>
            <div class="chart-placeholder" style="max-height: 300px; display:flex; justify-content:center;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="top-items">
        <h3>Top Selling Items</h3>
        <table class="orders-table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Earnings</th>
                </tr>
            </thead>
            <tbody id="top-items-list">
                </tbody>
        </table>
    </div>

    <style>
        .chart-placeholder { position: relative; height: 300px; width: 100%; }
        .time-filters { margin: 20px 0; display: flex; gap: 10px; }
        .time-filter-btn { padding: 8px 16px; border: 1px solid #ddd; background: white; cursor: pointer; border-radius: 4px; }
        .time-filter-btn.active { background: #e67e22; color: white; border-color: #e67e22; }
    </style>

    <script>
        let revenueChartInstance = null;
        let statusChartInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            fetchData('7'); // Load last 7 days by default
        });

        function filterRange(range, btn) {
            // UI Update
            document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Clear date inputs to show we are using preset
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';

            fetchData(range);
        }

        function applyCustomFilter() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;
            
            if(!start || !end) {
                alert('Please select both start and end dates');
                return;
            }

            document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
            fetchData('custom', start, end);
        }

        function fetchData(range, start = null, end = null) {
            let url = `/admin/sales/json?range=${range}`;
            if(range === 'custom') {
                url += `&start_date=${start}&end_date=${end}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    updateKPIs(data);
                    updateCharts(data);
                    updateTopItems(data.top_items);
                })
                .catch(err => console.error(err));
        }

        function updateKPIs(data) {
            document.getElementById('stat-revenue').textContent = '₱' + parseFloat(data.revenue).toLocaleString(undefined, {minimumFractionDigits: 2});
            document.getElementById('stat-orders').textContent = data.orders;
            document.getElementById('stat-average').textContent = '₱' + parseFloat(data.average).toLocaleString(undefined, {minimumFractionDigits: 2});
        }

        function updateTopItems(items) {
            const tbody = document.getElementById('top-items-list');
            tbody.innerHTML = '';

            if(items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" style="text-align:center">No sales in this period</td></tr>';
                return;
            }

            items.forEach(item => {
                tbody.innerHTML += `
                    <tr>
                        <td style="font-weight:bold">${item.name}</td>
                        <td>${item.total_sold} units</td>
                        <td>₱${parseFloat(item.total_earned).toFixed(2)}</td>
                    </tr>
                `;
            });
        }

        function updateCharts(data) {
            // 1. REVENUE CHART (Line)
            const ctxRev = document.getElementById('revenueChart').getContext('2d');
            
            if (revenueChartInstance) revenueChartInstance.destroy();

            revenueChartInstance = new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: data.trend.map(item => item.date),
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: data.trend.map(item => item.total),
                        borderColor: '#e67e22',
                        backgroundColor: 'rgba(230, 126, 34, 0.2)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // 2. STATUS CHART (Pie)
            const ctxStatus = document.getElementById('statusChart').getContext('2d');
            
            if (statusChartInstance) statusChartInstance.destroy();

            // Prepare Pie Data
            const labels = data.status_dist.map(i => i.status.toUpperCase());
            const counts = data.status_dist.map(i => i.count);
            const colors = {
                'DELIVERED': '#10b981',
                'PENDING': '#f59e0b',
                'CANCELLED': '#ef4444',
                'PREPARING': '#3b82f6',
                'READY': '#8b5cf6'
            };
            const bgColors = labels.map(l => colors[l] || '#999');

            statusChartInstance = new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: counts,
                        backgroundColor: bgColors
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        }
    </script>
@endsection