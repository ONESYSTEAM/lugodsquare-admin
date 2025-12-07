<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Booking Archive</h3>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Archived Bookings
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Schedule Date</th>
                                <th>Time Slot</th>
                                <th>Court Type</th>
                                <th>Name</th>
                                <th>Member</th>
                                <th>Total Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php if (!empty($schedules)): ?>
                            <tbody>
                                <?php

                                foreach ($schedules as $schedule): ?>
                                    <tr>
                                        <td>
                                            <span class="time-status badge bg-secondary"
                                                data-start="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['start_time'])) ?>"
                                                data-end="<?= date('Y-m-d H:i:s', strtotime($schedule['date'] . ' ' . $schedule['end_time'])) ?>">
                                                Pending
                                            </span>
                                        </td>

                                        <td><?= $schedule['date'] ?></td>
                                        <td><?= $schedule['start_time'] . ' - ' . $schedule['end_time'] ?></td>
                                        <td><?= $schedule['court_name'] ?></td>
                                        <td><?= $schedule['first_name'] . ' ' . $schedule['last_name'] ?></td>
                                        <td><?= !empty($schedule['membership_id']) ? 'Member' : 'Non-member' ?></td>
                                        <td><?= $schedule['total_amount'] ?></td>
                                        <td><a href="/viewArchive/<?= $schedule['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="8" class="text-center">No Schedules found.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>