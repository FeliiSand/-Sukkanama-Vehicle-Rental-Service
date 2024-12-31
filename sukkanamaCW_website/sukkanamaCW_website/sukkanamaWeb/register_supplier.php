<?php
// Include database connection
include 'db_connection.php';

// Get form data
$f_name = $_POST['f_name'];
$l_name = $_POST['l_name'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$reg_date = $_POST['reg_date']; // Add the registration date
$u_name = $_POST['u_name'];
$p_word = $_POST['p_word'];
$tel_no = $_POST['tel_no'];
$tax_id = $_POST['tax_id'];
$user_type = "supplier"; // Set user type to "supplier" for supplier registration

// Prepare and bind parameters
$stmt = $conn->prepare("INSERT INTO User (f_name, l_name, email, nic, reg_date, u_name, p_word, tel_no, user_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssss", $f_name, $l_name, $email, $nic, $reg_date, $u_name, $p_word, $tel_no, $user_type);

// Execute the statement
if ($stmt->execute()) {
    $user_id = $conn->insert_id; // Get the auto-generated user ID

    // Insert into Supplier table
    $stmt = $conn->prepare("INSERT INTO Supplier (user_id, tax_id) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $tax_id);

    if ($stmt->execute()) {
        // Close the statement and connection
        $stmt->close();
        $conn->close();

        // Popup message for successful signup and redirect
        echo "<script>alert('Sign up success!'); window.location.href = 'loginSupplier.php';</script>";
        exit; // Stop further execution
    } else {
        // Close the statement and connection
        $stmt->close();
        $conn->close();

        // Popup message for unsuccessful signup
        echo "<script>alert('Sign up unsuccessful!'); window.history.back();</script>";
        exit; // Stop further execution
    }
} else {
    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Popup message for unsuccessful signup
    echo "<script>alert('Sign up unsuccessful!'); window.history.back();</script>";
    exit; // Stop further execution
}
?>
