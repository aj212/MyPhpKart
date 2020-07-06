<?php
// Initialize the session
session_start();

require_once "db.php";

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$exams = array();
$getexams = mysqli_query($link, "SELECT ename FROM exams_final");
while($row = mysqli_fetch_array($getexams)) {
  $exams[] = explode(", ", $row['ename']);
}

if(isset($_POST["exam"])) {
  print $_POST["exam"];
  $_SESSION["exam"] = $_POST["exam"];
  unset($_POST["exam"]);
  header("location: view-grades.php");
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
    <a href="welcome.php" class="btn btn-danger">Return to homepage </a>
</body>
</html>

<script>
// Transfers array from PHP to JS
var exams = <?php echo json_encode($exams); ?>;
// Loops through array to create a button for each exam
for(var i = 0; i < exams.length; i++) {
  var ebutton = document.createElement('button');
  var ename = exams[i];
  ebutton.id = i;
  ebutton.type = "submit";
  ebutton.setAttribute('name', 'exam');
  ebutton.setAttribute('class', 'btn btn-primary');
  ebutton.setAttribute('method', 'POST');
  ebutton.setAttribute('value', ename)
  ebutton.innerHTML = exams[i];
  document.getElementById("appendme").appendChild(ebutton);
}



</script>
