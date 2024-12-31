<?php
// Start the session
session_start();

// Include database connection
include 'db_connection.php';

// Function to check if the user is logged in
function checkLoggedIn() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['u_name'])) {
        header("Location: loginSupplier.php");
        exit;
    }
}

// Function to retrieve supplier details
function getSupplierDetails($conn, $u_name) {
    $sql = "SELECT * FROM Supplier s JOIN User u ON s.user_id = u.user_id WHERE u.u_name='$u_name'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Function to check if the user has already added a vehicle
function hasVehicle($conn, $user_id) {
    $sql = "SELECT COUNT(*) AS count FROM Vehicle WHERE supplier_id = $user_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Check if the user is logged in
checkLoggedIn();

// Retrieve username from session variable
$u_name = $_SESSION['u_name'];

// Retrieve supplier details
$row_supplier = getSupplierDetails($conn, $u_name);

// Check if the user has already added a vehicle
$has_vehicle = hasVehicle($conn, $row_supplier['user_id']);

// Fetch vehicle data added by the current supplier from the database
$sql_vehicle = "SELECT * FROM Vehicle WHERE supplier_id = " . $row_supplier['user_id'] . " ORDER BY vehicle_id DESC LIMIT 1"; // Assuming the last inserted record is the one to be displayed
$result_vehicle = $conn->query($sql_vehicle);
$vehicle = $result_vehicle->fetch_assoc();

// Fetch photos associated with the vehicle
$photos = [];
if ($vehicle) {
    $vehicle_id = $vehicle['vehicle_id'];
    $sql_photos = "SELECT * FROM VehiclePhotos WHERE vehicle_id = $vehicle_id";
    $result_photos = $conn->query($sql_photos);
    while ($row_photo = $result_photos->fetch_assoc()) {
        $photos[] = $row_photo['photo_filename'];
    }
}

// Function to retrieve vehicle booking details
function getVehicleBookingDetails($conn, $supplier_id) {
    $sql = "SELECT Rent.rent_id, Rent.s_date, Rent.s_time, Rent.r_date, Rent.r_time, Rent.status, Rent.cost,
       Customer.customer_id, Customer.d_licen,
       Vehicle.vehicle_id, Vehicle.brand, Vehicle.model, Vehicle.per_day_chrg,
       Vehicle.eng_capacity, Vehicle.t_mission, Vehicle.no_of_doors, Vehicle.f_type, Vehicle.yom, Vehicle.color, Vehicle.seat_capacity, Vehicle.description,
       VehiclePhotos.photo_filename,
       User.f_name, User.l_name, User.tel_no,
       Vehicle.plate
    FROM Rent
    INNER JOIN Customer ON Rent.customer_id = Customer.customer_id
    INNER JOIN Vehicle ON Rent.vehicle_id = Vehicle.vehicle_id
    INNER JOIN User ON Customer.user_id = User.user_id
    LEFT JOIN VehiclePhotos ON Vehicle.vehicle_id = VehiclePhotos.vehicle_id
    WHERE Vehicle.supplier_id = $supplier_id";

    $result = $conn->query($sql);

    // Check if query was successful
    if ($result === false) {
        // Handle error
        echo "Error executing query: " . $conn->error;
        return [];
    }

    // Fetch and return the results if there are any
    $booking_details = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Fix undefined array keys and handle null values for htmlspecialchars()
            $row['eng_capacity'] = isset($row['eng_capacity']) ? htmlspecialchars($row['eng_capacity']) : '';
            $row['t_mission'] = isset($row['t_mission']) ? htmlspecialchars($row['t_mission']) : '';
            $row['no_of_doors'] = isset($row['no_of_doors']) ? htmlspecialchars($row['no_of_doors']) : '';
            $row['f_type'] = isset($row['f_type']) ? htmlspecialchars($row['f_type']) : '';
            $row['yom'] = isset($row['yom']) ? htmlspecialchars($row['yom']) : '';
            $row['color'] = isset($row['color']) ? htmlspecialchars($row['color']) : '';
            $row['seat_capacity'] = isset($row['seat_capacity']) ? htmlspecialchars($row['seat_capacity']) : '';
            $row['description'] = isset($row['description']) ? htmlspecialchars($row['description']) : '';

            // Add first name, last name, and telephone number to the $row array
            $row['f_name'] = htmlspecialchars($row['f_name']);
            $row['l_name'] = htmlspecialchars($row['l_name']);
            $row['tel_number'] = htmlspecialchars($row['tel_no']);

            $booking_details[] = $row;
        }
    }

    return $booking_details;
}

