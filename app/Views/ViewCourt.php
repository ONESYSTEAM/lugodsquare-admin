<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Courts</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/courts">Courts</a></li>
            <li class="breadcrumb-item active" aria-current="page">View Court</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card text-center">
            <?php
            if ($court['court_type'] === 'Basketball Court') {
                $imgSrc = '/img/basketball.jpg';
            } elseif ($court['court_type'] === 'Tennis Court') {
                $imgSrc = '/img/badminton.jpg';
            } else {
                $imgSrc = '/img/tennis.jpg';
            }
            ?>
            <div>
                <img src="<?= $imgSrc ?>" class="rounded mt-5" alt="court image" width="300" height="200">
            </div>
            <div class="card-body">
                <h2 class="card-text"><?= $court['court_type'] ?></h2>
                <ul class="list-group list-group-flush border">
                    <li class="list-group-item"><strong>Capacity : </strong><?= $court['capacity'] ?></li>
                    <li class="list-group-item"><strong>Amount : </strong>₱<?= $court['amount'] ?>.00</li>
                </ul>
            </div>

        </div>
    </div>
</div>

<?php
$this->stop();
?>