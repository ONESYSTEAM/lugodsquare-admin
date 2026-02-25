<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">User Attendance Logs</h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                <span class="font-weight-bold text-dark">Today's Attendance Records</span>
                <span class="badge badge-primary"><?= date('F j, Y') ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($attendances)): ?>
                                <?php foreach ($attendances as $log): ?>
                                    <tr>
                                        <td>#<?= $log['user_id'] ?></td>
                                        <td><strong><?= htmlspecialchars($log['id_number']) ?></strong></td>
                                        <td><?= htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) ?></td>
                                        <td><label class="badge badge-success"><?= date('h:i A', strtotime($log['time_in'])) ?></label></td>
                                        <td>
                                            <?= $log['time_out'] ? '<label class="badge badge-danger">' . date('h:i A', strtotime($log['time_out'])) . '</label>' : '<span class="text-muted small">In Progress</span>' ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= !$log['time_out'] ? 'success' : 'secondary' ?>">
                                                <?= !$log['time_out'] ? 'Active' : 'Completed' ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No activity yet today.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="d-grid mb-3">
            <button class="btn btn-outline-secondary btn-block text-left" type="button" data-bs-toggle="collapse" data-bs-target="#historicalLogs" aria-expanded="false">
                <i class="mdi mdi-history mr-2"></i> View Historical Attendance Logs (All Time)
            </button>
        </div>

        <div class="collapse" id="historicalLogs">
            <div class="card card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history)): ?>
                                <?php foreach ($history as $h): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($h['work_date'])) ?></td>
                                        <td><?= htmlspecialchars($h['first_name'] . ' ' . $h['last_name']) ?></td>
                                        <td><?= date('h:i A', strtotime($h['time_in'])) ?></td>
                                        <td><?= $h['time_out'] ? date('h:i A', strtotime($h['time_out'])) : '---' ?></td>
                                        <td>
                                            <?php
                                            if ($h['time_out']) {
                                                $start = new DateTime($h['time_in']);
                                                $end = new DateTime($h['time_out']);
                                                echo $start->diff($end)->format('%h hrs %i mins');
                                            } else {
                                                echo '<span class="text-danger">No clock out</span>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No historical data available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->stop(); ?>