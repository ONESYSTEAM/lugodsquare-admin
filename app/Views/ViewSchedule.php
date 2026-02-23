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
                <p class="card-text">
                    <?php
                    $start = $schedule['start_time'];
                    $end = $schedule['end_time'];

                    $formatTime = function ($timeString) {
                        $hour = (int)date('H', strtotime($timeString));

                        if ($hour >= 7 && $hour <= 11) {
                            return date('g:i', strtotime($timeString)) . ' AM';
                        } elseif ($hour == 12) {
                            return '12:00 NN';
                        } elseif ($hour >= 13 && $hour <= 17) {
                            return date('g:i', strtotime($timeString)) . ' PM';
                        } else {
                            // Fallback for any times outside your 7-17 range
                            return date('g:i A', strtotime($timeString));
                        }
                    };

                    echo $formatTime($start) . ' - ' . $formatTime($end);
                    ?>
                </p>
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
                        <?php if ($schedule['is_paid'] == 1): ?>
                            <span class="badge badge-success">Total Amount Paid</span> <br>
                        <?php else: ?>
                            <span class="text-danger"><strong>Remaining Amount : </strong>₱<?= $schedule['total_amount'] ?></span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span><strong>Total Amount : </strong>₱<?= $schedule['total_amount'] ?></span>
                    <?php endif; ?>
                    <!-- Mark as Paid btn -->
                    <br>
                    <?php if ($schedule['status'] === 1 && $schedule['total_amount'] !== '0.00' && $schedule['is_paid'] == 0): ?>
                        <a href="/setAmountPaid/<?= $schedule['id'] ?>" class="badge badge-success text-decoration-none ">
                            Mark as Fully Paid
                        </a>
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
                    <div>

                        <span class="time-status badge bg-secondary"
                            data-start="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['start_time'])) ?>"
                            data-end="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['end_time'])) ?>">
                            Pending
                        </span>
                        |
                        <?php if ($schedule['status'] === 1): ?>
                            <!-- reschedule booking -->
                            <button type="button" id="rescheduleBtn" class="badge badge-primary" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                                Reschedule Booking
                            </button>
                        <?php endif; ?>
                    </div>

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
                <?php else: ?>
                    <div class="alert alert-warning">No GCash receipt found for this booking.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .greyed-out {
        color: #adb5bd !important;
        font-style: italic;
        background-color: #f8f9fa;
    }

    .text-info.fw-bold {
        color: #0dcaf0 !important;
    }
</style>

<div class="modal fade" id="rescheduleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reschedule Booking</h5>
                <button type="button" class="btn-close" id="customCloseX"></button>
            </div>
            <form id="rescheduleForm" action="/viewSchedule/reschedule/<?= $schedule['id'] ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="orig_duration" value="<?= (strtotime($schedule['end_time']) - strtotime($schedule['start_time'])) / 3600 ?>">
                    <input type="hidden" id="orig_start" value="<?= substr($schedule['start_time'], 0, 5) ?>">
                    <input type="hidden" id="orig_end" value="<?= substr($schedule['end_time'], 0, 5) ?>">
                    <input type="hidden" id="reschedule_court" value="<?= $schedule['court_type'] ?>">
                    <input type="hidden" id="default_date_val" value="<?= $schedule['date'] ?>">

                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="fw-bold">Date</label>
                            <span class="badge bg-info">Required: <?= (strtotime($schedule['end_time']) - strtotime($schedule['start_time'])) / 3600 ?> Hour(s)</span>
                        </div>
                        <div class="form-floating">
                            <input type="date" class="form-control" id="res_date" name="date" value="<?= $schedule['date'] ?>" required>
                            <label for="res_date">Date</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-floating">
                                <select class="form-select" id="res_startTime" name="startTime" required>
                                    <option value="" hidden></option>
                                    <?php for ($i = 7; $i <= 16; $i++): $val = sprintf('%02d:00', $i); ?>
                                        <option value="<?= $val ?>"><?= date('g:i A', strtotime($val)) ?></option>
                                    <?php endfor; ?>
                                </select>
                                <label>Start Time</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <select class="form-select" id="res_endTime" name="endTime" required>
                                    <option value="" hidden></option>
                                    <?php for ($i = 8; $i <= 17; $i++): $val = sprintf('%02d:00', $i); ?>
                                        <option value="<?= $val ?>"><?= date('g:i A', strtotime($val)) ?></option>
                                    <?php endfor; ?>
                                </select>
                                <label>End Time</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="customCloseBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary reschedule-booking">Confirm Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
$this->stop();
?>