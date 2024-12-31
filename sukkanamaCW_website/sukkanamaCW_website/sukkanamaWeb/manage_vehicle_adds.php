<?php
// Include your database connection file here
include 'db_connection.php';

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if a delete request is made
if(isset($_POST['delete_vehicle']) && isset($_POST['vehicle_id'])) {
    // Sanitize the input to prevent SQL injection
    $vehicle_id = mysqli_real_escape_string($conn, $_POST['vehicle_id']);

    // Query to delete the vehicle and related records from other tables
    $delete_query = "
        DELETE Vehicle, Rent, Rate, VehiclePhotos
        FROM Vehicle
        LEFT JOIN Rent ON Vehicle.vehicle_id = Rent.vehicle_id
        LEFT JOIN Rate ON Vehicle.supplier_id = Rate.supplier_id
        LEFT JOIN VehiclePhotos ON Vehicle.vehicle_id = VehiclePhotos.vehicle_id
        WHERE Vehicle.vehicle_id = '$vehicle_id'
    ";
    
    // Execute the delete query
    if ($conn->query($delete_query) === TRUE) {
        // Redirect to prevent multiple deletion upon page refresh
        header("Location: manage_vehicle_ads.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Query to fetch vehicle ads with specified fields
$query = "SELECT vehicle_id, plate, eng_capacity, t_mission, brand, model, no_of_doors, f_type, yom, color, seat_capacity, per_day_chrg, description, supplier_id, user_id FROM Vehicle";
$result = $conn->query($query);

// Check if the query executed successfully
if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicle Ads</title>
    <style>


        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .edit{
            color: white;
            background-color: blue;

        }
        .delete {
             color: white;
            background-color: red;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Manage Vehicle Ads</h1>
    <!-- Add admin control panel button here -->
    <a href="AdminControlPanel.php" class="admin-btn">Admin Control Panel</a>
    <table>
        <thead>
            <tr>
                <th>Vehicle ID</th>
                <th>Plate</th>
                <th>Engine Capacity</th>
                <th>Transmission</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Number of Doors</th>
                <th>Fuel Type</th>
                <th>Year of Manufacture</th>
                <th>Color</th>
                <th>Seat Capacity</th>
                <th>Per Day Charge</th>
                <th>Description</th>
                <th>Supplier ID</th>
                <th>User ID</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Loop through each row in the result set
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['vehicle_id']}</td>";
                echo "<td>{$row['plate']}</td>";
                echo "<td>{$row['eng_capacity']}</td>";
                echo "<td>{$row['t_mission']}</td>";
                echo "<td>{$row['brand']}</td>";
                echo "<td>{$row['model']}</td>";
                echo "<td>{$row['no_of_doors']}</td>";
                echo "<td>{$row['f_type']}</td>";
                echo "<td>{$row['yom']}</td>";
                echo "<td>{$row['color']}</td>";
                echo "<td>{$row['seat_capacity']}</td>";
                echo "<td>{$row['per_day_chrg']}</td>";
                echo "<td>{$row['description']}</td>";
                echo "<td>{$row['supplier_id']}</td>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>
                        <form method='post' action='admin_edit_vehicle.php'>
                            <input type='hidden' name='id' value='{$row['vehicle_id']}'>
                            <button type='submit' name='edit_vehicle' class='edit'>Edit</button>
                        </form>
                        <form method='post' action='{$_SERVER['PHP_SELF']}' onsubmit=\"return confirm('Are you sure you want to delete this vehicle?')\">
                            <input type='hidden' name='vehicle_id' value='{$row['vehicle_id']}'>
                            <button type='submit' name='delete_vehicle' class='delete'>Delete</button>
                        </form>
                        </td>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
