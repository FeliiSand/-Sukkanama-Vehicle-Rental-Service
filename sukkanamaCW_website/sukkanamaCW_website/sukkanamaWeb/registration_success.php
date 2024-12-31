<?php
// Include database connection
include 'db_connection.php';

// Fetch vehicle data from the database
$sql = "SELECT * FROM Vehicle ORDER BY vehicle_id DESC LIMIT 1"; // Assuming the last inserted record is the one to be displayed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch data and store it in an associative array
    $vehicle = $result->fetch_assoc();

    // Fetch photos associated with the vehicle
    $sql_photos = "SELECT * FROM VehiclePhotos WHERE vehicle_id = " . $vehicle['vehicle_id'];
    $result_photos = $conn->query($sql_photos);
    $photos = [];
    if ($result_photos->num_rows > 0) {
        // Store photo filenames in an array
        while ($row = $result_photos->fetch_assoc()) {
            $photos[] = $row['photo_filename'];
        }
    }
} else {
    $vehicle = null;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Details</title>
    <link rel="stylesheet" href="style.css"> <!-- Include your CSS file here -->
    <style>
        /* Add your custom styles here */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
          .container h2 {
           text-align: center;
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Vehicle Details</h2>
        <?php if ($vehicle) : ?>
            <div class="vehicle-details">
                <p><strong>Plate:</strong> <?php echo isset($vehicle['plate']) ? htmlspecialchars($vehicle['plate']) : ''; ?></p>
                <p><strong>Engine Capacity:</strong> <?php echo isset($vehicle['eng_capacity']) ? htmlspecialchars($vehicle['eng_capacity']) : ''; ?></p>
                <p><strong>Transmission:</strong> <?php echo isset($vehicle['t_mission']) ? htmlspecialchars($vehicle['t_mission']) : ''; ?></p>
                <p><strong>Brand:</strong> <?php echo isset($vehicle['brand']) ? htmlspecialchars($vehicle['brand']) : ''; ?></p>
                <p><strong>Model:</strong> <?php echo isset($vehicle['model']) ? htmlspecialchars($vehicle['model']) : ''; ?></p>
                <p><strong>Number of Doors:</strong> <?php echo isset($vehicle['no_of_doors']) ? htmlspecialchars($vehicle['no_of_doors']) : ''; ?></p>
                <p><strong>Fuel Type:</strong> <?php echo isset($vehicle['f_type']) ? htmlspecialchars($vehicle['f_type']) : ''; ?></p>
                <p><strong>Year of Manufacture:</strong> <?php echo isset($vehicle['yom']) ? htmlspecialchars($vehicle['yom']) : ''; ?></p>
                <p><strong>Color:</strong> <?php echo isset($vehicle['color']) ? htmlspecialchars($vehicle['color']) : ''; ?></p>
                <p><strong>Seat Capacity:</strong> <?php echo isset($vehicle['seat_capacity']) ? htmlspecialchars($vehicle['seat_capacity']) : ''; ?></p>
                <p><strong>Per Day Charge:</strong> <?php echo isset($vehicle['per_day_chrg']) ? htmlspecialchars($vehicle['per_day_chrg']) : ''; ?></p>
                <p><strong>Description:</strong> <?php echo isset($vehicle['description']) ? htmlspecialchars($vehicle['description']) : ''; ?></p>
            </div>
            <?php if (!empty($photos)) : ?>
                <div class="vehicle-photos">
                    <?php foreach ($photos as $photo) : ?>
                        <img src="<?php echo $photo; ?>" alt="Vehicle Photo">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <p>No vehicle data found.</p>
        <?php endif; ?>


          <!-- Profile Button -->
        <div style="text-align: center; margin-top: 20px;">
            <a href="supplier_Dashboard.php" style="text-decoration: none; background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 5px;">Profile</a>
        </div>

        
    </div>
</body>
</html>
