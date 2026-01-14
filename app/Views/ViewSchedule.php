<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Schedules</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/schedules">Schedules</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Schedule</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header justify-content-between d-flex">
                <span><?= date('F j, Y', strtotime($schedule['date'])) ?></span>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?= $schedule['court_name'] ?></h5>
                <p class="card-text"><?= $schedule['start_time'] . ' - ' . $schedule['end_time'] ?></p>
                <ul class="list-group list-group-flush mb-3 border">
                    <li class="list-group-item"><strong>Name : </strong><?= $schedule['first_name'] . ' ' . $schedule['last_name'] ?></li>
                    <li class="list-group-item"><strong>Contact Number : </strong><?= $schedule['contact_number'] ?></li>
                    <li class="list-group-item"><strong>Email : </strong><?= $schedule['email'] ?></li>
                </ul>
                <p class="card-text">
                    <?php if ($schedule['total_amount'] === '0.00'): ?>
                        <span class="badge badge-success">Total Amount Paid</span> <br>
                    <?php elseif ($schedule['total_amount'] !== '0.00' && $schedule['status'] === 1): ?>
                        <span><strong>Total Amount : </strong>₱<?= $schedule['total_amount'] * 2 ?>.00</span> <br>
                        <span class="text-danger"><strong>Remaining Amount : </strong>₱<?= $schedule['total_amount'] ?></span>
                    <?php else: ?>
                        <span><strong>Total Amount : </strong>₱<?= $schedule['total_amount'] ?></span>
                    <?php endif; ?>
                </p>
                <!-- Button trigger modal -->
                <?php if ($schedule['status'] === 0 && $schedule['total_amount'] !== '0.00'): ?>
                    <button type="button" class="badge badge-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        View Partial Payment Receipt
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($schedule['status'] === 0): ?>
                <div class="card-footer text-end">
                    <a href="/viewSchedule/confirm/<?= $schedule['id'] ?>" class="btn btn-custom confirm-booking">Confirm Booking</a>
                    <a href="/viewSchedule/cancel/<?= $schedule['id'] ?>" class="btn btn-primary cancel-booking">Cancel Booking</a>
                </div>
            <?php elseif ($schedule['status'] === 1): ?>
                <div class="card-footer justify-content-between d-flex">
                    <span class="text-dark"><strong>Booking Status</strong></span>
                    <span class="time-status badge bg-secondary"
                        data-start="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['start_time'])) ?>"
                        data-end="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['end_time'])) ?>">
                        Pending
                    </span>
                </div>
            <?php elseif ($schedule['status'] === 2): ?>
                <div class="card-footer justify-content-between d-flex align-items-center">
                    <span class="text-danger"><strong>Booking Cancelled</strong></span>
                    <a href="/viewSchedule/undoCancel/<?= $schedule['id'] ?>" class="btn btn-primary undo-cancellation">Undo Cancellation</a>
                </div>
            <?php else: ?>
                <div class="card-footer text-end">
                    <span class="text-danger"><strong>Schedule Ended</strong></span>
                </div>
            <?php endif; ?>
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
            <div class="modal-body text-center">
                <?php if (!empty($schedule['gcash_receipt'])): ?>
                    <img src="/gcashReceipt/<?= str_replace(['.jpg', '.jpeg', '.png'], '', $schedule['gcash_receipt']) ?>"
                        alt="GCash Receipt"
                        class="img-fluid"
                        style="max-height: 550px; border: 1px solid #ddd; border-radius: 5px;">
                    <p><?= $schedule['gcash_receipt'] ?></p>
                <?php else: ?>
                    <div class="alert alert-warning">No GCash receipt found for this booking.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>