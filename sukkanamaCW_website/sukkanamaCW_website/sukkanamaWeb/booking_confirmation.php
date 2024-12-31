<?php
session_start();

include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginCustomer.php");
    exit;
}

// Retrieve booking details from the database based on the booking ID
if (isset($_GET['booking_id']) && is_numeric($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Prepare SQL statement
    $sql = "SELECT * FROM Rent WHERE rent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if booking details are found
    if ($result->num_rows > 0) {
        // Fetch booking details
        $booking = $result->fetch_assoc();
    } else {
        // Redirect back to a page if booking details are not found
        header("Location: customer_dashboard.php");
        exit;
    }

    $stmt->close();
} else {
    // Redirect back to a page if booking ID is not provided or is not numeric
    header("Location: customer_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
        }
        .booking-receipt {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
        }
        .booking-receipt ul {
            list-style-type: none;
            padding: 0;
        }
        .booking-receipt li {
            margin-bottom: 10px;
        }
        .booking-receipt li strong {
            font-weight: bold;
            margin-right: 5px;
        }
        .btn-back {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        .btn-back a {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Booking Receipt</h1>

    <div class="booking-receipt">
        <ul>
            <li><strong>Booking ID:</strong> <?php echo htmlspecialchars($booking['rent_id'] ?? ''); ?></li>
            <li><strong>Vehicle ID:</strong> <?php echo htmlspecialchars($booking['vehicle_id'] ?? ''); ?></li>
            <li><strong>Customer ID:</strong> <?php echo htmlspecialchars($booking['customer_id'] ?? ''); ?></li>
            <li><strong>Start Date:</strong> <?php echo htmlspecialchars($booking['s_date'] ?? ''); ?></li>
            <li><strong>Start Time:</strong> <?php echo htmlspecialchars($booking['s_time'] ?? ''); ?></li>
            <li><strong>Return Date:</strong> <?php echo htmlspecialchars($booking['r_date'] ?? ''); ?></li>
            <li><strong>Return Time:</strong> <?php echo htmlspecialchars($booking['r_time'] ?? ''); ?></li>
            <li><strong>Status:</strong> <?php echo htmlspecialchars($booking['status'] ?? ''); ?></li>
            <li><strong>Cost:</strong> <?php echo htmlspecialchars($booking['cost'] ?? ''); ?></li>
        </ul>
    </div>

    <div class="btn-back">
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
