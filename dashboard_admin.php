<?php

require_once 'auth.php';
authHTML();

require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_admin.php";


if ($_SESSION['user']['role'] !== 'admin'){
    if ($_SESSION['user']['role'] !== null){
        header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
        exit();
    }else if ($_SESSION['user']['role'] == null){
        header('Location: index.php');
        exit();
  }
}

  if(isset($_GET['task'])){
    switch($_GET['task']){
        case "add":{
            saveNewUser();  //in file ./funcs/funcs_admin.php
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "add_news":{
            addNews();  //in file ./funcs/funcs_admin.php
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "edit":{
            if(!isset($_GET['edit_user'])) {$ERR="Record not found.<br>";
                $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            }else{
                $CONTENT=getUserEditForm($_GET['edit_user']);
            }
            break;
        } 
        case "editNews":{
            if(!isset($_GET['edit_news'])) {$ERR="Record not found.<br>";
                $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            }else{
                $CONTENT=getNewsEditForm($_GET['edit_news']);
            }
            break;
        }
        case "updateNews":{
            if(!isset($_POST['news'])) {
                $ERR="user not updated.<br>";
            }else{
                updateNewsData($_POST['news']); //in file ./funcs/funcs_admin.php
            }
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "update":{
            if(!isset($_POST['user'])) {
                $ERR="user not updated.<br>";
            }else{
                updateUserData($_POST['user']); //in file ./funcs/funcs_admin.php
            }
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "update_profil":{
            if(!isset($_POST['user'])) {
                $ERR="user not updated.<br>";
            }else{
                updateProfilData($_POST['user']); //in file ./funcs/funcs_admin.php
            }
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "deleteNews":{
            $sql = "DELETE FROM `news` WHERE `ID`=" . (int)$_GET['id'];
            $result=$conn->query($sql);
            if($conn->affected_rows>0){
                $MSG= "Record deleted successfully";
            } else {
                $ERR= "Record not found ";
            }
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
        case "delete":{
            $sql = "DELETE FROM `users` WHERE `id`=" . (int)$_GET['delete_user'];
            $result=$conn->query($sql);
            if($conn->affected_rows>0){
                $MSG= "Record deleted successfully";
            } else {
                $ERR= "Record not found ";
            }
            $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
            break;
        }
    }
}else{
    $CONTENT=loadAllUsers();  //in file ./funcs/funcs_admin.php
}

    $HEADER_TITLE = 'AdminPage';
    require_once 'header.php';
  
    echo $CONTENT;
    
require_once 'footer.php';
