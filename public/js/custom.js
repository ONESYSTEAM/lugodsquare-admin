document.addEventListener("DOMContentLoaded", function () {
    
    // Confirm Booking
    document.querySelectorAll(".confirm-booking").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const url = this.href;

            Swal.fire({
                title: "Confirm Booking?",
                text: "Are you sure you want to confirm this booking?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Cancel Booking
    document.querySelectorAll(".cancel-booking").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const url = this.href;

            Swal.fire({
                title: "Cancel Booking?",
                text: "This action cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

    // Undo Cancellation
    document.querySelectorAll(".undo-cancellation").forEach(btn => {
        btn.addEventListener("click", function (e) {
            e.preventDefault();
            const url = this.href;

            Swal.fire({
                title: "Undo Cancellation?",
                text: "Are you sure you want to undo cancellation?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Confirm",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });

});
