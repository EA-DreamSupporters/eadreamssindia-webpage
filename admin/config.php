<?php
$servername = "localhost";
$database = "u978061437_eadreams";
$username = "root";
$password = "";
//$servername = "localhost";
//$database = "eadreamss";
//$username = "root";
//$password = "";
// Create connection
$con= mysqli_connect($servername, $username, $password,$database );

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected successfully";
?>