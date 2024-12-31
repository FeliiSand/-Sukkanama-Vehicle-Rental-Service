<?php
session_start();

// Include database connection
include 'db_connection.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        header("Location: AdminControlPanel.php");
        exit;
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
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<section id="header">
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
</section>

<div class="container">
    <h2>Admin Login</h2>
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
    <div class="register-link">
        <p>Not registered? <a href="signupAdmin.php">Register here</a></p>
    </div>
    </form>
</div>

<footer class="section-p1">
    <!-- Your footer code -->
</footer>

</body>
</html>
