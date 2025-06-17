<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <style>
        .wrapper{ width: calc(100% - 240px); padding-left: 240px; padding-top: 20px }
        /* Table enhancements */
        .table-wrapper{ width:100%; overflow-x:auto; }
        .table-bordered thead th{
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }
        .table-bordered tbody tr:hover{ background-color: #f2f2f2; }
        .fa-trash{ color:#d63031; transition:.2s; }
        .fa-trash:hover{ color:#ff5e5e; transform:scale(1.1);}
    </style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left fw-bold text-secondary">Account Management</h2>
                    <a href="../staffCrud/createStaff.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Staff</a>
                    <a href="../customerCrud/createCust.php" class="btn btn-success"><i class="fa fa-user-plus"></i> Add Membership</a>
                </div>
                
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <input required type="text" id="search" name="search" class="form-control" placeholder="Search by Account ID or Email">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class='fa fa-search'></i> Search</button>
                            </div>
                            <div class="col" style="text-align: right;" >
                                <a href="account-panel.php" class="btn btn-outline-secondary"><i class='fa fa-eye'></i> Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                
                <?php
                // Include config file
                require_once "../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT *
                                FROM Accounts
                                WHERE email LIKE '%$search%' OR account_id LIKE '%$search%'
                                ORDER BY account_id;";
                    } else {
                        // Default query to fetch all accounts
                        $sql = "SELECT *
                                FROM Accounts
                                ORDER BY account_id;";
                    }
                } else {
                    // Default query to fetch all accounts
                    $sql = "SELECT *
                            FROM Accounts
                            ORDER BY account_id;";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-wrapper"><table id="datatablesSimple" class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Account ID</th>";
                        echo "<th>Email</th>";
                        echo "<th>Register Date</th>";
                        echo "<th>Phone Number</th>";
                        echo "<th>Password</th>";
                        //echo "<th>Account Type</th>"; // Display account type
                        echo "<th style='width:6em;'>Actions</th>"; // Uncommented delete column header
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['account_id'] . "</td>";
                            echo "<td>" . $row['email'] . " <button class='btn btn-sm btn-light copy-mail' data-mail='".$row['email']."' title='Copy Email'><i class='fa fa-copy'></i></button></td>";
                            echo "<td><span class='badge bg-info text-dark'>" . $row['register_date'] . "</span></td>";
                            echo "<td><a href='tel:" . $row['phone_number'] . "'><span class='badge bg-success text-dark'>" . $row['phone_number'] . "</span></a></td>";
                            $masked=substr($row['password'],0,6).'••••';
                            echo "<td><span class='masked-pass' data-pass='".$row['password']."'>Click to reveal</span></td>"; 
                            //echo "<td>" . ucfirst($row['account_type']) . "</td>"; // Display account type
                            echo "<td>"; // Uncommented delete column cell
                            //  $deleteSQL = "DELETE FROM Accounts WHERE account_id = '" . $row['account_id'] . "';";
                            echo '<a href="../accountCrud/deleteAccountVerify.php?id=' . $row['account_id'] . '" title="Delete Record" class="btn btn-outline-danger btn-sm" '
                                    . 'onclick="return confirm(\'Admin permission Required!\\n\\nAre you sure you want to delete this Account?\\n\\nThis will alter other modules related to this Account!\\n\')"><i class="fa fa-trash"></i></a>';
                            echo "</td>"; // Uncommented delete column cell close
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
    $(document).ready(function() {
        $('#datatablesSimple').DataTable({
            "paging": true,
            "pageLength": 50,
            "lengthMenu": [ [10,25,50,100,-1], [10,25,50,100,"All"] ],
            "info": false,      // Disable 'Showing x of y entries' info
            "searching": false, // Disable DataTables default search box as you have a custom one

            "autoWidth": false,

        });
    });
    // Copy email
    $(document).on('click','.copy-mail',function(){
        const mail=$(this).data('mail');
        navigator.clipboard.writeText(mail);
        $(this).tooltip({title:'Copied!',trigger:'manual'}).tooltip('show');
        setTimeout(()=>{$(this).tooltip('hide');},1000);
    });
    // Reveal password tooltip
    $(document).on('click','.masked-pass',function(){
        const $el=$(this);
        if($el.text()==='Click to reveal'){
            $el.text($el.data('pass'));
        }else{
            $el.text('Click to reveal');
        }
            
    });
</script>
