<?php
session_start(); // Ensure session is started
?>
<?php  include '../inc/dashHeader.php'?>

<?php
require_once '../config.php';

// Get message from session if it exists
$reservation_message = null;
if (isset($_SESSION['reservation_message'])) {
    $reservation_message = $_SESSION['reservation_message'];
    unset($_SESSION['reservation_message']); // Clear the message after displaying
}

$head_count = $_GET['head_count'] ?? 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reservation </title>    
    <style>
        .wrapper{ width: 1300px; padding-left: 200px; padding-top: 80px  }

        /* Modern message styling */
        .modern-message {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        .modern-message.success {
            background-color: #ccffcc; /* Light green background */
            color: #2ecc71; /* Stronger green text */
            border: 1px solid #2ecc71;
        }

        .modern-message.error {
            background-color: #ffcccc; /* Light red background */
            color: #d63031; /* Stronger red text */
            border: 1px solid #d63031;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h3>Search for Available Time</h3>
        
        <?php if ($reservation_message): ?>
            <div class="modern-message <?php echo $reservation_message['type']; ?>">
                <?php echo $reservation_message['text']; ?>
            </div>
        <?php endif; ?>

        <div id="Search Table">
        <form id="reservation-form" method="GET" action="availability.php" class="ht-600 w-50">
            <div class="form-group">
                <label for="reservation_date" >Select Date</label><br>
                <input class="form-control" type="date" id="reservation_date" name="reservation_date" required><br>
            </div>
            
            <div class="form-group"> 
                <label for="reservation_time" >Available Reservation Times</label>
                    <div id="availability-table">
                                <?php
                                $availableTimes = array();
                                for ($hour = 10; $hour <= 20; $hour++) {
                                    for ($minute = 0; $minute < 60; $minute += 60) {
                                        $time = sprintf('%02d:%02d:00', $hour, $minute);
                                        $availableTimes[] = $time;
                                    }
                                }
                                echo '<select name="reservation_time" id="reservation_time" class="form-control" >';
                                echo '<option value="" selected disabled>Select a Time</option>';
                                foreach ($availableTimes as $time) {
                                    echo "<option  value='$time'>$time</option>";
                                }
                                ?>
                    </div>
            </div>
             <input type="number" id="head_count" name="head_count" value=1 hidden required>
            
            <div class="form-group mt-2">
                <input type="submit" name="submit" class="btn btn-dark" value="Search Available">
            </div> 
        </form>
        </div>
        <!-- AFTER SEARCH -->
        <div id="insert-reservation-into-table"><br>
                    <h3>Make the Reservation</h3>
                    
                    <form id="reservation-form" method="POST" action="insertReservation.php" class="ht-600 w-50">
                        
                        <div class="form-group">
                            <label for="customer_name">Customer Name</label><br>
                            <input type="text" id="customer_name" name="customer_name" class="form-control" required placeholder="Johnny Hatsoff"><br>
                        </div>
                        <?php
                        $defaultReservationDate = $_GET['reservation_date'] ?? date("Y-m-d");
                        $defaultReservationTime = $_GET['reservation_time'] ?? "13:00:00";
                        ?>
                        
                        <div class="form-group">
                            <label for="reservation_date">Reservation Date</label><br>
                            <input type="date" id="reservation_date" name="reservation_date"
                                   value="<?= $defaultReservationDate ?>" readonly required>
                            <input type="time" id="reservation_time" name="reservation_time"
                                   value="<?= $defaultReservationTime ?>" readonly required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="table_id_reserve">Pick a Table</label>
                            <select class="form-control" name="table_id" id="table_id_reserve" required>
                                <option value="" selected disabled>Select a Table</option>
                                <?php
                                $table_id_list = $_GET['reserved_table_id'];
                                $head_count = $_GET['head_count'] ?? 1;
                                $reserved_table_ids = explode(',', $table_id_list);
                                $select_query_tables = "SELECT * FROM restaurant_tables WHERE capacity >= '$head_count'";
                                if (!empty($reserved_table_ids)) {
                                    $reserved_table_ids_string = implode(',', $reserved_table_ids);
                                    $select_query_tables .= " AND table_id NOT IN ($reserved_table_ids_string)";
                                }
                                $result_tables = mysqli_query($link, $select_query_tables);
                                $resultCheckTables = mysqli_num_rows($result_tables);
                                if ($resultCheckTables > 0) {
                                    while ($row = mysqli_fetch_assoc($result_tables)) {
                                        echo '<option value="' . $row['table_id'] . '">For ' . $row['capacity'] . ' people. (Table Id: ' . $row['table_id'] . ')</option>';
                                    }
                                }  else {
                                    echo '<option disabled>No tables available, please choose another time.</option>';
                                    echo '<script>alert("No reservation tables found for the selected time. Please choose another time.");</script>';
                                }
                                ?>
                            </select>
                            <input type="number" id="head_count" name="head_count" value="<?= $head_count ?>" required hidden>
                        </div><br>
                        
                        <div class="form-group" >
                            <label for="special_request" >Special request:</label><br>
                            <input type="text" id="special_request" name="special_request" class="ht-600 w-50" placeholder="One baby chair"><br>
                        </div>
                        
                        <div class="form-group mt-2">
                            <input type="submit" name="submit" class="btn btn-dark" value="Make Reservation">
                        </div>                        
                        
                    </form>
                </div>
    <script>
        const viewDateInput = document.getElementById("reservation_date");
        const makeDateInput = document.getElementById("reservation_date");

        viewDateInput.addEventListener("change", function () {
            makeDateInput.value = this.value;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const message = document.querySelector('.modern-message');
            if (message) {
                setTimeout(() => {
                    message.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</body>

</html>
