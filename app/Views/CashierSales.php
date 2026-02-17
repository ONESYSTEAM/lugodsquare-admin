<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Cashier Sales</h3>
</div>

<!-- Daily Cashier Sales Table -->
<div class="row" id="daily-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Daily Cashier Sales Report</h5>
                <h5 class="mb-0">Date: <span id="todayDate"></span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Cashier</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Foods</th>
                                <th>Merch</th>
                                <th>Total Sales</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="cashierTableBody">
                            <tr>
                                <td colspan="7" class="text-center">No cashier shifts started today.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const today = new Date();
    document.getElementById('todayDate').textContent = today.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    function formatCurrency(amount) {
        return '₱' + Number(amount).toLocaleString('en-US', {
            minimumFractionDigits: 2
        });
    }

    function fetchLiveSales() {
        $.ajax({
            url: '/live-cashier-sales', // route to your liveCashierSales method
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update cards with fallback to 0
                $('#foodsCard').text(formatCurrency(data.dashboardTotals?.Foods || 0));
                $('#merchCard').text(formatCurrency(data.dashboardTotals?.Merch || 0));

                // Update table
                let tableBody = '';
                if (data.cashierShifts && data.cashierShifts.length > 0) {
                    data.cashierShifts.forEach(function(shift) {
                        tableBody += `<tr>
                            <td>${shift.cashier_name}</td>
                            <td>${shift.start_time ? new Date(shift.start_time).toLocaleString('en-US') : '-- --'}</td>
                            <td>${shift.end_time ? new Date(shift.end_time).toLocaleString('en-US') : '-- --'}</td>
                            <td>${formatCurrency(shift.category_totals?.Foods || 0)}</td>
                            <td>${formatCurrency(shift.category_totals?.Merch || 0)}</td>
                            <td>${formatCurrency(shift.total_sales || 0)}</td>
                            <td>${shift.status}</td>
                        </tr>`;
                    });
                } else {
                    // Fallback row if no shifts exist
                    tableBody = `<tr>
                        <td colspan="7" class="text-center">No cashier shifts started today.</td>
                    </tr>`;
                }

                $('#cashierTableBody').html(tableBody);
            },
            error: function() {
                // Optional: display error row if AJAX fails
                $('#cashierTableBody').html(`<tr><td colspan="7" class="text-center text-danger">Unable to load data. Please try again.</td></tr>`);
            }
        });
    }

    // Initial fetch and interval refresh every 5 seconds
    fetchLiveSales();
    setInterval(fetchLiveSales, 5000);
</script>

<?php $this->stop(); ?>