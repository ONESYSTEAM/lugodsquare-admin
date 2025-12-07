<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Schedules</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/schedules">Archived Bookings</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Archive</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header justify-content-between d-flex">
                <span><?= date('F j, Y', strtotime($schedule['date'])) ?></span>
                <span class="text-danger"><strong>Schedule Ended</strong></span>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $schedule['court_name'] ?></h5>
                <p class="card-text"><?= $schedule['start_time'] . ' - ' . $schedule['end_time'] ?></p>
                <ul class="list-group list-group-flush mb-3 border">
                    <li class="list-group-item"><strong>Name : </strong><?= $schedule['first_name'] . ' ' . $schedule['last_name'] ?></li>
                    <li class="list-group-item"><strong>Contact Number : </strong><?= $schedule['contact_number'] ?></li>
                    <li class="list-group-item"><strong>Email : </strong><?= $schedule['email'] ?></li>
                </ul>
                <p class="card-text"><strong>Total Amount : </strong>₱<?= $schedule['total_amount'] ?>.00</p>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Gcash Receipt</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="/uploads/gcash/<?= $schedule['gcash_receipt'] ?>" alt="GCash Receipt" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>