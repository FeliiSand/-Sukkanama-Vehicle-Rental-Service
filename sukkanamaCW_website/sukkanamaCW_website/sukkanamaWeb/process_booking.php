<?php
session_start();

include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Check if customer ID is set in the session
    if (!isset($_SESSION['user_id'])) {
        // Handle the case where the user is not logged in or the session expired
        header("Location: loginCustomer.php");
        exit;
    }

// Retrieve form data
$start_date = $_POST['start_date'];
$start_time = $_POST['start_time'];
$return_date = $_POST['return_date'];
$return_time = $_POST['return_time'];
$status = $_POST['status'];
$cost = $_POST['cost'];

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Initialize customer ID
$customer_id = null;

// Check if the user is a customer
$sql_check_customer = "SELECT customer_id FROM Customer WHERE user_id = ?";
$stmt_check_customer = $conn->prepare($sql_check_customer);
$stmt_check_customer->bind_param("i", $user_id);
$stmt_check_customer->execute();
$stmt_check_customer->store_result();

// If the user is a customer, retrieve the customer ID
if ($stmt_check_customer->num_rows > 0) {
    $stmt_check_customer->bind_result($customer_id);
    $stmt_check_customer->fetch();
}

// Close the statement
$stmt_check_customer->close();

// Retrieve vehicle ID from form
$vehicle_id = $_POST['vehicle_id'];

// Insert booking details into Rent table
$sql = "INSERT INTO Rent (rent_id, customer_id, vehicle_id, s_date, s_time, r_date, r_time, status, cost) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisssssd", $customer_id, $vehicle_id, $start_date, $start_time, $return_date, $return_time, $status, $cost);

    
    if (!$stmt) {
        die('Error in preparing the statement: ' . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("iisssssd", $customer_id, $vehicle_id, $start_date, $start_time, $return_date, $return_time, $status, $cost);
    
    // Execute the statement
    $stmt->execute();

    // Check if the execution was successful
    if ($stmt->affected_rows > 0) {
        // Redirect to a success page or display a success message
        header("Location: booking_confirmation.php");
        exit;
    } else {
        // Redirect to an error page or display an error message
        header("Location: booking_error.php");
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vehicle</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #0056b3;
        }
        .btn-see-vehicles {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-see-vehicles:hover {
            background-color: #0056b3;
        }

        .btn-calculateCost {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn-calculateCost:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <h1>Book Vehicle</h1>

    <?php
    // Check if vehicle ID is set in URL parameters
    if (!isset($_GET['vehicle_id']) || !is_numeric($_GET['vehicle_id'])) {
        // Redirect back to the vehicle ads page if vehicle ID is not provided or is not numeric
        header("Location: vehicle_ads.php");
        exit;
    }

    // Retrieve vehicle ID from URL parameters
    $vehicle_id = $_GET['vehicle_id'];

    // Retrieve vehicle details based on vehicle ID
    $sql = "SELECT * FROM Vehicle WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if vehicle details are found
    if ($result->num_rows > 0) {
        // Fetch vehicle details
        $vehicle = $result->fetch_assoc();
    } else {
        // Redirect back to the vehicle ads page if vehicle details are not found
        header("Location: vehicle_ads.php");
        exit;
    }

    $stmt->close();
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="vehicle_id" value="<?php echo htmlspecialchars($vehicle_id); ?>">
        <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($_SESSION['user_id'] ?? ''); ?>">
        
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="start_time">Start Time:</label>
        <input type="time" id="start_time" name="start_time" required>

        <label for="return_date">Return Date:</label>
        <input type="date" id="return_date" name="return_date" required>

        <label for="return_time">Return Time:</label>
        <input type="time" id="return_time" name="return_time" required>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required>

        <label for="cost">Cost:</label>
        <input type="number" id="cost" name="cost" required step="0.01" readonly>

        <input type="hidden" id="cost_per_day" value="<?php echo htmlspecialchars($vehicle['per_day_chrg']); ?>">

        <!-- Add the "Calculate Cost" button here -->
        <button type="button" class="btn-calculateCost" onclick="calculateCost()">Calculate Cost</button>

        <button type="submit" name="submit">Book Now</button>
        
        <!-- Add the "See Vehicles" button here -->
        <button class="btn-see-vehicles" onclick="navigateToVehicles()">See Vehicles</button>
    </form>

    <script>
        function calculateCost() {
            var startDate = new Date(document.getElementById('start_date').value);
            var startTime = document.getElementById('start_time').value.split(':');
            startDate.setHours(parseInt(startTime[0]), parseInt(startTime[1]), 0, 0);

            var returnDate = new Date(document.getElementById('return_date').value);
            var returnTime = document.getElementById('return_time').value.split(':');
            returnDate.setHours(parseInt(returnTime[0]), parseInt(returnTime[1]), 0, 0);

            var timeDifference = returnDate - startDate; // Difference in milliseconds
            var hoursDifference = timeDifference / (1000 * 60 * 60); // Convert milliseconds to hours
            var costPerDay = parseFloat(document.getElementById('cost_per_day').value); // Get cost per day
            var totalCost = Math.max(1, Math.ceil(hoursDifference / 24)) * costPerDay; // Calculate total cost

            document.getElementById('cost').value = totalCost.toFixed(2); // Set the calculated cost in the input field
        }

        function navigateToVehicles() {
            window.location.href = 'vehicle_ads.php';
        }
    </script>
</body>
</html>
