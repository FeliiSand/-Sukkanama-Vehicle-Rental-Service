<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet"  href="style.css">
    <title>Sukkanama Vehicle Ads</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
        }
        .vehicle-ad {
            border: 1px solid #ccc;
            margin-bottom: 20px;
            padding: 10px;
            width: 300px;
            display: inline-block;
            vertical-align: top;
            margin-right: 20px;
            box-sizing: border-box;
        }
        .vehicle-ad img {
            max-width: 100%;
            height: auto;
        }
        .vehicle-ad ul {
            list-style: none;
            padding: 0;
        }
        .vehicle-ad ul li {
            margin-bottom: 5px;
        }
        .book-now-btn {
            display: block;
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .book-now-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

           <section id="header">
        <a href="#"><img src="logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a  href="loginSupplier.php">Rent Your Vehicle</a></li>
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



    <h1>Vehicle Ads</h1>



    <!-- Add a logout button -->
<form action="loginCustomer.php" method="post">
    <button type="submit">Logout</button>
</form>


<!-- Filter section -->
<div class="filters">
    <label for="brand-filter">Filter by Brand:</label>
    <select id="brand-filter" onchange="filterAds()">
        <option value="all">All Brands</option>
        <?php
        // Include database connection
        include 'db_connection.php';

        // Retrieve distinct brands for filtering options
        $sql = "SELECT DISTINCT brand FROM Vehicle";
        $result = $conn->query($sql);

        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['brand']) . "'>" . htmlspecialchars($row['brand']) . "</option>";
            }
        } else {
            echo "<option value=''>No brands found</option>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </select>

    <label for="year-filter">Filter by Year:</label>
    <select id="year-filter" onchange="filterAds()">
        <option value="all">All Years</option>
        <?php
        // Include database connection
        include 'db_connection.php';

        // Retrieve distinct years for filtering options
        $sql = "SELECT DISTINCT yom FROM Vehicle";
        $result = $conn->query($sql);

        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . htmlspecialchars($row['yom']) . "'>" . htmlspecialchars($row['yom']) . "</option>";
            }
        } else {
            echo "<option value=''>No years found</option>";
        }

        // Close the database connection
        $conn->close();
        ?>
    </select>
</div>


<!-- Section for displaying vehicle ads -->
<div class="vehicle-ads-container">
    <?php
    // Include database connection
    include 'db_connection.php';

    // Retrieve all vehicle details with their photos
    $sql = "SELECT v.*, vp.photo_filename 
            FROM Vehicle v
            LEFT JOIN VehiclePhotos vp ON v.vehicle_id = vp.vehicle_id";
    $result = $conn->query($sql);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<div class='vehicle-ad' data-brand='" . htmlspecialchars($row['brand']) . "' data-year='" . htmlspecialchars($row['yom']) . "'>";
            
            // Check if the photo_filename exists and is not empty
            if (!empty($row['photo_filename'])) {
                // Construct the full image URL
                $image_url = $row['photo_filename'];
                // Display the image
                echo "<img src='$image_url' alt='Vehicle Photo'>";
            } else {
                // Display a placeholder image if no photo is available
                echo "<img src='placeholder_image.jpg' alt='Placeholder Image'>";
            }
            
            echo "<div class='vehicle-ad-details'>";
            echo "<ul>";
            echo "<li>Plate: " . htmlspecialchars($row['plate']) . "</li>";
            echo "<li>Brand: " . htmlspecialchars($row['brand']) . "</li>";
            echo "<li>Model: " . htmlspecialchars($row['model']) . "</li>";
            echo "<li>Year: " . htmlspecialchars($row['yom']) . "</li>";
            echo "<li>Rental Price: Rs." . htmlspecialchars($row['per_day_chrg']) . "/day</li>";
            // You can add more details here if needed
            echo "</ul>";
            echo "<a href='process_booking.php?vehicle_id={$row['vehicle_id']}' class='book-now-btn'>Book Now</a>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No vehicle details found.</p>";
    }

    // Close the database connection
    $conn->close();
    ?>
</div>

</div>



    <!-- JavaScript for filtering vehicle ads -->
    <script>
        // Function to filter vehicle ads based on selected brand and year
        function filterAds() {
            const brandFilter = document.getElementById('brand-filter').value;
            const yearFilter = document.getElementById('year-filter').value;
            const vehicleAds = document.querySelectorAll('.vehicle-ad');
            vehicleAds.forEach(ad => {
                const adBrand = ad.getAttribute('data-brand');
                const adYear = ad.getAttribute('data-year');
                if ((brandFilter === 'all' || adBrand === brandFilter) && (yearFilter === 'all' || adYear === yearFilter)) {
                    ad.style.display = 'inline-block'; // Display as inline-block to maintain horizontal layout
                } else {
                    ad.style.display = 'none';
                }
            });
        }
    </script>


    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get E-mail updates about our latest functions and <span>special offers.</span> </p>
        </div>
        <div class="form">
            <input type="text" placeholder="Your email address">
            <button class="normal">Signe Up</button>
        </div>
    </section>

<footer class="section-p1">
        <div class="col">
            <img class="logo" src="logo.png">
            <h4>Contact</h4>
            <p><strong>Address: </strong>562 Kithulampitiya Road, Street 31, Galle, SriLanka</p>
            <p><strong>Phone: </strong>+94 078 856 8282 / +94 077 987 8765</p>
            <p><strong>Hours: </strong>24 Hours</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-twitter"></i>
                    <i class="fab fa-instagram"></i>
                    <i class="fab fa-pinterest-p"></i>
                    <i class="fab fa-youtube"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>About</h4>
            <a href="#">About us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">Signe In</a>
            <a href="#">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p>Form App Store or Google Play</p>
            <div class="row">
                <img src="app.jpg" alt="">
                <img src="play.jpg" alt="">
            </div>
            <p>Secured Payment Gateways</p>
            <img src="pay.png" alt="">
        </div>

        <div class="copyright">
            <p>© 2024, Sukkanama etc - All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
