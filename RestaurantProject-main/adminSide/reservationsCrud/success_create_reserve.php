<?php
session_start();
// Assuming you have already established a database connection
require_once "../config.php";

$conn = $link;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $reservation_id = $_POST['reservation_id'];
    $customer_name = $_POST['customer_name'];
    $table_id = $_POST['table_id'];
    $reservation_time = $_POST['reservation_time'];
    $reservation_date = $_POST['reservation_date'];
    $head_count = $_POST['head_count'];
    $special_request = $_POST['special_request'];
    $bill_id = $_POST['bill_id'];

    // Prepare the SQL query for insertion
    $insert_query = "INSERT INTO Reservations (reservation_id, customer_name, table_id, reservation_time, reservation_date, head_count, special_request, bill_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);

    // Bind the parameters
    $stmt->bind_param("ssissssi", $reservation_id, $customer_name, $table_id, $reservation_time, $reservation_date, $head_count, $special_request, $bill_id);

    // Execute the query
    if ($stmt->execute()) {
        // Update table availability if reservation is successful
        $update_table_query = "UPDATE Restaurant_Tables SET is_available = FALSE WHERE table_id = ?";
        $update_stmt = $conn->prepare($update_table_query);
        $update_stmt->bind_param("i", $table_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        $_SESSION['reservation_message'] = ['type' => 'success', 'text' => "Reservation Created Successfully!"];
    } else {
        $_SESSION['reservation_message'] = ['type' => 'error', 'text' => "Error creating reservation: " . $conn->error];
    }

    // Close the prepared statement and connection
    $stmt->close();
    $conn->close();

    header("Location: ../panel/reservation-panel.php");
    exit();
}
?>