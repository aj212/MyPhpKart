<?php

session_start();

require_once "db.php";

// Both are going to be 2D arrays
/*
qID     qname       correct_answer     value
*/
$questions = array();
/*
qID     answer     correct
*/
$answers = array();
$exam = $_SESSION["exam"];
$username = $_SESSION["username"];
// Queries database to check if the exam has been taken or not
$checkquery = mysqli_query($link, "SELECT sid, ename FROM grades_final WHERE sid = '$username' AND ename = '$exam'");
if(mysqli_num_rows($checkquery) > 0) {
  print("You have already taken this exam! Please return to your homepage to view your grade");
}
else {
  // If not taken webpage queries the data from question and answer tables
  $questionquery = mysqli_query($link, "SELECT qID, qname, correct_answer, answer_choices, value FROM questions_final WHERE ename = '$exam'");
  $answerquery = mysqli_query($link, "SELECT qID, answer, correct FROM answers_final WHERE ename = '$exam' ORDER BY qID, answer");
  // Error checking for both tables. If either fails then it outputs an error
  if(!$questionquery) {
    printf("Error: %s\n", mysqli_error($link));
  }
  else {
    while($table = mysqli_fetch_array($questionquery, MYSQL_ASSOC)){
      $questions[] = $table;
    }
  }
  if(!$answerquery) {
    printf("Error: %s\n", mysqli_error($link));
  }
  else {
    while($table = mysqli_fetch_array($answerquery, MYSQL_ASSOC)) {
      $answers[] = $table;
    }
  }
  // Individual array for each attribute of both tables
  $qids = array();
  $qname = array();
  $correct_answer = array();
  $answer_choices = array();
  $value = array();
  // Separates data from the question and answer arrays into their respective attribute arrays
  foreach($questions as $question) {
    $qids[] = $question["qID"];
    $qname[] = $question["qname"];
    $correct_answer[] = $question["correct_answer"];
    $answer_choices[] = $question["answer_choices"];
    $value[] = $question["value"];
  }
  $ans_qids = array();
  $ans = array();
  $correct = array();
  foreach($answers as $answer) {
    $ans_qids[] = $answer["qID"];
    $ans[] = $answer["answer"];
    $correct[] = $answer["correct"];
  }
  // Check if the first question is properly set and if so checks the rest
  if(isset($_POST["q1"])) {
    $q = array($_POST["q1"]);
    // Loads answers selected by student into another array
    for($i = 1; $i < count($qids); $i++) {
      $temp = "q" . ($i + 1);
      array_push($q, $_POST[$temp]);
    }
    // Calculates the score
    $score = 0;
    $escore = 0;
    for($i = 0; $i < count($qids); $i++) {
      $score = 0;
      // adds value to total score based off of whether they answered correct or not
      if($q[$i] == $correct_answer[$i]) {
        $score = $value[$i];
        $escore += $value[$i];
      }
      $gradequery = mysqli_query($link, "INSERT INTO grades_final VALUES('$username', '$exam', '$qids[$i]', '$qname[$i]', '$q[$i]', '$correct_answer[$i]', '$score')");
    }
    // Redirects user to the exam grade once submitted
    header("location: view-grades.php");
  }
}
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
     </style>
 </head>
 <body>
     <div class="page-header">
       <form id="form1" method="post">
         <button type="submit" class="btn btn-primary" method="post">Submit</button>
         <a href="view-grades.php" class="btn btn-success">View Grade!</a>
         <a href="welcome.php" class="btn btn-danger">Return Home</a>
         <p id="appendme"></p>
       </form>
     </div>

 </body>
 </html>

<script>
// Transfers arrays from PHP to JS
var qids = <?php echo json_encode($qids); ?>;
var qname = <?php echo json_encode($qname); ?>;
var answer_choices = <?php echo json_encode($answer_choices); ?>;
var correct_answer = <?php echo json_encode($correct_answer); ?>;
var value = <?php echo json_encode($value); ?>;
var ans_qids = <?php echo json_encode($ans_qids); ?>;
var ans = <?php echo json_encode($ans); ?>;
var correct = <?php echo json_encode($correct); ?>;
var ansid = 0; // This is used to check how many answers each question has
// Loops through the length of the qID array for each quetion
for(var i = 0; i < qids.length; i++) {
  // Loads the question number
  var qnum = document.createElement("h1");
  qnum.innerHTML = "Question" + (i + 1);
  qnum.value = value[i];
  // Loads the question
  var question = document.createElement("p");
  var br = document.createElement("br");
  question.innerHTML = qname[i];
  question.name = correct_answer[i];
  // Adds them to the webpage
  document.getElementById("appendme").appendChild(qnum);
  document.getElementById("appendme").appendChild(br);
  document.getElementById("appendme").appendChild(question);
  // Loops through answer array to find the answers for each question
  for(var j = ansid; j < parseInt(ansid) + parseInt(answer_choices[i]); j++) {
    // Loads radio buttons into webpage
    var choice = document.createElement("input");
    // Sets choice to the name of each answer so when submitted the value received is
    // What the user chose
    choice.type = "radio";
    choice.name = qids[i];
    choice.id = "ans" + j;
    choice.value = ans[j];
    var divv = document.createElement("div");
    var choiceName = document.createTextNode(ans[j]);
    document.getElementById("appendme").appendChild(divv);
    document.getElementById("appendme").appendChild(choice);
    document.getElementById("appendme").appendChild(choiceName);
    document.getElementById("appendme").appendChild(br);
  }
  // Updates ansid so that it can continue to loop
  ansid =  parseInt(ansid) + parseInt(answer_choices[i]);
}

</script>
