<?php

// $PAGE_ROLE="admin"; 
// require_once 'auth.php';
// authHTML();

require 'db_connection.php';
require 'output.php';
include_once "funcs/funcs_admin.php";


    if(isset($_GET['task'])){
        switch($_GET['task']){
            
            case "add":{
                saveNewUser();
            break;
            }
            case "edit":{
                // $CONTENT = getUserEditForm($_GET['edit_user']);
                header('Location: EditForm.php');
                
            break;
            }

            case "update":{
                    updateUserData($_POST['user']); //in file ./funcs/funcs_admin.php
                   
                  //in file ./funcs/funcs_admin.php
                break;
            }
            case "delete":{
                $sql = "DELETE FROM `users` WHERE `id`=" . (int)$_GET['delete_user'];
                $result=$conn->query($sql);
                if($conn->affected_rows>0){
                } else {
                    $ERR= "Record not found ";
                }
                  //in file ./funcs/funcs_admin.php
                break;
            }
        }
    }

    $HEADER_TITLE = 'AdminPage';
    require_once 'header.php';
  
    // echo $CONTENT;
    
require_once 'footer.php';
