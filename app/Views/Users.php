<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Users</h3>
    <a href="/addUser" class="btn btn-gradient-warning">Add User</a>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User Type</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <?php
                                        if ($user['user_type'] == 1) {
                                            echo 'Administrator';
                                        } elseif ($user['user_type'] == 2) {
                                            echo 'Cashier';
                                        } else {
                                            echo 'User';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $user['first_name'] . ' ' . $user['last_name'] ?></td>
                                    <td>
                                        <button class="btn btn-gradient-primary btn-sm"><i class="fa fa-eye"></i> View</button>
                                        <button class="btn btn-gradient-info btn-sm"><i class="fa fa-edit"></i> Update</button>
                                        <button class="btn btn-gradient-danger btn-sm"><i class="fa fa-trash-o"></i> Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$this->stop();
?>