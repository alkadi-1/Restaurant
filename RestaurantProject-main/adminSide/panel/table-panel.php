<?php
session_start();
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>

<style>
    :root {
        --primary-color: #4e73df;
        --success-color: #1cc88a;
        --danger-color: #e74a3b;
        --warning-color: #f6c23e;
        --text-color: #5a5c69;
        --border-color: #e3e6f0;
        --light-bg: #f8f9fc;
    }
    
    .wrapper {
        width: calc(100% - 250px);
        margin-left: 250px;
        padding: 5rem 2rem 2rem;
        transition: all 0.3s;
    }
    
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        color: var(--text-color);
        font-weight: 600;
        margin: 0;
        font-size: 1.75rem;
    }
    
    .btn-add {
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 5px;
        padding: 0.6rem 1.25rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
    }
    
    .btn-add:hover {
        background-color: #2e59d9;
        transform: translateY(-1px);
        color: white;
    }
    
    .search-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .search-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .search-input {
        flex: 1;
        min-width: 250px;
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
        padding: 0.6rem 1.25rem;
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
        padding: 0.6rem 1.25rem;
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
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
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
        background-color: var(--light-bg);
        color: var(--primary-color);
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
    
    .table td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .badge {
        padding: 0.5em 0.8em;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.8em;
    }
    
    .badge-available {
        background-color: #d4edda;
        color: #155724;
    }
    
    .badge-unavailable {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 5px;
        color: var(--text-color);
        background: transparent;
        transition: all 0.2s;
        border: 1px solid var(--border-color);
    }
    
    .action-btn:hover {
        background-color: var(--light-bg);
        transform: translateY(-2px);
    }
    
    .action-btn.delete {
        color: var(--danger-color);
        border-color: rgba(231, 76, 60, 0.2);
    }
    
    .action-btn.delete:hover {
        background-color: rgba(231, 76, 60, 0.1);
    }
    
    .no-records {
        padding: 3rem 1rem;
        text-align: center;
        color: #6c757d;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .no-records i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #d1d3e2;
    }
    
    @media (max-width: 768px) {
        .wrapper {
            width: 100%;
            margin-left: 0;
            padding: 1rem;
        }
        
        .header-container {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input, .btn-search, .btn-show-all {
            width: 100%;
        }
        
        .table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
</style>
<div class="wrapper">
    <div class="container-fluid">
        <div class="header-container">
            <h1 class="page-title">Table Management</h1>
            <a href="../tableCrud/createTable.php" class="btn btn-add">
                <i class="fas fa-plus"></i> Add New Table
            </a>
        </div>
        
        <div class="search-container">
            <form method="POST" action="#" class="search-form">
                <input 
                    type="text" 
                    id="search" 
                    name="search" 
                    class="form-control search-input" 
                    placeholder="Search by Table ID or Capacity"
                    value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>"
                >
                <button type="submit" class="btn btn-search">
                    <i class="fas fa-search me-1"></i> Search
                </button>
                <a href="table-panel.php" class="btn btn-show-all">
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
                $sql = "SELECT *
                        FROM Restaurant_Tables
                        WHERE table_id LIKE '%$search%' 
                        OR capacity LIKE '%$search%' 
                        ORDER BY table_id;";
            } else {
                $sql = "SELECT *
                        FROM Restaurant_Tables
                        ORDER BY table_id;";
            }

            if ($result = mysqli_query($link, $sql)) {
                if (mysqli_num_rows($result) > 0) {
                    echo '<div class="table-responsive">';
                    echo '<table class="table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>Table ID</th>';
                    echo '<th>Capacity</th>';
                    echo '<th>Status</th>';
                    echo '<th class="text-end">Actions</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td><strong>Table #' . htmlspecialchars($row['table_id']) . '</strong></td>';
                        echo '<td><span class="badge bg-primary">' . htmlspecialchars($row['capacity']) . ' Persons</span></td>';
                        
                        // Status badge
                        if ($row['is_available'] == true) {
                            echo '<td><span class="badge badge-available"><i class="fas fa-check-circle me-1"></i> Available</span></td>';
                        } else {
                            echo '<td><span class="badge badge-unavailable"><i class="fas fa-times-circle me-1"></i> Occupied</span></td>';
                        }
                        
                        // Actions
                        echo '<td class="text-end">';
                        echo '<div class="d-flex justify-content-end gap-2">';
                        
                      
                      
                        // Delete button
                        echo '<a href="../tableCrud/deleteTableVerify.php?id=' . $row['table_id'] . '" ';
                        echo 'class="action-btn delete" ';
                        echo 'title="Delete Table" ';
                        echo 'data-bs-toggle="tooltip" ';
                        echo 'onclick="return confirm(\'Admin Permissions Required!\\n\\nAre you sure you want to delete this Table?\\n\\nThis will alter other modules related to this Table!\')">';
                        echo '<i class="fas fa-trash"></i>';
                        echo '</a>';
                        
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>'; // Close table-responsive
                } else {
                    echo '<div class="no-records">';
                    echo '<i class="fas fa-table fa-3x mb-3"></i>';
                    echo '<h4>No tables found</h4>';
                    if (!empty($search)) {
                        echo '<p>No tables match your search criteria. Try different keywords.</p>';
                    } else {
                        echo '<p>There are no tables in the system yet. Add your first table to get started.</p>';
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

<!-- Initialize tooltips -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>

<?php  include '../inc/dashFooter.php'?>
