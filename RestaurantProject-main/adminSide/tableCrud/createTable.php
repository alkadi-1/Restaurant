<?php
session_start(); // Ensure session is started
?>
<?php  include '../inc/dashHeader.php'?>
<?php
// Include config file
require_once "../config.php";

$conn = $link;

 
$input_table_id = $table_id_err = $table_id = "";

// Get message from session if it exists
$table_message = null;
if (isset($_SESSION['table_message'])) {
    $table_message = $_SESSION['table_message'];
    unset($_SESSION['table_message']); // Clear the message after displaying
}

// Function to get the next available table id
function getNextAvailableTableID($conn) {
    $sql = "SELECT MAX(table_id) as max_table_id FROM Restaurant_Tables";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $next_table_id = ($row['max_table_id'] !== null) ? $row['max_table_id'] + 1 : 1;
    return $next_table_id;
}

// Get the next available table id
$next_table_id = getNextAvailableTableID($conn);

?>
<head>
    <meta charset="UTF-8">
    <title>Create New Table</title>
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

 <div class="wrapper" >
    <h3>Create New Table</h1>
    <p>Please fill in the Table Information  </p>
    
    <?php if ($table_message): ?>
        <div class="modern-message <?php echo $table_message['type']; ?>">
            <?php echo $table_message['text']; ?>
        </div>
    <?php endif; ?>

<form method="POST" action="succ_create_table.php" class="ht-600 w-50">
    
        <div class="form-group">
            <label for="table_id" class="form-label">Table ID :</label>
            <input min="1" type="number" name="table_id" placeholder="1" class="form-control" id="next_tab_idle" required value="<?php echo $next_table_id; ?>" readonly><br>
        </div>
    
        <div class="form-group"> 
            <label for="capacity">Capacity :</label>
            <input placeholder="8" type="number" name="capacity" min=1 id="capacity" required class="form-control" ><br>
        </div>

        
        
        <div class="form-group">
            <input type="submit" class="btn btn-dark" value="Create table">
        </div>    
        
    
 </form>
 </div>
 
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

