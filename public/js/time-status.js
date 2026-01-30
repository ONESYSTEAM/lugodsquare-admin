function parseDateTime(str) {
  const [datePart, timePart] = str.split(" ");
  const [year, month, day] = datePart.split("-").map(Number);
  const [hour, min, sec] = timePart.split(":").map(Number);
  return new Date(year, month - 1, day, hour, min, sec);
}

function updateTimeStatus() {
  const now = new Date();

  document.querySelectorAll(".time-status").forEach((el) => {
    const startTime = parseDateTime(el.dataset.start || el.dataset.startTime);
    const endTime = parseDateTime(el.dataset.end || el.dataset.endTime);

    const remaining = (endTime - now) / 1000; // seconds

    let statusText = "";
    let bootstrapClass = "bg-secondary"; // default gray
    let showReschedule = false; // logic for button visibility

    if (now < startTime) {
      statusText = "Pending";
      bootstrapClass = "bg-secondary";
      showReschedule = true; // Only show if still pending
    } else if (now >= startTime && now < endTime) {
      if (remaining <= 300) {
        // 5 minutes
        statusText = "Ending Soon";
        bootstrapClass = "bg-warning";
      } else {
        statusText = "Ongoing";
        bootstrapClass = "bg-success";
      }
      showReschedule = false; // Hide if ongoing
    } else {
      statusText = "Ended";
      bootstrapClass = "bg-danger";
      showReschedule = false; // Hide if ended
    }

    // Update badge text and classes
    el.textContent = statusText;
    el.className = "time-status badge " + bootstrapClass;

    // --- Handle Reschedule Button Visibility ---
    // We find the button with the specific data-bs-target inside the same parent card
    const cardBody = el.closest(".card").querySelector(".card-body");
    const rescheduleBtn = cardBody
      ? cardBody.querySelector('[data-bs-target="#rescheduleModal"]')
      : null;

    if (rescheduleBtn) {
      if (showReschedule) {
        rescheduleBtn.style.display = "inline-block";
      } else {
        rescheduleBtn.style.display = "none";
      }
    }
  });
}

// Initial call + every 10 seconds
updateTimeStatus();
setInterval(updateTimeStatus, 10000);
