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

    const remaining = (endTime - now) / 1000;

    let statusText = "";
    let bootstrapClass = "bg-secondary";
    let showReschedule = false;

    if (now < startTime) {
      statusText = "Pending";
      bootstrapClass = "bg-secondary";
      showReschedule = true;
    } else if (now >= startTime && now < endTime) {
      if (remaining <= 300) {
        statusText = "Ending Soon";
        bootstrapClass = "bg-warning";
      } else {
        statusText = "Ongoing";
        bootstrapClass = "bg-success";
      }
      showReschedule = false;
    } else {
      statusText = "Ended";
      bootstrapClass = "bg-danger";
      showReschedule = false;
    }

    el.textContent = statusText;
    el.className = "time-status badge " + bootstrapClass;

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

updateTimeStatus();
setInterval(updateTimeStatus, 10000);
