<?php
session_start();

// Include database connection
include 'db_connection.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input to prevent SQL injection
    $u_name = mysqli_real_escape_string($conn, $_POST['u_name']);
    $p_word = mysqli_real_escape_string($conn, $_POST['p_word']);

    // Query to check if username and password match in User table
    $user_sql = "SELECT * FROM User WHERE u_name='$u_name' AND p_word='$p_word'";
    $user_result = $conn->query($user_sql);

    if ($user_result === false) {
        // SQL query execution error
        die("Error executing the user query: " . $conn->error);
    }

    // Query to check if username and password match in Supplier table
    $supplier_sql = "SELECT * FROM Supplier INNER JOIN User ON Supplier.user_id = User.user_id WHERE User.u_name='$u_name' AND User.p_word='$p_word'";
    $supplier_result = $conn->query($supplier_sql);

    if ($supplier_result === false) {
        // SQL query execution error
        die("Error executing the supplier query: " . $conn->error);
    }

    if ($supplier_result->num_rows == 1) {
        // Supplier login successful
        $row = $supplier_result->fetch_assoc();

        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['u_name'] = $u_name;
        $_SESSION['user_id'] = $row['user_id'];

        // Redirect to the supplier dashboard page
        header("Location: supplier_Dashboard.php");
        exit;
    } elseif ($user_result->num_rows == 1) {
        // User found but not a supplier
        $login_error = "Only suppliers can log in. Please check your username and password.";
    } else {
        // Username or password is incorrect
        $login_error = "Login failed. Please check your username and password.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Sukkanama Supplier Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet"  href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
            margin-top: 100px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .register-link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<section id="header">
    <a href="#"><img src="logo.png" class="logo" alt=""></a>

    <div>
        <ul id="navbar">
            <li><a  href="index.php">Home</a></li>
            <li><a class="active" href="loginSupplier.php">Rent Your Vehicle</a></li>
            <li><a href="loginCustomer.php">Find Your Vehicle</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
           
        </ul>
    </div>
    <div id="mobile">
        <a href="cart.html"><i class="fas fa-shopping-bag"></i></a>
        <i id="bar" class="fas fa-outdent"></i>
    </div>
</section>

<div class="container">
    <h2>Supplier Login</h2>
    <!-- Display login error message if login fails -->
    <?php if(isset($login_error)) { ?>
        <div style="color: red; margin-bottom: 10px;"><?php echo $login_error; ?></div>
    <?php } ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="u_name">Username:</label>
            <input type="text" id="u_name" name="u_name" required>
        </div>
        <div class="form-group">
            <label for="p_word">Password:</label>
            <input type="password" id="p_word" name="p_word" required>
        </div>
        <input type="submit" value="Login">
    </form>
    <div class="register-link">
        <p>Not registered? <a href="signup_supplier.php">Register here</a></p>
    </div>
</div>


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
