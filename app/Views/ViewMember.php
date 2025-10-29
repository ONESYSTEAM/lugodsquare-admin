<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Members</h3>
    <!-- <a href="/addUser" class="btn btn-gradient-warning">Add User</a> -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/members">Members</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Member</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="row card-body">
                <div class="col-md-3 d-flex justify-content-center align-items-center">
                    <img src="https://ui-avatars.com/api/?name=<?= $member['first_name'] . ' ' . $member['last_name'] ?>" alt="member avatar" class="rounded-circle mb-3" width="150" height="150">
                </div>
                <div class="col-md-9 ">
                    <h2 class="mb-4 text-center text-md-start"><?= $member['first_name'] . ' ' . $member['last_name'] ?></h2>
                    <hr>
                    <p class="card-text"><span class="fw-bold">Membership ID:</span> <?= $member['membership_id'] ?></p>
                    <p class="card-text"><span class="fw-bold">Birth Date:</span> <?= $member['birth_date'] ?></p>
                    <p class="card-text"><span class="fw-bold">Address:</span> <?= $member['address'] ?></p>
                    <p class="card-text"><span class="fw-bold">Contact Number:</span> <?= $member['contact_number'] ?></p>
                    <p class="card-text"><span class="fw-bold">Email:</span> <?= $member['email'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>