<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title text-muted"><span><a href="/products" class="text-black text-decoration-none">Products /</a></span> <?= $category ?></h3>
    <a href="/addProduct/<?= $category ?>" class="btn btn-custom"><?php echo ($category == 'Rentals') ? 'Add Rental' : 'Add Product'; ?></a>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <?php if ($category === 'Rentals'): ?>
                            <thead>
                                <tr>
                                    <th>Rental Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php if (!empty($products)): ?>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= $product['product_name'] ?></td>
                                            <td>₱<?= $product['price'] ?>.00</td>
                                            <td>
                                                <a href="/updateProduct/<?= $product['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-edit"></i> Update</a>
                                                <a href="/deleteProduct/<?= $product['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
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
                        <?php else: ?>
                            <thead>
                                <tr>
                                    <th>Product Number</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
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
                                            <td><?= $product['qty'] ?></td>
                                            <td>
                                                <a href="/viewProduct/<?= $product['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a>
                                                <a href="/updateProduct/<?= $product['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-edit"></i> Update</a>
                                                <a href="/deleteProduct/<?= $product['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
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