// Retrieve vehicle booking details for the supplier's vehicles
$vehicle_booking_details = getVehicleBookingDetails($conn, $row_supplier['supplier_id']);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Add jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet"  href="style.css">
      <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
            font-size: 28px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .logout-btn {
            text-align: center;
            margin-top: 20px;
        }
        .logout-btn a {
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 16px;
            display: inline-block;
        }
        .logout-btn a:hover {
            background-color: #c82333;
        }
        .profile-section {
            margin-top: 20px;
        }
        .profile-section h2 {
            text-align: center;
        }
        .profile-section table {
            width: 50%;
            margin: 0 auto;
        }
        .profile-section table td {
            padding: 8px;
        }
        .vehicle-details {
            margin-top: 20px;
        }
        .vehicle-details p {
            margin-bottom: 10px;
            font-size: 16px;
        }
        .vehicle-photos {
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }
        .vehicle-photos img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

            .booking-details-section {
        margin-top: 20px;
        max-width: 100%; /* Adjust the width as needed */
        overflow-x: auto; /* Add horizontal scrolling if needed */
    }

    .booking-details-section table {
        width: 100%; /* Set the table width to 100% */
    }

    .booking-details-section th,
    .booking-details-section td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
        white-space: nowrap; /* Prevent line breaks within table cells */
    }

    .booking-details-section th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-transform: uppercase;
    }

    .booking-details-section tr:hover {
        background-color: #f9f9f9;
    }

    .booking-details-section .logout-btn {
        text-align: center;
        margin-top: 20px;
    }

    .booking-details-section .logout-btn a {
        padding: 10px 20px;
        background-color: #dc3545;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
        font-size: 16px;
        display: inline-block;
    }

    .booking-details-section .logout-btn a:hover {
        background-color: #c82333;
    }

    </style>
</head>
<body>


        <section id="header">
        <a href="#"><img src="logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="loginSupplier.php">Rent Your Vehicle</a></li>
                <li><a href="loginCustomer.php">Find Your Vehicle</a></li>
                <li><a href="about.html">About</a></li>
                <li><a href="contact.html">Contact</a></li>
                
