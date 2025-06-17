<?php
session_start(); // Ensure session is started

require_once "../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User-provided input
    $provided_account_id = $_POST['account_id'];
    $provided_password = $_POST['password'];

    // Query to fetch staff record based on provided account_id
    $query = "SELECT * FROM Accounts WHERE account_id = '$provided_account_id'";
    $result = $link->query($query);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['password'];

        if (password_verify($provided_password, $stored_password)) {
            // Password matches, login successful

            // Check if the account_id exists in the Staffs table
            $staff_query = "SELECT * FROM Staffs WHERE account_id = '$provided_account_id'";
            $staff_result = $link->query($staff_query);

            if ($staff_result->num_rows === 1) {
                $staff_row = $staff_result->fetch_assoc();
                $logged_staff_name = $staff_row['staff_name']; // Get staff_name
                
                // After successful login, store staff_name in session
                $_SESSION['logged_account_id'] = $provided_account_id;
                $_SESSION['logged_staff_name'] = $logged_staff_name;
                
                //Directly go to the pos panel upon successful login
                header("Location: ../panel/pos-panel.php");
                exit;
                
            } else {
                // Staff ID not found in Staffs table
                $_SESSION['login_error'] = "Staff ID not found.<br>Please try again to choose a correct Staff ID.";
                header("Location: login.php");
                exit;
            }      
            
        } else {
            $_SESSION['login_error'] = "Incorrect password.<br>Please try again to type your password.";
            header("Location: login.php");
            exit;
        }
    } else {
        $_SESSION['login_error'] = "Staff ID not found.<br>Please try again to choose a correct Staff ID.";
        header("Location: login.php");
        exit;
    }
}

// Close the database connection
$link->close();
?>