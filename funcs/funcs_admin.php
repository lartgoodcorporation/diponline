<?php
  require_once 'header.php';
  include_once "funcs_basic.php";
  
  
  function loadAllUsers(){
    $tmpl=file_get_contents("tmpl/nav_block.php");
    $content='
            <div class="container" id="rof_off">
                <div class="row" >
                    <div align="center" id="tabs_office">
                        <ul class="tabs">
                            <li class="tab col l3 s3">
                                <a href="#admins">Admins</a>
                            </li>
                            <li class="tab col l3 s3">
                                <a href="#curators">Curators</a>
                            </li>
                            <li class="tab col l3 s3">
                                <a href="#students">Students</a>
                            </li>
                            <li class="tab col l3 s3">
                                <a href="#addUser">+Add New User</a>
                            </li>
                        </ul>

                        <div id="admins" role="tabpanel" class="col l12 s12 m12">
                            '.loadUsersByRole('admin').'
                        </div>
                        <div id="curators" role="tabpanel" class="col l12 s12 m12">
                            '.loadUsersByRole('curator').'
                        </div>
                        <div id="students" role="tabpanel" class="col l12 s12 m12">
                            '.loadUsersByRole('student').'
                        </div>
                        <div id="addUser" role="tabpanel" class="col l12 s12 m12">

                            <form class="col l12 s12" action="?task=add" method="post">
                                <div class="row" align="center">
                                    <div class="addUser">
                                        <div class="input-field col s6">
                                            <input name="firstname" type="text" class="validate">
                                            <label for="firstname">First Name</label>
                                        </div>
                                        <div class="input-field col s6">
                                            <input name="lastname" type="text" class="validate">
                                            <label for="lastname">Last Name</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="patronymic" type="text" class="validate">
                                            <label for="patronymic">Patronymic</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="sign_up_email" type="email" class="validate">
                                            <label for="sign_up_email">Email</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <input name="sign_up_password" type="password" class="validate">
                                            <label for="sign_up_password">Password</label>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="role">
                                                <option disabled selected>Select user role</option>
                                                <option value="admin">Administrator</option>
                                                <option value="curator">Project curator</option>
                                            </select>
                                            <label for="role">Select your group</label>
                                        </div>
                                        <button class="waves-effect waves-light btn" type="submit" id="add" name="add">Add User</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                   
                    <div id="profil_form" class="profil_form col l12 m12 s12" hidden="true">

                        <div class="container">
                            <div class="dataBox">' .loadProfil(). '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div id="news_form" class="col xl12 l12 m12 s12" hidden="true">
                    <div class="col xl6 l6 m6 s6 offset-xl3">' .getNewsAdmin(). '
                    </div>
                </div>
            </div>
    ';

    return $tmpl.$content;
}

function loadProfil(){
    $userdata=loadUserData($_SESSION['user']['id']);
if(is_array($userdata)){
    $content=' 
        <div class="row">
            <div class="col xl12 l12 m12 s12">
                <div class="user_icon" align="center">
                    <img src="fonts/user.png" alt="">
                    <div>' .$userdata['lastname']." ".$userdata['firstname']." ".$userdata['patronymic']. '</div>
                </div>
                    <div id="adminData" class="col s5 m5 l5 xl5">
                        <div class="studentData input-field col xl12 l12 m12 s12">
                            <p>Your Role: '.$userdata['role'].'</p>
                            <p>Email: '.$userdata['email'].'</p>
                            <p>Password: '.$userdata['password'].'</p>
                            <button  id="getEditInput" class="waves-effect waves-light btn center">Edit</button>
                        </div>
                        
                    </div>
                    <div id="add_news">
                        <div class="box col s7 m7 l7 xl7">
                                <form id="newForm" class="col xl12 l12 m12 s12" action="?task=add_news" method="post">
                                
                                    <div class="row">
                                        <div class="input-field col xl12 l12 m12 s12">
                                            <input id="news_title" name="news_title" type="text" data-length="100">
                                            <label for="news_title">News Title</label>
                                        </div>
                                        <div class="input-field col xl12 l12 m12 s12">
                                            <textarea id="news_content" name="news_content" class="materialize-textarea" data-length="1000"></textarea>
                                            <label for="news_content">News content...</label>
                                        </div>
                                        <button id="add_news" name="add_news" class="waves-effect waves-light btn center" type="submit">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <div id="adminDataEdit" class="col xl12 l12 m12 s12" hidden="true">
                    <form class="col xl12 l12 m12 s12" action="dashboard_admin.php?task=update_profil" method="post">
                        <div class="studentData">'.getUserSalutation().'</div>
                        <div class="input-field col l12 m12 s12">
                            <input title="The password must be at least 4 characters long" name="password" id="password" type="text" value="'.$userdata['password'].'">
                            <label for="password">Your password:</label>
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <input name="email" id="email" type="text" value="'.$userdata['email'].'">
                            <label for="email">Email:</label>
                        </div>
                        <input type="hidden" name="user" value="'.$userdata['id'].'">
                        <button type="submit" id="upp" name="upp" class="waves-effect waves-light btn center">Save</button>
                    </form>
                </div>
            </div>
        </div>
';
}
return $content;
}

