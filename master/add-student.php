<?php
session_start();

require_once "db.php";

$sID = "";
$name = "";
$major = "";
$password = "";
$sID_err = "";
$name_err = "";
$major_err = "";
$password_err = "";


if($_SERVER["REQUEST_METHOD"] == "POST") {
  // Makes sure all required info is inputted
  if(trim($_POST["sID"]) == false) {
    $sID_err = "Error: Please insert the student's ID";
  }
  else {
    $sID = trim($_POST["sID"]);
  }
  if(trim($_POST["name"]) == false) {
    $name_err = "Error: Please insert the student's name";
  }
  else {
    $name = trim($_POST["name"]);
  }
  if(trim($_POST["major"]) == false) {
    $major_err = "Error: Please insert the student's major";
  }
  else {
    $major = trim($_POST["major"]);
  }
  if(trim($_POST["password"]) == false) {
    $password_err = "Error: Please insert a password";
  }
  else {
    $password = trim($_POST["password"]);
  }
  // Checks to make sure everything has been inputted
  if(empty($sID_err) && empty($name_err) && empty($major_err) && empty($password_err)) {
    // Begins to prepare statement
    $addsql = "CALL addStudent(?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $addsql)) {
      // Binds params to statement
      mysqli_stmt_bind_param($stmt, "ssss", $param_sID, $param_name, $param_major, $param_password);
      $param_sID = $sID;
      $param_name = $name;
      $param_major = $major;
      $param_password = md5($password);
      // Executes prepared statement
      if(mysqli_stmt_execute($stmt)) {
        echo "Student $sID added successfully!";
      }
      else {
        echo "Error: Something went wrong try again";
      }
    }
    // Closes prepared statement and link to DB
    mysqli_stmt_close($stmt);
  }
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Add Student</h2>
        <p>Please fill in all text boxes with student information.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($sID_err)) ? 'has-error' : ''; ?>">
                <label>Student ID</label>
                <input type="text" name="sID" class="form-control">
                <span class="help-block"><?php echo $sID_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Name</label>
                <input type="text" name="name" class="form-control">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($major_err)) ? 'has-error' : ''; ?>">
                <label>Major</label>
                <input type="text" name="major" class="form-control">
                <span class="help-block"><?php echo $major_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create">
            </div>
            <p>Click <a href="adminpage.php">here</a> to go back to your page</p>
        </form>
    </div>
</body>
</html>
