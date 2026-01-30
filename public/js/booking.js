$(document).ready(function () {
  const origHours = parseFloat($("#orig_duration").val());
  const origStart = $("#orig_start").val();
  const origEnd = $("#orig_end").val();
  const defaultDate = $("#default_date_val").val();

  $("#rescheduleModal").on("shown.bs.modal", function () {
    $("#res_date").trigger("change");
  });

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

        options
          .prop("disabled", false)
          .removeClass("text-danger text-info fw-bold greyed-out");

        options.each(function () {
          $(this).text($(this).text().replace(" (Current)", ""));
        });

        if (date === today) {
          options.each(function () {
            const optHour = parseInt($(this).val().split(":")[0]);
            if (optHour <= currentHour) {
              $(this).prop("disabled", true).addClass("greyed-out");
            }
          });
        }

        if (date === defaultDate) {
          options.each(function () {
            const val = $(this).val();
            if (val >= origStart && val < origEnd) {
              $(this).addClass("text-info fw-bold");
              if (!$(this).text().includes("(Current)")) {
                $(this).text($(this).text() + " (Current)");
              }
            }
          });
        }

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

  $("#rescheduleModal").on("hidden.bs.modal", function () {
    $("#rescheduleForm")[0].reset();
    $("#res_date").val(defaultDate);
    $("#res_startTime option, #res_endTime option")
      .text(function (_, t) {
        return t.replace(" (Current)", "");
      })
      .removeClass("text-info text-danger fw-bold greyed-out")
      .prop("disabled", false);
  });

  $(document).on("submit", "#rescheduleForm", function (e) {
    e.preventDefault();
    const form = this;

    const newDate = $("#res_date").val();
    const newStart = $("#res_startTime").val();
    const newEnd = $("#res_endTime").val();

    if (!newDate || !newStart || !newEnd) {
      Swal.fire({
        icon: "error",
        title: "Missing Information",
        text: "Please ensure Date, Start Time, and End Time are all selected before confirming.",
        confirmButtonColor: "#007bff",
      });
      return false;
    }

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
