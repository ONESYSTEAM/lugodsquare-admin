$(document).ready(function () {
  const origHours = parseFloat($("#orig_duration").val());
  const origStart = $("#orig_start").val();
  const origEnd = $("#orig_end").val();
  const defaultDate = $("#default_date_val").val();

  // --- 1. Automatic Check when Modal Opens ---
  // This fixes your "first click" observation
  $("#rescheduleModal").on("shown.bs.modal", function () {
    $("#res_date").trigger("change");
  });

  // --- 2. Custom Close with Confirmation ---
  $(document).on("click", "#customCloseX, #customCloseBtn", function () {
    const isDirty =
      $("#res_startTime").val() !== "" || $("#res_date").val() !== defaultDate;
    if (isDirty) {
      Swal.fire({
        title: "Discard changes?",
        text: "Unsaved changes will be lost.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, discard",
        cancelButtonText: "Stay here",
      }).then((result) => {
        if (result.isConfirmed) $("#rescheduleModal").modal("hide");
      });
    } else {
      $("#rescheduleModal").modal("hide");
    }
  });

  // --- 3. The Core Availability Logic ---
  $(document).on("change", "#res_date", function () {
    const court = $("#reschedule_court").val();
    const date = $(this).val();
    const excludeId = $("#rescheduleForm").attr("action").split("/").pop();

    if (!court || !date) return;

    $.ajax({
      url: "/get-booked-slots",
      method: "POST",
      dataType: "json",
      data: {
        court: court,
        date: date,
        exclude_id: excludeId,
      },
      success: function (response) {
        const options = $("#res_startTime option, #res_endTime option").not(
          '[hidden], [value=""]',
        );
        const now = new Date();
        const today = now.toISOString().split("T")[0];
        const currentHour = now.getHours();

        // Reset state
        options
          .prop("disabled", false)
          .removeClass("text-danger text-info fw-bold greyed-out");

        // Always clean labels before re-applying to prevent stacking or missing labels
        options.each(function () {
          $(this).text($(this).text().replace(" (Current)", ""));
        });

        // A. Disable Past Times (If Date is Today)
        if (date === today) {
          options.each(function () {
            const optHour = parseInt($(this).val().split(":")[0]);
            if (optHour <= currentHour) {
              $(this).prop("disabled", true).addClass("greyed-out");
            }
          });
        }

        // B. Highlight "Current" Slots
        if (date === defaultDate) {
          options.each(function () {
            const val = $(this).val();
            if (val >= origStart && val < origEnd) {
              $(this).addClass("text-info fw-bold");
              // This fixes your "(Current) label gone" observation
              if (!$(this).text().includes("(Current)")) {
                $(this).text($(this).text() + " (Current)");
              }
            }
          });
        }

        // C. Mark Booked Slots (Red)
        if (response.bookedSlots) {
          response.bookedSlots.forEach((slot) => {
            const s = slot.start_time.substring(0, 5);
            const e = slot.end_time.substring(0, 5);
            options.each(function () {
              const v = $(this).val();
              if (v >= s && v < e) {
                $(this).prop("disabled", true).addClass("text-danger");
              }
            });
          });
        }
      },
    });
  });

  // --- 4. Duration Validation ---
  $(document).on("change", "#res_startTime, #res_endTime", function () {
    const start = $("#res_startTime").val();
    const end = $("#res_endTime").val();
    if (start && end) {
      const duration =
        parseInt(end.split(":")[0]) - parseInt(start.split(":")[0]);
      if (duration !== origHours) {
        Swal.fire({
          icon: "warning",
          title: "Invalid Duration",
          text: `Must be exactly ${origHours} hour(s).`,
        });
        $("#res_endTime").val("");
        return;
      }
      // Check for overlaps in range
      let hasOverlap = false;
      $("#res_startTime option").each(function () {
        if (
          $(this).val() >= start &&
          $(this).val() < end &&
          $(this).prop("disabled")
        )
          hasOverlap = true;
      });
      if (hasOverlap) {
        Swal.fire({
          icon: "error",
          title: "Slot Unavailable",
          text: "This range overlaps with an existing booking.",
        });
        $("#res_startTime, #res_endTime").val("");
      }
    }
  });

  // --- 5. Modal Reset Cleanup ---
  $("#rescheduleModal").on("hidden.bs.modal", function () {
    $("#rescheduleForm")[0].reset();
    // Reset the date value explicitly to default
    $("#res_date").val(defaultDate);
    // Clean all options text
    $("#res_startTime option, #res_endTime option")
      .text(function (_, t) {
        return t.replace(" (Current)", "");
      })
      .removeClass("text-info text-danger fw-bold greyed-out")
      .prop("disabled", false);
  });

  // --- 6. Form Submission Confirmation with Validation ---
  $(document).on("submit", "#rescheduleForm", function (e) {
    e.preventDefault();
    const form = this;

    // 1. Grab inputs
    const newDate = $("#res_date").val();
    const newStart = $("#res_startTime").val();
    const newEnd = $("#res_endTime").val();

    // 2. Check if any are empty
    if (!newDate || !newStart || !newEnd) {
      Swal.fire({
        icon: "error",
        title: "Missing Information",
        text: "Please ensure Date, Start Time, and End Time are all selected before confirming.",
        confirmButtonColor: "#007bff",
      });
      return false; // Stop right here
    }

    // 3. If filled, proceed to confirmation
    const startText = $("#res_startTime option:selected").text();
    const endText = $("#res_endTime option:selected").text();

    Swal.fire({
      title: "Confirm Reschedule?",
      html: `You are moving this booking to:<br><b>${newDate}</b> at <b>${startText} - ${endText}</b>`,
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#007bff",
      cancelButtonColor: "#6c757d",
      confirmButtonText: "Yes, Update Schedule",
      cancelButtonText: "No, keep original",
    }).then((result) => {
      if (result.isConfirmed) {
        // Show processing state while email/database updates
        Swal.fire({
          title: "Updating...",
          text: "Please wait while we process the reschedule and send the email.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });

        form.submit();
      }
    });
  });
});
