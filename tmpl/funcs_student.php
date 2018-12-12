<?php
require_once 'header.php';
include_once "funcs_basic.php";
function loadDashboard(){
    $tmpl=file_get_contents("tmpl/student.html");
    $content='
    <div class="container">
        <div class="row">
            <div id="tabs_office" >
                <ul class="tabs">
                    <li class="tab col s3">
                        <a href="#yours">Yours Themes</a>
                    </li>
                    <li class="tab col s3">
                        <a href="#request">Request</a>
                    </li>
                    <li class="tab col s3">
                        <a href="#allThemes">All Themes</a>
                    </li>
                </ul>


                <div id="yours" role="tabpanel active" class="col s12">
                    '.loadActiveThemes('admin').'
                </div>
                <div id="request" role="tabpanel" class="col s12">
                    '.loadRequests('curator').'
                </div>
                <div id="allThemes" role="tabpanel" class="col s12">
                    '.loadAllThemes('student').'
                </div>
            </div>

            <div id="profil_form" hidden="true">

            </div>

        </div>
    </div>
    ';

    return $tmpl.$content;
}


function loadActiveThemes(){
    global $conn;
$start='
<table class="table table-hover">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Тема</th>
      <th>Тип</th>
      <th>Групи</th>
      <th>Подати заяву</th>
    </tr>
    </thead>
    <tbody class="">

';
$end="
    </tbody>
</table>
";
    $group_id = getGoups4Student($_SESSION['user']['id']);
    $sql = "SELECT * FROM `projects` WHERE `allowedForGroup` LIKE '%$group_id%' AND `status` = 'open'";
    $result = $conn->query($sql); $i=1; $item=""; $rez="";
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.getThemeType($row['type'])."</td>\n";
            $item.='<td>'.getGoups4Theme($row['allowedForGroup'])."</td>\n";
            $item.='<td><a class="btn btn-info btn-sm" href="?task=addRequest&id='.$row['id'].'">Подати заяву</a></td>';
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
}

function getGoups4Student($id){
    global $conn;

    $sql = 'SELECT * FROM `student_groups` WHERE `user_id` = '.$_SESSION['user']['id'];
    $result = $conn->query($sql);
    $rez="";
    if(!$result) return "";
    while ($row = $result->fetch_assoc()) {
        $rez.=$row['group_id'];
    }
    return $rez;
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
<table class="striped">
  <thead>
    <tr>
      <th>№ п/п</th>
      <th>Тема проекту</th>
      <th>Керівник проекту</th>
      <th>Група</th>
      <th>Статус</th>
      <th>Відхилити</th>
    </tr>
    </thead>
    <tbody class="">

';
$end="
    </tbody>
</table>
";
        $sql = '
        SELECT `requests`.`id`,`projects`.`theme`, `users`.`firstname`, `users`.`lastname`,  `users`.`patronymic`, `groups`.`group_name`, `requests`.`status` FROM `requests`
        LEFT JOIN `projects` ON `projects`.`id`=`requests`.`project_id`
        LEFT JOIN `users` ON `users`.`id`=`projects`.`curator`
        LEFT JOIN `student_groups` ON `student_groups`.`user_id`  =`requests`.`student_id`
        LEFT JOIN `groups` ON `student_groups`.`group_id`  =`groups`.`id`
        WHERE `requests`.`student_id`= '.$_SESSION['user']['id'];
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>\n";
            $item.='<td>'.$row['group_name']."</td>\n"; 
            if ($row['status'] === 'accepted'){
                $item.='<td style="color: green;">Тема прийнята!</td>\n';
            }else{
                $item.='<td style="color: rgba(34, 34, 36, 0.863);">Переглядається...</td>\n';
            }
            if ($row['status'] === 'pending'){
            $item.='<td><a class="btn btn-warning btn-sm" href="?task=deleteRequest&id='.$row['id'].'">Відхилити</a></td>';
            }else{
                $item.='<td><a class="btn btn-secondary btn-sm" disabled>Відхилити</a></td>';
            }
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
}

function loadAllThemes(){
    global $conn;
$start='
<table class="striped">
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
//WHERE `id`<>".$_SESSION['user']['id']." ORDER BY `id` ASC
$end="
    </tbody>
</table>
";
$sql="
SELECT `users`.`firstname`, `users`.`lastname`, `users`.`patronymic`, `projects`.`theme`, `projects`.`status`  FROM `projects`
LEFT JOIN `users` ON `users`.`id`=`projects`.`curator`
";
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item='<td>'.$i.'</td>';
            $item.='<td>'.$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>";
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.='<td>'.$row['status']."</td>\n";
            $rez.="\n<tr>".$item."</tr>\n";
            $i++;
        }
    }
    return $start.$rez.$end;
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

function addRequest($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $student_id = $_SESSION['user']['id'];
    $sql = "INSERT INTO requests (project_id, student_id, status) VALUES ('$id', '$student_id', 'pending')";
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
        exit();
      } else {
        return false;
      }
 
}
?>