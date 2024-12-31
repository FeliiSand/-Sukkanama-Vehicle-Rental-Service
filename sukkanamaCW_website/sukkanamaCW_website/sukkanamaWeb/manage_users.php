<?php
session_start();

// Include database connection
include 'db_connection.php';

// Function to fetch all users
function getUsers($conn) {
    $sql = "SELECT * FROM User";
    $result = $conn->query($sql);
    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

function deleteUser($conn, $user_id) {
    // Delete related records from Rent table first
    $sql_delete_rent = "DELETE FROM Rent WHERE customer_id IN (SELECT customer_id FROM Customer WHERE user_id = $user_id)";
    if ($conn->query($sql_delete_rent) === TRUE) {
        // Now delete related records from Customer table
        $sql_delete_customer = "DELETE FROM Customer WHERE user_id = $user_id";
        if ($conn->query($sql_delete_customer) === TRUE) {
            // Now delete related records from Vehicle table
            $sql_delete_vehicle = "DELETE FROM Vehicle WHERE supplier_id IN (SELECT supplier_id FROM Supplier WHERE user_id = $user_id)";
            if ($conn->query($sql_delete_vehicle) === TRUE) {
                // Now delete related records from Supplier table
                $sql_delete_supplier = "DELETE FROM Supplier WHERE user_id = $user_id";
                if ($conn->query($sql_delete_supplier) === TRUE) {
                    // Now delete the user from User table
                    $sql_delete_user = "DELETE FROM User WHERE user_id = $user_id";
                    if ($conn->query($sql_delete_user) === TRUE) {
                        // User deleted successfully
                        header("Location: manage_users.php");
                        exit;
                    } else {
                        echo "Error deleting user: " . $conn->error;
                    }
                } else {
                    echo "Error deleting related supplier records: " . $conn->error;
                }
            } else {
                echo "Error deleting related vehicle records: " . $conn->error;
            }
        } else {
            echo "Error deleting related customer records: " . $conn->error;
        }
    } else {
        echo "Error deleting related rent records: " . $conn->error;
    }
}


// Handle edit user action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $user_id = $_POST['user_id'];
    // Redirect to edit user page with user ID
    header("Location: edit_user.php?user_id=$user_id");
    exit;
}

// Handle delete user action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    deleteUser($conn, $user_id);
}

// Fetch all users
$users = getUsers($conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }

        th {
            background-color: #f2f2f2;
        }

        button {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 20px; /* Added margin */
        }

        button:hover {
            background-color: #45a049;
        }

        form {
            display: inline;
        }

        button.delete {
            background-color: #f44336;
        }

        button.delete:hover {
            background-color: #d32f2f;
        }

        button.edit {
            background-color: #2196F3;
        }

        button.edit:hover {
            background-color: #1976D2;
        }
    </style>
</head>
<body>
    <h2>Manage Users</h2>
    <button onclick="window.location.href = 'AdminControlPanel.php';">Admin Control Panel</button>
    <table>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>NIC</th>
            <th>Registration Date</th>
            <th>Username</th>
            <th>Password</th>
            <th>Telephone Number</th>
            <th>User Type</th>
            <th>Action</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['user_id']; ?></td>
                <td><?php echo $user['f_name']; ?></td>
                <td><?php echo $user['l_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['nic']; ?></td>
                <td><?php echo $user['reg_date']; ?></td>
                <td><?php echo $user['u_name']; ?></td>
                <td><?php echo $user['p_word']; ?></td>
                <td><?php echo $user['tel_no']; ?></td>
                <td><?php echo $user['user_type']; ?></td>
                <td>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" name="edit_user" class="edit">Edit</button>
                    </form>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return confirm('Are you sure you want to delete this user?')">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" name="delete_user" class="delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
