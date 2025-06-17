<?php
// reservation.php
require_once '../config.php';
session_start();

// Enable mysqli error reporting to throw exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get the values from the form
        $customer_name = $_POST["customer_name"];
        $table_id = intval($_POST["table_id"]);
        $reservation_time = $_POST["reservation_time"];
        $reservation_date = $_POST["reservation_date"];
        $special_request = $_POST["special_request"];

        // Input validation
        if (preg_match('/[0-9]/', $customer_name)) {
            $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Customer Name cannot contain numbers. Please enter a valid name."];
            header("Location: reservePage.php");
            exit();
        }

        if (preg_match('/[0-9]/', $special_request)) {
            $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Special Request cannot contain numbers. Please describe your request without digits."];
            header("Location: reservePage.php");
            exit();
        }
        
        $select_query_capacity = "SELECT capacity FROM restaurant_tables WHERE table_id='$table_id';";
        $results_capacity = mysqli_query($link, $select_query_capacity);

        if ($results_capacity) {
            $row = mysqli_fetch_assoc($results_capacity);
            $head_count = $row['capacity'];

            // Prepare the SQL query for insertion into Reservations (let reservation_id auto-increment)
            $insert_query1 = "INSERT INTO Reservations (customer_name, table_id, reservation_time, reservation_date, head_count, special_request) 
                            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt1 = mysqli_prepare($link, $insert_query1);
            mysqli_stmt_bind_param($stmt1, "sissss", $customer_name, $table_id, $reservation_time, $reservation_date, $head_count, $special_request);
            
            if (mysqli_stmt_execute($stmt1)) {
                // Get the last inserted reservation_id
                $reservation_id = mysqli_insert_id($link);

                // Prepare the SQL query for insertion into Table_Availability (let availability_id auto-increment)
                $insert_query2 = "INSERT INTO Table_Availability (table_id, reservation_date, reservation_time, status) 
                                VALUES (?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($link, $insert_query2);
                $status_no = 'no'; // Define 'no' as a variable
                mysqli_stmt_bind_param($stmt2, "isss", $table_id, $reservation_date, $reservation_time, $status_no);
                
                if (mysqli_stmt_execute($stmt2)) {
                    $_SESSION['customer_reservation_message'] = ['type' => 'success', 'text' => "Reservation Created Successfully!"];
                } else {
                    // This else block might be redundant if strict reporting is on, but kept for clarity
                    $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Error creating table availability: " . mysqli_error($link)];
                }
                mysqli_stmt_close($stmt2);
            } else {
                // This else block might be redundant if strict reporting is on, but kept for clarity
                $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Error creating reservation: " . mysqli_error($link)];
            }
            mysqli_stmt_close($stmt1);
            $_SESSION['customer_name'] = $customer_name; // Keep this for potential future use or if other parts rely on it
        } else {
            // Handle the case where the query failed to fetch table capacity
            $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Error fetching table capacity: " . mysqli_error($link)];
        }
    } catch (mysqli_sql_exception $e) {
        // Catch MySQLi exceptions and store them in the session for modern display
        $errorMessage = $e->getMessage();
        if (strpos($errorMessage, 'Duplicate entry') !== false && strpos($errorMessage, 'PRIMARY') !== false) {
            $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "A reservation with these details might already exist, or a system error occurred. Please try different details or contact support if the issue persists."];
        } else {
            $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "Database error: " . $errorMessage];
        }
    } catch (Exception $e) {
        // Catch any other general exceptions
        $_SESSION['customer_reservation_message'] = ['type' => 'error', 'text' => "An unexpected error occurred: " . $e->getMessage()];
    }

    header("Location: reservePage.php");
    exit();
}
?>
