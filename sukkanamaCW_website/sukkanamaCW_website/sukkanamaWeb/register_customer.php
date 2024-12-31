<?php
// Include database connection
include 'db_connection.php';

// Get form data
$f_name = $_POST['f_name'];
$l_name = $_POST['l_name'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$reg_date = $_POST['reg_date']; // Added registration date
$u_name = $_POST['u_name'];
$p_word = $_POST['p_word'];
$tel_no = $_POST['tel_no'];
$d_licen = $_POST['d_licen'];
$user_type = "customer"; // Set user type to "customer" for customer registration

// Insert into User table with user_type column
$sql = "INSERT INTO User (f_name, l_name, email, nic, reg_date, u_name, p_word, tel_no, user_type)
        VALUES ('$f_name', '$l_name', '$email', '$nic', '$reg_date', '$u_name', '$p_word', '$tel_no', '$user_type')";
if ($conn->query($sql) === TRUE) {
    $user_id = $conn->insert_id; // Get the auto-generated user ID
    
    // Insert into Customer table
    $sql = "INSERT INTO Customer (user_id, d_licen) VALUES ('$user_id', '$d_licen')";
    if ($conn->query($sql) === TRUE) {
        // Close the database connection
        $conn->close();
        
        // Popup message for successful signup and redirect
        echo "<script>alert('Sign up success!'); window.location.href = 'loginCustomer.php';</script>";
        exit; // Stop further execution
    } else {
        // Close the database connection
        $conn->close();
        
        // Popup message for unsuccessful signup
        echo "<script>alert('Sign up unsuccessful!'); window.history.back();</script>";
        exit; // Stop further execution
    }
} else {
    // Close the database connection
    $conn->close();
    
    // Popup message for unsuccessful signup
    echo "<script>alert('Sign up unsuccessful!'); window.history.back();</script>";
    exit; // Stop further execution
}
?>
