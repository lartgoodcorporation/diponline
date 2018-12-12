<?php

session_set_cookie_params(3600,"/");
session_start();

function authHTML()
{
  if (empty($_SESSION['user'])) {
    header('Location: index.php');
    exit();
  }
}


function isValidUser($email, $pass)
{
  require_once 'db_connection.php';

  $sql = 'SELECT * FROM users WHERE email="' . $email . '" AND password="' . $pass . '"';
  $result = $conn->query($sql);

  if ($result->fetch_assoc()) {
    return true;
  }
  return false;
}