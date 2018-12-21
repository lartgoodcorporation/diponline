<?php
//lCzxZGRFX8
  $servername = "localhost";
  $username = "admin_kafedra";
  $password = "pass";
  $dbname = "admin_kafedra";



// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}else{
    $sql="SET NAMES utf8";
    $result = $conn->query($sql);
}