<?php
$this->layout('Layout/DashboardLayout', ['mainContent' => $this->fetch('Layout/DashboardLayout')]);
$this->start('mainContent');
$this->insert('Errors/Toasts');
?>

<style>
    #calendar-wrapper {
        max-width: 1000px;
        margin: 0 auto;
    }

    .fc .fc-daygrid-day-frame {
        min-height: 80px !important;
    }

    .fc-daygrid-event {
        border-radius: 4px;
        padding: 2px 5px;
        border: none !important;
        background-color: #dc3545 !important;
        color: white !important;
        font-size: 0.75rem;
    }

    .fc-event-title {
        font-weight: 600 !important;
        text-transform: uppercase;
    }

    .fc-day-today {
        background: rgba(220, 53, 69, 0.05) !important;
    }

    .fc-button-primary {
        background-color: #343a40 !important;
        border-color: #343a40 !important;
        text-transform: capitalize;
        padding: 0.25rem 0.5rem !important;
        font-size: 0.85rem !important;
    }

    .fc-button-primary:hover {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }

    .event-completed {
        opacity: 0.7;
        text-decoration: line-through;
    }

    .fc a {
        text-decoration: none !important;
        color: inherit !important;
    }

    .fc .fc-daygrid-day-number {
        text-decoration: none !important;
        font-weight: 500;
    }

    .fc .fc-col-header-cell-cushion,
    .fc .fc-toolbar-title {
        text-decoration: none !important;
    }

    #bookingDetailsModal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    #bookingDetailsModal .modal-header {
        padding: 1.5rem 1.5rem 0.5rem;
    }

    #bookingDetailsModal .modal-title {
        font-size: 1.1rem;
        color: #2d3436;
        letter-spacing: -0.02em;
    }

    #bookingDetailsModal .modal-body {
        padding: 1.5rem;
    }

    #bookingDetailsModal label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #a0a0a0;
        margin-bottom: 4px;
    }

    #mdlMemberName {
        font-size: 1.05rem;
        color: #2d3436;
        display: block;
    }

    #mdlBookingTime {
        font-size: 0.95rem;
        color: #636e72;
        font-weight: 500;
    }

    #mdlCourtName {
        background-color: #f1f2f6 !important;
        color: #6c5ce7 !important;
        border: 1px solid #dcdde1;
        padding: 6px 12px;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 6px;
        text-transform: none;
    }

    #bookingDetailsModal .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        color: #495057;
        font-weight: 600;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    #bookingDetailsModal .btn-light:hover {
        background-color: #f1f2f6;
        color: #2d3436;
    }

    #bookingDetailsModal label {
        font-size: 0.65rem !important;
        opacity: 0.7;
    }

    #mdlMemberName {
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .badge.rounded-pill {
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

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