<?php

require_once 'header.php';

if (!empty($_SESSION['user'])) {
  header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
  exit();
}

require_once 'auth.php';

if (!empty($_POST['sign_in_email']) && !empty($_POST['sign_in_password'])) {
    require_once 'db_connection.php';

    $sql = 'SELECT * FROM users WHERE email="' . $_POST['sign_in_email'] . '" AND password="' . $_POST['sign_in_password'] . '"';
    $result = $conn->query($sql);
  
    $user = $result->fetch_assoc(); 

    if (!!$user) {
      $_SESSION['user'] = $user;
      
      header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
      exit();
    } else {
      
      $_SESSION['user'] = FALSE;
      header('Location: index.php');
    }
}