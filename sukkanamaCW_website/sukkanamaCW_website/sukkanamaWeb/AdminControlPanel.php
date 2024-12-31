<?php
session_start();

// Include database connection
include 'db_connection.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are provided
    if (isset($_POST['u_name']) && isset($_POST['p_word'])) {
        // Sanitize user input to prevent SQL injection
        $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
        $p_word = mysqli_real_escape_string($conn, $_POST['p_word']);

        // Query to check if username and password match for an admin user
        $admin_sql = "SELECT * FROM Admin INNER JOIN User ON Admin.user_id = User.user_id WHERE User.u_name='$u_name' AND User.p_word='$p_word'";
        $admin_result = $conn->query($admin_sql);

        if ($admin_result === false) {
            // SQL query execution error
            die("Error executing the admin query: " . $conn->error);
        }

        if ($admin_result->num_rows == 1) {
            // Admin login successful
            $row = $admin_result->fetch_assoc();

            // Set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['u_name'] = $u_name;
            $_SESSION['user_id'] = $row['user_id'];

            // Redirect to the admin dashboard page
            header("Location: adminControlPanel.php");
            exit;
        } else {
            // Username or password is incorrect
            $login_error = "Login failed. Please check your username and password.";
        }
    } else {
        $login_error = "Username or password is missing.";
    }
}

// Count total users
$sql_users = "SELECT COUNT(*) AS total_users FROM User";
$result_users = $conn->query($sql_users);
$row_users = $result_users->fetch_assoc();
$total_users = $row_users['total_users'];

// Count total vehicle ads
$sql_vehicle_ads = "SELECT COUNT(*) AS total_vehicle_ads FROM Vehicle";
$result_vehicle_ads = $conn->query($sql_vehicle_ads);
$row_vehicle_ads = $result_vehicle_ads->fetch_assoc();
$total_vehicle_ads = $row_vehicle_ads['total_vehicle_ads'];

// Count total rates
$sql_rates = "SELECT COUNT(*) AS total_rates FROM Rate";
$result_rates = $conn->query($sql_rates);
$row_rates = $result_rates->fetch_assoc();
$total_rates = $row_rates['total_rates'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sukkanama Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('adminDashboard.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.7); /* Transparent white background */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .dashboard-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        .dashboard-item h3 {
            margin-top: 0;
        }

        .dashboard-item p {
            margin-bottom: 0;
        }

        .logout-btn {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-align: center;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Welcome to the Sukkanama Admin Dashboard</h2>
    <div class="dashboard-item">
        <h3>Recent Activity</h3>
        <!-- Display recent activity here -->
    </div>
    <div class="dashboard-item">
        <h3>Users</h3>
        <!-- Display total users and provide links for managing users -->
        <p>Total users: <?php echo $total_users; ?></p>
        <!-- Add link to manage_users.php -->
        <a href="manage_users.php">Manage Users</a>
    </div>
    <div class="dashboard-item">
        <h3>Vehicle Ads</h3>
        <!-- Display total vehicle ads and provide links for managing vehicle ads -->
        <p>Total Vehicle Ads: <?php echo $total_vehicle_ads; ?></p>
        <a href="manage_vehicle_adds.php">Manage Vehicle Ads</a>
    </div>

    <!--
    <div class="dashboard-item">
        <h3>Rates</h3>
         Display total rates and provide links for managing rates 
        <p>Total Rates: <?php echo $total_rates; ?></p>
        <a href="manage_rates.php">Manage Rates</a>
    </div>-->
    <form method="post" action="loginAdmin.php">
        <!-- Add hidden input fields for username and password -->
        <input type="hidden" name="u_name" value="<?php echo isset($_SESSION['u_name']) ? $_SESSION['u_name'] : ''; ?>">
        <input type="hidden" name="p_word" value="<?php echo isset($_SESSION['p_word']) ? $_SESSION['p_word'] : ''; ?>">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>
</body>
</html>
