<?php
  
require_once 'auth.php';
echo "'".$_POST['firstname']."','".$_POST['lastname']."','".$_POST['patronymic']."','".$_POST['sign_up_email']."','".$_POST['sign_up_password']."','".$_POST['group']."'";
if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['patronymic']) && !empty($_POST['sign_up_email']) && !empty($_POST['sign_up_password']) && !empty($_POST['group'])) {
    
  require 'db_connection.php';

  $role = $role ? $role : 'student';
  // $id = md5($firstname . $lastname . $patronymic . $email);

  $sql = "SELECT * FROM `users` WHERE `email` = '" . $_POST['sign_up_email'] . "'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();

  if (!$user) {
    $sql = "INSERT INTO `users` (`firstname`, `lastname`, `patronymic`, `email`, `password`, `role`) VALUES ('".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['patronymic']."', '".$_POST['sign_up_email']."', '".$_POST['sign_up_password']."', '$role')";

    if ($conn->query($sql) === TRUE) {

      $id = mysqli_insert_id($conn);
      $sql = "INSERT INTO `student_groups` (`user_id`, `group_id`) VALUES ('$id', '".$_POST['group']."')";
  

      $sql = 'SELECT * FROM users WHERE email="' . $_POST['sign_up_email'] . '" AND password="' . $_POST['sign_up_password'] . '"';
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



    //   if ($conn->query($sql) === TRUE) {
    //     header('Location: start_page.php');
        
    //     exit();
    //   } else {
    //     return false;
    //   }
    } else {
      return false;
    }
  }
  }else {
    $_SESSION['user'] = FALSE;
    header('Location: index.php');
  }