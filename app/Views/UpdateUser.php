<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Users</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/users">Users</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update User</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/updateUser/<?= $user['id'] ?>/update" method="POST">
                    <input type="hidden" name="userId" id="" value="<?= $user['id'] ?>">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="<?= $user['first_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName" value="<?= $user['last_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type</label>
                        <select class="form-select" id="userType" name="userType">
                            <option value="1" <?= ($user['user_type'] == 1) ? 'selected' : '' ?>>Administrator</option>
                            <option value="2" <?= ($user['user_type'] == 2) ? 'selected' : '' ?>>Cashier</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" placeholder="Username" name="username" value="<?= $user['username'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="/users" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>