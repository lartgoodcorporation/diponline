<?php
include_once "funcs_basic.php";
function loadDashboard(){
    $tmpl=file_get_contents("tmpl/curator.html");
    $content='
    <!-- Tab panes -->
    <div class="tab-content">
      <div class="tab-pane active" id="yours" role="tabpanel">'
        .loadYours().
      '</div>
      <div class="tab-pane" id="request" role="tabpanel">'
        .loadRequests().
      '</div>
      <div class="tab-pane" id="allThemes" role="tabpanel">'
        .loadAllThemes().
      '</div>
      <div class="tab-pane" id="addTheme" role="tabpanel">
<form method="post" method="post" action="?task=addTheme"  enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-4 offset-md-2">
        <label for="themeName" class="col-form-label">Тип</label>
        <input id="themeName" name="theme" class="form-control" type="text" placeholder="Тема проекту..." required>
      </div>
      <div class="col-md-4">
        <label for="themeType" class="col-form-label">Тип</label>
        <select id="themeType" class="form-control" name="type">
          <option disabled selected>Оберіть тип проекту</option>
          <option value="course">Курсова робота</option>
          <option value="course_prj">Курсовий проект</option>
          <option value="diploma">Дипломна робота</option>
          <option value="diploma_prj">Дипломний проект</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8  offset-md-2">
        <label for="groupSelect" class="col-form-label">Для яких груп</label>
        <select id="groupSelect" multiple  class="form-control chosen-select" name="group[]">
        '.getGroupOption().'
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <hr>
        <input class="btn btn-primary float-right" name="addTheme" type="submit" value="Зберегти тему">
      </div>
    </div>

</form>
      </div>
    </div>
    ';

    return $tmpl.$content;
}
//теми викладача
function loadYours(){
    global $conn;
$start='
<table class="table table-hover">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Тема</th>
      <th>Тип</th>
      <th>Група</th>
      <th>Редагувати</th>
      <th>Видалити</th>
    </tr>
    </thead>
    <tbody class="">

';
$end="
    </tbody>
</table>
";
    $sql = 'SELECT * FROM `projects` WHERE `curator` = '.$_SESSION['user']['id'];
    $result = $conn->query($sql); $i=1; $item=""; $rez="";
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.getThemeType($row['type'])."</td>\n";
            $item.='<td>'.getGoups4Theme($row['allowedForGroup'])."</td>\n";
            $item.='<td><a class="btn btn-info btn-sm" href="?task=editTheme&id='.$row['id'].'">Редагувати</a></td>';
            $item.='<td><a class="btn btn-warning btn-sm" href="?task=deleteTheme&id='.$row['id'].'">Вилучити</a></td>';
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
}

function getGoups4Theme($ids){
    global $conn;
    $ids=str_replace("|",", ",$ids);

    $sql = 'SELECT * FROM `groups` WHERE `id` in ('.$ids.');';
    $result = $conn->query($sql);
    $rez="";
    if(!$result) return "";
    while ($row = $result->fetch_assoc()) {
        $rez.=$row['group_name']." ";
    }
    return $rez;
}

function getThemeType($str){
    switch($str){
        case "course":{
            $str="Курсова робота";
            break;
        }
        case "course_prj":{
            $str="Курсовий проект";
            break;
        }
        case "diploma":{
            $str="Дипломна робота";
            break;
        }
        case "diploma_prj":{
            $str="Дипломний проект";
            break;
        }
    }
    return $str;
}

function loadRequests(){
    global $conn;
$start='
<table class="table table-hover">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Тема</th>
      <th>Студент</th>
      <th>Група</th>
      <th>Редагувати</th>
      <th>Видалити</th>
    </tr>
    </thead>
    <tbody class="">

';
$end="
    </tbody>
</table>
";
        $sql = '
        SELECT `requests`.`id`,`projects`.`theme`, `users`.`firstname`, `users`.`lastname`,  `users`.`patronymic`, `groups`.`group_name` FROM `requests`
        LEFT JOIN `projects` ON `projects`.`id`=`requests`.`project_id`
        LEFT JOIN `users` ON `users`.`id`=`requests`.`student_id`
        LEFT JOIN `student_groups` ON `student_groups`.`user_id`  =`requests`.`student_id`
        LEFT JOIN `groups` ON `student_groups`.`group_id`  =`groups`.`id`
        WHERE `projects`.`curator`='.$_SESSION['user']['id'];
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>\n";
            $item.='<td>'.$row['group_name']."</td>\n";
            $item.='<td><a class="btn btn-success btn-sm" href="?task=aceptRequest&id='.$row['id'].'">Затвердити</a></td>';
            $item.='<td><a class="btn btn-warning btn-sm" href="?task=deleteRequest&id='.$row['id'].'">Вилучити</a></td>';
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
}

function loadAllThemes(){
    global $conn;
$start='
<table class="table table-hover tblcompact">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Керівник</th>
      <th>Тема</th>
      <th>Статус</th>
    </tr>
    </thead>
    <tbody class="">

';
$end="
    </tbody>
</table>
";
$sql="
SELECT `users`.`firstname`, `users`.`lastname`, `users`.`patronymic`, `projects`.`theme`, `projects`.`status`  FROM `projects`
LEFT JOIN `users` ON `users`.`id`=`projects`.`curator`
WHERE `curator`<>".$_SESSION['user']['id']." ORDER BY `curator` ASC
";
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.='<td>'.$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>\n";
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.$row['status']."</td>\n";
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
}

