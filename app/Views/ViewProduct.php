<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Products</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Product</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="row card-body">
                <div class="col-md-12">
                    <p class="card-text">
                        <span class="fw-bold">Product Number:</span> <?= $product['product_number'] ?> <br>
                        <span class="fw-bold">Product Name:</span> <?= $product['product_name'] ?> <br>
                        <span class="fw-bold">Price:</span> <?= $product['price'] ?> <br>
                        <span class="fw-bold">Quantity:</span> <?= $product['qty'] ?> <br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>