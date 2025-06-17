<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>

<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #f8f9fc;
        --accent-color: #36b9cc;
        --text-color: #5a5c69;
        --border-color: #e3e6f0;
    }
    
    .wrapper {
        width: calc(100% - 250px);
        margin-left: 250px;
        padding: 5rem 2rem 2rem; /* Increased top padding to 5rem */
        transition: all 0.3s;
    }
    
    .search-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .search-container h2 {
        color: var(--text-color);
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
    }
    
    .search-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .search-input {
        flex: 1;
        min-width: 300px;
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        outline: none;
    }
    
    .btn-search {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-search:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
    }
    
    .btn-show-all {
        background-color: #eaecf4;
        color: var(--text-color);
        border: 1px solid var(--border-color);
        border-radius: 5px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    .btn-show-all:hover {
        background-color: #dddfeb;
        color: var(--text-color);
    }
    
    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        padding: 1.5rem;
        overflow-x: auto;
    }
    
    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: var(--text-color);
        border-collapse: collapse;
    }
    
    .table thead th {
        background-color: var(--secondary-color);
        color: #4e73df;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 1rem;
        border-bottom: 2px solid var(--border-color);
    }
    
    .table tbody tr {
        transition: all 0.2s;
        border-bottom: 1px solid var(--border-color);
    }
    
    .table tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.05);
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .action-btn {
        color: var(--primary-color);
        font-size: 1.1rem;
        margin: 0 5px;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        color: #2e59d9;
        transform: scale(1.1);
    }
    
    .receipt-btn {
        color: #1cc88a;
    }
    
    .no-records {
        padding: 2rem;
        text-align: center;
        color: #6c757d;
    }
    
    @media (max-width: 768px) {
        .wrapper {
            width: 100%;
            margin-left: 0;
            padding: 1rem;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input {
            width: 100%;
            min-width: auto;
        }
    }
</style>

<div class="wrapper">
    <div class="container-fluid">
        <div class="search-container">
            <h2>Bill Management</h2>
            <form method="POST" action="#" class="search-form">
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="form-control search-input" 
                    placeholder="Search by Bill ID, Table, Card, or Payment Method"
                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>"
                >
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <a href="bill-panel.php" class="btn btn-show-all">
                    <i class="fas fa-sync-alt me-1"></i> Show All
                </a>
            </form>
        </div>
                
        <div class="table-container">
            <?php
            // Include config file
            require_once "../config.php";
            
            $search = isset($_POST['search']) ? trim($_POST['search']) : '';
            
            if (!empty($search)) {
                $search = mysqli_real_escape_string($link, $search);
                $sql = "SELECT * FROM Bills 
                        WHERE table_id LIKE '%$search%' 
                        OR payment_method LIKE '%$search%' 
                        OR bill_id LIKE '%$search%' 
                        OR card_id LIKE '%$search%'
                        ORDER BY bill_id DESC";
            } else {
                // Default query to fetch all bills, newest first
                $sql = "SELECT * FROM Bills ORDER BY bill_id DESC";
            }
            
            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table table-hover">';
                    echo '<thead class="thead-light">';
                    echo '<tr>';
                    echo '<th>Bill ID</th>';
                    echo '<th>Staff</th>';
                    echo '<th>Member</th>';
                    echo '<th>Reservation</th>';
                    echo '<th>Table</th>';
                    echo '<th>Card</th>';
                    echo '<th>Payment</th>';
                    echo '<th>Bill Time</th>';
                    echo '<th>Payment Time</th>';
                    echo '<th class="text-center">Receipt</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td><strong>#' . htmlspecialchars($row['bill_id']) . '</strong></td>';
                        echo '<td>' . ($row['staff_id'] ? 'Staff #' . htmlspecialchars($row['staff_id']) : '-') . '</td>';
                        echo '<td>' . ($row['member_id'] ? 'Member #' . htmlspecialchars($row['member_id']) : 'Guest') . '</td>';
                        echo '<td>' . ($row['reservation_id'] ? '#' . htmlspecialchars($row['reservation_id']) : '-') . '</td>';
                        echo '<td>' . ($row['table_id'] ? 'Table ' . htmlspecialchars($row['table_id']) : '-') . '</td>';
                        echo '<td>' . ($row['card_id'] ? '•••• ' . substr(htmlspecialchars($row['card_id']), -4) : 'N/A') . '</td>';
                        echo '<td><span class="badge bg-' . 
                             (strtolower($row['payment_method']) == 'cash' ? 'success' : 'primary') . 
                             '">' . htmlspecialchars(ucfirst($row['payment_method'])) . '</span></td>';
                        echo '<td>' . date('M j, Y h:i A', strtotime($row['bill_time'])) . '</td>';
                        echo '<td>' . ($row['payment_time'] ? date('M j, Y h:i A', strtotime($row['payment_time'])) : '-') . '</td>';
                        echo '<td class="text-center">';
                        echo '<a href="../posBackend/receipt.php?bill_id=' . $row['bill_id'] . '" ';
                        echo 'class="action-btn receipt-btn" ';
                        echo 'title="View Receipt" ';
                        echo 'data-toggle="tooltip" ';
                        echo 'target="_blank">';
                        echo '<i class="fas fa-receipt"></i>';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>'; // Close table-responsive
                } else {
                    echo '<div class="no-records">';
                    echo '<i class="fas fa-search fa-3x mb-3" style="color: #dddfeb;"></i>';
                    echo '<h4>No bills found</h4>';
                    if (!empty($search)) {
                        echo '<p>No records match your search criteria. Try different keywords.</p>';
                    } else {
                        echo '<p>There are no bills in the system yet.</p>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-danger">';
                echo '<i class="fas fa-exclamation-circle me-2"></i>';
                echo 'Oops! Something went wrong. Please try again later.';
                echo '<div class="small text-muted mt-2">' . htmlspecialchars(mysqli_error($link)) . '</div>';
                echo '</div>';
            }
            
            // Close connection
            mysqli_close($link);
            ?>
        </div>
            </div>
        </div>
    </div>
</div>

<?php include '../inc/dashFooter.php'; ?>
