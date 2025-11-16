<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Procucts</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Product</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/updateProduct/<?= $product['id'] ?>/update" method="POST">
                    <div class="form-group">
                        <label for="productCat">Product Category</label>
                        <select class="form-select" id="productCat" name="productCat">
                            <option value="Foods" <?= ($product['product_category'] == "Foods") ? 'selected' : '' ?>>Foods</option>
                            <option value="Merch" <?= ($product['product_category'] == "Merch") ? 'selected' : '' ?>>Merch</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="firstName">Product Number</label>
                        <input type="text" class="form-control" id="courtType" placeholder="Pruduct Number" name="productNumber" value="<?= $product['product_number'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Product Name</label>
                        <input type="text" class="form-control" id="capacit" placeholder="Product Name" name="productName" value="<?= $product['product_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="userType">Price</label>
                        <input type="text" class="form-control" id="amount" placeholder="Price" name="price" value="<?= $product['price'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control" id="quantity" placeholder="Quamtity" name="qty" value="<?= $product['qty'] ?>">
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
                    <a href="/products" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>