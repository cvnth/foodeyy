@extends('admin.AdminDashboard')

@section('title', 'Sales Report')
@section('page-title', 'Sales Report')

@section('page-content')
    <div class="sales-header">
        <h2>Sales Report</h2>
        <div style="display: flex; gap: 15px; align-items: center;">
            <div class="date-filter">
                <input type="date" class="date-input" id="start-date">
                <span>to</span>
                <input type="date" class="date-input" id="end-date">
                <button class="filter-btn" onclick="applyDateFilter()">
                    <i class="material-icons">filter_list</i>
                    Apply Filter
                </button>
            </div>
            <button class="export-btn" onclick="exportReport()">
                <i class="material-icons">download</i>
                Export Report
            </button>
        </div>
    </div>

    <div class="time-filters">
        <button class="time-filter-btn active" data-range="7">Last 7 Days</button>
        <button class="time-filter-btn" data-range="30">Last 30 Days</button>
        <button class="time-filter-btn" data-range="90">Last 90 Days</button>
        <button class="time-filter-btn" data-range="365">Last Year</button>
        <button class="time-filter-btn" data-range="custom">Custom Range</button>
    </div>

    <div class="sales-overview">
        <div class="sales-card revenue">
            <h3>₱45,680</h3>
            <p>Total Revenue</p>
            <div class="trend up">
                <i class="material-icons">arrow_upward</i>
                <span>15.2% from last period</span>
            </div>
        </div>
        <div class="sales-card orders">
            <h3>234</h3>
            <p>Total Orders</p>
            <div class="trend up">
                <i class="material-icons">arrow_upward</i>
                <span>8.7% from last period</span>
            </div>
        </div>
        <div class="sales-card average">
            <h3>₱195.21</h3>
            <p>Average Order Value</p>
            <div class="trend up">
                <i class="material-icons">arrow_upward</i>
                <span>6.1% from last period</span>
            </div>
        </div>
        <div class="sales-card growth">
            <h3>24.8%</h3>
            <p>Revenue Growth</p>
            <div class="trend up">
                <i class="material-icons">arrow_upward</i>
                <span>+3.2% from last month</span>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="chart-card">
            <h3>Revenue Trend</h3>
            <div class="chart-placeholder">
                <div style="text-align: center;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">bar_chart</i>
                    <p>Revenue Chart Visualization</p>
                    <small>This would show daily/weekly revenue trends</small>
                </div>
            </div>
        </div>
        <div class="chart-card">
            <h3>Order Status Distribution</h3>
            <div class="chart-placeholder">
                <div style="text-align: center;">
                    <i class="material-icons" style="font-size: 48px; margin-bottom: 10px;">pie_chart</i>
                    <p>Order Status Pie Chart</p>
                    <small>Showing completed, pending, cancelled orders</small>
                </div>
            </div>
        </div>
    </div>

    <div class="top-items">
        <h3>Top Selling Items</h3>
        <div class="items-list" id="top-items-list">
            </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('admin/js/admin-sales.js') }}"></script>
@endpush