<?php
// Include your database connection code here (not shown in this example).
require_once '../config.php';
session_start();

// Enable mysqli error reporting to throw exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$signupMessage = null;

// Define variables and initialize them to empty values
$email = $member_name = $password = $phone_number = "";
$email_err = $member_name_err = $password_err = $phone_number_err = "";

// Check if the form was submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else if (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email. Ex: johndoe@email.com";
    } else {
        $email = trim($_POST["email"]);
    }

    $selectCreatedEmail = "SELECT email from Accounts WHERE email = ?";

    if($stmt = $link->prepare($selectCreatedEmail)){
        $stmt->bind_param("s", $_POST['email']);

        $stmt->execute();

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $email_err = "This email is already registered.";
        } else {
            $email = trim($_POST["email"]);
        }
        $stmt->close();
    }

    // Validate member name
    if (empty(trim($_POST["member_name"]))) {
        $member_name_err = "Please enter your member name.";
    } else {
        $member_name = trim($_POST["member_name"]);
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    }

    // Validate phone number
    if (empty(trim($_POST["phone_number"]))) {
        $phone_number_err = "Please enter your phone number.";
    } else if(!is_numeric(trim($_POST['phone_number']))){
        $phone_number_err = "Only enter numeric values!";
    } else if (strlen(trim($_POST["phone_number"])) !== 10) {
        $phone_number_err = "Phone number must be 10 digits long.";
    } else {
        $phone_number = trim($_POST["phone_number"]);
    }

    // Check input errors before inserting into the database
    if (empty($email_err) && empty($member_name_err) && empty($password_err) && empty($phone_number_err)) {
        // Start a transaction
        mysqli_begin_transaction($link);

        // Prepare an insert statement for Accounts table
        $sql_accounts = "INSERT INTO Accounts (email, password, phone_number, register_date) VALUES (?, ?, ?, NOW())";
        if ($stmt_accounts = mysqli_prepare($link, $sql_accounts)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt_accounts, "sss", $param_email, $param_password, $param_phone_number);

            // Set parameters
            $param_email = $email;
            // Hash the password before storing
            $param_password = $password;
            $param_phone_number = $phone_number;

            echo "Debug: param_password (hashed) = " . $param_password . "<br>"; // Debugging line

            // Attempt to execute the prepared statement for Accounts table
            echo "Debug: Attempting to execute Accounts insert...<br>"; // Debugging line
            if (mysqli_stmt_execute($stmt_accounts)) {
                echo "Debug: Accounts insert successful.<br>"; // Debugging line
                // Get the last inserted account_id
                $last_account_id = mysqli_insert_id($link);
                echo "Debug: Last inserted Account ID = " . $last_account_id . "<br>"; // Debugging line

                // Prepare an insert statement for Memberships table
                $sql_memberships = "INSERT INTO Memberships (member_name, points, account_id) VALUES (?, ?, ?)";
                if ($stmt_memberships = mysqli_prepare($link, $sql_memberships)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt_memberships, "sii", $param_member_name, $param_points, $last_account_id);

                    // Set parameters for Memberships table
                    $param_member_name = $member_name;
                    $param_points = 0; // You can set an initial value for points

                    echo "Debug: Attempting to execute Memberships insert...<br>"; // Debugging line
                    // Attempt to execute the prepared statement for Memberships table
                    if (mysqli_stmt_execute($stmt_memberships)) {
                        echo "Debug: Memberships insert successful.<br>"; // Debugging line
                        echo "Debug: Attempting to commit transaction...<br>"; // Debugging line
                        // Commit the transaction
                        if (mysqli_commit($link)) {
                            echo "Debug: Transaction committed successfully.<br>"; // Debugging line
                            // Registration successful, redirect to the login page
                            $_SESSION['signup_message'] = ['type' => 'success', 'text' => "Registration successful! Welcome to Johnny's Bar & Dining."];
                            header("location: register.php"); // Redirect to register.php to display message
                            exit; // Re-enabled
                        } else {
                            echo "Debug: Commit failed. Error: " . mysqli_error($link) . "<br>"; // Debugging line
                            // Log the error for debugging
                            error_log("Memberships insert error: " . mysqli_error($link));
                            // Rollback the transaction if there was an error
                            mysqli_rollback($link);
                            echo "Oops! Something went wrong. Please try again later.";
                        }
                    } else {
                        // Log the error for debugging
                        error_log("Memberships insert error: " . mysqli_error($link));
                        // Rollback the transaction if there was an error
                        mysqli_rollback($link);
                        echo "Oops! Something went wrong. Please try again later.";
                    }

                    // Close the statement for Memberships table
                    mysqli_stmt_close($stmt_memberships);
                }
            } else {
                // Log the error for debugging
                error_log("Accounts insert error: " . mysqli_error($link));
                // Rollback the transaction if there was an error
                mysqli_rollback($link);
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close the statement for Accounts table
            mysqli_stmt_close($stmt_accounts);
        }
    }
}

// Check for and display session message on page load
if (isset($_SESSION['signup_message'])) {
    $signupMessage = $_SESSION['signup_message'];
    unset($_SESSION['signup_message']); // Clear the message after displaying it
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            display: flex;
            flex-direction: column; /* Stack children vertically */
            justify-content: center; /* Vertically center content */
            align-items: center; /* Center horizontally in a column layout */
            height: 100vh;
            margin: 0; /* Remove default margin */
            padding-top: 0; /* Remove padding-top */
            background-color:black;
             background-image: url('../image/loginBackground.jpg'); /* Set the background image path */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white;
            /* Removed z-index: auto; */
        }


        
