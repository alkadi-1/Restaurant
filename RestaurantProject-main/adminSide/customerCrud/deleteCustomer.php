<?php
// Include config file
require_once "../config.php";

// Check if the member_id parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the member_id from the URL and sanitize it
    $member_id = intval($_GET['id']);

    // Disable foreign key checks
    $disableForeignKeySQL = "SET FOREIGN_KEY_CHECKS=0;";
    mysqli_query($link, $disableForeignKeySQL);

    // Get the account_id associated with this membership before deleting the membership
    $getAccountIdSQL = "SELECT account_id FROM Memberships WHERE member_id = ?";
    $stmt_get_account_id = $link->prepare($getAccountIdSQL);
    $stmt_get_account_id->bind_param("i", $member_id);
    $stmt_get_account_id->execute();
    $result_account_id = $stmt_get_account_id->get_result();
    $row_account_id = $result_account_id->fetch_assoc();
    $account_id_to_delete = $row_account_id['account_id'];
    $stmt_get_account_id->close();

    // Construct the DELETE query for Memberships
    $deleteMembershipSQL = "DELETE FROM Memberships WHERE member_id = ?";
    $stmt_membership = $link->prepare($deleteMembershipSQL);
    $stmt_membership->bind_param("i", $member_id);

    // Construct the DELETE query for Accounts
    $deleteAccountSQL = "DELETE FROM Accounts WHERE account_id = ?";
    $stmt_account = $link->prepare($deleteAccountSQL);
    $stmt_account->bind_param("i", $account_id_to_delete);

    // Execute both DELETE queries
    if ($stmt_membership->execute() && $stmt_account->execute()) {
        // Both membership and account successfully deleted, redirect
        header("location: ../panel/customer-panel.php");
        exit();
    } else {
        // Error occurred during execution
        echo "Error deleting membership or account: " . mysqli_error($link);
    }

    // Enable foreign key checks
    $enableForeignKeySQL = "SET FOREIGN_KEY_CHECKS=1;";
    mysqli_query($link, $enableForeignKeySQL);

    // Close the statements
    $stmt_membership->close();
    $stmt_account->close();

    // Close the connection
    mysqli_close($link);
}
?>
