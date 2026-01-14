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
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/addProductGeneral/add" method="POST">
                    <div class="form-group mb-3">
                        <label for="productCat" class="form-label">Product Category</label>
                        <select class="form-select" id="productCat" name="productCat" required>
                            <option value="" disabled selected hidden>-- Select a Category --</option>
                            <option value="Foods">Foods</option>
                            <option value="Merch">Merch</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productNumber">Product Number</label>
                        <input type="text" class="form-control" id="productNumber" placeholder="Product Number" name="productNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Product Name</label>
                        <input type="text" class="form-control" id="capacity" placeholder="Product Name" name="productName">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" id="price" placeholder="Price" name="price" required>
                    </div>
                    <div class="form-group">
                        <label for="qty">Quantity</label>
                        <input type="text" class="form-control" id="qty" placeholder="Quantity" name="qty" required>
                    </div>
                    <button type="submit" class="btn btn-custom me-2">Submit</button>
                    <a href="/products" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>