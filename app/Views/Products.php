<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Products</h3>
    <a href="/addProduct" class="btn btn-primary">Add Product</a>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php if (!empty($products)): ?>
                            <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td><?= $product['product_number'] ?></td>
                                        <td><?= $product['product_name'] ?></td>
                                        <td>₱<?= $product['price'] ?>.00</td>
                                        <td>
                                            <a href="/viewUser/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateProduct/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteProduct/<?= $product['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
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