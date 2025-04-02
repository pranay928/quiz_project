<?php
include 'connection.php';


$sql = "SELECT * FROM quize";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Store all questions in an array
    $questions = array();
    while ($row = $result->fetch_assoc() ) {
        $questions[] = array(
            'id' => $row['id'],
            'question' => $row['que'],
            'option1' => $row['ops1'],
            'option2' => $row['ops2'],
            'option3' => $row['ops3'],
            'option4' => $row['ops4'],
            'answer' => $row['ans'],
            'mark' => $row['mark']            
        );
    }  
}
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
<style>
* {
  box-sizing: border-box;
}
body {
  background-color: #f1f1f1;
}
#regForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}
h1 {
  text-align: center;  
}
input[type="radio"] {
  margin-right: 10px;
}
button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  cursor: pointer;
}
button:hover {
  opacity: 0.8;
}
#prevBtn {
  background-color: #bbbbbb;
}
.tab {
  display: none;
}
</style>
</head>
<body>
  <a href="enter_question.php">admin</a>

<form id="regForm" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
  <h1>PHP Quiz</h1>  
  <!-- Loop through questions and create tabs -->
  <?php 
  $index = 0;
  while ($index < count($questions)) {
    $q = $questions[$index];
  ?>
  <div class="tab">
    <h3>Question <?php echo $index + 1; ?>:</h3>
    <p><?php echo $q['question']; ?>  <span style="text-align: right; color: green;">marks :<?php echo $q['mark']; ?> </span></p>    
   
    <p><input type="radio" name="answer[<?php echo $q['id']; ?>] " value="1"> <?php echo $q['option1']; ?></p>
    <input type="hidden" name="option1[<?php echo $q['id']; ?>]" value="<?php echo $q['option1']; ?>">

    <p><input type="radio" name="answer[<?php echo $q['id']; ?>]" value="2"> <?php echo $q['option2']; ?></p>
    <input type="hidden" name="option2[<?php echo $q['id']; ?>]" value="<?php echo $q['option2']; ?>">

    <p><input type="radio" name="answer[<?php echo $q['id']; ?>]" value="3"> <?php echo $q['option3']; ?></p>
     <input type="hidden" name="option3[<?php echo $q['id']; ?>]" value="<?php echo $q['option3']; ?>">

    <p><input type="radio" name="answer[<?php echo $q['id']; ?>]" value="4"> <?php echo $q['option4']; ?></p>
    <input type="hidden" name="option4[<?php echo $q['id']; ?>]" value="<?php echo $q['option4']; ?>">   

    <input type="hidden" name="correct_answer[<?php echo $q['id']; ?>]" value="<?php echo $q['answer']; ?>">
  </div>
  <?php 
  
    $index++;
  }
  ?>

<div style="overflow:auto;">
  <div style="float:right;">
    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
    <button type="button" id="nextBtn">Next</button>
  </div>
</div>

</form>
<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
  // Compare submitted answers with correct answers
  $correct = 0;
  $wrong = 0;
  $total_marks = 0; 
  $wrong_marks = 0; 
  $total_possible_marks = 0; 

  foreach ($_POST['answer'] as $id => $answer) {
    
    switch ($answer) {
      case 1:
        $user_answer = $_POST["option1"][$id];
        break;
      case 2:
        $user_answer = $_POST["option2"][$id];
        break;
      case 3:
        $user_answer = $_POST["option3"][$id];
        break;
      case 4:
        $user_answer = $_POST["option4"][$id];
        break;
      default:
        $user_answer = "";
    }
    
    // Find the question and its marks
    $current_question = null;
    foreach ($questions as $question) {
      if ($question['id'] == $id) {
        $current_question = $question;
        $total_possible_marks += intval($question['mark']);
        break;
      }
    }

    // Compare mapped answer with correct answer
    if (trim(strtolower($user_answer)) == trim(strtolower($_POST['correct_answer'][$id]))) {
      $correct++; 
      $total_marks += intval($current_question['mark']); 
      
      echo "<br> Question : " . $current_question['question'] . "<br>"; 
      echo "<p style='color:green;'>Your answer: " . $user_answer . " (+". $current_question['mark'] ." marks)<br></p>";
      echo "Correct answer: " . $_POST['correct_answer'][$id] . "<br>";  

    } else {
      $wrong++;      
      $wrong_marks += intval($current_question['mark']); // Track marks lost

      echo "<br> Question : " . $current_question['question'] . "<br>"; 
      echo "<p style='color:red;'>Your answer: " . $user_answer . " (-". $current_question['mark'] ." marks)<br></p>";
      echo "Correct answer: " . $_POST['correct_answer'][$id] . "<br>";  
      echo "<br>"; 
    }
  }
  
  echo "<hr>";
  echo "<h3>Quiz Results:</h3>";
  echo "Correct answers: $correct (+" . $total_marks . " marks)<br>";
  echo "Wrong answers: $wrong (-" . $wrong_marks . " marks)<br>";
  echo "Total questions: " . count($_POST['correct_answer']) . "<br>";
  echo "Total possible marks: " . $total_possible_marks . "<br>";
  echo "Your total marks: " . $total_marks . " out of " . $total_possible_marks . "<br>";
  echo "Percentage Score: " . round(($total_marks / $total_possible_marks * 100), 2) . "%";
}
?>

<script>
let currentTab = 0;
showTab(currentTab);

function showTab(n) {
  let tabs = document.getElementsByClassName("tab");
  
  // Hide all tabs
  for (let i = 0; i < tabs.length; i++) {
    tabs[i].style.display = "none";
  }
  // Show the current tab
  tabs[n].style.display = "block";

  // Previous button display logic
  if (n == 0) {
   document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }

  // Next/Submit button logic
  if (n == (tabs.length - 1)) {
  document.getElementById("nextBtn").innerHTML = "Submit";
  document.getElementById("nextBtn").setAttribute("onclick", "");
  document.getElementById("nextBtn").onclick = function() {
    if (validateForm()) {
      document.getElementById("regForm").submit();
    }
  };
} else {
  document.getElementById("nextBtn").innerHTML = "Next";
  document.getElementById("nextBtn").setAttribute("type", "button");
  document.getElementById("nextBtn").onclick = function() { nextPrev(1); };
}

}

function validateForm() {
  let tabs = document.getElementsByClassName("tab");
  let currentInputs = tabs[currentTab].getElementsByTagName("input");
  let radioChecked = false;
  
  for (let input of currentInputs) {
    if (input.type === "radio" && input.checked) {
      radioChecked = true;
      break;
    }
  }
  
  if (!radioChecked) {
    alert("Please select an answer before proceeding.");
    return false;
  }
  return true;
}

function nextPrev(n) {
  // Exit the function if validation fails
  if (n > 0 && !validateForm()) {
    return false;
  }

  let tabs = document.getElementsByClassName("tab");
  
  // Hide current tab
  tabs[currentTab].style.display = "none";
  
  // Calculate the new tab index
  let newTab = currentTab + n ;
  console.log(currentTab, newTab);
  
  
    
  if (newTab >= tabs.length) {
    tabs[currentTab].style.display = "block"; 
    
    return false;
  }
  
  // Update current tab
  currentTab = newTab;
  
  
  // Display the correct tab
  showTab(currentTab);
}

</script>
</body>
</html>


