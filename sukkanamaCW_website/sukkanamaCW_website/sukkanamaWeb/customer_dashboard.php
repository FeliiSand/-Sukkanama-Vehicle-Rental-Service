<?php
// Start the session
session_start();

// Include database connection
include 'db_connection.php';

// Function to check if the user is logged in
function checkLoggedIn() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['u_name'])) {
        header("Location: loginCustomer.php");
        exit;
    }
}

// Function to retrieve customer details
function getCustomerDetails($conn, $u_name) {
    $sql = "SELECT * FROM Customer c JOIN User u ON c.user_id = u.user_id WHERE u.u_name='$u_name'";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Function to retrieve booked vehicles for the customer with supplier details
function getBookedVehicles($conn, $customer_id) {
    $sql = "SELECT Rent.rent_id, Rent.s_date, Rent.s_time, Rent.r_date, Rent.r_time, Rent.status, Rent.cost,
                   Customer.customer_id, Customer.d_licen,
                   Vehicle.vehicle_id, Vehicle.plate, Vehicle.brand, Vehicle.model, Vehicle.per_day_chrg,
                   Supplier.tax_id, User.f_name AS supplier_fname, User.l_name AS supplier_lname, User.tel_no AS supplier_tel,
                   VehiclePhotos.photo_filename
            FROM Rent
            INNER JOIN Customer ON Rent.customer_id = Customer.customer_id
            INNER JOIN Vehicle ON Rent.vehicle_id = Vehicle.vehicle_id
            INNER JOIN Supplier ON Vehicle.supplier_id = Supplier.supplier_id
            INNER JOIN User ON Supplier.user_id = User.user_id
            LEFT JOIN VehiclePhotos ON Vehicle.vehicle_id = VehiclePhotos.vehicle_id
            WHERE Rent.customer_id = $customer_id";
    $result = $conn->query($sql);
    $booked_vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $booked_vehicles[] = $row;
    }
    return $booked_vehicles;
}

// Check if the user is logged in
checkLoggedIn();

// Retrieve username from session variable
$u_name = $_SESSION['u_name'];

// Retrieve customer details
$row_customer = getCustomerDetails($conn, $u_name);

// Check if $row_customer is not null
if (!$row_customer) {
    // Redirect to login page or handle the error
    header("Location: loginCustomer.php");
    exit;
}

// Retrieve booked vehicles for the customer
$booked_vehicles = getBookedVehicles($conn, $row_customer['customer_id']);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <!-- Add jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        /* Reset default margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        /* Container styles */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header styles */
        header {
            background-color: #333;
            padding: 10px 0;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        /* Section styles */
        .section {
            margin-bottom: 40px;
        }

        .section-header {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        /* Vehicle card styles */
        .vehicle-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .vehicle-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(33.33% - 20px); /* Adjust width as needed */
        }

        .vehicle-card h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
        }

        .vehicle-card p {
            margin-bottom: 5px;
            color: #666;
        }

        .vehicle-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
          <section id="header">
        <a href="#"><img src="logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="loginSupplier.php">Rent Your Vehicle</a></li>
                <li><a class="active" href="loginCustomer.php">Find Your Vehicle</a></li>
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

    <div class="container">
        <section class="section">
            <h2 class="section-header">Welcome, <?php echo $row_customer['f_name']; ?>!</h2>
            <div class="section-content">
                <p><strong>Name:</strong> <?php echo $row_customer['f_name'] . ' ' . $row_customer['l_name']; ?></p>
                <p><strong>Email:</strong> <?php echo $row_customer['email']; ?></p>
                <p><strong>Telephone:</strong> <?php echo $row_customer['tel_no']; ?></p>
                <p><strong>License Number:</strong> <?php echo $row_customer['d_licen']; ?></p>
            </div>
        </section>
        
        <section class="section">
            <h2 class="section-header">Booked Vehicles</h2>
            <div class="section-content">
                <?php if (empty($booked_vehicles)) : ?>
                    <p>No vehicles booked yet.</p>
                <?php else : ?>
                    <div class="vehicle-cards">
                        <?php foreach ($booked_vehicles as $vehicle) : ?>
                            <div class="vehicle-card">
                                <h3><?php echo $vehicle['brand'] . ' ' . $vehicle['model']; ?></h3>
                                <?php if ($vehicle['photo_filename']) : ?>
                                    <img src="<?php echo $vehicle['photo_filename']; ?>" alt="Vehicle Photo">
                                <?php endif; ?>
                                <p><strong>Plate:</strong> <?php echo $vehicle['plate']; ?></p>
                                <p><strong>Status:</strong> <?php echo $vehicle['status']; ?></p>


<!-- Display symbol based on status -->
<?php if (isset($row['status'])) : ?>
    <?php if ($row['status'] === 'Accepted') : ?>
        <p class="status"><i class="fas fa-check-circle" style="color: green;"></i> Accepted</p>
    <?php elseif ($row['status'] === 'Rejected') : ?>
        <p class="status"><i class="fas fa-times-circle" style="color: red;"></i> Rejected</p>
    <?php else : ?>
        <p class="status"><i class="fas fa-question-circle" style="color: gray;"></i> Pending</p>
    <?php endif; ?>
<?php else : ?>
    <p class="status"><i class="fas fa-question-circle" style="color: gray;"></i> Status Not Available</p>
<?php endif; ?>


                                <p><strong>Start Date:</strong> <?php echo $vehicle['s_date']; ?></p>
                                <p><strong>End Date:</strong> <?php echo $vehicle['r_date']; ?></p>
                                <p><strong>Supplier Name:</strong> <?php echo $vehicle['supplier_fname'] . ' ' . $vehicle['supplier_lname']; ?></p>
                                <p><strong>Supplier Telephone:</strong> <?php echo $vehicle['supplier_tel']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
          <div class="section">
            <a href="vehicle_ads.php" class="btn">View Vehicle Ads</a>
        </div>
    </div>
</body>
</html>