</a></li>
                <a href="#" id="close"><i class="fa fa-window-close" aria-hidden="true"></i></a>
                
            </ul>
        </div>
        <div id="mobile">
            <a href="cart.html"><i class="fas fa-shopping-bag"></i></a>
            <i id="bar" class="fas fa-outdent"></i>
        </div>
    </section>





    <!-- Supplier dashboard content -->
    <div class="container">
        <h1>Supplier Dashboard</h1>
        
        <!-- Profile Section -->
        <div class="profile-section">
            <h2>Profile</h2>
            <table>
                <tbody>
                    <tr>
                        <td>User ID</td>
                        <td><?php echo $row_supplier['user_id']; ?></td>
                    </tr>
                    <tr>
                        <td>First Name</td>
                        <td><?php echo $row_supplier['f_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Last Name</td>
                        <td><?php echo $row_supplier['l_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $row_supplier['email']; ?></td>
                    </tr>
                    <tr>
                        <td>NIC</td>
                        <td><?php echo $row_supplier['nic']; ?></td>
                    </tr>
                    <tr>
                        <td>Registration Date</td>
                        <td><?php echo $row_supplier['reg_date']; ?></td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?php echo $row_supplier['u_name']; ?></td>
                    </tr>
                    <tr>
                        <td>Telephone Number</td>
                        <td><?php echo $row_supplier['tel_no']; ?></td>
                    </tr>
                    <tr>
                        <td>Tax ID</td>
                        <td><?php echo $row_supplier['tax_id']; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
<div class="vehicle-section">
    <h2>Vehicle Details</h2>
    <?php if ($vehicle) : ?>
        <div class="vehicle-details">
            <p><strong>Plate:</strong> <?php echo htmlspecialchars($vehicle['plate']); ?></p>
            <p><strong>Engine Capacity:</strong> <?php echo htmlspecialchars($vehicle['eng_capacity']); ?></p>
            <p><strong>Transmission:</strong> <?php echo htmlspecialchars($vehicle['t_mission']); ?></p>
            <p><strong>Brand:</strong> <?php echo htmlspecialchars($vehicle['brand']); ?></p>
            <p><strong>Model:</strong> <?php echo htmlspecialchars($vehicle['model']); ?></p>
            <p><strong>Number of Doors:</strong> <?php echo htmlspecialchars($vehicle['no_of_doors']); ?></p>
            <p><strong>Fuel Type:</strong> <?php echo htmlspecialchars($vehicle['f_type']); ?></p>
            <p><strong>Year of Manufacture:</strong> <?php echo htmlspecialchars($vehicle['yom']); ?></p>
            <p><strong>Color:</strong> <?php echo htmlspecialchars($vehicle['color']); ?></p>
            <p><strong>Seat Capacity:</strong> <?php echo htmlspecialchars($vehicle['seat_capacity']); ?></p>
            <p><strong>Per Day Charge:</strong> <?php echo htmlspecialchars($vehicle['per_day_chrg']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($vehicle['description']); ?></p>
        </div>

        <!-- Edit link -->
        <div class="edit-link">
            <a href="edit_vehicle.php?vehicle_id=<?php echo $vehicle['vehicle_id']; ?>">Edit Vehicle</a>
        </div>

        <?php if (!empty($photos)) : ?>
            <div class="vehicle-photos">
                <?php foreach ($photos as $photo) : ?>
                    <img src="<?php echo $photo; ?>" alt="Vehicle Photo">
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No photos available for this vehicle.</p>
        <?php endif; ?>
    <?php else : ?>
        <p>No vehicle data found.</p>
    <?php endif; ?>
</div>


        
        <!-- Display "Vehicle Add" button if no vehicle exists -->
        <?php if (!$has_vehicle) : ?>
            <div class="logout-btn">
                <a href="process_vehicle_registration.php">Vehicle Add</a>
            </div>
        <?php endif; ?>
        
        <!-- Logout button -->
        <div class="logout-btn">
            <a href="loginSupplier.php">Logout</a>
        </div>





    </div>


   

<div class="container">
    <h1>Bookings</h1>
    
    <!-- Profile Section -->
    <div class="profile-section">
        <!-- Display supplier profile details -->
    </div>
    
    <!-- Vehicle Details Section -->
    <div class="vehicle-section">
        <!-- Display vehicle details -->
    </div>
    
<!-- Vehicle Booking Details Section -->
<div class="booking-details-section">
    <h2>Vehicle Booking Details</h2>
    <table>
        <thead>
            <tr>
                <th>Rent ID</th>
                <th>Customer ID</th>
                <th>Customer First Name</th>
                <th>Customer Last Name</th>
                <th>Customer Tel Number</th>
                <th>Start Date</th>
                <th>Start Time</th>
                <th>Return Date</th>
                <th>Return Time</th>
                <th>Status</th>
                <th>Cost</th>
                <th>Plate</th> <!-- Adding plate column -->
                <th>Action</th> <!-- New column for action buttons -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicle_booking_details as $booking) : ?>
                <tr>
                    <td><?php echo $booking['rent_id']; ?></td>
                    <td><?php echo $booking['customer_id']; ?></td>
                    <td><?php echo $booking['f_name']; ?></td>
                    <td><?php echo $booking['l_name']; ?></td>
                    <td><?php echo $booking['tel_number']; ?></td>
                    <td><?php echo $booking['s_date']; ?></td>
                    <td><?php echo $booking['s_time']; ?></td>
                    <td><?php echo $booking['r_date']; ?></td>
                    <td><?php echo $booking['r_time']; ?></td>
                    <td><?php echo $booking['status']; ?></td>
                    <td><?php echo $booking['cost']; ?></td>
                    <td><?php echo $booking['plate']; ?></td> <!-- Displaying plate -->
                    <td>
                        <form action="process_booking.php" method="POST">
                            <input type="hidden" name="rent_id" value="<?php echo $booking['rent_id']; ?>">
                            <button type="submit" name="accept">Accept</button>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>






<script>
$(document).ready(function() {
    // AJAX request for Accept button
    $('button[name="accept"]').click(function(e) {
        e.preventDefault();
        var rent_id = $(this).closest('tr').find('td:first').text();
        var $statusCell = $(this).closest('tr').find('td:nth-child(10)'); // Select the cell containing the status

        $.ajax({
            type: "POST",
            url: "process_booking.php",
            data: { rent_id: rent_id, status: "accepted" }, // Include the status parameter
            success: function(response) {
                // Show success message
                alert("Booking accepted successfully.");
                // Update the status cell to "Accepted"
                $statusCell.html('<i class="fas fa-check-circle" style="color: green;"></i> Accepted');
                // Send notification to customer dashboard
                sendNotification(rent_id, "accepted");
                // Update status dynamically on customer page
                updateCustomerPage(rent_id, "Accepted");
            }
        });
    });


    // AJAX request for Reject button
    $('button[name="reject"]').click(function(e) {
        e.preventDefault();
        var rent_id = $(this).closest('tr').find('td:first').text();
        var $statusCell = $(this).closest('tr').find('td:nth-child(10)'); // Select the cell containing the status

        $.ajax({
            type: "POST",
            url: "process_booking.php",
            data: { rent_id: rent_id, action: "reject" }, // Send rent_id and action
            success: function(response) {
                // Show success message
                alert("Booking rejected successfully.");
                // Update the status cell to "Rejected"
                $statusCell.html('<i class="fas fa-times-circle" style="color: red;"></i> Rejected');
                // Send notification to customer dashboard
                sendNotification(rent_id, "rejected");
                // Update status dynamically on customer page
                updateCustomerPage(rent_id, "Rejected");
            }
        });
    });

    // Function to send notification to customer dashboard
    function sendNotification(rent_id, status) {
        $.ajax({
            type: "POST",
            url: "customer_dashboard_notification.php", // PHP script to handle notification
            data: { rent_id: rent_id, status: status },
            success: function(response) {
                // Notification sent
                console.log("Notification sent to customer dashboard.");
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
                alert("Error sending notification.");
            }
        });
    }

    // Function to update status dynamically on customer page
    function updateCustomerPage(rent_id, status) {
        // Send AJAX request to update status on customer page
        $.ajax({
            type: "POST",
            url: "update_customer_page.php", // PHP script to update customer page
            data: { rent_id: rent_id, status: status },
            success: function(response) {
                // Customer page updated
                console.log("Customer page updated.");
                // Update symbol based on status
                updateSymbol(status, rent_id);
            },
            error: function(xhr, status, error) {
                // Handle errors
                console.error(xhr.responseText);
                alert("Error updating customer page.");
            }
        });
    }

    // Function to update symbol based on status
    function updateSymbol(status, rent_id) {
        // Find the status element and update the symbol
        var $statusElement = $("td[data-rent-id='" + rent_id + "'] .status");
        if (status === 'Accepted') {
            $statusElement.html('<i class="fas fa-check-circle" style="color: green;"></i> Accepted');
        } else if (status === 'Rejected') {
            $statusElement.html('<i class="fas fa-times-circle" style="color: red;"></i> Rejected');
        } else {
            $statusElement.html('<i class="fas fa-question-circle" style="color: gray;"></i> Pending');
        }
    }
});
</script>




</div>






    


</body>
</html>
