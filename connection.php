<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mydatabase";


// Create connection
$conn = new mysqli($servername, $username, $password,$database );
if($conn){
   ;
}
else{
    echo "Not Connected";
}
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>