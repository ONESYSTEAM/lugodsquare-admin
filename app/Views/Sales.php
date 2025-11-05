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

<div class="page-header">
    <h3 class="page-title">Sales</h3>
    <!-- <a href="/addProduct" class="btn btn-primary">Add Product</a> -->
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
                <h5 class="mb-0">Daily Sales Report</h5>
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
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (!empty($daily)): ?>
                            <tbody>
                                <?php foreach ($daily as $sales): ?>
                                    <tr>
                                        <td><?= $sales['product_number'] ?></td>
                                        <td><?= $sales['item_name'] ?></td>
                                        <td><?= $sales['total_qty'] ?></td>
                                        <td>₱<?= $sales['unit_price'] ?></td>
                                        <td>₱<?= $sales['raw_sales'] ?></td>
                                        <td>₱<?= $sales['total_discount'] ?></td>
                                        <td>₱<?= $sales['total_sales'] ?></td>
                                        <!-- <td>
                                            <a href="/viewUser/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No Sales Generated.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $dailyTotal ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="weekly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Weekly Sales Report</h5>
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
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (!empty($daily)): ?>
                            <tbody>
                                <?php foreach ($weekly as $sales): ?>
                                    <tr>
                                        <td><?= $sales['product_number'] ?></td>
                                        <td><?= $sales['item_name'] ?></td>
                                        <td><?= $sales['total_qty'] ?></td>
                                        <td>₱<?= $sales['unit_price'] ?></td>
                                        <td>₱<?= $sales['raw_sales'] ?></td>
                                        <td>₱<?= $sales['total_discount'] ?></td>
                                        <td>₱<?= $sales['total_sales'] ?></td>
                                        <!-- <td>
                                            <a href="/viewUser/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No Sales Generated.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $weeklyTotal ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="monthly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Monthly Sales Report</h5>
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
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (!empty($monthly)): ?>
                            <tbody>
                                <?php foreach ($monthly as $sales): ?>
                                    <tr>
                                        <td><?= $sales['product_number'] ?></td>
                                        <td><?= $sales['item_name'] ?></td>
                                        <td><?= $sales['total_qty'] ?></td>
                                        <td>₱<?= $sales['unit_price'] ?></td>
                                        <td>₱<?= $sales['raw_sales'] ?></td>
                                        <td>₱<?= $sales['total_discount'] ?></td>
                                        <td>₱<?= $sales['total_sales'] ?></td>
                                        <!-- <td>
                                            <a href="/viewUser/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No Sales Generated.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $monthlyTotal ?>.00</h5>
            </div>
        </div>
    </div>
</div>

<div class="row d-none" id="yearly-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0">Yearly Sales Report</h5>
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
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (!empty($yearly)): ?>
                            <tbody>
                                <?php foreach ($yearly as $sales): ?>
                                    <tr>
                                        <td><?= $sales['product_number'] ?></td>
                                        <td><?= $sales['item_name'] ?></td>
                                        <td><?= $sales['total_qty'] ?></td>
                                        <td>₱<?= $sales['unit_price'] ?></td>
                                        <td>₱<?= $sales['raw_sales'] ?></td>
                                        <td>₱<?= $sales['total_discount'] ?></td>
                                        <td>₱<?= $sales['total_sales'] ?></td>
                                        <!-- <td>
                                            <a href="/viewUser/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $sales['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No Sales Generated.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <h5>Total Sales:</h5>
                <h5>₱<?= $yearlyTotal ?>.00</h5>
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
            $('#weekly-con').addClass('d-none');
            $('#monthly-con').addClass('d-none');
            $('#yearly-con').addClass('d-none');

        });
        $('#weekly').on('click', function() {
            $('#weekly-con').removeClass('d-none');
            $('#daily-con').addClass('d-none');
            $('#monthly-con').addClass('d-none');
            $('#yearly-con').addClass('d-none');

        });
        $('#monthly').on('click', function() {
            $('#monthly-con').removeClass('d-none');
            $('#daily-con').addClass('d-none');
            $('#weekly-con').addClass('d-none');
            $('#yearly-con').addClass('d-none');

        });
        $('#yearly').on('click', function() {
            $('#yearly-con').removeClass('d-none');
            $('#daily-con').addClass('d-none');
            $('#weekly-con').addClass('d-none');
            $('#monthly-con').addClass('d-none');

        });
    });
</script>
<?php
$this->stop();
?>