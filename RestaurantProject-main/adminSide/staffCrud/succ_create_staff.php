<?php
session_start();
require_once "../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $account_id = $_POST["account_id"];
    $email = $_POST["email"];
    $register_date = $_POST["register_date"];
    $phone_number = $_POST["phone_number"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $staff_name = $_POST["staff_name"];
    $role = $_POST["role"];
    $conn = $link;
    // Start a transaction to ensure consistency across multiple table inserts
    $conn->begin_transaction();
    

    try {
        // Insert Data into Accounts Table
        $insert_account_query = "INSERT INTO Accounts (account_id, email, register_date, phone_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt_account = $conn->prepare($insert_account_query);
        $stmt_account->bind_param("issss", $account_id, $email, $register_date, $phone_number, $password);

        // Execute the query to insert data into Accounts table
        if (!$stmt_account->execute()) {
            throw new Exception("Error creating account: " . $stmt_account->error);
        }

        // Insert Data into Staffs Table
        $insert_staff_query = "INSERT INTO Staffs (staff_id, staff_name, role, account_id) VALUES (?, ?, ?, ?)";
        $stmt_staff = $conn->prepare($insert_staff_query);
        $stmt_staff->bind_param("issi", $account_id, $staff_name, $role, $account_id);

        // Execute the query to insert data into Staffs table
        if (!$stmt_staff->execute()) {
            throw new Exception("Error creating staff: " . $stmt_staff->error);
        }

        // Commit the transaction if everything is successful
        $conn->commit();

        $_SESSION['staff_message'] = ['type' => 'success', 'text' => "Account and Staff created successfully."];
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $conn->rollback();

        $_SESSION['staff_message'] = ['type' => 'error', 'text' => "Error: " . $e->getMessage()];
    } finally {
        // Close the prepared statements
        $stmt_account->close();
        $stmt_staff->close();

        // Close the connection
        $conn->close();
    }
    header("Location: ../panel/staff-panel.php");
    exit();
}
?>