document.addEventListener("DOMContentLoaded", function () {
  // Confirm Booking
  document.querySelectorAll(".confirm-booking").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.href;

      Swal.fire({
        title: "Confirm Booking?",
        text: "Are you sure you want to confirm this booking?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

  // Cancel Booking
  document.querySelectorAll(".cancel-booking").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.href;

      Swal.fire({
        title: "Cancel Booking?",
        text: "This action cannot be undone.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

  // Undo Cancellation
  document.querySelectorAll(".undo-cancellation").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.href;

      Swal.fire({
        title: "Undo Cancellation?",
        text: "Are you sure you want to undo cancellation?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("bookingCalendar");
  var now = new Date();

  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    height: "auto",
    aspectRatio: 2.2,
    handleWindowResize: true,
    displayEventEnd: true,
    eventTimeFormat: {
      hour: "numeric",
      minute: "2-digit",
      meridiem: "short",
      omitZeroMinute: true,
    },
    events: "/get-booked-dates",

    eventDataTransform: function (eventData) {
      var eventEnd = new Date(eventData.end);
      if (eventEnd < now) {
        eventData.title = "COMPLETED";
        eventData.classNames = ["event-completed"];
      }
      return eventData;
    },

    eventContent: function (arg) {
      let timeText = arg.timeText.replace(":00", "");
      return {
        html: `<div class="fc-content text-truncate text-white">
                 <span class="fc-time"><b>${timeText}</b></span>
                 <span class="fc-title ml-1">${arg.event.title}</span>
               </div>`,
      };
    },

    eventDidMount: function (info) {
      info.el.style.cursor = "pointer";

      // FORCE GRAY COLOR FOR COMPLETED
      if (info.event.classNames.includes("event-completed")) {
        info.el.style.setProperty("background-color", "#6c757d", "important");
        info.el.style.setProperty("border-color", "#6c757d", "important");
      }
    },

    eventClick: function (info) {
      const props = info.event.extendedProps;
      const statusEl = document.getElementById("mdlStatus");
      const isCompleted = info.event.classNames.includes("event-completed");

      if (isCompleted) {
        statusEl.textContent = "Completed";
        statusEl.className =
          "badge rounded-pill bg-secondary text-white px-3 py-2";
      } else {
        statusEl.textContent = "Booked";
        statusEl.className =
          "badge rounded-pill bg-danger text-white px-3 py-2";
      }

      document.getElementById("mdlMemberName").textContent =
        props.memberName || "Guest";
      document.getElementById("mdlCourtName").textContent =
        props.courtName || "General Court";
      document.getElementById("mdlBookingTime").textContent =
        props.timeRange || "TBA";

      var myModal = new bootstrap.Modal(
        document.getElementById("bookingDetailsModal"),
      );
      myModal.show();
    },
  });

  calendar.render();
});
