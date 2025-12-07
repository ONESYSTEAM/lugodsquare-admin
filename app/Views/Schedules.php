<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Schedules</h3>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Confirmed Schedules
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
                                // Filter schedules with status 3
                                $filteredSchedules = array_filter($schedules, fn($s) => $s['status'] === 1);

                                if (!empty($filteredSchedules)):
                                    foreach ($filteredSchedules as $schedule): ?>
                                        <tr>
                                            <td>
                                                <span class="time-status badge bg-secondary"
                                                    data-id="<?= $schedule['id'] ?>"
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
                                            <td><?= $schedule['total_amount'] === '0.00' ? 'Paid' : $schedule['total_amount'] ?></td>
                                            <td><a href="/viewSchedule/<?= $schedule['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Schedules found.</td>
                                    </tr>
                                <?php endif; ?>
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

    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Pending Schedules
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
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
                                // Filter schedules with status 3
                                $filteredSchedules = array_filter($schedules, fn($s) => $s['status'] === 0);

                                if (!empty($filteredSchedules)):
                                    foreach ($filteredSchedules as $schedule): ?>
                                        <tr>
                                            <td><?= $schedule['date'] ?></td>
                                            <td><?= $schedule['start_time'] . ' - ' . $schedule['end_time'] ?></td>
                                            <td><?= $schedule['court_name'] ?></td>
                                            <td><?= $schedule['first_name'] . ' ' . $schedule['last_name'] ?></td>
                                            <td><?= !empty($schedule['membership_id']) ? 'Member' : 'Non-member' ?></td>
                                            <td><?= $schedule['total_amount'] === '0.00' ? 'Paid' : $schedule['total_amount'] ?></td>
                                            <td><a href="/viewSchedule/<?= $schedule['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Schedules found.</td>
                                    </tr>
                                <?php endif; ?>
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
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header">
                Cancelled Schedules
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
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

                                $filteredSchedules = array_filter($schedules, fn($s) => $s['status'] === 2);

                                if (!empty($filteredSchedules)):
                                    foreach ($filteredSchedules as $schedule): ?>
                                        <tr>
                                            <td><?= $schedule['date'] ?></td>
                                            <td><?= $schedule['start_time'] . ' - ' . $schedule['end_time'] ?></td>
                                            <td><?= $schedule['court_name'] ?></td>
                                            <td><?= $schedule['first_name'] . ' ' . $schedule['last_name'] ?></td>
                                            <td><?= !empty($schedule['membership_id']) ? 'Member' : 'Non-member' ?></td>
                                            <td><?= $schedule['total_amount'] ?></td>
                                            <td><a href="/viewSchedule/<?= $schedule['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No Schedules found.</td>
                                    </tr>
                                <?php endif; ?>
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