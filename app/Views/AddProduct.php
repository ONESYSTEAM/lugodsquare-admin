<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title"><?= $category ?></h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/products">Products</a></li>
            <li class="breadcrumb-item"><a href="/products/<?= $category ?>"><?= $category ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/addProduct/<?= $category ?>/add" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="productCat">Product Category</label>
                        <input type="text" class="form-control" id="productCat" name="productCat" value="<?= $category ?>" readonly>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="productNumber">Product Number</label>
                        <input type="text" class="form-control" id="productNumber" placeholder="Product Number" name="productNumber" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control" id="productName" placeholder="Product Name" name="productName" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" placeholder="Price" name="price" required>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="qty">Quantity</label>
                        <input type="text" class="form-control" id="qty" placeholder="Quantity" name="qty" required>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="productImage">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="productImage">
                        <input type="hidden" id="defaultImage" name="defaultImage" value="">
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
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

<?php $this->stop(); ?>