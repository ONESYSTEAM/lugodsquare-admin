function parseDateTime(str) {
    const [datePart, timePart] = str.split(' ');
    const [year, month, day] = datePart.split('-').map(Number);
    const [hour, min, sec] = timePart.split(':').map(Number);
    return new Date(year, month - 1, day, hour, min, sec);
}

function updateTimeStatus() {
    const now = new Date();

    document.querySelectorAll('.time-status').forEach(el => {
        const startTime = parseDateTime(el.dataset.start || el.dataset.startTime);
        const endTime   = parseDateTime(el.dataset.end || el.dataset.endTime);

        const remaining = (endTime - now) / 1000; // seconds

        let statusText = '';
        let bootstrapClass = 'bg-secondary'; // default gray

        if (now < startTime) {
            statusText = 'Pending';
            bootstrapClass = 'bg-secondary';
        } else if (now >= startTime && now < endTime) {
            if (remaining <= 300) { // 5 minutes
                statusText = 'Ending Soon';
                bootstrapClass = 'bg-warning';
            } else {
                statusText = 'Ongoing';
                bootstrapClass = 'bg-success';
            }
        } else {
            statusText = 'Ended';
            bootstrapClass = 'bg-danger';
        }

        // Update badge text
        el.textContent = statusText;

        // Reset Bootstrap classes and add new one
        el.className = 'time-status badge ' + bootstrapClass;
    });
}

// Initial call + every 10 seconds
updateTimeStatus();
setInterval(updateTimeStatus, 10000);
