<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sukkanama Home</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
	<link rel="stylesheet"  href="style.css">

	  <style>
        /* Modern styles for the vehicle ads section */
        .vehicle-ads-container {
            display: flex;
            overflow-x: auto;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        .vehicle-ad {
            flex: 0 0 auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
            background-color: #fff;
            width: 300px; /* Adjust as needed */
        }
        .vehicle-ad img {
            width: 100%;
            height: auto;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .vehicle-ad-details {
            padding: 20px;
        }
        .vehicle-ad-details ul {
            padding: 0;
            margin: 0;
            list-style-type: none;
        }
        .vehicle-ad-details li {
            margin-bottom: 10px;
        }
        .vehicle-ad .book-now-btn {
            display: block;
            text-align: center;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .vehicle-ad .book-now-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

	<section id="header">
		<a href="#"><img src="logo.png" class="logo" alt=""></a>

		<div>
			<ul id="navbar">
				<li><a class="active" href="index.php">Home</a></li>
				<li><a href="loginSupplier.php">Rent Your Vehicle</a></li>
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

	<section id="hero">
		<h4>Services</h4>
		<h2>Super value vehicles</h2>
		<h1>Sukkanama</h1>
		<p>Save time, connect with us!</p>
		<button onclick="browseVehicles()">Browse Now</button>

<script>
    function browseVehicles() {
        window.location.href = 'loginCustomer.php';
    }
</script>

	</section>

	<section id="feature" class="section-p1">
		<div class="fe-box">
			<img src="f1.png">
			<h6>Free Service</h6>
		</div>
		<div class="fe-box">
			<img src="f2.png">
			<h6>Online Book</h6>
		</div>
		<div class="fe-box">
			<img src="f3.png">
			<h6>Save Money</h6>
		</div>
		<div class="fe-box">
			<img src="f4.png">
			<h6>Promotions</h6>
		</div>
		<div class="fe-box">
			<img src="f5.png">
			<h6>Happy Service</h6>
		</div>
		<div class="fe-box">
			<img src="f6.png">
			<h6>F24/7 Support</h6>
		</div>
	</section>

	

	<section id="banner" class="section-m1">
		<h4>Trusted Services</h4>
		<h2>Select <span>100% Free</span> - Many Vehicle Collection for Select</h2>
		<button class="normal">Explore More</button>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Find the button element with the class "normal"
        var exploreButton = document.querySelector(".normal");
        
        // Add a click event listener to the button
        exploreButton.addEventListener("click", function() {
            // Navigate to the vehicle_ads.php page
            window.location.href = 'loginCustomer.php';
        });
    });
</script>

	</section>



    <h1>Vehicle Ads</h1>

    <!-- Filter dropdowns for brand and year -->
    <div>
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
            echo "<a href='loginCustomer.php?vehicle_id={$row['vehicle_id']}' class='book-now-btn'>Book Now</a>";
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

        // Add event listeners to the "Book Now" buttons after filtering
        addBookNowEventListeners();
    }

    // Function to add event listeners to the "Book Now" buttons
    function addBookNowEventListeners() { 
        const bookNowButtons = document.querySelectorAll('.book-now-btn');
        bookNowButtons.forEach(button => {
          button.addEventListener('click', function() {
    // Redirect to the loginCustomer.php page
    window.location.href = 'loginCustomer.php';
});

        });
    }

    // Call the filterAds function initially to ensure the event listeners are set up
    filterAds();
</script>


		
	
	

	<section id="sm-banner" class="section-p1">
		<div class="banner-box">
			<h4>Rent Vehicle Owners</h4>
			<h2>Publish Your Add</h2>
			<span>Find Customers in Eazy Steps</span>
			<button class="white" onclick="window.location.href='loginSupplier.php'">Sign Up</button>

		</div>
		<div class="banner-box banner-box2">
			<h4>Vehicle Finders for Rent</h4>
			<h2>Select & Book Your Vehicle</h2>
			<span>Find Your vehicle in Eazy Steps</span>
			<button class="white" onclick="window.location.href='loginCustomer.php'">Sign Up</button>
		</div>
	</section>

	<section id="banner3">
		<div class="banner-box">
			<h2>GPS Suport</h2>
			<h3>secure Tracking Systems</h3>
		</div>
		<div class="banner-box banner-box2">
			<h2>For Special Occasions<h2>
			<h3>Many Vehicle range to select</h3>
		</div>
		<div class="banner-box banner-box3">
			<h2>All Around The Country</h2>
			<h3>Island Wide</h3>
		</div>
	</section>

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


	<script src="script.js"></script>
</body>
</html>
