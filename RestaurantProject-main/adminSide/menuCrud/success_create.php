<?php
session_start();
require_once "../config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values from the form
    $item_id = $_POST["item_id"];
    $item_name = $_POST["item_name"];
    $item_type = $_POST["item_type"];
    $item_category = $_POST["item_category"];
    $item_price = $_POST["item_price"];
    $item_description = $_POST["item_description"];
    $conn = $link;

    // Prepare the SQL query to check if the item_id already exists
    $check_query = "SELECT item_id FROM Menu WHERE item_id = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("s", $item_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // Check if the item_id already exists
    if ($check_result->num_rows > 0) {
        $message = "The item_id is already in use.<br>Please try again to choose a different item_id.";
        $iconClass = "fa-times-circle";
        $cardClass = "alert-danger";
        $bgColor = "#FFA7A7"; // Custom background color for error
    } else {
        // Prepare the SQL query for insertion
        $insert_query = "INSERT INTO Menu (item_id, item_name, item_type, item_category, item_price, item_description) 
                        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);

        // Bind the parameters
        $stmt->bind_param("ssssds", $item_id, $item_name, $item_type, $item_category, $item_price, $item_description);

        // Execute the query
        if ($stmt->execute()) {
            $message = "Item created successfully.";
            $iconClass = "fa-check-circle";
            $cardClass = "alert-success";
            $bgColor = "#D4F4DD"; // Custom background color for success
        } else {
            $message = "Error: " . $insert_query . "<br>" . $conn->error;
            $iconClass = "fa-times-circle";
            $cardClass = "alert-danger";
            $bgColor = "#FFA7A7"; // Custom background color for error
        }

        // Close the prepared statement
        $stmt->close();
    }

    // Store feedback in session for display on form page
    $_SESSION['menu_message'] = [
        'type' => ($cardClass === 'alert-success') ? 'success' : 'error',
        'text' => strip_tags($message)
    ];

    // Close the check statement and the connection
    $check_stmt->close();
    $conn->close();

    // Redirect back to the create item form
    header("Location: ../panel/menu-panel.php");
    exit();
}
?>

    </style>
</head>
<body>
    <div id="modernMessage" class="modern-message <?php echo ($cardClass === 'alert-success') ? 'success' : 'error'; ?>">
        <?php echo strip_tags($message); ?>
    </div>
    <script>
        const msg=document.getElementById('modernMessage');
        if(msg){
            setTimeout(()=>{
                msg.style.opacity='0';
                setTimeout(()=>{ window.location.href='createItem.php'; },500);
            },2000);
        }
    </script>
    <div class="card <?php echo $cardClass; ?>" style="display: none;">
        <div style="border-radius: 200px; height: 200px; width: 200px; background: #F8FAF5; margin: 0 auto;">
            <?php if ($iconClass === 'fa-check-circle'): ?>
                <i class="checkmark">✓</i>
            <?php else: ?>
                <i class="custom-x" style="font-size: 100px; line-height: 200px;">✘</i>
            <?php endif; ?>
        </div>
        <h1><?php echo ($cardClass === 'alert-success') ? 'Success' : 'Error'; ?></h1>
        <p><?php echo $message; ?></p>
    </div>

    <div style="text-align: center; margin-top: 20px;">Redirecting back in <span id="countdown">3</span></div>

    <script>
        // Function to show the message card as a pop-up and start the countdown
        function showPopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "block";

            var i = 3;
            var countdownElement = document.getElementById("countdown");
            var countdownInterval = setInterval(function() {
                i--;
                countdownElement.textContent = i;
                if (i <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "createItem.php";
                }
            }, 1000); // 1000 milliseconds = 1 second
        }

        // Show the message card and start the countdown when the page is loaded
        window.onload = showPopup;

        // Function to hide the message card after a delay
        function hidePopup() {
            var messageCard = document.querySelector(".card");
            messageCard.style.display = "none";
            // Redirect to another page after hiding the pop-up (adjust the delay as needed)
            setTimeout(function () {
                window.location.href = "createItem.php"; // Replace with your desired URL
            }, 3000); // 3000 milliseconds = 3 seconds
        }

        // Hide the message card after 3 seconds (adjust the delay as needed)
        setTimeout(hidePopup, 3000);
    </script>
</body>
</html>
