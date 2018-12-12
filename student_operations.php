<?php

require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_student.php";


if(isset($_GET['task'])){
    switch($_GET['task']){
        case "addRequest":{
            if(!isset($_GET['id'])) {
                echo "Record not found.<br>";
            }else{
                addRequest((int)$_GET['id']);
                echo loadDashboard();
            }
            
            var_dump(loadDashboard());
            break;
        }
        case "deleteRequest":{
            if(!isset($_GET['id'])) {
                echo "Record not found.<br>";
            }else{
                deleteRequest((int)$_GET['id']);
            }
            break;

        }
    }
}

$HEADER_TITLE = 'StudentPage';
require_once 'header.php';

require_once 'footer.php';

?>