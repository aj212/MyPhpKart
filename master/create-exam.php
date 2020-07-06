<?php
session_start();

require_once "db.php";

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["username"] != "Bonus"){
    header("location: admin.php");
    exit;
}


if($_SERVER["REQUEST_METHOD"] == "POST") {
  alert($_POST["data"]);
}


?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <meta charset="UTF-8">
    <title>Create Exam</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 650px; padding: 10px; }
        form{ margin-left: 150px; }

    </style>
</head>
<body>
<div class="wrapper">
  <h2>Create an Exam</h2>
  <div class="form-inline">
    <input type="text" class="form-control" name="examname" style='margin-bottom: 10px;' placeholder="Exam name goes here"/>
    <p>
      <button type="button" class="btn btn-primary" onclick="addQuestion()">Add Question</button>
      <button type="button" id="submit" class="btn btn-success" onclick="submit()">Submit</button>
      <a href="adminpage.php" class="btn btn-danger">Go back</a>
    </p>
    <p id="appendme"></p>
  </div>
</div>
</body>
</html>

<script>
	var qID = 1;
  var ans = 1;
  function submit() {
    var datastring = $("#f1").serialize();
    for(var i = 2; i < qID; i++) {
      var formname = "#f" + i
      datastring += "&" + $(formname).serialize();
    }
    $.ajax({
      type: "POST",
      url: "exam-helper.php",
      data: datastring,
      success: function(response) {
        alert(datastring);
      },
      failure: function() {
        alert("IT BROKE");
      }
    });
  }
  function addQuestion() {
    var QNum = document.createTextNode("Question #" + qID + ": ");
    var Question = document.createElement("input");
    Question.type = "text";
    Question.id = "q" + qID;
    Question.name = "questions[]";
    Question.classList.add("form-control");
    Question.placeholder = "Type question here";
    Question.style.marginTop = "20px";
    var id = Question.id;
    var points = document.createElement("input");
    points.type = "text";
    points.id = "p" + qID;
    points.name = "p" + qID;
    points.classList.add("form-control");
    points.placeholder = "Point value of question"
    points.style.marginLeft = "97px";
    points.style.marginTop = "10px";
    points.style.marginBottom = "5px";

    var bspan = document.createElement("span");
      // var fancyspan = document.createElement("span");
      // fancyspan.innerHTML = '<span class="help-block"/>'
    bspan.innerHTML = '<button id="'+qID+'" onclick="addAnswer(this.id)" class="btn btn-primary" style="margin-left: 10px; margin-top: 20px;">Add Answer</button>';
    var br = document.createElement("br");
    var form = document.createElement("form");
    form.id = "f" + qID;
    form.name = "q" + qID + "_answers";
    document.getElementById("appendme").appendChild(QNum);
    document.getElementById("appendme").appendChild(Question);
    document.getElementById("appendme").appendChild(bspan);
    document.getElementById("appendme").appendChild(br);
    document.getElementById("appendme").appendChild(points);
    document.getElementById("appendme").appendChild(form);
    // document.getElementById("appendme").appendChild(fancyspan);
    qID++;
  }
  function addAnswer(id) {
    var Answer = document.createElement("input");
    Answer.type = "text";
    Answer.placeholder = "Type answer choice here";
    Answer.id = "a" + ans;
    ansRad = Answer.id;
    Answer.name = "q" + id +"a" + ans;
    Answer.classList.add("form-control");
    Answer.style.marginBottom = "5px";
    Answer.style.marginTop = "5px";
    var txt = document.createTextNode(" Check here if correct:")

    var radio = document.createElement("input");
    radio.type = "radio";
    radio.name = "a" + ans + "correct";
    radio.id = "q" + id + "r" + ans;
    radio.classList.add("form-check-input");
    radio.style.marginTop = "10px";
    radio.style.marginLeft = "4px";

    id = "f" + id;
    var br = document.createElement("br");
    document.getElementById(id).appendChild(Answer);
    document.getElementById(id).appendChild(txt);
    document.getElementById(id).appendChild(radio);
    document.getElementById(id).appendChild(br);
    ans++;
  }
</script>
