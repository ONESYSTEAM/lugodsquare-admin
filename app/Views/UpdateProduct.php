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
            <li class="breadcrumb-item active" aria-current="page">Update Product</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/updateProduct/<?= $product['id'] ?>/update" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="productCat">Product Category</label>
                        <?php if ($product['product_category'] == 'Rentals'): ?>
                            <input type="text" class="form-control" id="productCat" name="productCat" value="<?= $product['product_category'] ?>" readonly>
                        <?php else: ?>
                            <select class="form-select" id="productCat" name="productCat">
                                <option value="Foods" <?= ($product['product_category'] == "Foods") ? 'selected' : '' ?>>Foods</option>
                                <option value="Merch" <?= ($product['product_category'] == "Merch") ? 'selected' : '' ?>>Merch</option>
                            </select>
                        <?php endif; ?>
                    </div>
                    <div class="form-group hide-on-rental">
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
                    <div class="form-group hide-on-rental">
                        <label for="quantity">Quantity</label>
                        <input type="text" class="form-control" id="quantity" placeholder="Quamtity" name="qty" value="<?= $product['qty'] ?>">
                    </div>
                    <div class="form-group hide-on-rental">
                        <label for="productImage">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*">
                        <input type="hidden" name="existingImage" value="<?= $product['product_image'] ?>">
                    </div>

                    <button type="submit" class="btn btn-custom me-2">Submit</button>
                    <a href="/products" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Since the category is fixed/readonly on this page, we check it once on load
        if ($('#productCat').val() === 'Rentals') {

            // 1. Hide the specific fields
            $('.hide-on-rental').addClass('d-none');

            // 2. Set default values so DB doesn't complain about empty strings/nulls
            $('#productNumber').val('N/A').removeAttr('required');
            $('#qty').val('1').removeAttr('required');
        }
    });
</script>
<?php
$this->stop();
?>