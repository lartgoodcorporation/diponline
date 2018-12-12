<?php
  require_once 'header.php';
  include_once "funcs_basic.php";
  
  function AddUserForm(){
    global $conn;
$start='
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
                            <option value="admin">Адміністратор</option>
                            <option value="curator">Керіник проекту</option>
                        </select>
                        <label for="role">Select your group</label>
                    </div>
                    <button  class="waves-effect waves-light btn" type="submit" id="add" name="add">Add User</button>
                </div>
            </div>
        </form>

';
    return $start;
}


function loadUsersByRole($role){
    global $conn;
$start='
<table class="striped">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Ім\'я</th>
      <th>Прізвище</th>
      <th>Email</th>
      <th>Змінити</th>
      <th>Видалити</th>
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
        $item.="\n<td>".$row['firstname']." ".$row['patronymic']."</td>\n";
        $item.='<td>'.$row['lastname']."</td>\n";
        $item.='<td>'.$row['email']."</td>";
        $item.='<td><a class="waves-effect waves-light btn" href="EditForm.php?edit_user='.$row['id'].'">Редагувати</a></td>';
        $item.='<td><a class="waves-effect waves-light btn" href="?task=delete&delete_user='.$row['id'].'">Вилучити</a></td>';
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


function loadUserData($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `users` WHERE id='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { 
        $rez= $result->fetch_assoc();
    }
    return $rez;
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
        $ERR.=$err_msg;
    }
}

?>
<script type="text/javascript" src="js/btn_operations.js"></script>