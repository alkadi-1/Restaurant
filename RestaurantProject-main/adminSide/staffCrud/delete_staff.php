<?php
// Include config file
require_once "../config.php";

// Check if the staff_id parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the staff_id from the URL and sanitize it
    $staff_id = intval($_GET['id']);

    // Disable foreign key checks
    $disableForeignKeySQL = "SET FOREIGN_KEY_CHECKS=0;";
    mysqli_query($link, $disableForeignKeySQL);

    // Get the account_id associated with this staff member before deleting the staff
    $getAccountIdSQL = "SELECT account_id FROM Staffs WHERE staff_id = ?";
    $stmt_get_account_id = $link->prepare($getAccountIdSQL);
    $stmt_get_account_id->bind_param("i", $staff_id);
    $stmt_get_account_id->execute();
    $result_account_id = $stmt_get_account_id->get_result();
    $row_account_id = $result_account_id->fetch_assoc();
    $account_id_to_delete = $row_account_id['account_id'];
    $stmt_get_account_id->close();

    // Construct the DELETE query for Staffs
    $deleteStaffSQL = "DELETE FROM Staffs WHERE staff_id = ?";
    $stmt_staff = $link->prepare($deleteStaffSQL);
    $stmt_staff->bind_param("i", $staff_id);

    // Construct the DELETE query for Accounts
    $deleteAccountSQL = "DELETE FROM Accounts WHERE account_id = ?";
    $stmt_account = $link->prepare($deleteAccountSQL);
    $stmt_account->bind_param("i", $account_id_to_delete);

    // Execute both DELETE queries
    if ($stmt_staff->execute() && $stmt_account->execute()) {
        // Both staff and account successfully deleted, redirect
        header("location: ../panel/staff-panel.php");
        exit();
    } else {
        // Error occurred during execution
        echo "Error deleting staff or account: " . mysqli_error($link);
    }

    // Enable foreign key checks
    $enableForeignKeySQL = "SET FOREIGN_KEY_CHECKS=1;";
    mysqli_query($link, $enableForeignKeySQL);

    // Close the statements
    $stmt_staff->close();
    $stmt_account->close();

    // Close the connection
    mysqli_close($link);
}
?>
