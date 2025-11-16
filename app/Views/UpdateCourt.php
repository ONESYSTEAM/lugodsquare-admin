<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Courts</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/courts">Courts</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Court</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/updateCourt/<?= $court['id'] ?>/update" method="POST">
                    <div class="form-group">
                        <label for="firstName">Court Type</label>
                        <input type="text" class="form-control" id="courtType" placeholder="Court Type" name="courtType" value="<?=$court['court_type']?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Capacity</label>
                        <input type="text" class="form-control" id="capacit" placeholder="Capacity" name="capacity" value="<?=$court['capacity']?>">
                    </div>
                    <div class="form-group">
                        <label for="userType">Amount</label>
                        <input type="text" class="form-control" id="amount" placeholder="Amount" name="amount" value="<?=$court['amount']?>">
                    </div>
                    <button type="submit" class="btn btn-custom me-2">Submit</button>
                    <a href="/courts" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>