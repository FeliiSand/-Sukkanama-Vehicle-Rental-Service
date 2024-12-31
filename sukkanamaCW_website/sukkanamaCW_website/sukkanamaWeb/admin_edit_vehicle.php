<?php
session_start();

// Include database connection
include 'db_connection.php';

// Initialize variables
$vehicle = null;
$error_message = '';

// Fetch vehicle details if vehicle ID is provided in the URL
if(isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $sql = "SELECT * FROM Vehicle WHERE vehicle_id = $vehicle_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $vehicle = $result->fetch_assoc();
    } else {
        $error_message = "Vehicle not found.";
    }
}

// Handle form submission to update vehicle details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_vehicle'])) {
    // Retrieve vehicle ID from form
    $vehicle_id = $_POST['vehicle_id'];

    // Retrieve other form data
    $plate = $_POST['plate'];
    $eng_capacity = $_POST['eng_capacity'];
    $t_mission = $_POST['t_mission'];
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $no_of_doors = $_POST['no_of_doors'];
    $f_type = $_POST['f_type'];
    $yom = $_POST['yom'];
    $color = $_POST['color'];
    $seat_capacity = $_POST['seat_capacity'];
    $per_day_chrg = $_POST['per_day_chrg'];
    $description = $_POST['description'];
    $supplier_id = $_POST['supplier_id'];
    $user_id = $_POST['user_id'];

    // Update vehicle details
    $sql = "UPDATE Vehicle SET plate='$plate', eng_capacity=$eng_capacity, t_mission='$t_mission', brand='$brand', model='$model', no_of_doors='$no_of_doors', f_type='$f_type', yom='$yom', color='$color', seat_capacity=$seat_capacity, per_day_chrg=$per_day_chrg, description='$description', supplier_id=$supplier_id, user_id=$user_id WHERE vehicle_id=$vehicle_id";
    if ($conn->query($sql) === TRUE) {
        // Redirect to manage vehicles page
        header("Location: manage_vehicle_adds.php");
        // Alert message
        echo "<script>alert('Vehicle details updated successfully.');</script>";
        exit;
    } else {
        $error_message = "Error updating vehicle: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

  form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

.container {
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="text"],
select,
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    height: 100px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    float: right;
}

button:hover {
    background-color: #45a049;
}

.error-message {
    color: red;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <h2>Edit Vehicle</h2>
    <?php if ($error_message): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

     <!-- Add back button here -->
                <th><a href="manage_vehicle_adds.php" class="admin-btn">Back</a></th>

                
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="vehicle_id">Enter Vehicle ID:</label>
        <input type="text" id="vehicle_id" name="vehicle_id" value="<?php echo isset($_GET['vehicle_id']) ? $_GET['vehicle_id'] : ''; ?>">
        <button type="submit">Edit</button>
    </form>

    <?php if ($vehicle): ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle['vehicle_id']; ?>">
            <!-- Add input fields for vehicle details -->
            <label for="plate">Plate:</label>
            <input type="text" id="plate" name="plate" value="<?php echo $vehicle['plate']; ?>"><br><br>
            <label for="eng_capacity">Engine Capacity:</label>
            <input type="text" id="eng_capacity" name="eng_capacity" value="<?php echo $vehicle['eng_capacity']; ?>"><br><br>
            <label for="t_mission">Transmission:</label>
            <select id="t_mission" name="t_mission">
                <option value="Auto" <?php if($vehicle['t_mission'] == 'Auto') echo 'selected'; ?>>Auto</option>
                <option value="Manual" <?php if($vehicle['t_mission'] == 'Manual') echo 'selected'; ?>>Manual</option>
            </select><br><br>
            <label for="brand">Brand:</label>
            <input type="text" id="brand" name="brand" value="<?php echo $vehicle['brand']; ?>"><br><br>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" value="<?php echo $vehicle['model']; ?>"><br><br>
            <label for="no_of_doors">Number of Doors:</label>
            <select id="no_of_doors" name="no_of_doors">
                <option value="1" <?php if($vehicle['no_of_doors'] == '1') echo 'selected'; ?>>1</option>
                <option value="2" <?php if($vehicle['no_of_doors'] == '2') echo 'selected'; ?>>2</option>
                <option value="3" <?php if($vehicle['no_of_doors'] == '3') echo 'selected'; ?>>3</option>
                <option value="4" <?php if($vehicle['no_of_doors'] == '4') echo 'selected'; ?>>4</option>
                <option value="5" <?php if($vehicle['no_of_doors'] == '5') echo 'selected'; ?>>5</option>
            </select><br><br>
            <label for="f_type">Fuel Type:</label>
            <select id="f_type" name="f_type">
                <option value="Petrol" <?php if($vehicle['f_type'] == 'Petrol') echo 'selected'; ?>>Petrol</option>
                <option value="Diesel" <?php if($vehicle['f_type'] == 'Diesel') echo 'selected'; ?>>Diesel</option>
                <option value="Hybrid" <?php if($vehicle['f_type'] == 'Hybrid') echo 'selected'; ?>>Hybrid</option>
                <option value="Electric" <?php if($vehicle['f_type'] == 'Electric') echo 'selected'; ?>>Electric</option>
            </select><br><br>
            <label for="yom">Year of Manufacture:</label>
            <input type="text" id="yom" name="yom" value="<?php echo $vehicle['yom']; ?>"><br><br>
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" value="<?php echo $vehicle['color']; ?>"><br><br>
            <label for="seat_capacity">Seat Capacity:</label>
            <input type="text" id="seat_capacity" name="seat_capacity" value="<?php echo $vehicle['seat_capacity']; ?>"><br><br>
            <label for="per_day_chrg">Per Day Charge:</label>
            <input type="text" id="per_day_chrg" name="per_day_chrg" value="<?php echo $vehicle['per_day_chrg']; ?>"><br><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description"><?php echo $vehicle['description']; ?></textarea><br><br>
            <label for="supplier_id">Supplier ID:</label>
            <input type="text" id="supplier_id" name="supplier_id" value="<?php echo $vehicle['supplier_id']; ?>"><br><br>
            <label for="user_id">User ID:</label>
            <input type="text" id="user_id" name="user_id" value="<?php echo $vehicle['user_id']; ?>"><br><br>
            <button type="submit" name="update_vehicle">Update</button>
        </form>
    <?php endif; ?>
</body>
</html>
