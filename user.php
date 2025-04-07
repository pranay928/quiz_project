<?php
 session_start();
include 'connection.php';


if(isset($_POST['submit']))
{
    $fname =$_POST['fname'];
    $Lname =$_POST['lname'];
    $dob =$_POST['dob'];
    $gender =$_POST['gender'];
    $class =$_POST['class'];
    $branch =$_POST['branch'];
    $board =$_POST['board'];
    $sql = "INSERT INTO user_quize (fname,lname,dob,gender,class,branch,board) VALUES ('$fname','$Lname','$dob','$gender','$class','$branch','$board')";
    $result = mysqli_query($conn,$sql);
    if($result){
        echo "data inserted successfully";
       
        $sqlf = "SELECT * FROM user_quize WHERE fname='$fname' AND lname='$Lname' AND dob='$dob' AND gender='$gender' AND class='$class' AND branch='$branch' AND board='$board'";
        $resultf = mysqli_query($conn,$sqlf); 
        if($resultf)
        {               
            $row = mysqli_fetch_assoc($resultf);
            $id = $row['id'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $dob = $row['dob'];
            $gender = $row['gender'];
            $class = $row['class'];
            $branch = $row['branch'];
            $board = $row['board'];
            
            $_SESSION['user_id'] = $id;
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['dob'] = $dob;
            $_SESSION['gender'] = $gender;
            $_SESSION['class'] = $class;
            $_SESSION['branch'] = $branch;
            $_SESSION['board'] = $board;  
            
            ?>
            
            <a href="show_question_one.php?id=<?php echo $id;?>">start quize</a>
            <?php
            header("Location: show_question_one.php?id=$id");
        }
        else{
            echo "data not inserted";
        }
    }
    else{
        echo "data not inserted";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .container{
            width:50%;            
            margin:0 auto;
            padding:20px;
            border:1px solid black;
            border-radius:10px;
            background-color:rgb(162, 215, 219);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #333;
            text-align: left;
            margin-top: 50px;
            margin-bottom: 50px;           
            border-radius: 10px;
        }
        label {
            display: block;            
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;            
        }
       .input_text 
        {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        #class 
        {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .input_radio 
        {
            margin-right: 10px;
            margin-bottom: 20px;
        }
        #submit 
        {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #submit:hover 
        {
            background-color: #45a049;
        }

       
        .error 
        {
            color: red;
            font-size: 14px;
        }
        


    </style>
    <title>User authentication</title>
</head>
<body>
    <div class="container">
        
    <form action="" method="post" id="regForm">
       <h1>User Register</h1>
       <label  for="fname">First Name:</label>
       <input class="input_text" type="text" id="fname" name="fname"><br><br>
       <label for="lname">Last Name:</label>
       <input class="input_text" type="text" id="lname" name="lname"><br><br>
       <label for="dob">Date of bith:</label>
       <input class="input_text" type="date" id="dob" name="dob"><br><br>
       <label for="gender">Gender:</label>
           <input class="input_radio" type="radio" id="male" name="gender" value="male">Male
           <input class="input_radio" type="radio" id="female" name="gender" value="female">Female<br><br>
       <label for="class">Class:</label>       
       <select name="class" id="class">            
           <option value="9">9th</option>
           <option value="10">10th</option>
           <option value="11">11th</option>
           <option value="12">12th</option>           
       </select> <br><br>
       <label for="branch">Branch:</label>
           <input class="input_radio" type="radio" name="branch" id="CS" value="computer science">Computer Science
           <input class="input_radio" type="radio" name="branch" id="commerce" value="commerce">Commerce
           <input class="input_radio" type="radio" name="branch" id="science" value="science">Science<br><br>
       <label for="board">Board:</label>
           <input class="input_radio" type="radio" name="board" id="cbse" value="cbse">CBSE
           <input class="input_radio" type="radio" name="board" id="ssc" value="ssc">SSC
           <input class="input_radio" type="radio" name="board" id="hsc" value="hsc">HSC<br><br>
       
       <input id="submit" type="submit" value="Submit" name="submit">
       

    </form> 
</div>   
</body>
</html>
