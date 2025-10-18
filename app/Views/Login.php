<?php
$this->layout('Layout', ['mainContent' => $this->fetch('Layout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="brand-logo d-flex align-items-between justify-content-between">
                            <img src="../../assets/images/logo.svg">
                            <h5>Admin</h5>
                        </div>
                        <h4>Hello! let's get started</h4>
                        <h6 class="font-weight-light">Login to continue.</h6>
                        <form action="#" method="post" class="pt-3">
                            <div class="form-group mb-2">
                                <input type="text" class="form-control form-control-lg" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" placeholder="Password">
                            </div>
                            <div class="mt-3 d-grid gap-2">
                                <button type="submit" class="btn btn-block btn-gradient-danger btn-lg font-weight-medium auth-form-btn text-uppercase">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
</div>

<?php
$this->stop();
?>