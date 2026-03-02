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
            <li class="breadcrumb-item active" aria-current="page">Add Product</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form id="productForm" class="forms-sample" action="/addProductGeneral/add" method="POST" enctype="multipart/form-data">

                    <div class="form-group mb-3">
                        <label for="productCat" class="form-label fw-bold">Product Category</label>
                        <select class="form-select" id="productCat" name="productCat" required>
                            <option value="" disabled selected hidden>-- Select a Category --</option>
                            <option value="Foods">Foods</option>
                            <option value="Merch">Merch</option>
                            <option value="Rentals">Rentals</option>
                        </select>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="productNumber">Product Number</label>
                        <input type="text" class="form-control reset-input" id="productNumber" placeholder="Product Number" name="productNumber" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="productName">Product Name</label>
                        <input type="text" class="form-control reset-input" id="productName" placeholder="Product Name" name="productName" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price</label>
                        <input type="text" class="form-control reset-input" id="price" placeholder="Price" name="price" required>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="qty">Quantity</label>
                        <input type="number" class="form-control reset-input" id="qty" placeholder="Quantity" name="qty" required>
                    </div>

                    <div class="form-group mb-3 hide-on-rental">
                        <label for="productImage">Product Image</label>
                        <input type="file" class="form-control reset-input" id="productImage" name="productImage">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-danger px-4">Submit</button>
                        <a href="/products" class="btn btn-light px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productCat').on('change', function() {
            const category = $(this).val();

            if (category === 'Rentals') {
                // 1. Hide the specific fields
                $('.hide-on-rental').addClass('d-none');
                $('.reset-input').val('');
                // 2. Set default values for hidden required fields
                $('#productNumber').val('N/A').removeAttr('required');
                $('#qty').val('1').removeAttr('required'); // Defaulting qty to 1 for rentals

            } else {
                // 1. Show the fields back
                $('.hide-on-rental').removeClass('d-none');
                $('.reset-input').val(''); // Clear any default values

                // 2. Clear values and restore required attribute
                $('#productNumber').val('').attr('required', true);
                $('#qty').val('').attr('required', true);
            }
        });
    });
</script>

<?php
$this->stop();
?>