<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php include '../inc/dashHeader.php'; ?>
    <style>
        .wrapper{ max-width:90%; padding-left:220px; padding-top:20px }
        .table-wrapper{overflow-x:auto;}
        .table thead th{position:sticky;top:0;background:#f8f9fa;z-index:10;}
        .table tbody tr:hover{background:#f2f2f2;}
        /* Card layout */
        .search-container, .table-container{background:#fff;border-radius:10px;box-shadow:0 0.15rem 1.75rem rgba(58,59,69,.15);padding:1.5rem;margin-bottom:2rem;}
        .table-container{overflow-x:auto;}
        .action-btn{font-size:1.1rem;margin:0 5px;transition:.2s;}
        .action-btn:hover{transform:scale(1.1);} 
        .edit-btn{color:#f1c40f;}
        .edit-btn:hover{color:#d4ac0d;}
        .delete-btn{color:#ff6b6b;}
        .delete-btn:hover{color:#c0392b;}
        .table td, .table th {
            vertical-align: middle;
        }
        .table img {
            object-fit: cover;
        }
    </style>

<div class="wrapper">
    <div class="container-fluid pt-5 pl-600">
        <div class="row">
            <div class="m-50"><div class="search-container">
                <div class="mt-5 mb-3">
                    <h2 class="pull-left">Items Details</h2>
                    <a href="../menuCrud/createItem.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Item</a>
                </div>
                <div class="mb-3">
                    <form method="POST" action="#">
                        <div class="row">
                            <div class="col-md-6">
                                <select name="search" id="search" class="form-control">
                                    <option value="">Select Item Type or Item Category</option>      
                                    <option value="Main Dishes">Main Dishes</option>
                                    <option value="Side Snacks">Side Snacks</option>
                                    <option value="Drinks">Drinks</option>                                    
                                    <option value="Steak & Ribs">Steak & Ribs</option>
                                    <option value="Seafood">Seafood</option>
                                    <option value="Pasta">Pasta</option>
                                    <option value="Lamb">Lamb</option>
                                    <option value="Chicken">Chicken</option>
                                    <option value="Burgers & Sandwiches">Burgers & Sandwiches</option>
                                    <option value="Bar Bites">Bar Bites</option>
                                    <option value="House Dessert">House Dessert</option>
                                    <option value="Salad">Salad</option>
                                    <option value="Shoney Kid">Shoney Kid</option>
                                    <option value="Side Dishes">Side Dishes</option>
                                    <option value="Classic Cocktails">Classic Cocktails</option>
                                    <option value="Cold Pressed Juice">Cold Pressed Juice</option>
                                    <option value="House Cocktails">House Cocktails</option>
                                    <option value="Mocktails">Mocktails</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary"><i class='fa fa-search'></i> Search</button>
                            </div>
                            <div class="col" style="text-align: right;" >
                                <a href="menu-panel.php" class="btn btn-outline-secondary"><i class='fa fa-eye'></i> Show All</a>
                            </div>
                        </div>
                    </form>
                </div>
                </div>
                <div class="table-container">
<?php
                // Include config file
                require_once "../config.php";

                if (isset($_POST['search'])) {
                    if (!empty($_POST['search'])) {
                        $search = $_POST['search'];

                        $sql = "SELECT * FROM Menu WHERE item_type LIKE '%$search%' OR item_category LIKE '%$search%' OR item_name LIKE '%$search%' OR item_id LIKE '%$search%' ORDER BY item_id;";
                    } else {
                        // Default query to fetch all items
                        $sql = "SELECT * FROM Menu ORDER BY item_id;";
                    }
                } else {
                    // Default query to fetch all items
                    $sql = "SELECT * FROM Menu ORDER BY item_id;";
                }

                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<div class="table-responsive"><table class="table table-hover">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Item ID</th>";
                        echo "<th>Image</th>";
                        echo "<th>Name</th>";
                        echo "<th>Type</th>";
                        echo "<th>Category</th>";
                        echo "<th>Price</th>";
                        echo "<th>Description</th>";
                        echo "<th>Edit</th>";
                        echo "<th>Delete</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['item_id'] . "</td>";
                            echo "<td>";
                            if (!empty($row['image']) && file_exists(__DIR__ . "/../uploads/" . $row['image'])) {
                                echo '<img src="../uploads/' . htmlspecialchars($row['image']) . '" style="max-width: 60px; max-height: 60px;" class="img-thumbnail">';
                            } else {
                                echo '<div style="width: 60px; height: 60px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;" class="img-thumbnail">
                                        <i class="fa fa-image" style="font-size: 20px; color: #999;"></i>
                                      </div>';
                            }
                            echo "</td>";
                            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_category']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_price']) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($row['item_description'], 0, 50)) . (strlen($row['item_description']) > 50 ? '...' : '') . "</td>";
                            echo "<td>";
                            // Modify link with the pencil icon
                             $update_sql = "UPDATE Menu SET item_name=?, item_type=?, item_category=?, item_price=?, item_description=? WHERE item_id=?";
                            echo '<a href="../menuCrud/updateItemVerify.php?id='. $row['item_id'] .'" title="Modify Record" data-toggle="tooltip"'
                                    . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to Edit this Item?\')">'
                             . '<i class="fa fa-pencil action-btn edit-btn" aria-hidden="true"></i></a>';
                            echo "</td>";

                            echo "<td>";
                            echo '<a href="../menuCrud/deleteMenuVerify.php?id='. $row['item_id'] .'" title="Delete Record" data-toggle="tooltip" class="action-btn delete-btn" '
                                    . 'onclick="return confirm(\'Admin permission Required!\n\nAre you sure you want to delete this Item?\n\nThis will alter other modules related to this Item!\n\nYou may see unwanted changes in bills.\')"><i class="fa fa-trash"></i></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table></div></div>";
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
