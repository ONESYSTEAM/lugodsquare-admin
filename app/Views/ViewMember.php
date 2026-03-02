<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>
<?php
$today = date('Y-m-d');
$isExpired = (!empty($member['subscription_end']) && $member['subscription_end'] < $today);
$isDueToday = (!empty($member['subscription_end']) && $member['subscription_end'] == $today);
?>
<?php if ($isExpired): ?>
    <div class="alert alert-danger d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
        <i class="fa fa-clock me-3 fs-4"></i>
        <div>
            <strong>Subscription Expired!</strong><br>
            This member's access ended on <?= date('M d, Y', strtotime($member['subscription_end'])) ?>.
            Please renew their plan to restore access.
        </div>
    </div>
<?php endif; ?>
<?php if ($isDueToday): ?>
    <div class="alert alert-warning d-flex align-items-center border-0 shadow-sm mb-4" role="alert">
        <i class="fa fa-clock me-3 fs-4"></i>
        <div>
            <strong>Subscription Due Today!</strong><br>
            This member's subscription is due to expire today, <?= date('M d, Y', strtotime($member['subscription_end'])) ?>.
        </div>
    </div>
<?php endif; ?>
<div class="page-header">
    <h3 class="page-title">Members</h3>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="mb-0 lh-1"><?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?></h2>

                        <div>
                            <?php if ($isExpired): ?>
                                <span class="badge rounded-pill bg-danger px-3 shadow-sm">
                                    <i class="fa fa-exclamation-circle"></i> Inactive (Expired)
                                </span>
                            <?php elseif ($member['is_active'] == 1): ?>
                                <span class="badge rounded-pill bg-success px-3 shadow-sm">Active</span>
                            <?php else: ?>
                                <span class="badge rounded-pill bg-secondary px-3 shadow-sm">Inactive</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text">
                                <span class="fw-bold">Membership ID:</span> <?= $member['membership_id'] ?> <br>
                                <span class="fw-bold">Card ID:</span> <?= $member['card_number'] ?> <br>
                                <span class="fw-bold">Birth Date:</span> <?= $member['birth_date'] ?> <br>
                                <span class="fw-bold">Address:</span> <?= $member['address'] ?> <br>
                                <span class="fw-bold">Contact Number:</span> <?= $member['contact_number'] ?> <br>
                                <span class="fw-bold">Email:</span> <?= $member['email'] ?> <br>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="card-text">
                                <span class="fw-bold">Wallet:</span> ₱<?= $member['wallet'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <?php if ($member['is_active'] == 0 && !empty($member['membership_plan'])): ?>
                            <?php if (!$isExpired): ?>
                                <a class="btn btn-success btn-sm px-4 fw-bold shadow-sm" href="/viewMember/<?= $member['id'] ?>/activate-membership">
                                    <i class="fa fa-check-circle"></i> Activate Membership
                                </a>

                                <button class="btn btn-link btn-sm text-muted text-decoration-none ms-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#membershipModal"
                                    onclick="showSelectionView()">
                                    <i class="fa fa-edit"></i> Change Plan
                                </button>
                            <?php else: ?>
                                <button class="btn btn-custom btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#membershipModal">
                                    <i class="fa fa-users"></i>
                                    <?= empty($member['membership_plan']) ? 'Choose Membership Plan' : 'Manage Subscription'; ?>
                                </button>
                            <?php endif; ?>

                        <?php else: ?>
                            <button class="btn btn-custom btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#membershipModal">
                                <i class="fa fa-users"></i>
                                <?= empty($member['membership_plan']) ? 'Choose Membership Plan' : 'Manage Subscription'; ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- 
<div class="modal fade" id="membershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <?php if (empty($member['membership_plan'])): ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Select Billing Cycle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="/viewMember/<?= $member['id'] ?>/update-billing" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="member_id" value="<?= $member['id'] ?>">

                        <div class="form-group mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Subscription Plan</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input ms-1" type="radio" name="billing_cycle" id="monthly" value="Monthly" checked>
                                <label class="form-check-label" for="monthly">
                                    Monthly Plan
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input ms-1" type="radio" name="billing_cycle" id="yearly" value="Yearly">
                                <label class="form-check-label" for="yearly">
                                    Yearly Plan (Best Value)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Set Plan</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="modal-header border-0 bg-light bg-opacity-50">
                    <h5 class="modal-title fw-bold">Current Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                        <div class="flex-shrink-0 bg-secondary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                            <i class="fa fa-gem text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-muted mb-0 small text-uppercase fw-bold">Active Plan</p>
                            <h4 class="mb-0 fw-bold"><?= $member['membership_plan'] ?></h4>
                        </div>
                    </div>

                    <div class="row g-0 border-top pt-3 ">
                        <div class="col-6 border-end ps-2">
                            <p class="text-muted mb-1 small text-uppercase">Start Date</p>
                            <p class="fw-bold mb-0 text-dark"><?= date('M d, Y', strtotime($member['subscription_start'])) ?></p>
                        </div>
                        <div class="col-6 ps-4">
                            <p class="text-muted mb-1 small text-uppercase">End Date</p>
                            <p class="fw-bold mb-0 text-danger"><?= date('M d, Y', strtotime($member['subscription_end'])) ?></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="viewMember/<?= $member['id'] ?>/renew-subscription" class="btn btn-primary w-100">Renew Subscription</a>
                    <a href="viewMember/<?= $member['id'] ?>/cancel-subscription" class="btn btn-secondary w-100">Cancel Subscription</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> -->

<div class="modal fade" id="membershipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">

            <div id="selection-view" class="<?= !empty($member['membership_plan']) ? 'd-none' : '' ?>">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Select Billing Cycle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="billingForm" action="/viewMember/<?= $member['id'] ?>/update-billing" method="POST">
                    <div class="modal-body py-4">
                        <input type="hidden" name="member_id" value="<?= $member['id'] ?>">
                        <div class="form-group mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Subscription Plan</label>
                            <div class="list-group">
                                <label class="list-group-item list-group-item-action d-flex align-items-center py-3 border rounded mb-2">
                                    <input class="form-check-input me-3" type="radio" name="billing_cycle" value="Monthly" checked>
                                    <div>
                                        <span class="d-block fw-bold">Monthly Plan</span>
                                        <small class="text-muted">Billed every 30 days</small>
                                    </div>
                                </label>
                                <label class="list-group-item list-group-item-action d-flex align-items-center py-3 border rounded">
                                    <input class="form-check-input me-3" type="radio" name="billing_cycle" value="Yearly">
                                    <div>
                                        <span class="d-block fw-bold text-primary">Yearly Plan</span>
                                        <small class="text-muted">Annual billing</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" onclick="confirmSetPlan()" class="btn btn-danger px-4 fw-bold">Set Plan</button>
                    </div>
                </form>
            </div>

            <?php if (!empty($member['membership_plan'])): ?>
                <div id="display-view">
                    <div class="modal-header border-0 bg-light bg-opacity-50">
                        <h5 class="modal-title fw-bold">Current Subscription</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body py-4 text-center">
                        <div class="d-flex align-items-center mb-4 p-3 bg-light rounded-3">
                            <div class="flex-shrink-0 <?php echo $isExpired ? 'bg-secondary' : 'bg-success'; ?> rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px;">
                                <i class="fa fa-gem text-primary"></i>
                            </div>
                            <?php if ($isExpired): ?>
                                <div class="ms-3">
                                    <p class="text-muted mb-0 small text-uppercase fw-bold">Expired Plan</p>
                                    <h4 class="mb-0 fw-bold text-danger"><?= $member['membership_plan'] ?></h4>
                                </div>
                            <?php else: ?>
                                <div class="ms-3">
                                    <p class="text-muted mb-0 small text-uppercase fw-bold">Active Plan</p>
                                    <h4 class="mb-0 fw-bold"><?= $member['membership_plan'] ?></h4>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row g-0 border-top text-start">
                            <div class="col-6 border-end ps-2">
                                <p class="text-muted mb-1 small text-uppercase">Start Date</p>
                                <p class="fw-bold mb-0"><?= date('M d, Y', strtotime($member['subscription_start'])) ?></p>
                            </div>
                            <div class="col-6 ps-4">
                                <p class="text-muted mb-1 small text-uppercase text-danger">End Date</p>
                                <p class="fw-bold mb-0 text-danger"> <?php if ($isExpired) echo '<i class="fa fa-exclamation-circle"></i> '; ?><?= date('M d, Y', strtotime($member['subscription_end'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer flex-column border-0">
                        <?php if ($isExpired): ?>
                            <button type="button" onclick="handleRenew()" class="btn btn-primary w-100 fw-bold">Renew Subscription</button>
                        <?php endif; ?>
                        <button type="button" onclick="handleCancel()" class="btn btn-outline-secondary w-100 btn-sm border-0">Cancel Subscription</button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 1. Handling the Set Plan (Initial or Changing)
    function confirmSetPlan() {
        Swal.fire({
            title: 'Confirm Plan?',
            text: "This will update the member's billing cycle.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#e91e63',
            confirmButtonText: 'Yes, set it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('billingForm').submit();
            }
        });
    }

    // 2. Handling the Renew Logic
    function handleRenew() {
        Swal.fire({
            title: 'Renew Subscription',
            text: "Would you like to remain on the current plan or change to a different one?",
            icon: 'info',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#28a745',
            denyButtonColor: '#007bff',
            confirmButtonText: 'Keep Current Plan',
            denyButtonText: 'Change Plan',
            cancelButtonText: 'Go Back'
        }).then((result) => {
            if (result.isConfirmed) {
                // Option: Keep current - Proceed to renewal URL
                window.location.href = "/viewMember/<?= $member['id'] ?>/renew-membership";
            } else if (result.isDenied) {
                // Option: Change - Hide the display and show the selection form
                document.getElementById('display-view').classList.add('d-none');
                document.getElementById('selection-view').classList.remove('d-none');
            }
        });
    }

    // 3. Handling the Cancel Logic
    function handleCancel() {
        Swal.fire({
            title: 'Are you sure?',
            text: "The member will lose access after the current cycle ends.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, cancel subscription'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/viewMember/<?= $member['id'] ?>/cancel-membership";
            }
        });
    }

    function showSelectionView() {
        // Ensure the selection form is visible
        const selectionView = document.getElementById('selection-view');
        const displayView = document.getElementById('display-view');

        if (selectionView) selectionView.classList.remove('d-none');
        if (displayView) displayView.classList.add('d-none');
    }

    // Optional: Reset the modal when it's closed so it goes back to default next time
    document.getElementById('membershipModal').addEventListener('hidden.bs.modal', function() {
        const selectionView = document.getElementById('selection-view');
        const displayView = document.getElementById('display-view');

        // If a plan exists, default back to display view for next time
        <?php if (!empty($member['membership_plan'])): ?>
            if (selectionView) selectionView.classList.add('d-none');
            if (displayView) displayView.classList.remove('d-none');
        <?php endif; ?>
    });
</script>

<?php
$this->stop();
?>