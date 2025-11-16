<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Members</h3>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Membership ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <?php if (!empty($members)): ?>
                            <tbody>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <td> <?= $member['membership_id'] ?></td>
                                        <td><?= $member['first_name'] . ' ' . $member['last_name'] ?></td>
                                        <td> <?= $member['email'] ?></td>
                                        <td>
                                            <a href="/viewMember/<?= $member['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> View</a>
                                            <a href="/updateMember/<?= $member['id'] ?>" class="btn btn-custom btn-sm"><i class="fa fa-eye"></i> Update</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="text-center">No members found.</td>
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