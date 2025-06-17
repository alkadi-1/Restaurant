<?php
session_start(); // Ensure session is started

// Assuming you have already established a database connection
require_once "../config.php";
$conn = $link;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $table_id = $_POST["table_id"];
    $capacity = $_POST["capacity"];
    

    // Prepare the SQL query to check if the table_id already exists
    $check_query = "SELECT table_id FROM Restaurant_Tables  WHERE table_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $table_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // Check if the table_id already exists
    if ($check_result->num_rows > 0) {
        $_SESSION['table_message'] = ['type' => 'error', 'text' => "The table ID is already in use.<br>Please try again to choose a different table ID."];
    } else {
        // Prepare the SQL query for insertion
        $insert_query = "INSERT INTO Restaurant_Tables (table_id, capacity, is_available) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        // Define $is_available as TRUE (1)
        $is_available = 1;

        // Bind the parameters
        $stmt->bind_param("ssd", $table_id, $capacity, $is_available);

        // Execute the query
        if ($stmt->execute()) {
            $_SESSION['table_message'] = ['type' => 'success', 'text' => "Table created successfully."];
        } else {
            $_SESSION['table_message'] = ['type' => 'error', 'text' => "Error: " . $insert_query . "<br>" . $conn->error];
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Close the check statement and the connection
    $check_stmt->close();
    $conn->close();

    header("Location: createTable.php");
    exit();
}
?>
