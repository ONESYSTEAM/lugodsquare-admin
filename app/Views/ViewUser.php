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
            <li class="breadcrumb-item active" aria-current="page">View User</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="row card-body">
                <div class="col-md-3 d-flex justify-content-center align-items-center">
                    <img src="https://ui-avatars.com/api/?name=<?= $user['first_name'] . ' ' . $user['last_name'] ?>" alt="user avatar" class="rounded-circle mb-3" width="150" height="150">
                </div>
                <div class="col-md-9 ">
                    <h2 class="mb-4 text-center text-md-start"><?= $user['first_name'] . ' ' . $user['last_name'] ?></h2>
                    <hr>
                    <p class="card-text"><span class="fw-bold">Role:</span>
                        <?php
                        if ($user['user_type'] == 1) {
                            echo 'Administrator';
                        } elseif ($user['user_type'] == 2) {
                            echo 'Cashier';
                        } else {
                            echo 'User';
                        }
                        ?>
                    </p>
                    <p class="card-text"><span class="fw-bold">Username:</span> <?= $user['username'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>