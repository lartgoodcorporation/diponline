<?php
//lCzxZGRFX8
  $servername = "localhost";
  $username = "id8213147_admin";
  $password = "admin";
  $dbname = "id8213147_bestfuckingdb";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}else{
    $sql="SET NAMES utf8";
    $result = $conn->query($sql);
}