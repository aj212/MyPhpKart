<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "db.php";
$new_password = "";
$confirm_password = "";
$new_password_error = "";
$confirm_password_error = "";
$username = $_SESSION["username"];

// Server requests the form once posted
if($_SERVER["REQUEST_METHOD"] == "POST") {
  //Checks to make sure everything is inputted
  if(trim($_POST["new_password"] == false)) {
    $new_password_error = "Error: Please enter your new password";
  }
  else {
    $new_password = trim($_POST["new_password"]);
  }
  if(trim($_POST["confirm_password"] == false)) {
    $confirm_password_error = "Error: Please confirm your password";
  }
  else {
    $confirm_password = trim($_POST["confirm_password"]);
    if(empty($new_password_error) && strcmp($new_password, $confirm_password) != 0) {
      $confirm_password_error = "Error: Passwords do not match";
    }
  }
  if(empty($new_password_error) && empty($confirm_password_error)) {
    // Prepares a statement to update the students database
    $sqlchangepass = "UPDATE student_final SET password = ? WHERE sID = ?";

    if($stmt = mysqli_prepare($link, $sqlchangepass)) {
      // Binds new password to statement
      mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
      $param_username = $username;
      $param_password = md5($new_password);
      // Updates and returns student to home page
      if(mysqli_stmt_execute($stmt)) {
        session_destroy();
        header("location: welcome.php");
        exit();
      }
      else {
        echo "Error: Something went wrong try again";
      }
    }
    mysqli_stmt_close($stmt);
  }
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group <?php echo (!empty($new_password_error)) ? 'has-error' : ''; ?>">
              <label>New Password</label>
              <input type="password" name="new_password" class="form-control">
              <span class="help-block"><?php echo $new_password_error; ?></span>
          </div>
          <div class="form-group <?php echo (!empty($confirm_password_error)) ? 'has-error' : ''; ?>">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control">
              <span class="help-block"><?php echo $confirm_password_error; ?></span>
          </div>
          <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <a class="btn btn-link" href="welcome.php">Cancel</a>
          </div>
      </form>
    </div>
</body>
</html>
