<?php
include 'connection.php';
session_start();

$user_id = $_GET['id'] ?? null; 

if ($user_id) {
    $sql = "SELECT * FROM user_quize WHERE id = $user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fname = $row['fname'];
        $lname = $row['lname'];
        $gender = $row['gender'];       
        $dob =$row['dob'];        
        $class =$row['class'];
        $branch =$row['branch'];
        $board =$row['board'];
        echo "<h1 style='font-family: Raleway; font-size: 30px;' >Welcome " . $row['fname'] . " " . $row['lname'] . "</h1>";
    } else {
        echo "<h1>User not found</h1>";
    }
} else {
    header("Location: user.php");
}


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
  <a href="enter_question.php">admin</a><br>
  <a href="user.php">User</a>

  <form id="regForm" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" method="post">

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
  $user_id = $_GET['id'] ?? $_SESSION['user_id'] ?? null;

  if (!$user_id) {
    echo "Error: User ID not found";
    exit();
}

 // Fetch user data from database
 $sql = "SELECT * FROM user_quize WHERE id = $user_id";
 $result = $conn->query($sql);
 if ($result->num_rows > 0) {
     $user_data = $result->fetch_assoc();
     $fname = $user_data['fname'];
     $lname = $user_data['lname'];
     $gender = $user_data['gender'];
     $dob = $user_data['dob'];
     $class = $user_data['class'];
     $branch = $user_data['branch'];
     $board = $user_data['board'];
 }


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
      
      // echo "<br> Question : " . $current_question['question'] . "<br>"; 
      // echo "<p style='color:green;'>Your answer: " . $user_answer . " (+". $current_question['mark'] ." marks)<br></p>";
      // echo "Correct answer: " . $_POST['correct_answer'][$id] . "<br>";  

    } else {
      $wrong++;      
      $wrong_marks += intval($current_question['mark']); // Track marks lost

      // echo "<br> Question : " . $current_question['question'] . "<br>"; 
      // echo "<p style='color:red;'>Your answer: " . $user_answer . " (-". $current_question['mark'] ." marks)<br></p>";
      // echo "Correct answer: " . $_POST['correct_answer'][$id] . "<br>";  
      // echo "<br>"; 
    }
  }  
  ?>
<!-- certificate for result using $user_id data  -->
 <div class="certificate">
  <h1 style="text-align: center; font-size: 30px; background-color: #f2f2f2; padding: 20px; font-family: 'Times New Roman', serif;">Certificate of Quize Completion</h1>
  <p style="text-align: center; font-size: 25px; font-family: 'Times New Roman', serif;">This is to certify that</p>
  <h2 style="text-align: left; font-size: 40px;"><?php echo $fname . " " . $lname; ?></h2>
  <p style="text-align: left; font-size: 20px;"> OF Class <?php echo $class; ?>, <?php echo $branch; ?>, <?php echo $board; ?> Board</p>
  <p style="text-align: left; font-size: 20px;">Date of Birth: <?php echo $dob; ?></p>
  <p style="text-align: center; font-size: 25px; font-family: 'Times New Roman', serif;">Has successfully completed the PHP Quiz.</p>
  <p style="text-align: center; font-size: 20px;">Date of Completion: <?php echo date("Y-m-d"); ?></p><hr>
  <p style="text-align: center; font-size: 20px; font-weight: bold;">Results</p>
  <p style="text-align: center; font-size: 20px; color: green;">Total Correct Answers: <?php echo $correct; ?></p>
  <p style="text-align: center; font-size: 20px; color: red;">Total Wrong Answers: <?php echo $wrong; ?></p>
  <p style="text-align: center; font-size: 20px; color:green ;">Total Marks: <?php echo $total_marks; ?></p>
  <p style="text-align: center; font-size: 20px; color: red;">Marks Lost: <?php echo $wrong_marks; ?></p>
  <p style="text-align: center; font-size: 20px;">Total Possible Marks: <?php echo $total_possible_marks; ?></p>
  <p style="text-align: center; font-size: 20px; font-weight: bold;  ">Percentage Score: <?php echo round(($total_marks / $total_possible_marks * 100), 2) . "%"; ?></p> <br>
  <?php
  if(round(($total_marks / $total_possible_marks * 100), 2)>=50) {    
    echo "<p style='text-align: center; font-size: 20px; color: green;'>Congratulations! You have passed the quiz.</p>";
  } else {
    echo "<p style='text-align: center; font-size: 20px; color: red;'>Unfortunately, you did not pass the quiz. Better luck next time!</p>";
  }
  ?>
  <p style="text-align: center; font-size: 20px; font-weight: bold;">This certificate is awarded for successfully completing the quiz.</p>
  <p style="text-align: center; font-size: 20px;">Thank you for participating!</p> 
 <div class="fotter">
  <p style="text-align: left; font-size: 20px;">Date: <?php echo date("d-m-y"); ?></p>
  <div class="signature" style="text-align: right;">
  <img src="sig.png" alt="Signature" style=" height:150px; width:250px; margin-top:20px;"> 
  <p style="text-align: right; font-size: 25px; font-family: 'Times New Roman', serif; ">Authorized Signature</p>
</div>
</div>
</div>


  <style>
    .fotter {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
    }
    .certificate {
      border: 5px solid #4CAF50;
      padding: 20px;
      width: 80%;
      margin: 0 auto;
      background-color: #f9f9f9;
      text-align: center;
      font-family: Arial, sans-serif;
    }
    .certificate h1 {
      font-size: 40px;
      color: #4CAF50;
    }
    .certificate p {
      font-size: 20px;
    }
  </style>
<?php 
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
