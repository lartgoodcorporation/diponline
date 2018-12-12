<?php

// $PAGE_ROLE="admin"; //- not used please dont delete, ignore this val
// // $MSG=""; $ERR=""; $CONTENT="";
require_once 'auth.php';
authHTML();


require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_student.php";

if ($_SESSION['user']['role'] !== 'student') {
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
        case "addRequest":{
            if (!isset($_GET['id'])) {
            } else {
                addRequest((int)$_GET['id']);
                
            }
            
            $CONTENT=loadDashboard();
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
        
        case "deleteRequest":{
            if(!isset($_GET['id'])) {
                echo "Record not found.<br>";
            }else{
                deleteRequest((int)$_GET['id']);
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
} else {
    $CONTENT=loadDashboard();  //in file ./funcs/funcs_curator.php
}

$HEADER_TITLE = 'StudentPage';
require_once 'header.php';

echo $CONTENT;

require_once 'footer.php';
