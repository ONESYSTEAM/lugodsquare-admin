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

<?php
$foods = array_filter($inventory, fn($item) => $item['product_category'] === 'Foods');
$merch = array_filter($inventory, fn($item) => $item['product_category'] === 'Merch');

function renderTable($products)
{
    if (empty($products)) {
        echo '<tbody><tr><td colspan="6" class="text-center">No Inventory Generated.</td></tr></tbody>';
        return;
    }
    echo '<tbody>';
    foreach ($products as $product) {
        echo '<tr>
                <td>' . $product['product_number'] . '</td>
                <td>' . $product['product_name'] . '</td>
                <td>' . $product['qty'] . '</td>
                <td>₱' . $product['unit_price'] . '.00</td>
                <td>' . $product['total_qty'] . '</td>
                <td>₱' . $product['total_sales'] . '</td>
              </tr>';
    }
    echo '</tbody>';
}
?>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Foods</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Sold</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderTable($foods); ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Merch</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Number</th>
                                <th>Product Name</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Sold</th>
                                <th>Sales</th>
                            </tr>
                        </thead>
                        <?php renderTable($merch); ?>
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