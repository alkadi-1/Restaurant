<?php
session_start();
require_once "../config.php";

// Check if 'id' is set and not empty
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $member_id_to_delete = intval($_GET['id']);
} else {
    header("Location: ../panel/customer-panel.php");
    exit(); // Make sure to exit after redirect
}

// Initialize error message variable
$admin_login_error = '';

// Check for message from session
if (isset($_SESSION['admin_login_error'])) {
    $admin_login_error = $_SESSION['admin_login_error'];
    unset($_SESSION['admin_login_error']); // Clear the error after displaying it
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // User-provided input
    $provided_account_id = $_POST['admin_id']; // 99999
    $provided_password = $_POST['password']; // 12345
    $uniqueString = $provided_account_id . $provided_password;

    if ($uniqueString == "9999912345") {
        // Correct credentials, redirect to deleteCustomer.php with the member ID
        header("Location: ../customerCrud/deleteCustomer.php?id=" . $member_id_to_delete);
        exit();
    } else {
        // Incorrect credentials, set session error and redirect back to this page
        $_SESSION['admin_login_error'] = "Incorrect ID or Password!";
        header("Location: deleteCustomerVerify.php?id=" . $member_id_to_delete);
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="../css/verifyAdmin.css" rel="stylesheet" />
    <style>
        /* Modern error message styling */
        .modern-error-message {
            background-color: #ffcccc; /* Light red background */
            color: #d63031; /* Stronger red text */
            border: 1px solid #d63031;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login_wrapper">
            <div class="wrapper">
                <h2 style="text-align: center;">Admin Login</h2>
                <h5>Admin Credentials needed to Delete Member</h5>

                <?php if (!empty($admin_login_error)): ?>
                    <div class="modern-error-message">
                        <?php echo $admin_login_error; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group">
                        <label>Admin Id</label>
                        <input type="number" name="admin_id" class="form-control" placeholder="Enter Admin ID" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Admin Password" required>
                    </div>

                    <button class="btn btn-light" type="submit" name="submit" value="submit">Delete Member</button>
                    <a class="btn btn-danger" href="../panel/customer-panel.php" >Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = document.querySelector('.modern-error-message');
            if (message) {
                setTimeout(() => {
                    message.style.display = 'none';
                }, 2000);
            }
        });
    </script>
</body>
</html>
