<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Products</h3>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Category</th>
                                <th>No of Items</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php if (!empty($products)): ?>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['product_category'] ?></td>
                                        <td><?= $product['no_of_items'] ?></td>
                                        <td>
                                            <a href="/products/<?= $product['product_category'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/addProduct/<?= $product['product_category'] ?>" class="btn btn-primary btn-sm"><i class="bi bi-bag-plus-fill"></i> Add Product</a>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No products found.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>