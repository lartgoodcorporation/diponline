<?php
include_once "funcs_basic.php";
function loadAllUsers(){
    $tmpl=file_get_contents("tmpl/admin.html");
    $content='
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="admins" role="tabpanel">'
        .loadUsersByRole('admin').
      '</div>
      <div class="tab-pane" id="curators" role="tabpanel">'
        .loadUsersByRole('curator').
      '</div>
      <div class="tab-pane" id="students" role="tabpanel">'
        .loadUsersByRole('student').
      '</div>
      <div class="tab-pane" id="addUser" role="tabpanel">
        <form class="news_form" method="post" action="?task=add"  enctype="multipart/form-data">
          <div class="form-group">
            <input class="form-control" type="text" name="lastname" placeholder="Прізвище" required>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="firstname" placeholder="Ім\'я" required>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="patronymic" placeholder="Ім\'я по батькові" required>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="email" placeholder="Введіть Email" required>
          </div>
          <div class="form-group">
            <input class="form-control" type="text" name="password" placeholder="Пароль" required>
          </div>
          <div class="form-group">
            <select class="form-control" name="role">
              <option disabled selected>Оберіть роль</option>
              <option value="admin">Адміністратор</option>
              <option value="curator">Керіник проекту</option>
            </select>
          </div>
            <button  class="btn btn-success" type="submit" name="add">Зберегти</button>
        </form>
      </div>
    </div>
    ';

    return $tmpl.$content;
}
function loadUsersByRole($role){
    global $conn;
$start='
<table class="table table-hover">
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
    <tbody class="">

';
$end="
    </tbody>
</table>
";
    $sql = 'SELECT * FROM `users` WHERE `role` = "'.$role.'"';
    $result = $conn->query($sql); 
    $i=1; $item=""; $rez="";
    while ($row = $result->fetch_assoc()) {
        $item='<td>'.$i.'</td>';
        $item.="\n<td>".$row['firstname']." ".$row['patronymic']."</td>\n";
        $item.='<td>'.$row['lastname']."</td>\n";
        $item.='<td>'.$row['email']."</td>";
        $item.='<td><a class="btn btn-info btn-sm" href="?task=edit&edit_user='.$row['id'].'">Редагувати</a></td>';
        $item.='<td><a class="btn btn-warning btn-sm" href="?task=delete&delete_user='.$row['id'].'">Вилучити</a></td>';
        $rez.="\n<tr>".$item."</tr>\n";
        $i++;
    }
    return $start.$rez.$end;
}

function saveNewUser(){
    global $MSG, $ERR, $conn;
    if (!isset($_POST['firstname']) || strlen($_POST['firstname'])<3) {$err_msg.="Не вказано ім’я<br>";}
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {$err_msg.="Не коректна пошта<br>";}
    if (!isset($_POST['password']) ||  strlen($_POST['firstname'])<4) {$err_msg.="Занадто слабкий пароль<br>";}

    if($err_msg==""){
        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
      $firstname = $_POST['firstname'];
      $lastname = $_POST['lastname'];
      $email = $_POST['email'];
      $password = $_POST['password'];
      $patronymic = $_POST['patronymic'];
      $role = $_POST['role'];
      $status = $_POST['status'];

      //$id = md5($firstname . $lastname . $patronymic);


      $sql = "INSERT INTO `users` ( `firstname`, `lastname`, `patronymic`, `email`, `password`, `role`, `status`)
      VALUES ( '$firstname', '$lastname', '$patronymic', '$email', '$password', '$role', '$status')";

      if ($conn->query($sql) === TRUE) {
        $MSG= "User " . $firstname . " successfully inserted!";
      } else {
            if($conn->errno==1062){
                $ERR= "Такий користувач уже існує...";
            }else{
                $ERR= "Error: " . $conn->error;
            }
      }
    }else{
        $ERR.=$err_msg;
    }
}


function getUserEditForm($id){
    global $MSG, $ERR;
    $fields=loadUserData($id);
    if(is_array($fields)){
        $tmpl='
                <form class="news_form"  enctype="multipart/form-data" method="post" action="?task=update">
                  <div class="form-group">
                    <input class="form-control" type="text" name="lastname" placeholder="Прізвище" required value="'.$fields['lastname'].'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="firstname" placeholder="Ім\'я" required  value="'.$fields['firstname'].'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="patronymic" placeholder="Ім\'я по батькові" required  value="'.$fields['patronymic'].'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="email" placeholder="Введіть Email" required  value="'.$fields['email'].'">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="text" name="password" placeholder="Пароль" >
                  </div>
                  <div class="form-group">
                    <select class="form-control" name="role">
                      <option disabled selected>Оберіть роль</option>
                      <option value="admin" '.($fields['role']=="admin"?" selected ":"").'>Адміністратор</option>
                      <option value="curator" '.($fields['role']=="curator"?" selected ":"").'>Керіник проекту</option>
                      <option value="student" '.($fields['role']=="student"?" selected ":"").'>Студент</option>
                    </select>
                  </div>
                    <input type="hidden" name="user"  value="'.$fields['id'].'">
                    <button  class="btn btn-success" type="submit" name="add">Зберегти Зміни</button>
                </form>
        ';
    }else{
        $ERR="record not found<br>";
        $tmpl= loadAllUsers();

    }
    return $tmpl;
}

function loadUserData($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `users` WHERE id='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { $rez= $result->fetch_assoc();}
    return $rez;
}

function updateUserData($id){
    global $MSG, $ERR, $conn;
    $rez=0;
    if (!isset($_POST['firstname']) || strlen($_POST['firstname'])<3) {$err_msg.="Не вказано ім’я<br>";}
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {$err_msg.="Не коректна пошта<br>";}
    if (isset($_POST['password']) &&  strlen($_POST['firstname'])<4) {$err_msg.="Занадто слабкий пароль<br>";}
    if($err_msg==""){
        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
        $id=(int)$id;
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $patronymic = $_POST['patronymic'];
        $role = $_POST['role'];
        $status = $_POST['status'];
        if(isset($_POST['password']) && strlen($_POST['firstname'])>=4){
            $password = $_POST['password'];
            $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `email`='$email', `password`='$password', `role`='$role' WHERE `id`='$id'";
        }else{
            $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `email`='$email', `role`='$role' WHERE `id`='$id'";
        }
        $result = $conn->query($sql);
        if($conn->affected_rows>0) $MSG="Record updated<br>";
        else{$MSG="Record not found or no changes<br>".$conn->error;}
    }else{
        $ERR.=$err_msg;
    }
}
?>