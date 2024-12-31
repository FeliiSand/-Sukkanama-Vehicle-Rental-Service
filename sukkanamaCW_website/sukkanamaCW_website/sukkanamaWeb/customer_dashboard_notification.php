<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <!-- Include jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- Your HTML content here -->
    <!-- For example, a button to trigger the notification -->
    <button onclick="sendNotification(123, 'accepted')">Send Notification</button>

    <!-- Include the sendNotification function script -->
    <script>
        // Function to send notification to customer dashboard
        function sendNotification(rent_id, status) {
            $.ajax({
                type: "POST",
                url: "customer_dashboard_notification.php", // PHP script to handle notification
                data: { rent_id: rent_id, status: status },
                success: function(response) {
                    // Notification sent
                    alert("Notification sent to customer dashboard.");
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                    alert("Error sending notification.");
                }
            });
        }
    </script>
</body>
</html>
