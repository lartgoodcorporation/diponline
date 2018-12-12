<?php

require_once 'auth.php';
authHTML();


require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_curator.php";

if ($_SESSION['user']['role'] !== 'curator') {
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
        case "aceptRequest":{
            if(!isset($_GET['id'])) {
                $ERR="Record not found.<br>";
            }else{
                aceptRequest((int)$_GET['id']);
                header('Location:dashboard_curator.php');
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
            if(!isset($_GET['id'])) {$ERR="Record not found.<br>";
                $CONTENT=loadDashboard();  //in file ./funcs/funcs_admin.php
            }else{
                $CONTENT=getThemeEditForm((int)$_GET['id']);
            }
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
        case "update_profil":{
            if(!isset($_POST['user'])) {
                $ERR="user not updated.<br>";
            }else{
                updateProfilData($_POST['user']); //in file ./funcs/funcs_admin.php
            }
            $CONTENT=loadDashboard();  //in file ./funcs/funcs_admin.php
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
        case 'update_tasks' : {
            $student_ID = $_POST['user_id'];
            $tasks = implode(";", $_POST['tasks']);

            $entry = get_tasks($student_ID);
            
            if ($entry) {
                update_tasks($entry['ID'], $student_ID, $tasks);
            } else {
                add_tasks($student_ID, $tasks);
            }

            $CONTENT=loadDashboard();
            break;
        }
    }
}else{
    $CONTENT=loadDashboard();  //in file ./funcs/funcs_curator.php
}


$HEADER_TITLE = 'CuratorPage';

echo $CONTENT;

require_once 'footer.php';

