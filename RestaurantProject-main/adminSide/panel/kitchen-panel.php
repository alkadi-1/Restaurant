<?php
session_start(); // Ensure session is started
require_once '../posBackend/checkIfLoggedIn.php';
?>
<?php
include '../inc/dashHeader.php';
require_once '../config.php';
$query = "SELECT * FROM Kitchen WHERE time_ended IS NULL";
$result = mysqli_query($link, $query);
?>

    <link href="../css/pos.css" rel="stylesheet" />
    <meta http-equiv="refresh" content="5">

<style>
.wrapper{max-width:90%;padding-left:220px;padding-top:20px}
.table-wrapper{overflow-x:auto;}
.table-bordered thead th{position:sticky;top:0;background:#f8f9fa;z-index:10;}
.table-bordered tbody tr:hover{background:#f2f2f2;}
.badge-wait{background:#fdcb6e;color:#2d3436;}
.badge-end{background:#55efc4;color:#2d3436;}
</style>
<div class="wrapper">
    <div class="container-fluid pt-5 pl-600 mt-5">
          <div class="">
            <div class="col" style="text-align: left; display: flex; justify-content: space-between;">
                <h2 class="">Kitchen Orders</h2>
                <a href="../posBackend/kitchenBackend/undo.php?UndoUnshow=true" class="btn btn-warning mb-2">Undo</a>
            </div>
          </div>

        <div class="table-wrapper"><table class="table table-bordered table-striped">
            <thead>
                
                <tr>
                    <th>Kitchen ID</th>
                    <th>Table ID</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Time Submitted</th>
                    <th>Time Ended</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $kitchen_id = $row['kitchen_id'];
                        $table_id = $row['table_id'];
                        $item_id = $row['item_id'];
                        $quantity = $row['quantity'];
                        $time_submitted = $row['time_submitted'];
                        $time_ended = $row['time_ended'];

                        // Get item name from Menu table
                        $itemQuery = "SELECT item_name FROM Menu WHERE item_id = '$item_id'";
                        $itemResult = mysqli_query($link, $itemQuery);
                        $itemRow = mysqli_fetch_assoc($itemResult);
                        $item_name = $itemRow['item_name']??"Deleted";

                        echo '<tr>';
                        echo '<td>' . $kitchen_id . '</td>';
                        echo '<td>' . $table_id . '</td>';
                        echo '<td>' . $item_name . '</td>';
                        echo '<td><span class="badge bg-primary">' . $quantity . '</span></td>';
                        echo '<td><span class="badge bg-info text-dark">' . $time_submitted . '</span></td>';
                        if($time_ended){
                            echo '<td><span class="badge badge-end">'.$time_ended.'</span></td>';
                        }else{
                            echo '<td><span class="badge badge-wait">Not Ended</span></td>';
                        }
                        echo '<td>';
                        if (!$time_ended) {
                            echo '<a href="../posBackend/kitchenBackend/kitchen-panel-back.php?action=set_time_ended&kitchen_id=' . $kitchen_id . '" class="btn btn-outline-danger btn-sm">Done</a>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">No records in the Kitchen table.</td></tr>';
                }
                ?>
            </tbody>
        </table></div>
    </div>
</div>


