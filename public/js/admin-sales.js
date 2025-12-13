// public/js/admin-sales.js

let revenueChartInstance = null;
let statusChartInstance = null;
let currentRangeLabel = 'Last 7 Days';

document.addEventListener('DOMContentLoaded', () => {
    // Set current date for print header
    const printDateEl = document.getElementById('print-date');
    if(printDateEl) printDateEl.innerText = new Date().toLocaleDateString();
    
    // Initial fetch
    fetchData('7'); // Changed to 7 to match default active button
});

function prepareAndPrint() {
    document.getElementById('print-range').innerText = currentRangeLabel;
    window.print();
}

function filterRange(range, btn) {
    document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentRangeLabel = btn.innerText;
    document.getElementById('start-date').value = '';
    document.getElementById('end-date').value = '';
    fetchData(range);
}

function applyCustomFilter() {
    const start = document.getElementById('start-date').value;
    const end = document.getElementById('end-date').value;
    if(!start || !end) { alert('Select start and end dates'); return; }
    currentRangeLabel = `${start} to ${end}`;
    document.querySelectorAll('.time-filter-btn').forEach(b => b.classList.remove('active'));
    fetchData('custom', start, end);
}

function fetchData(range, start = null, end = null) {
    let url = `/admin/sales/json?range=${range}`;
    if(range === 'custom') url += `&start_date=${start}&end_date=${end}`;

    // Show loading state
    document.getElementById('stat-revenue').innerText = 'Loading...';
    
    fetch(url)
        .then(res => res.json())
        .then(data => {
            updateKPIs(data);
            updateCharts(data);
            updateTopItems(data.top_items);
        })
        .catch(err => console.error("Error fetching sales data:", err));
}

function updateKPIs(data) {
    document.getElementById('stat-revenue').innerText = '₱' + parseFloat(data.revenue).toLocaleString(undefined, {minimumFractionDigits: 2});
    document.getElementById('stat-orders').innerText = data.orders;
    document.getElementById('stat-average').innerText = '₱' + parseFloat(data.average).toLocaleString(undefined, {minimumFractionDigits: 2});
}

function updateTopItems(items) {
    const tbody = document.getElementById('top-items-list');
    tbody.innerHTML = '';
    if(!items || !items.length) { 
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">No data available for this period</td></tr>'; 
        return; 
    }
    items.forEach(item => {
        tbody.innerHTML += `
            <tr>
                <td style="font-weight:bold">${item.name}</td>
                <td>${item.total_sold} units</td>
                <td>₱${parseFloat(item.total_earned).toFixed(2)}</td>
            </tr>`;
    });
}

function updateCharts(data) {
    // 1. Revenue Chart
    const revCanvas = document.getElementById('revenueChart');
    if (revCanvas) {
        const ctxRev = revCanvas.getContext('2d');
        if (revenueChartInstance) revenueChartInstance.destroy();
        
        revenueChartInstance = new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: data.trend.map(i => i.date),
                datasets: [{
                    label: 'Revenue', 
                    data: data.trend.map(i => i.total),
                    borderColor: '#1565C0', 
                    backgroundColor: 'rgba(21, 101, 192, 0.1)', 
                    fill: true, 
                    tension: 0.3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: (val) => '₱' + val } }
                }
            }
        });
    }

    // 2. Status Chart
    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        const ctxStatus = statusCanvas.getContext('2d');
        if (statusChartInstance) statusChartInstance.destroy();
        
        const colors = { 
            'DELIVERED': '#10b981', 
            'PENDING': '#f59e0b', 
            'CANCELLED': '#ef4444', 
            'PREPARING': '#3b82f6', 
            'READY': '#8b5cf6' 
        };
        
        const labels = data.status_dist.map(i => i.status.toUpperCase());
        
        statusChartInstance = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data.status_dist.map(i => i.count),
                    backgroundColor: labels.map(l => colors[l] || '#ccc'),
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                plugins: { 
                    legend: { position: 'right', labels: { boxWidth: 12 } } 
                },
                cutout: '70%'
            }
        });
    }
}