function loadUsersByRole($role){
    global $conn;
$start='
<table class="striped">
  <thead class="centered">
    <tr>
      <th>№</th>
      <th>Name</th>
      <th>Email</th>
      <th>Edit</th>
      <th>Delete</th>
    </tr>
    </thead>
    <tbody>

';
$end="
    </tbody>
</table>
";
    $sql = 'SELECT * FROM `users` WHERE `role` = "'.$role.'"';
    $result = $conn->query($sql); $i=1; $item=""; $rez="";
    while ($row = $result->fetch_assoc()) {
        $item='<td>'.$i.'</td>';
        $item.="\n<td>".$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>\n";
        $item.='<td>'.$row['email']."</td>";
        $item.='<td><a class="waves-effect waves-light btn" href="?task=edit&edit_user='.$row['id'].'">Edit</a></td>';
        $item.='<td><a class="waves-effect waves-light btn" href="?task=delete&delete_user='.$row['id'].'">Delete</a></td>';
        $rez.="\n<tr>".$item."</tr>\n";
        $i++;
    }
    return $start.$rez.$end;
}

function saveNewUser(){
    if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['patronymic']) && !empty($_POST['sign_up_email']) && !empty($_POST['sign_up_password']) && !empty($_POST['role'])) {

        require 'db_connection.php';

        $sql = "SELECT * FROM `users` WHERE `email` = '" . $_POST['sign_up_email'] . "'";
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();

        if (!$user) {
            $sql = "INSERT INTO `users` (`firstname`, `lastname`, `patronymic`, `email`, `password`, `role`) VALUES ('".$_POST['firstname']."', '".$_POST['lastname']."', '".$_POST['patronymic']."', '".$_POST['sign_up_email']."', '".$_POST['sign_up_password']."', '".$_POST['role']."')";
        
            if ($conn->query($sql) === TRUE) {
                $sql = 'SELECT * FROM users WHERE email="' . $_POST['sign_up_email'] . '" AND password="' . $_POST['sign_up_password'] . '"';
                $result = $conn->query($sql);
                $newUser = $result->fetch_assoc();
              
            } else {
                return false;
              }

        }else if (isset($user)) {
                echo "Такий користувач уже існує...";
           
        }

    }
}

function getUserEditForm($id){
    global $MSG, $ERR;
    $pre=file_get_contents("tmpl/nav_edit_block.php");
    $userdata=loadUserData($id);
    if(is_array($userdata)){
        $tmpl='
        <div class="container">
    <div class="row" align="center">
        <form id="edit_form" class="col m12 l12 s12" action="dashboard_admin.php?task=update" method="post">
            <div class="editUser">
                <div class="input-field col m6 l6 s6">
                    <input name="firstname" type="text" class="validate" value="'.$userdata['firstname'].'">
                    <label for="firstname">First Name</label>
                </div>

                <div class="input-field col m6 l6 s6">
                    <input name="lastname" type="text" class="validate" value="'.$userdata['lastname'].'">
                    <label for="lastname">Last Name</label>
                </div>

                <div class="input-field col m12 l12 s12">
                    <input name="patronymic" type="text" class="validate" value="'.$userdata['patronymic'].'">
                    <label for="patronymic">Patronymic</label>
                </div>

                <div class="input-field col m12 l12 s12">
                    <input name="sign_up_email" type="email" class="validate" value="'.$userdata['email'].'">
                    <label for="sign_up_email">Email</label>
                </div>

                <div class="input-field col m12 l12 s12">
                    <input name="sign_up_password" type="password" class="validate">
                    <label for="sign_up_password">Password</label>
                </div>

                <div class="input-field col m12 l12 s12">
                    <select name="role">
                        <option disabled selected>Select role</option>
                        <option value="admin" '.($userdata['role']=="admin" ? " selected ": "").'>Administrator</option>
                        <option value="curator" '.($userdata['role']=="curator" ? " selected ": "").'>Project curator</option>
                        <option value="student" '.($userdata['role']=="student" ? " selected ": "").'>Student</option>
                    </select>
                    <label for="role">Select your group</label>
                </div>
                <input type="hidden" name="user" value="'.$userdata['id'].'">
                <button class="btn btn-warning btn-sm" href="dashboard_admin.php" type="submit" id="add" name="add">Save</button>
            </div>
        </form>
    </div>
</div>
        ';
    }else{
        $ERR="record not found<br>";
        $tmpl= loadAllUsers();

    }
    return $pre.$tmpl;
}

