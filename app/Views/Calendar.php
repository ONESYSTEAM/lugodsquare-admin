<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<div class="page-header">
    <h3 class="page-title">Booking Calendar</h3>
</div>

<div id="calendar-wrapper">
    <div id="bookingCalendar" class="bg-white p-3 rounded shadow-sm border"></div>
</div>

<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content">
            <div class="modal-header border-0 d-flex justify-content-between">
                <h5 class="modal-title fw-bold">Booking Details</h5>

                <span id="mdlStatus" class="badge rounded-pill px-3 py-2">--</span>
            </div>
            <hr class="m-0">
            <div class="modal-body">
                <div class="mb-4">
                    <label>Member Name</label> <br>
                    <span id="mdlMemberName" class="fw-bold">-</span>
                </div>
                <div class="mb-4">
                    <label>Court Location</label>
                    <div><span id="mdlCourtName" class="badge">-</span></div>
                </div>
                <div class="mb-2">
                    <label>Schedule</label>
                    <div id="mdlBookingTime">-</div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php
$this->stop();
?>