/* Style for the container within login.php */
.register-container {
  padding: 50px; /* Adjust the padding as needed */
  border-radius: 10px; /* Add rounded corners */
  margin: 100px auto; /* Center the container horizontally and vertically within flex item */
  max-width: 500px; /* Set a maximum width for the container */
}
        .register_wrapper {
            width: 400px; /* Increase the container width */
            padding: 20px;
        }

        h2 {
            text-align: center;
            font-family: 'Montserrat', serif;
        }

        p {
            font-family: 'Montserrat', serif;
        }

        .form-group {
            margin-bottom: 15px; /* Add space between form elements */
        }

        ::placeholder {
            font-size: 12px; /* Adjust the font size as needed */
        }

        /* Add flip animation class to all Font Awesome icons */
        .fa-flip {
            animation: fa-flip 3s infinite;
        }

        /* Keyframes for the flip animation */
        @keyframes fa-flip {
            0% {
                transform: scale(1) rotateY(0);
            }
            50% {
                transform: scale(1.2) rotateY(180deg);
            }
            100% {
                transform: scale(1) rotateY(360deg);
            }
        }

        @keyframes scale {
            0%, 100% {
                transform: none;
            }
            50% {
                transform: scale3d(1.1, 1.1, 1);
            }
        }

        /* Adjusted styles for modern message to be a centered block like reservation error */
        .modern-message {
            position: static; 
            top: auto; 
            left: auto; 
            right: auto; 
            z-index: auto; 
            box-shadow: none;

            padding: 8px 12px; /* Even smaller padding for a more compact look */
            margin: 15px auto; /* Center the message horizontally and provide vertical spacing */
            border-radius: 8px; 
            font-weight: bold;
            text-align: center;
            opacity: 1;
            transition: opacity 0.5s ease-in-out;
            width: fit-content; /* Adjust width to content */
            max-width: 300px; /* Make it even narrower */
        }

        .modern-message.success {
            background-color: #28a745; /* Green for success */
            color: white;
        }
        .modern-message.error {
            background-color: #dc3545; /* Red for error */
            color: white;
        }

        .modern-message p {
            font-size: 0.9em; /* Smaller text for compactness */
            margin-bottom: 0; /* Remove default paragraph margin */
        }

        .modern-message h2 {
            font-size: 1.2em; /* Smaller heading for compactness (if kept) */
            margin-top: 0;
            margin-bottom: 0; /* No margin below heading if no icon */
            display: none; /* Hide the H2 (Success) as the reservation message doesn't have it */
        }

        .success-icon-container {
            display: none; /* Hide the success icon container */
        }

        .checkmark-icon {
            display: none; /* Hide the checkmark icon */
        }

        /* Ensure register-container is not pushed down unnecessarily if message is not fixed */
        .register-container {
            margin-top: 20px; /* Adjust margin to bring form closer to message */
            position: static; 
            z-index: auto; 
        }
        
    </style>
</head>
<body>
    <div class="register-container">
    <div class="register_wrapper"> <!-- Updated class name -->
        <a class="nav-link" href="../home/home.php#hero"> <h1 class="text-center" style="font-family:Copperplate; color:white;"> JOHNNY'S</h1><span class="sr-only"></span></a>
       
	<?php if ($signupMessage): ?>
		<div id="modernMessage" class="modern-message <?= $signupMessage['type'] ?>">
			<p><?= htmlspecialchars($signupMessage['text']) ?></p>
		</div>
	<?php endif; ?>

        <form action="register.php" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" placeholder="Enter Email">
                                <span class="text-danger"><?php echo $email_err; ?></span>
            </div>

            <div class="form-group">
                <label>Member Name</label>
                <input type="text" name="member_name" class="form-control" placeholder="Enter Member Name">
                                <span class="text-danger"><?php echo $member_name_err; ?></span>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter Password">
                                <span class="text-danger"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone_number" class="form-control" placeholder="Enter Phone Number" maxlength="10">
                                <span class="text-danger"><?php echo $phone_number_err; ?></span>
            </div>

            <button style="background-color:black;" class="btn btn-dark" type="submit" name="register" value="Register">Register</button>
           
        </form>

        <p style="margin-top:1em; color:white;">Already have an account? <a href="../customerLogin/login.php" >Proceed to Login</a></p>
    </div>
    </div>
    <script src="
	https://code.jquery.com/jquery-3.5.1.slim.min.js"
		integrity="
	sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
		crossorigin="anonymous">
	</script>
	
	<script src="
	https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
		integrity=
	"sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
		crossorigin="anonymous">
	</script>
	
	<script src="
	https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
		integrity=
	"sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
		crossorigin="anonymous">
	</script>

	<script>
		// Auto-hide the modern message after 2 seconds
		const modernMessage = document.getElementById("modernMessage");
		if (modernMessage) {
			const messageType = modernMessage.classList.contains('success') ? 'success' : 'error';

			if (messageType === 'success') {
				// For success messages, fade out and redirect after 2 seconds
				setTimeout(() => {
					modernMessage.style.opacity = "0";
					setTimeout(() => {
						modernMessage.style.display = "none";
						window.location.href = '../customerLogin/login.php'; // Redirect to login page immediately after fade out
					}, 500); // Wait for the fade-out transition to complete
				}, 2000); // 2 seconds
			} else {
				// For error messages, just fade out after 2 seconds
				setTimeout(() => {
					modernMessage.style.opacity = "0";
					setTimeout(() => {
						modernMessage.style.display = "none";
					}, 500); // Wait for the fade-out transition to complete
				}, 2000); // 2 seconds
			}
		}
	</script>
</body>
</html>