function saveNewTheme(){
    global $MSG, $ERR, $conn;
    $err_msg="";
    if (!isset($_POST['theme']) || strlen($_POST['theme'])<3) {$err_msg.="Не ввказано тему<br>";}
    if (!isset($_POST['type']) || strlen($_POST['type'])<3) {$err_msg.="Не ввказано тип<br>";}
    if (!isset($_POST['group'])) {$err_msg.="Вкажіть хочаб 1 групу<br>";}

    if($err_msg==""){
        $_POST=protectSQL_vals($_POST);
        $group="";
        if(is_array($_POST['group'])){
            foreach($_POST['group'] as $val){
                $group.=$val."|";
            }
            $group=substr($group,0,strlen($group)-1);
        }else{
            $group=$_POST['group'];
        }
      $sql = "INSERT INTO `projects`
            (`type`, `theme`, `curator`, `status`, `allowedForGroup`)
            VALUES ('".$_POST['type']."', '".$_POST['theme']."', '".$_SESSION['user']['id']."', 'open', '".$group."')";

      if ($conn->query($sql) === TRUE) {
        $MSG= "Theme " . $_POST['theme'] . " successfully saved!";
      } else {
        $ERR= "Error: " . $conn->error;
      }
    }else{
        $ERR.=$err_msg;
    }
}


function getThemeEditForm($id){
    global $MSG, $ERR;
    $fields=loadThemeData($id);
    if(is_array($fields)){
        $tmpl='

<form method="post" method="post" action="?task=updateTheme"  enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-4 offset-md-2">
        <label for="themeName" class="col-form-label">Тема проекту</label>
        <input id="themeName" name="theme" class="form-control" type="text" placeholder="Тема проекту..." required value="'.$fields['theme'].'">
      </div>
      <div class="col-md-4">
        <label for="themeType" class="col-form-label">Тип</label>
        <select id="themeType" class="form-control" name="type">
          <option value="course" '.($fields['type']=="course"?" selected ":"").'>Курсова робота</option>
          <option value="course_prj" '.($fields['type']=="course_prj"?" selected ":"").'>Курсовий проект</option>
          <option value="diploma" '.($fields['type']=="diploma"?" selected ":"").'>Дипломна робота</option>
          <option value="diploma_prj" '.($fields['type']=="diploma_prj"?" selected ":"").'>Дипломний проект</option>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8  offset-md-2">
        <label for="groupSelect" class="col-form-label">Для яких груп</label>
        <select id="groupSelect" multiple  class="form-control chosen-select" name="group[]">
        '.getGroupOption($fields['allowedForGroup']).'
        </select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 offset-md-2">
        <hr>
        <input type="hidden" name="theme_id"  value="'.$fields['id'].'">
        <input class="btn btn-primary float-right" name="add_project" type="submit" value="Зберегти тему">
      </div>
    </div>

</form>

        ';
    }else{
        $ERR="record not found<br>";
        $tmpl= loadAllUsers();

    }
    return $tmpl;
}

function loadThemeData($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `projects` WHERE id='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { $rez= $result->fetch_assoc();}
    return $rez;
}

function updateThemeData($id){
    global $MSG, $ERR, $conn;
    $rez=0;$err_msg="";
    if (!isset($_POST['theme']) || strlen($_POST['theme'])<3) {$err_msg.="Не вказано тему<br>";}
    if (!isset($_POST['type']) || strlen($_POST['type'])<3) {$err_msg.="Не вказано тип<br>";}
    if (!isset($_POST['group'])) {$err_msg.="Вкажіть хочаб 1 групу<br>";}
    if($err_msg==""){
        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
        $group="";
        if(is_array($_POST['group'])){
            foreach($_POST['group'] as $val){
                $group.=$val."|";
            }
            $group=substr($group,0,strlen($group)-1);
        }else{
            $group=$_POST['group'];
        }
        $id=(int)$id;

        $sql = "UPDATE `projects` SET `theme`='".$_POST['theme']."' ,`type` = '".$_POST['type']."', `curator` = '".$_SESSION['user']['id']."', `allowedForGroup` = '".$group."' WHERE `projects`.`id` = ".$id." AND `curator`='".$_SESSION['user']['id']."';";
        $result = $conn->query($sql);
        if($result && $conn->affected_rows>0) $MSG="Record updated<br>";
        else{$MSG="Record not found or no changes<br>".$conn->error;}
    }else{
        $ERR.=$err_msg;
    }
}

function deleteRequest($id){
    global $MSG, $ERR, $conn;
    $rez=0;
    $id=(int)$id;
    $sql = 'DELETE FROM `requests` WHERE id='. $id; 
    $result = $conn->query($sql);

    if($result === TRUE) { 
        $MSG="";
    } else {
      return false;
    }  
   
}

function aceptRequest($id){
    global $MSG, $ERR, $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `requests` WHERE id='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { 
        $rez= $result->fetch_assoc();
    }

    $sql = 'UPDATE `projects` SET `status`="close" WHERE id=' . $rez['project_id'];
    if ($conn->query($sql) === TRUE) {
      
        $MSG= 'success!';
      } else {
        $ERR='trouble!';
      }
    
    $sql = 'UPDATE `requests` SET `status`="accepted" WHERE id=' . $rez['id'];
      if ($conn->query($sql) === TRUE) {
        
          $MSG= 'success!';
        } else {
          $ERR='trouble!';
        }

    $sql = 'DELETE FROM `requests` WHERE `student_id` ='. $rez['student_id'] . ' AND id !=' . $id;

    if ($conn->query($sql) === TRUE) {
      
        $MSG= 'success!';
      } else {
        $ERR='trouble!';
      }
    
}
?>