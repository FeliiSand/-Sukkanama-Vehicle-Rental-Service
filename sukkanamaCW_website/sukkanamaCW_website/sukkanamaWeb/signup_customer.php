<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Signup</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet"  href="style.css">
    <style>

        body {
    background-image: url('backgroundCustomer.jpg'); /* Add your background image URL */
    background-size: cover;
    font-family: Arial, sans-serif;
}
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
     .container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    border-radius: 10px;
    background-color: rgba(255, 255, 255, 0.7); /* Transparent white background */
    box-shadow: 0px 0px 10px 0px rgba(0,0,0,0.1);
}
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        input[type="date"] {
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
    </style>
</head>
<body>

    <section id="header">
    <a href="#"><img src="logo.png" class="logo" alt=""></a>

    <div>
        <ul id="navbar">
            <li><a  href="index.html">Home</a></li>
            <li><a  href="loginSupplier.php">Rent Your Vehicle</a></li>
            <li><a  class="active" href="loginCustomer.php">Find Your Vehicle</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li id="lg-bag"><a href="cart.html"><i class="fas fa-user"></i></a></li>
            <a href="#" id="close"><i class="fa fa-window-close" aria-hidden="true"></i></a>
        </ul>
    </div>
    <div id="mobile">
        <a href="cart.html"><i class="fas fa-shopping-bag"></i></a>
        <i id="bar" class="fas fa-outdent"></i>
    </div>
</section>


    <div class="container">
        <h2>Customer Signup</h2>
        <form action="register_customer.php" method="post">
            <div class="form-group">
                <label for="f_name">First Name:</label>
                <input type="text" id="f_name" name="f_name" required>
            </div>

            <div class="form-group">
                <label for="l_name">Last Name:</label>
                <input type="text" id="l_name" name="l_name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="nic">NIC:</label>
                <input type="text" id="nic" name="nic" required>
            </div>

            <div class="form-group">
                <label for="reg_date">Registration Date:</label>
                <input type="date" id="reg_date" name="reg_date" required>
            </div>

            <div class="form-group">
                <label for="u_name">Username:</label>
                <input type="text" id="u_name" name="u_name" required>
            </div>

            <div class="form-group">
                <label for="p_word">Password:</label>
                <input type="password" id="p_word" name="p_word" required>
            </div>

            <div class="form-group">
                <label for="tel_no">Telephone Number:</label>
                <input type="tel" id="tel_no" name="tel_no" required>
            </div>
            
            <div class="form-group">
                <label for="d_licen">Driving License:</label>
                <input type="text" id="d_licen" name="d_licen" required>
            </div>

            <input type="submit" value="Register">
            <p>Allready Have an Account? <a href="loginCustomer.php">Login here</a></p>
        </form>
    </div>

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
