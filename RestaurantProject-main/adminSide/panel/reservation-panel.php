<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
    <style>
        .wrapper{ max-width:90%; padding-left:220px; padding-top:20px }
        .table-wrapper{overflow-x:auto;}
        .table-bordered thead th{position:sticky;top:0;background:#f8f9fa;z-index:10;}
        .table-bordered tbody tr:hover{background:#f2f2f2;}
        .fa-trash, .fa-receipt{color:#2d3436;transition:.2s;}
        .fa-trash:hover{color:#ff5e5e;transform:scale(1.1);}
        .fa-receipt:hover{color:#0984e3;transform:scale(1.1);} 
        /* Card layout */
        .search-container, .table-container{background:#fff;border-radius:10px;box-shadow:0 0.15rem 1.75rem 0 rgba(58,59,69,.15);padding:1.5rem;margin-bottom:2rem;}
        .table-container{overflow-x:auto;}
        .action-btn{font-size:1.1rem;margin:0 5px;transition:.2s;}
        .action-btn:hover{transform:scale(1.1);} 
        .delete-btn{color:#ff6b6b;}
        .delete-btn:hover{color:#c0392b;}
        .receipt-btn{color:#1cc88a;}
        .receipt-btn:hover{color:#0b8457;}

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
<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left fw-bold text-secondary">Reservation Management</h2>
                    <a href="../reservationsCrud/createReservation.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Reservation</a>
                </div>
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input required type="text" id="search" name="search" class="form-control" placeholder="Search by Reservation ID, Customer Name, or Date (YYYY-MM-DD)">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class='fa fa-search'></i> Search</button>
                            </div>
                            <div class="col" style="text-align: right;" >
                                <a href="reservation-panel.php" class="action-btn delete-btn"><i class='fa fa-eye'></i> Show All</a>
                            </div>
                        </div>
                    </form>
                </div></div>
                
                <div class="table-container">
<?php
                // Get message from session if it exists
                $reservation_message = null;
                if (isset($_SESSION['reservation_message'])) {
                    $reservation_message = $_SESSION['reservation_message'];
                    unset($_SESSION['reservation_message']); // Clear the message after displaying
                }
                
                // Display the message if it exists
                if ($reservation_message): ?>
                    <div class="modern-message <?php echo $reservation_message['type']; ?>">
                        <?php echo $reservation_message['text']; ?>
                    </div>
                <?php endif; ?>

                <?php
                // Include config file
                require_once "../config.php";
                $sql = "SELECT * FROM reservations ORDER BY reservation_id;";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT * FROM reservations WHERE reservation_date LIKE '%$search%' OR reservation_id LIKE '%$search%' OR customer_name LIKE '%$search%'";
                    } else {
                        // Default query to fetch all reservations
                        $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";
                    }
                } else{
                    $sql = "SELECT * FROM reservations ORDER BY reservation_date DESC, reservation_time DESC;";

                }
                
                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-responsive"><table class="table table-hover">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Reservation ID</th>";
                        echo "<th>Customer Name</th>";
                        echo "<th>Table ID</th>";
                        echo "<th>Reservation Time</th>";
                        echo "<th>Reservation Date</th>";
                        echo "<th>Head Count</th>";
                        echo "<th>Special Request</th>";
                        echo "<th>Delete</th>";
                        echo "<th>Receipt</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['reservation_id'] . "</td>";
                            echo "<td>" . $row['customer_name'] . "</td>";
                            echo "<td>" . $row['table_id'] . "</td>";
                            echo "<td><span class='badge bg-info text-dark'>" . $row['reservation_time'] . "</span></td>";
                            echo "<td><span class='badge bg-success text-dark'>" . $row['reservation_date'] . "</span></td>";
                            echo "<td><span class='badge bg-primary'>" . $row['head_count'] . "</span></td>";
                            echo "<td>" . $row['special_request'] . "</td>";
                            echo "<td>";
                            echo '<a href="../reservationsCrud/deleteReservationVerify.php?id='. $row['reservation_id'] .'" title="Delete Record" data-toggle="tooltip" '
                                   . 'onclick="return confirm(\'Admin permission Required!\\n\\nAre you sure you want to delete this Reservation?\\n\\nThis will alter other modules related to this Reservation!\\n\')"><i class="fa fa-trash"></i></a>';
                            echo "</td>";
                            echo "<td>";
                            echo '<a href="../reservationsCrud/reservationReceipt.php?reservation_id='. $row['reservation_id'] .'" class="action-btn receipt-btn" title="Receipt"><i class="fa fa-receipt"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table></div></div>";
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const message = document.querySelector('.modern-message');
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 5000); // 5000 milliseconds = 5 seconds
        }
    });
</script>
