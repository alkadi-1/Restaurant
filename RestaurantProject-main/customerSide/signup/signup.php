<?php
require_once '../config.php';
session_start();

// Enable mysqli error reporting to throw exceptions for better error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$signupMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	try {
		$username = $_POST["username"];
		$password = $_POST["password"];
		$cpassword = $_POST["cpassword"];
		$email = $_POST["email"];
		$phone_number = $_POST["phone_number"];

		// Basic validation: Check if fields are not empty
		if (empty($username) || empty($password) || empty($email) || empty($phone_number)) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "All fields are required."];
			header("Location: signup.php");
			exit();
		}

		// Validate email format
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Invalid email format. Please enter a valid email address."];
			header("Location: signup.php");
			exit();
		}

		// Validate phone number (simple numeric check for 7 to 15 digits)
		if (!preg_match('/^[0-9]{7,15}$/', $phone_number)) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Invalid phone number. Please enter 7 to 15 digits."];
			header("Location: signup.php");
			exit();
		}

		// Check if username already exists in 'users' table
		$sql_check_user = "SELECT username FROM users WHERE username = ?";
		$stmt_check_user = mysqli_prepare($link, $sql_check_user);
		mysqli_stmt_bind_param($stmt_check_user, "s", $username);
		mysqli_stmt_execute($stmt_check_user);
		mysqli_stmt_store_result($stmt_check_user);

		if (mysqli_stmt_num_rows($stmt_check_user) > 0) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Username already exists. Please choose a different username."];
			header("Location: signup.php");
			exit();
		}
		mysqli_stmt_close($stmt_check_user);

		// Check if email already exists in 'Accounts' table
		$sql_check_email = "SELECT email FROM Accounts WHERE email = ?";
		$stmt_check_email = mysqli_prepare($link, $sql_check_email);
		mysqli_stmt_bind_param($stmt_check_email, "s", $email);
		mysqli_stmt_execute($stmt_check_email);
		mysqli_stmt_store_result($stmt_check_email);

		if (mysqli_stmt_num_rows($stmt_check_email) > 0) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Email already registered. Please use a different email or login."];
			header("Location: signup.php");
			exit();
		}
		mysqli_stmt_close($stmt_check_email);

		// Check if passwords match
		if ($password !== $cpassword) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Passwords do not match."];
			header("Location: signup.php");
			exit();
		}

		// Hash the password
		$hash = password_hash($password, PASSWORD_DEFAULT);

		// Insert into Accounts table first (assuming account_id is AUTO_INCREMENT)
		$sql_insert_account = "INSERT INTO Accounts (email, register_date, phone_number, password) VALUES (?, CURRENT_TIMESTAMP(), ?, ?)";
		$stmt_insert_account = mysqli_prepare($link, $sql_insert_account);
		mysqli_stmt_bind_param($stmt_insert_account, "sss", $email, $phone_number, $hash);
		mysqli_stmt_execute($stmt_insert_account);
		$account_id = mysqli_insert_id($link); // Get the auto-incremented account_id
		mysqli_stmt_close($stmt_insert_account);

		// Insert into users table
		$sql_insert_user = "INSERT INTO users (username, password, date) VALUES (?, ?, CURRENT_TIMESTAMP())";
		$stmt_insert_user = mysqli_prepare($link, $sql_insert_user);
		mysqli_stmt_bind_param($stmt_insert_user, "ss", $username, $hash);
		mysqli_stmt_execute($stmt_insert_user);
		mysqli_stmt_close($stmt_insert_user);

		// If both insertions are successful
		$_SESSION['signup_message'] = ['type' => 'success', 'text' => "Registration successful! Welcome to Johnny's Bar & Dining. Please login with your account."];

	} catch (mysqli_sql_exception $e) {
		// Catch MySQLi exceptions
		$errorMessage = $e->getMessage();
		if (strpos($errorMessage, 'Duplicate entry') !== false) {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "A user with this data already exists. Please try different details."];
		} else {
			$_SESSION['signup_message'] = ['type' => 'error', 'text' => "Database error during registration: " . $errorMessage];
		}
	} catch (Exception $e) {
		// Catch any other general exceptions
		$_SESSION['signup_message'] = ['type' => 'error', 'text' => "An unexpected error occurred during registration: " . $e->getMessage()];
	}

	header("Location: signup.php");
	exit();
}

// Check for and display session message on page load
if (isset($_SESSION['signup_message'])) {
	$signupMessage = $_SESSION['signup_message'];
	unset($_SESSION['signup_message']); // Clear the message after displaying it
}

?>
	
<!doctype html>
	
<html lang="en">

<head>
	
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content=
		"width=device-width, initial-scale=1,
		shrink-to-fit=no">
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href=
"https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
		integrity=
"sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
		crossorigin="anonymous">
	<title>Signup</title>
	<style>
		body {
			font-family: 'Montserrat', sans-serif;
			background-color: rgb(37, 42, 52);
			color: white;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			min-height: 100vh;
			margin: 0;
		}

		.container {
			background-color: #2b303b; /* Darker background for the form container */
			padding: 30px;
			border-radius: 10px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			max-width: 500px;
			width: 100%;
		}

		h1 {
			color: #ffffff;
			margin-bottom: 25px;
			font-family: Copperplate;
		}

		.form-group label {
			color: #cccccc;
		}

		.form-control {
			background-color: #3b424e;
			border: 1px solid #5a6268;
			color: white;
		}

		.form-control:focus {
			background-color: #3b424e;
			border-color: #007bff;
			box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
			color: white;
		}

		.btn-primary {
			background-color: #007bff;
			border-color: #007bff;
			width: 100%;
			padding: 10px;
			font-size: 1.1em;
			margin-top: 20px;
		}

		.btn-primary:hover {
			background-color: #0056b3;
			border-color: #0056b3;
		}

		.form-text {
			color: #999999 !important;
		}

		a {
			color: #007bff;
		}

		a:hover {
			color: #0056b3;
		}

		.modern-message {
			padding: 15px;
			margin-bottom: 20px;
			border-radius: 8px;
			font-weight: bold;
			text-align: center;
			opacity: 1;
			transition: opacity 0.5s ease-in-out;
			width: 80%; /* Adjust width as needed */
			max-width: 600px; /* Max width for larger screens */
		}
		.modern-message.success {
			background-color: #28a745; /* Green for success */
			color: white;
		}
		.modern-message.error {
			background-color: #dc3545; /* Red for error */
			color: white;
		}

		.success-icon-container {
			display: flex;
			flex-direction: column;
			align-items: center;
			margin-bottom: 15px;
		}

		.checkmark-icon {
			width: 60px;
			height: 60px;
			border-radius: 50%;
			display: block;
			stroke-width: 3;
			stroke: #fff;
			stroke-miterlimit: 10;
			box-shadow: inset 0px 0px 0px #7ac142;
			animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
		}

		.checkmark-icon circle {
			stroke-dasharray: 166;
			stroke-dashoffset: 166;
			stroke-width: 3;
			stroke-miterlimit: 10;
			stroke: #7ac142; /* Green circle */
			fill: none;
			animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
		}

		.checkmark-icon path {
			transform-origin: 50% 50%;
			stroke-dasharray: 48;
			stroke-dashoffset: 48;
			animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
		}

		.modern-message h2 {
			color: white;
			margin-top: 10px;
			font-size: 2em;
			font-weight: bold;
		}

		.modern-message p {
			margin-top: 10px;
			font-size: 1.1em;
		}

		@keyframes stroke {
			100% {
				stroke-dashoffset: 0;
			}
		}

		@keyframes fill {
			100% {
				box-shadow: inset 0px 0px 0px 30px #7ac142;
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
	</style>
</head>
	
<body>
	
	<a class="nav-link" href="../home/home.php#hero">
		<h1 class="text-center" style="font-family: Copperplate; color: whitesmoke;">JOHNNY'S</h1>
		<span class="sr-only"></span>
	</a>

	<?php if ($signupMessage): ?>
		<div id="modernMessage" class="modern-message <?= $signupMessage['type'] ?>">
			<?php if ($signupMessage['type'] === 'success'): ?>
				<div class="success-icon-container">
					<svg class="checkmark-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
						<circle cx="26" cy="26" r="25" fill="none"/>
						<path fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
					</svg>
					<h2>Success</h2>
				</div>
			<?php endif; ?>
			<p><?= htmlspecialchars($signupMessage['text']) ?></p>
		</div>
	<?php endif; ?>
	
	<div class="container my-4 ">
	
		<h1 class="text-center">Signup Here</h1>
		<form action="signup.php" method="post">
	
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>	
			</div>
	
			<div class="form-group">
				<label for="username">Member Name</label>
				<input type="text" class="form-control" id="username" name="username" required>
			</div>
	
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control"
				id="password" name="password" required>
			</div>
	
			<div class="form-group">
				<label for="cpassword">Confirm Password</label>
				<input type="password" class="form-control"
					id="cpassword" name="cpassword" required>
	
				<small id="emailHelp" class="form-text text-muted">
				Make sure to type the same password
				</small>
			</div>	
	
			<div class="form-group">
				<label for="phone_number">Phone Number</label>
				<input type="text" class="form-control" id="phone_number" name="phone_number" required>
			</div>
	
			<button type="submit" class="btn btn-primary">
			Register
			</button>
		</form>
		<p class="text-center mt-3">Already have an account? <a href="../customerLogin/login.php">Proceed to Login</a></p>
	</div>
	
	<!-- Optional JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	
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
				let countdown = 5; // Redirect after 5 seconds
				const redirectMessage = document.createElement('p');
				redirectMessage.id = 'redirectMessage';
				redirectMessage.style.marginTop = '10px';
				redirectMessage.style.fontSize = '0.9em';
				redirectMessage.textContent = `Redirecting in ${countdown} seconds...`;
				modernMessage.appendChild(redirectMessage);

				const countdownInterval = setInterval(() => {
					countdown--;
					if (countdown > 0) {
						redirectMessage.textContent = `Redirecting in ${countdown} seconds...`;
					} else {
						clearInterval(countdownInterval);
						window.location.href = '../customerLogin/login.php'; // Redirect to login page
					}
				}, 1000);
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
