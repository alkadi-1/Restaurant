<?php
session_start(); // Ensure session is started
?>
<?php
// Include config file
require_once "../config.php";

// Initialize variables for form validation and item data
$item_id = $item_name = $item_type = $item_category = $item_price = $item_description = "";
$item_id_err = "";

// Check if item_id is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $item_id = $_GET['id'];

    // Retrieve item details based on item_id
    $sql = "SELECT * FROM Menu WHERE item_id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_item_id);
        $param_item_id = $item_id;
        
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $item_name = $row['item_name'];
                $item_type = $row['item_type'];
                $item_category = $row['item_category'];
                $item_price = $row['item_price'];
                $item_description = $row['item_description'];
            } else {
                echo "Item not found.";
                exit();
            }
        } else {
            echo "Error retrieving item details.";
            exit();
        }
     
    }
}

// Process form submission when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $item_name = trim($_POST["item_name"]);
    $item_type = trim($_POST["item_type"]);
    $item_category = trim($_POST["item_category"]);
    $item_price = floatval($_POST["item_price"]);
    $item_description = $_POST["item_description"];
    $current_image = '';

    // Handle file upload
    if(isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $target_dir = __DIR__ . "/../uploads/"; // Use absolute path
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_extension = strtolower(pathinfo($_FILES["item_image"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Debug info
        error_log("Uploading file to: " . $target_file);
        
        // Check if image file is an actual image
        $check = getimagesize($_FILES["item_image"]["tmp_name"]);
        if($check === false) {
            echo "File is not an image.";
            exit();
        }
        
        // Check file size (max 5MB)
        if ($_FILES["item_image"]["size"] > 5000000) {
            echo "Sorry, your file is too large. Maximum size is 5MB.";
            exit();
        }
        
        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if(!in_array($file_extension, $allowed_types)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            exit();
        }
        
        // Upload file
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            $current_image = $new_filename;
            error_log("File uploaded successfully: " . $current_image);
            
            // Delete old image if it exists
            $old_image_query = "SELECT image FROM Menu WHERE item_id = '$item_id'";
            $old_image_result = mysqli_query($link, $old_image_query);
            if($old_image_row = mysqli_fetch_assoc($old_image_result)) {
                $old_image_path = dirname(__DIR__) . "/uploads/" . $old_image_row['image'];
                if(!empty($old_image_row['image']) && file_exists($old_image_path)) {
                    unlink($old_image_path);
                    error_log("Deleted old image: " . $old_image_path);
                }
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Update the item in the database
    $update_sql = "UPDATE Menu SET 
        item_name = '$item_name', 
        item_type = '$item_type', 
        item_category = '$item_category', 
        item_price = '$item_price', 
        item_description = '$item_description'";
        
    // Add image to query if a new one was uploaded
    if(!empty($current_image)) {
        $update_sql .= ", image = '$current_image'";
        error_log("Updating database with new image: " . $current_image);
    }
    
    $update_sql .= " WHERE item_id = '$item_id'";
    
    $resultItems = mysqli_query($link, $update_sql);
    
    if ($resultItems) {
        // Item updated successfully
        header("Location: ../panel/menu-panel.php");
        exit();
    } else {
        echo "Error updating item: " . mysqli_error($link);
    }

       
    }
    
    /*
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
     */

    // Close the database connection
    

?>

<!-- Create your HTML form for updating the item details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">  
    <title>Update Item</title>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
     <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: black;
            color: white;
        }

        .login-container {
            padding: 50px; /* Adjust the padding as needed */
            border-radius: 10px; /* Add rounded corners */
            margin: 100px auto; /* Center the container horizontally */
            max-width: 500px; /* Set a maximum width for the container */
        }

      

    



    </style>
</head>
<body>
     <div class="login-container">
        <div class="login_wrapper">
   
    <div class="wrapper">
    <h2 style="text-align: center;">Update Item</h2>
    <h5>Admin Credentials needed to Edit Item</h5>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="item_name"  class="form-label" >Item Name:</label>
            <input type="text" name="item_name" id="item_name" class="form-control"  placeholder="Spaghetti" value="<?php echo htmlspecialchars($item_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="item_type"  class="form-label">Item Type:</label>
            <input type="text" name="item_type" id="item_type" class="form-control"placeholder="Beer, Cocktail, etc .." value="<?php echo htmlspecialchars($item_type); ?>" required>
        </div>
        <div class="form-group"  class="form-label">
            <label for="item_category" >Item Category:</label>
            <input type="text" name="item_category" id="item_category" class="form-control" placeholder="Main Dish/ Side Dish/ Drinks" value="<?php echo htmlspecialchars($item_category); ?>" required>
        </div>
        <div class="form-group" class="form-label">
            <label for="item_price">Item Price:</label>
            <input type="number" min=0.01 step="0.01" name="item_price" id="item_price" placeholder="Enter Item Price"class="form-control" value="<?php echo htmlspecialchars($item_price);?>" required>
        </div>
        <div class="form-group">
            <label for="item_description" class="form-label">Item Description:</label>
            <textarea name="item_description" id="item_description" placeholder="The dish...." required class="form-control"><?php echo htmlspecialchars($item_description); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="item_image" class="form-label">Item Image:</label>
            <input type="file" name="item_image" id="item_image" class="form-control-file" accept="image/*">
            <?php 
            // Show current image if it exists
            $current_image_query = "SELECT image FROM Menu WHERE item_id = '$item_id'";
            $current_image_result = mysqli_query($link, $current_image_query);
            if($current_image_row = mysqli_fetch_assoc($current_image_result)) {
                if(!empty($current_image_row['image']) && file_exists("../uploads/" . $current_image_row['image'])) {
                    echo '<div class="mt-2">';
                    echo '<p>Current Image:</p>';
                    echo '<img src="../uploads/' . htmlspecialchars($current_image_row['image']) . '" style="max-width: 200px; max-height: 150px;" class="img-thumbnail">';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <br>
        <input type="hidden" name="item_id" value="   class="form-control">
        <button class="btn btn-light" type="submit" name="submit" value="submit">Update</button>
        <a class="btn btn-danger" href="../panel/menu-panel.php" >Cancel</a>
    </form>
    </div>
        </div>
    </div>
</body>
</html>