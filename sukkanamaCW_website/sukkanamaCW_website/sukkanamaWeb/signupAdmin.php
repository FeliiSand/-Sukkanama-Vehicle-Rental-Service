<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Admin Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('admin.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            padding: 0;
            margin: 0;
        }
        .container {
            max-width: 500px;
            margin: 60px auto;
            padding: 50px;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.8); /* Transparent white background */
        }
        .container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group input[type="tel"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .form-group a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: #007bff;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Sign Up</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <div class="form-group">
            <label for="fname">First Name:</label>
            <input type="text" id="fname" name="fname" required>
        </div>
        <div class="form-group">
            <label for="lname">Last Name:</label>
            <input type="text" id="lname" name="lname" required>
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
            <label for="uname">Username:</label>
            <input type="text" id="uname" name="uname" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="tel">Telephone Number:</label>
            <input type="tel" id="tel" name="tel" required>
        </div>
        <input type="hidden" name="user_type" value="admin">
        <div class="form-group">
            <input type="submit" value="Sign Up">
        </div>
    </form>
   <h2>Already signed up?<a href="loginAdmin.php"> Log In</a></h2> 
</div>
<?php
// Include database connection
include 'db_connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $uname = $_POST['uname'];
    $password = $_POST['password']; // Note: Password should be hashed for security
    $tel = $_POST['tel'];
    $user_type = $_POST['user_type'];

    // Insert user data into the User table
    $insert_user_sql = "INSERT INTO User (f_name, l_name, email, nic, reg_date, u_name, p_word, tel_no, user_type) 
                        VALUES ('$fname', '$lname', '$email', '$nic', CURDATE(), '$uname', '$password', '$tel', '$user_type')";

    if ($conn->query($insert_user_sql) === TRUE) {
        // Get the user ID of the newly created user
        $user_id = $conn->insert_id;
        
        // Insert user data into the Admin table
        $insert_admin_sql = "INSERT INTO Admin (user_id) VALUES ('$user_id')";
        if ($conn->query($insert_admin_sql) === TRUE) {
            echo "<script>alert('New admin record created successfully');</script>";
        } else {
            echo "<script>alert('Error creating admin record: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error creating user record: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>
</body>
</html>
