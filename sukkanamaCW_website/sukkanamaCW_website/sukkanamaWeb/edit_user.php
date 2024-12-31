<?php
session_start();

// Include database connection
include 'db_connection.php';

// Initialize variables
$user = null;
$error_message = '';

// Fetch user details if user ID is provided in the URL
if(isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $sql = "SELECT * FROM User WHERE user_id = $user_id";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        $error_message = "User not found.";
    }
}

// Handle form submission to update user details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    // Retrieve user ID from form
    $user_id = $_POST['user_id'];

    // Retrieve other form data
    $f_name = $_POST['f_name'];
    $l_name = $_POST['l_name'];
    $email = $_POST['email'];
    $nic = $_POST['nic'];
    $reg_date = $_POST['reg_date'];
    $u_name = $_POST['u_name'];
    $p_word = $_POST['p_word'];
    $tel_no = $_POST['tel_no'];
    $user_type = $_POST['user_type'];

    // Update user details
    $sql = "UPDATE User SET f_name='$f_name', l_name='$l_name', email='$email', nic='$nic', reg_date='$reg_date', u_name='$u_name', p_word='$p_word', tel_no='$tel_no', user_type='$user_type' WHERE user_id=$user_id";
    if ($conn->query($sql) === TRUE) {
        // Redirect to manage users page
        header("Location: manage_users.php");
        exit;
    } else {
        $error_message = "Error updating user: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="tel"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        select {
            height: 36px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Edit User</h2>
    <?php if ($error_message): ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="user_id">Enter User ID:</label>
        <input type="text" id="user_id" name="user_id" value="<?php echo isset($_GET['user_id']) ? $_GET['user_id'] : ''; ?>">
        <button type="submit">Edit</button>
    </form>

    <?php if ($user): ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
            <!-- Add input fields for user details -->
            <label for="f_name">First Name:</label>
            <input type="text" id="f_name" name="f_name" value="<?php echo $user['f_name']; ?>"><br><br>
            <label for="l_name">Last Name:</label>
            <input type="text" id="l_name" name="l_name" value="<?php echo $user['l_name']; ?>"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>"><br><br>
            <label for="nic">NIC:</label>
            <input type="text" id="nic" name="nic" value="<?php echo $user['nic']; ?>"><br><br>
            <label for="reg_date">Registration Date:</label>
            <input type="date" id="reg_date" name="reg_date" value="<?php echo $user['reg_date']; ?>"><br><br>
            <label for="u_name">Username:</label>
            <input type="text" id="u_name" name="u_name" value="<?php echo $user['u_name']; ?>"><br><br>
            <label for="p_word">Password:</label>
            <input type="password" id="p_word" name="p_word" value="<?php echo $user['p_word']; ?>"><br><br>
            <label for="tel_no">Telephone Number:</label>
            <input type="tel" id="tel_no" name="tel_no" value="<?php echo $user['tel_no']; ?>"><br><br>
            <label for="user_type">User Type:</label>
            <select id="user_type" name="user_type">
                <option value="customer" <?php if($user['user_type'] == 'customer') echo 'selected'; ?>>Customer</option>
                <option value="supplier" <?php if($user['user_type'] == 'supplier') echo 'selected'; ?>>Supplier</option>
                <option value="admin" <?php if($user['user_type'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select><br><br>
            <button type="submit" name="update_user">Update</button>
        </form>
    <?php endif; ?>
</body>
</html>
