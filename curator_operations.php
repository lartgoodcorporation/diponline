<?php

// require_once 'auth.php';
// authHTML();


require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_curator.php";

if ($_SESSION['user']['role'] !== 'curator') {
    header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
    exit();
  }

if(isset($_GET['task'])){
    switch($_GET['task']){
        case "aceptRequest":{
            if(!isset($_GET['id'])) {
                $ERR="Record not found.<br>";
            }else{
                aceptRequest((int)$_GET['id']);
            }
            $CONTENT=loadDashboard();
            break;
        }
        case "deleteRequest":{
            if(!isset($_GET['id'])) {
                $ERR="Record not found.<br>";
            }else{
                deleteRequest((int)$_GET['id']);
            }
            $CONTENT=loadDashboard();
            break;
        }
        case "addTheme":{
            saveNewTheme();  //in file ./funcs/funcs_curator.php
            $CONTENT=loadDashboard();  //in file ./funcs/funcs_curator.php
            break;
        }
        case "editTheme":{
            header('Location: EditThemeForm.php');

            break;
        }
        case "updateTheme":{
            if(!isset($_POST['theme_id'])) {
                $ERR="Theme not updated.<br>";
            }else{
                updateThemeData($_POST['theme_id']); //in file ./funcs/funcs_curator.php
            }
            $CONTENT=loadDashboard();  //in file ./funcs/funcs_curator.php
            break;
        }
        case "deleteTheme":{
            $sql = "DELETE FROM `projects` WHERE `id`=" . (int)$_GET['id']." AND `curator`='".$_SESSION['user']['id']."'";
            $result=$conn->query($sql);
            if($conn->affected_rows>0){
                $MSG= "Theme deleted successfully";
            } else {
                $ERR= "Record not found ";
            }
            $CONTENT=loadDashboard();
            break;
        }
    }
}else{
    $CONTENT=loadDashboard();  //in file ./funcs/funcs_curator.php
}


$HEADER_TITLE = 'Куратор проекту';

echo $CONTENT;

require_once 'footer.php';

