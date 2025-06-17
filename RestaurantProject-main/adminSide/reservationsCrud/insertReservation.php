<?php
// reservation.php
require_once '../config.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $customer_name = $_POST["customer_name"];
    $table_id = intval($_POST["table_id"]);
    $reservation_time = $_POST["reservation_time"];
    $reservation_date = $_POST["reservation_date"];
    $special_request = $_POST["special_request"];
    
    $select_query_capacity = "SELECT capacity FROM restaurant_tables WHERE table_id='$table_id';";
    $results_capacity = mysqli_query($link, $select_query_capacity);

    if ($results_capacity) {
        $row = mysqli_fetch_assoc($results_capacity);
        $head_count = $row['capacity'];
        
        // Prepare the SQL query for insertion into Reservations (let reservation_id auto-increment)
        $insert_query1 = "INSERT INTO Reservations (customer_name, table_id, reservation_time, reservation_date, head_count, special_request) 
                        VALUES ('$customer_name', '$table_id', '$reservation_time', '$reservation_date', '$head_count', '$special_request');";
        
        if (mysqli_query($link, $insert_query1)) {
            // Get the last inserted reservation_id
            $reservation_id = mysqli_insert_id($link);

            // Prepare the SQL query for insertion into Table_Availability (let availability_id auto-increment)
            $insert_query2 = "INSERT INTO Table_Availability (table_id, reservation_date, reservation_time, status) 
                            VALUES ('$table_id', '$reservation_date', '$reservation_time',  'no');";
            
            if (mysqli_query($link, $insert_query2)) {
                $_SESSION['reservation_message'] = ['type' => 'success', 'text' => "Reservation Created Successfully!"];
            } else {
                $_SESSION['reservation_message'] = ['type' => 'error', 'text' => "Error creating table availability: " . mysqli_error($link)];
            }
        } else {
            $_SESSION['reservation_message'] = ['type' => 'error', 'text' => "Error creating reservation: " . mysqli_error($link)];
        }

        $_SESSION['customer_name'] = $customer_name;
    } else {
        // Handle the case where the query failed to fetch table capacity
        $_SESSION['reservation_message'] = ['type' => 'error', 'text' => "Error fetching table capacity: " . mysqli_error($link)];
    }

    header("Location: ../panel/reservation-panel.php");
    exit();
}
?>