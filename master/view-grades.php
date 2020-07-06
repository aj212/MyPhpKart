<?php
session_start();
require_once "db.php";
$exam = $_SESSION["exam"];
$username = $_SESSION["username"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; align: center; }
        button{margin-left: 5px;}
        a{margin-top: 10px;}
    </style>
</head>
<body>
  <body>
      <table class="table table-striped table-bordered">
      <thead>
          <tr>
              <td>sID</td>
              <td>Test</td>
              <td>Question</td>
              <td>Your Answer</td>
              <td>Correct Answer</td>
              <td>Points Earned</td>
          </tr>
      </thead>
      <tbody>
      <?php
        // Queries the username and exam of the current logged in user
          $checkquery = mysqli_query($link, "SELECT * FROM grades_final WHERE sid = '$username' AND ename = '$exam'");
          // Gets the total earned from the test and logged in student
          $score = mysqli_query($link, "SELECT sum(points_earned) as score FROM grades_final WHERE sid = '$username' AND ename = '$exam'");
          // Gets exam max possible score
          $total = mysqli_query($link, "SELECT total FROM exams_final WHERE eName = '$exam'");
          if(mysqli_num_rows($checkquery) == 0) {
            print("Exam not taken yet please return to your home page and take the exam");
          }
          else {
            // Creates a table of all the values of the exam
            while($row = mysqli_fetch_array($checkquery, MYSQL_ASSOC)) {
              ?>
              <tr>
                  <td><?php echo $row['sid']?></td>
                  <td><?php echo $row['ename']?></td>
                  <td><?php echo $row['qname']?></td>
                  <td><?php echo $row['answer']?></td>
                  <td><?php echo $row['correct_answer']?></td>
                  <td><?php echo $row['points_earned']?></td>
              </tr>
              <?php
            }
          }
          ?>
          </tbody>
          </table>
    <p> <?php while($row = mysqli_fetch_array($score, MYSQL_ASSOC)) {
      // Prints out score earned / max score
      echo $row['score'];
    } ?> /
    <?php while($col = mysqli_fetch_array($total, MYSQL_ASSOC)) {
      echo $col['total'];
    }
    ?> </p>
    <a href="view-exams.php" class="btn btn-danger">View Exams</a>
</body>
</html>
