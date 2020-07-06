<?php
session_start();
// Checks if instructor is already logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true && $_SESSION["username"] == "Bonus"){
    header("location: adminpage.php");
    exit;
}
// Connects to DB
require_once "db.php";

$username = "";
$password = "";
$user_err = "";
$pass_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
// Checks if all fields are filled
  if(trim($_POST["username"]) == false) {
		$user_err = "Error: No username inputted";
	}
	else {
		$username = trim($_POST["username"]);
	}
	if(trim($_POST["password"]) == false) {
		$pass_err = "Error: No password inputted";
	}
	else {
		$password = trim($_POST["password"]);
	}
// If all fields are filled begins to prepare statement
	if(empty($user_err) && empty($pass_err)) {
		$loginsql = "SELECT iName, password FROM instructor_final WHERE iName = ?";

		if($stmt = mysqli_prepare($link, $loginsql)) {
// Binds params to prepared statement
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;
// Executes prepared statement
			if(mysqli_stmt_execute($stmt)) {
				mysqli_stmt_store_result($stmt);
// Checks to make sure only one account is returned
				if(mysqli_stmt_num_rows($stmt) == 1) {
// Binds results from prepared statement to variables
					mysqli_stmt_bind_result($stmt, $username, $check_password);
					if(mysqli_stmt_fetch($stmt)) {
// Checks if passwords are matching
						if(md5($password) == $check_password) {
// Stores log in info in session along with username then redirects to the admin page
							session_start();
							$_SESSION["loggedin"] = true;
							$_SESSION["username"] = $username;
							header("location: adminpage.php");
						}
						else {
							$pass_err = "Error: Incorrect password";
						}
					}
				}
				else {
					$user_err = "Error: Username does not exist";
				}
			}
			else {
				echo "Something went wrong try again";
			}
		}
// Closes prepared statement and link to database to not cause future errors
		mysqli_stmt_close($stmt);
	}
	mysqli_close($link);
}

?>
<!DOCTYPE html>
<!-- Bootstraps CSS in -->
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Admin Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <!-- If there is an error with the username, message will be displayed -->
            <div class="form-group <?php echo (!empty($user_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $user_err; ?></span>
            </div>
          <!-- If there is an error with the password, message will be displayed -->
            <div class="form-group <?php echo (!empty($pass_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $pass_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Click <a href="login.php">here</a> to go back to student login</p>
        </form>
    </div>
</body>
</html>
