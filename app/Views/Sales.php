<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<style>
    .dropdown-item:hover {
        background-color: #dc3545 !important;
    }
</style>

<?php
function separateByCategory($sales, $category)
{
    return array_filter($sales, fn($s) => $s['product_category'] === $category);
}

function renderSalesTable($sales)
{
    if (empty($sales)) {
        echo '<tbody><tr><td colspan="7" class="text-center">No Sales Generated.</td></tr></tbody>';
        return;
    }
    echo '<tbody>';
    foreach ($sales as $s) {
        echo '<tr>
            <td>' . $s['product_number'] . '</td>
            <td>' . $s['item_name'] . '</td>
            <td>' . $s['total_qty'] . '</td>
            <td>₱' . $s['unit_price'] . '</td>
            <td>₱' . $s['raw_sales'] . '</td>
            <td>₱' . $s['total_discount'] . '</td>
            <td>₱' . $s['total_sales'] . '</td>
        </tr>';
    }
    echo '</tbody>';
}
?>

<div class="page-header">
    <h3 class="page-title">Sales</h3>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Sales Report
        </button>
        <ul class="dropdown-menu">
            <li><button class="dropdown-item" id="daily">Daily Sales</button></li>
            <li><button class="dropdown-item" id="weekly">Weekly Sales</button></li>
            <li><button class="dropdown-item" id="monthly">Monthly Sales</button></li>
            <li><button class="dropdown-item" id="yearly">Yearly Sales</button></li>
        </ul>
    </div>
</div>

<div class="row" id="daily-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Daily Sales Report - Foods</h5>
                <h5 class="mb-0">Date: <span id="todayDate"></span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($daily, 'Foods')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $dailyByCategory['Foods'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Daily Sales Report - Merch</h5>
                <h5 class="mb-0">Date: <span id="todayDate"></span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($daily, 'Merch')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $dailyByCategory['Merch'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="weekly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Weekly Sales Report - Foods</h5>
                <h5 class="mb-0">Date: <?= $weekStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($weekly, 'Foods')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $weeklyByCategory['Foods'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Weekly Sales Report - Merch</h5>
                <h5 class="mb-0">Date: <?= $weekStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <h6>Merch</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($weekly, 'Merch')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $weeklyByCategory['Merch'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="monthly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Monthly Sales Report - Foods</h5>
                <h5 class="mb-0">Date: <?= $monthStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($monthly, 'Foods')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $monthlyByCategory['Foods'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Monthly Sales Report - Merch</h5>
                <h5 class="mb-0">Date: <?= $monthStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <h6>Merch</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($monthly, 'Merch')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $monthlyByCategory['Merch'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="yearly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Yearly Sales Report - Foods</h5>
                <h5 class="mb-0">Date: <?= $yearStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($yearly, 'Foods')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $yearlyByCategory['Foods'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Yearly Sales Report - Merch</h5>
                <h5 class="mb-0">Date: <?= $yearStart . ' - ' . $today ?></h5>
            </div>
            <div class="card-body">
                <h6>Merch</h6>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Raw Sales</th>
                                <th>Total Discount</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderSalesTable(separateByCategory($yearly, 'Merch')); ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $yearlyByCategory['Merch'] ?? '0' ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const today = new Date();
    const options = {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    document.getElementById('todayDate').textContent = today.toLocaleDateString('en-US', options);

    $(document).ready(function() {
        $('#daily').on('click', function() {
            $('#daily-con').removeClass('d-none');
            $('#weekly-con, #monthly-con, #yearly-con').addClass('d-none');
        });
        $('#weekly').on('click', function() {
            $('#weekly-con').removeClass('d-none');
            $('#daily-con, #monthly-con, #yearly-con').addClass('d-none');
        });
        $('#monthly').on('click', function() {
            $('#monthly-con').removeClass('d-none');
            $('#daily-con, #weekly-con, #yearly-con').addClass('d-none');
        });
        $('#yearly').on('click', function() {
            $('#yearly-con').removeClass('d-none');
            $('#daily-con, #weekly-con, #monthly-con').addClass('d-none');
        });
    });
</script>

<?php $this->stop(); ?>