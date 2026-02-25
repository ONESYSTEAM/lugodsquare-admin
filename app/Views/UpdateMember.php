<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">members</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/members">members</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update member</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form class="forms-sample" action="/updateMember/<?= $member['id'] ?>/update" method="POST">
                    <input type="hidden" name="memberId" id="" value="<?= $member['id'] ?>">
                    <div class="form-group">
                        <label for="membershipId">Membership ID</label>
                        <input type="text" class="form-control" id="membershipId" placeholder="Membership ID" name="membershipId" value="<?= $member['membership_id'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="cardId">Card ID</label>
                        <input type="text" class="form-control" id="cardId" placeholder="Card ID" name="cardId" value="<?= $member['card_number'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="<?= $member['first_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Last Name" name="lastName" value="<?= $member['last_name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="Address" name="address" value="<?= $member['address'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" placeholder="Contact Number" name="contactNumber" value="<?= $member['contact_number'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" id="email" placeholder="Email" name="email" value="<?= $member['email'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="wallet">Wallet</label>
                        <input type="text" class="form-control" id="wallet" placeholder="Wallet" name="wallet" value="<?= $member['wallet'] ?>">
                    </div>
                    <button type="submit" class="btn btn-custom me-2">Submit</button>
                    <a href="/members" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$this->stop();
?>