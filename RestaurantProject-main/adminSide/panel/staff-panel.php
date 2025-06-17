<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
    <style>
        .wrapper{ max-width: 90%; padding-left: 220px; padding-top: 20px }

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
        /* Table enhancements */
        .table-wrapper{ overflow-x: auto; }
        .table-bordered thead th{
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }
        .table-bordered tbody tr:hover{
            background-color: #f2f2f2;
        }
        .fa-trash{ color:#d63031; transition:0.2s; }
        .fa-trash:hover{ color:#ff5e5e; transform: scale(1.1); }
    </style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left fw-bold text-secondary">Staff Management</h2>
                    <a href="../staffCrud/createStaff.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Staff</a>
                </div>
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input required type="text" id="search" name="search" class="form-control" placeholder="Search by Staff ID or Name">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class='fa fa-search'></i> Search</button>
                            </div>
                            <div class="col" style="text-align: right;" >
                                <a href="staff-panel.php" class="btn btn-outline-secondary"><i class='fa fa-eye'></i> Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php
                // Get message from session if it exists
                $staff_message = null;
                if (isset($_SESSION['staff_message'])) {
                    $staff_message = $_SESSION['staff_message'];
                    unset($_SESSION['staff_message']); // Clear the message after displaying
                }

                // Display the message if it exists
                if ($staff_message): ?>
                    <div class="modern-message <?php echo $staff_message['type']; ?>">
                        <?php echo $staff_message['text']; ?>
                    </div>
                <?php endif; ?>

                <?php
                // Include config file
                require_once "../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        // Modified query to search staff members by staff_name or staff_id
                        /*
                        $sql = "SELECT *
                                FROM Staffs stf
                                INNER JOIN Accounts acc ON stf.account_id = acc.account_id
                                WHERE stf.staff_name LIKE '%$search%' OR stf.staff_id = '$search'
                                ORDER BY stf.staff_id";
                         *
                         */
                        $sql = "SELECT * FROM Staffs WHERE staff_name LIKE '%$search%' OR staff_id = '$search' ORDER BY account_id";
                    } else {
                        // Default query to fetch all staff members
                        /*
                        $sql = "SELECT *
                                FROM Staffs stf
                                INNER JOIN Accounts acc ON stf.account_id = acc.account_id
                                ORDER BY stf.staff_id";
                         *
                         */
                        $sql = "SELECT * FROM Staffs ORDER BY account_id";
                    }
                } else {
                    // Default query to fetch all staff members
                    /*
                    $sql = "SELECT *
                            FROM Staffs stf
                            INNER JOIN Accounts acc ON stf.account_id = acc.account_id
                            ORDER BY stf.staff_id";
                     *
                     */
                    $sql = "SELECT * FROM Staffs ORDER BY account_id";
                }


                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-wrapper"><table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th style='width:5em;'>Staff ID</th>";
                        echo "<th>Staff Name</th>";
                        echo "<th style='width:7em;'>Role</th>";
                        echo "<th>Account ID</th>";
                        //echo "<th>Email</th>";
                        //echo "<th>Phone Number</th>";
                        echo "<th style='width:6em;'>Actions</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['staff_id'] . "</td>";
                            echo "<td>" . $row['staff_name'] . "</td>";
                            echo "<td><span class='badge bg-primary'>" . $row['role'] . "</span></td>";
                            echo "<td>" . $row['account_id'] . "</td>";
                            //echo "<td>" . $row['email'] . "</td>";
                            //echo "<td>" . $row['phone_number'] . "</td>";
                            echo "<td>";
                            echo '<a href="../staffCrud/delete_staffVerify.php?id=' . $row['staff_id'] . '" title="Delete Record" class="btn btn-outline-danger btn-sm" onclick="return confirm(\'Admin permission Required!\\n\\nAre you sure you want to delete this Staff?\\n\\nThis will alter other modules related to this Staff!\\n\')"><i class="fa fa-trash"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table></div>";
                        // Free result set
                        mysqli_free_result($result);
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
            }, 2000); 
        }
    });
</script>
