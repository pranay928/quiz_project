<?php
include "connection.php" ;


if(isset($_POST['submit'])){

    $question = $_POST['question'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $answer = $_POST['answer'];
    $marks = $_POST['marks'];
    
    $sql = "INSERT INTO quize (que,ops1,ops2,ops3,ops4,ans,mark) VALUES ('$question','$option1','$option2','$option3','$option4','$answer','$marks')";
    $result = $conn->query($sql);    
    if ($result === TRUE) {
        echo "New record created successfully";
    }
    else{
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <label for="question">Enter Question</label><br>
        <input type="text" name="question" id="question" required><br>
        <label for="option1">Option 1</label><br>
        <input type="text" name="option1" id="option1" required><br>
        <label for="option2">Option 2</label><br>
        <input type="text" name="option2" id="option2" required><br>
        <label for="option3">Option 3</label><br>
        <input type="text" name="option3" id="option3" required><br>
        <label for="option4">Option 4</label><br>
        <input type="text" name="option4" id="option4" required><br>
        <label for="answer">Answer</label><br>        
        <input type="text" name="answer" id="answer" required><br>
        <label for="marks">Marks</label><br>
        <input type="text" name="marks" id="marks" required><br>
        <input type="submit" name="submit" value="Submit"><br>
    </form>    
    <a href="show_question_one.php">Show Question</a>
       <br>
        
</body>
</html>