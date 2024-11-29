function cancelBooking(bookingId) {
    if (confirm("Are you sure you want to cancel this booking?")) {
        fetch('cancelBooking.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `booking_id=${bookingId}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            location.reload(); // Reload to refresh booking list
        })
        .catch(error => console.error('Error:', error));
    }
}