<?php
// Initialize the session
session_start();

require_once "db.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Get the student ids
$students = array();
$getstudents = mysqli_query($link, "SELECT sid FROM student_final");
while($row = mysqli_fetch_array($getstudents)) {
  $students[] = explode(", ", $row['sid']);
}
// Checks if a username has been picked and if so it puts it into a session
// Then it unsets the $_POST making it so the admin is able to easily view another students
// grade
if(isset($_POST["sUsername"])) {
  print $_POST["sUsername"];
  $_SESSION["sUsername"] = $_POST["sUsername"];
  unset($_POST["sUsername"]);
  header("location: view-grades-admin.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        button{margin-left: 5px;}
        a{margin-top: 10px;}
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Please select an exam grade to view <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
    </div>
    <form id="appendme" method="post">

    </form>
    <a href="adminpage.php" class="btn btn-danger">Return to homepage </a>
</body>
</html>

<script>
// Creates a button for each student
var students = <?php echo json_encode($students); ?>;
for(var i = 0; i < students.length; i++) {
  var ebutton = document.createElement('button');
  var sid = students[i];
  ebutton.id = i;
  ebutton.type = "submit";
  ebutton.setAttribute('name', 'sUsername');
  ebutton.setAttribute('class', 'btn btn-primary');
  ebutton.setAttribute('method', 'POST');
  ebutton.setAttribute('value', sid)
  ebutton.innerHTML = students[i];
  document.getElementById("appendme").appendChild(ebutton);
}



</script>
