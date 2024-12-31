<?php
// Start the session (if not already started)
session_start();

// Include database connection
include 'db_connection.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Handle the case where the user is not logged in
        // Redirect or display an error message
        // For example:
        echo "You must be logged in to register a vehicle.";
        exit; // Exit the script
    }

    // Check if the user ID is set in the session
    if (!isset($_SESSION['user_id'])) {
        // Handle the case where the user ID is not set in the session
        // Redirect or display an error message
        // For example:
        echo "User ID is not set in the session.";
        exit; // Exit the script
    }

    // Get the supplier's user ID from the session
    $supplier_id = $_SESSION['user_id'];

    // Extract data from the form
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

    // Prepare and execute the SQL statement to insert the vehicle details
    $sql = "INSERT INTO Vehicle (plate, eng_capacity, t_mission, brand, model, no_of_doors, f_type, yom, color, seat_capacity, per_day_chrg, description, supplier_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisssssssisss", $plate, $eng_capacity, $t_mission, $brand, $model, $no_of_doors, $f_type, $yom, $color, $seat_capacity, $per_day_chrg, $description, $supplier_id);
    
    // Check if the statement was prepared correctly
    if ($stmt === false) {
        die("Error: Unable to prepare statement");
    }

    // Execute the statement
    $stmt->execute();

    // Check if the execution was successful
    if ($stmt === false) {
        die("Error: Unable to execute statement");
    }

    // Get the auto-generated vehicle ID
    $vehicle_id = $conn->insert_id;

    // Upload and insert vehicle photo
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        $photo_filename = $target_file;

        // Prepare and execute the SQL statement to insert vehicle photo
        $sql_photo = "INSERT INTO VehiclePhotos (vehicle_id, photo_filename) VALUES (?, ?)";
        $stmt_photo = $conn->prepare($sql_photo);

        // Check if the statement was prepared correctly
        if ($stmt_photo === false) {
            die("Error: Unable to prepare statement for photo");
        }

        // Bind parameters and execute the statement
        $stmt_photo->bind_param("is", $vehicle_id, $photo_filename);
        $stmt_photo->execute();

        // Check if the execution was successful
        if ($stmt_photo === false) {
            die("Error: Unable to execute statement for photo");
        }

        // Close the statement
        $stmt_photo->close();
    } else {
        die("Error uploading file");
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $conn->close();

    // Redirect to a success page or display a success message
    header("Location: registration_success.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Registration Form</title>
</head>
<body>
    <h2>Vehicle Registration Form</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
        <label for="plate">Plate:</label>
        <input type="text" id="plate" name="plate" required><br><br>
        
        <label for="eng_capacity">Engine Capacity:</label>
        <input type="text" id="eng_capacity" name="eng_capacity" required><br><br>
        
        <label for="t_mission">Transmission:</label>
        <select id="t_mission" name="t_mission" required>
            <option value="Auto">Auto</option>
            <option value="Manual">Manual</option>
        </select><br><br>
        
        <label for="brand">Brand:</label>
        <select id="brand" name="brand" required>
            <option value="Audi">Audi</option>
            <option value="BMW">BMW</option>
            <option value="Daihatsu">Daihatsu</option>
            <option value="Dimo">Dimo</option>
            <option value="Ford">Ford</option>
            <option value="Honda">Honda</option>
            <option value="Hyundai">Hyundai</option>
            <option value="Isuzu">Isuzu</option>
            <option value="Jeep">Jeep</option>
            <option value="KIA">KIA</option>
            <option value="Mazda">Mazda</option>
            <option value="Benze">Benze</option>
            <option value="Mitsubishi">Mitsubishi</option>
            <option value="Nissan">Nissan</option>
            <option value="Perodua">Perodua</option>
            <option value="suzuki">Suzuki</option>
            <option value="Toyota">Toyota</option>
            <option value="Micro">Micro</option>
        </select><br><br>
        
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><br><br>
        
        <label for="no_of_doors">Number of Doors:</label>
        <select id="no_of_doors" name="no_of_doors" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select><br><br>
        
        <label for="f_type">Fuel Type:</label>
        <select id="f_type" name="f_type" required>
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Hybrid">Hybrid</option>
            <option value="Electric">Electric</option>
        </select><br><br>
        
        <label for="yom">Year of Manufacture:</label>
        <select id="yom" name="yom" required>
            <?php 
            // Generate options for years
            for ($year = date("Y"); $year >= 2000; $year--) {
                echo "<option value='{$year}'>{$year}</option>";
            }
            ?>
        </select><br><br>
        
        <label for="color">Color:</label>
        <input type="text" id="color" name="color" required><br><br>
        
        <label for="seat_capacity">Seat Capacity:</label>
        <input type="number" id="seat_capacity" name="seat_capacity" required><br><br>
        
        <label for="per_day_chrg">Per Day Charge:</label>
        <input type="number" id="per_day_chrg" name="per_day_chrg" step="500" required><br><br>
        
        <label for="description">Description:</label><br>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea><br><br>
        
        <label for="photo">Photo:</label>
        <input type="file" id="photo" name="photo" accept="image/*" required><br><br>
        
        <input type="submit" name="submit" value="Update">
    </form>

    <!-- Profile Button -->
    <div style="text-align: center; margin-top: 20px;">
        <a href="supplier_Dashboard.php" style="text-decoration: none; background-color: #007bff; color: #fff; padding: 10px 20px; border-radius: 5px;">Profile</a>
    </div>

</body>
</html>
