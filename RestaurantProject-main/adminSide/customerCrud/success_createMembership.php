<?php
session_start();
require_once "../config.php";


// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $member_id = $_POST["member_id"];
    $member_name = $_POST["member_name"];
    $points = $_POST["points"];
    $account_id = $_POST["account_id"];
    $email = $_POST["email"];
    $register_date = $_POST["register_date"];
    $phone_number = $_POST["phone_number"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
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

        // Insert Data into Memberships Table
        $insert_membership_query = "INSERT INTO Memberships (member_id, member_name, points, account_id) VALUES (?, ?, ?, ?)";
        $stmt_membership = $conn->prepare($insert_membership_query);
        $stmt_membership->bind_param("issi", $member_id, $member_name, $points, $account_id);

        // Execute the query to insert data into Memberships table
        if (!$stmt_membership->execute()) {
            throw new Exception("Error creating membership: " . $stmt_membership->error);
        }

        // Commit the transaction if everything is successful
        $conn->commit();

        $_SESSION['membership_message'] = ['type' => 'success', 'text' => "Membership created successfully."];
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        $conn->rollback();

        $_SESSION['membership_message'] = ['type' => 'error', 'text' => "Error: " . $e->getMessage()];
    } finally {
        // Close the prepared statements
        $stmt_account->close();
        $stmt_membership->close();

        // Close the connection
        $conn->close();
    }
    header("Location: ../panel/customer-panel.php");
    exit();
}
?>

