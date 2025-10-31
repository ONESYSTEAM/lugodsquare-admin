<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Courts</h3>
    <a href="/addCourt" class="btn btn-primary">Add Court</a>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Court Type</th>
                                <th>Capacity</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php if (!empty($courts)): ?>
                            <tbody>
                                <?php foreach ($courts as $court): ?>
                                    <tr>
                                        <td>
                                            <?= $court['court_type'] ?>
                                        </td>
                                        <td><?= $court['capacity'] ?></td>
                                        <td>₱<?= $court['amount'] ?>.00</td>
                                        <td>
                                            <a href="/viewCourt/<?= $court['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="updateCourt/<?= $court['id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> Update</a>
                                            <a href="/deleteCourt/<?=$court['id']?>" class="btn btn-primary btn-sm"><i class="fa fa-trash-o"></i> Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center">No courts available.</td>
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