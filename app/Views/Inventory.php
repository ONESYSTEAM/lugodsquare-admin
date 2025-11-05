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
    <h3 class="page-title">Inventory</h3>
    <h5 class="mb-0">Date: <span id="todayDate"></span></h5>
</div>
<div class="row" id="daily-con">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="colspan-3">Product Number</th>
                                <th>Product Name</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Sold</th>
                                <th>Sales</th>
                                <!-- <th>Action</th> -->
                            </tr>
                        </thead>
                        <?php if (!empty($inventory)): ?>
                            <tbody>
                                <?php foreach ($inventory as $product): ?>
                                    <tr>
                                        <td><?= $product['product_number'] ?></td>
                                        <td><?= $product['product_name'] ?></td>
                                        <td><?= $product['qty'] ?></td>
                                        <td>₱<?= $product['unit_price']?>.00</td>
                                        <td><?= $product['total_qty'] ?></td>
                                        <td>₱<?= $product['total_sales'] ?></td>
                                        <!-- <td>
                                            <a href="/viewUser/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No Inventory Generated.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
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
</script>
<?php
$this->stop();
?>