function getUserData($id){
    $userdata=loadUserData($id);
    if(is_array($userdata)){
        $start='
        
        ';
        return $userdata;
    }
    return false;
}


function updateProfilData($id){
    global $conn;

    $rez=0;
    $userdata=loadUserData($id);
    if (!empty($_POST['email']) || !empty($_POST['role']) || !empty($_POST['password'])) {

        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
        $id=(int)$id;
        $firstname = $userdata['firstname'];
        $lastname = $userdata['lastname'];
        $patronymic = $userdata['patronymic'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        if(isset($_POST['email']) || isset($_POST['role']) || strlen($_POST['password'])>=4){
            $password = $_POST['password'];
            $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `patronymic`='$patronymic', `email`='$email', `role`='$role', `password`='$password' WHERE `id`='$id'";
        }else{
            $sql = "UPDATE `users` SET `email`='$email', `role`='$role', `password`='$password' WHERE `id`='$id'";
        }
        $result = $conn->query($sql);
        if($conn->affected_rows>0) $MSG="Record updated<br>";
        else{$MSG="Record not found or no changes<br>".$conn->error;}
    }else{
        $ERR.=$err_msg;
    }
}

function updateUserData($id){
    global $conn;

    $rez=0;

    if (!empty($_POST['firstname']) || !empty($_POST['lastname']) || !empty($_POST['patronymic']) || !empty($_POST['sign_up_email']) || !empty($_POST['sign_up_password']) || !empty($_POST['role'])) {

        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
        $id=(int)$id;
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['sign_up_email'];
        $patronymic = $_POST['patronymic'];
        $role = $_POST['role'];
        if(isset($_POST['password']) && strlen($_POST['firstname'])>=4){
            $password = $_POST['password'];
            $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `patronymic`='$patronymic', `email`='$email', `password`='$password', `role`='$role' WHERE `id`='$id'";
        }else{
            $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `patronymic`='$patronymic', `email`='$email', `role`='$role' WHERE `id`='$id'";
        }
        $result = $conn->query($sql);
        if($conn->affected_rows>0) $MSG="Record updated<br>";
        else{$MSG="Record not found or no changes<br>".$conn->error;}
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
        return header('Location:dashboard_admin.php');
    }
}


function addNews(){
    if (!empty($_POST['news_title']) && !empty($_POST['news_content'])) {

        require 'db_connection.php';

        $sql = "SELECT * FROM `news` WHERE `news_content` = '" . $_POST['news_content'] . "'";
        $result = $conn->query($sql);
        $new = $result->fetch_assoc();

        if (!$new) {
            $sql = "INSERT INTO `news` (`news_title`, `news_content`) VALUES ('".$_POST['news_title']."', '".$_POST['news_content']."')";
        
            if ($conn->query($sql) === TRUE) {
                // $sql = 'SELECT * FROM `news` WHERE `news_content` = "' . $_POST['news_title'] . '" AND `news_content` = "'.$_POST['news_content'].'"';
                // $result = $conn->query($sql);
                // $newUser = $result->fetch_assoc();
                return header('Location:dashboard_admin.php');
              
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
                return header('Location:dashboard_admin.php');
              }

        }else if (isset($new)) {
                echo "This new already exists";
                echo "Error: " . $sql . "<br>" . $conn->error;
                return header('Location:dashboard_admin.php');
        }

    }
}




?>
<script type="text/javascript" src="js/btn_operations.js"></script>