<?php
  require_once 'header.php';
  include_once "funcs_basic.php";
  require_once "all_tasks.php";

function loadDashboard(){
    $tmpl=file_get_contents("tmpl/nav_block.php");
    $content='
    <div class="container" id="rof_off">
    <div class="row" >
        <div align="center" id="tabs_office">
            <ul class="tabs">
                <li class="tab col l3 s3">
                    <a href="#yours">Yours Themes</a>
                </li>
                <li class="tab col l3 s3">
                    <a href="#request">Request</a>
                </li>
                <li class="tab col l3 s3">
                    <a href="#allThemes">All Themes</a>
                </li>
                <li class="tab col l3 s3">
                    <a href="#addNewTheme">+ New Theme</a>
                </li>
            </ul>


            <div id="yours" role="tabpanel active" class="col l12 m12 s12">
                '.loadYours().'
            </div>
            <div id="request" role="tabpanel" class="col l12 m12 s12">
                '.loadRequests().'
            </div>
            <div id="allThemes" role="tabpanel" class="col l12  m12 s12">
                '.loadAllThemes().'
            </div>

            <div id="addNewTheme" role="tabpanel" class="col l12  m12 s12">

                <form class="col l12 s12" action="?task=addTheme" method="post">
                    <div class="row" align="center">
                        <div class="addUser">
                            <div class="input-field col l12 m12 s12">
                                <input id="themeName" name="theme" type="text" class="validate">
                                <label for="themeName">Theme</label>
                            </div>
                            <div class="input-field col l12 m12 s12">
                                <select name="type">
                                    <option disabled selected>Select project type</option>
                                    <option value="course_prj">Course project</option>
                                    <option value="diploma_prj">Diploma project</option>
                                </select>
                                <label for="type">Тип проекту</label>
                            </div>
                            <div class="input-field col l12 m12 s12">
                                <select id="groupSelect" multiple name="group[]">
                                    '.getGroupOption().'
                                </select>
                                <label for="groupSelect">Select groups</label>
                            </div>
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <button class="waves-effect waves-light btn" type="submit" id="addTheme" name="addTheme">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="profil_form" class="profil_form col l12 m12 s12" hidden="true">
            <div class="container">
                <div class="dataBox">' 
                    .loadProfil(). 
                '</div>
            </div>
        </div>
    </div>
</div>
    <div class="row">
        <div id="news_form" class="col xl12 l12 m12 s12" hidden="true">
            <div class="col xl6 l6 m6 s6 offset-xl3">'
                .getNews().
            '</div>
        </div>
    </div>
    ';
return $tmpl.$content; 
} //теми викладача function loadYours(){ global $conn; $start=' function loadYours(){ global $conn;

    function loadProfil(){
        $userdata=loadUserData($_SESSION['user']['id']);
    if(is_array($userdata)){
        $content=' 
        <div class="row">
        <div class="col s12 m12 l12">
            <div class="user_icon" align="center">
                <img src="fonts/user.png" alt="">
                <div>' .$userdata['lastname']." ".$userdata['firstname']." ".$userdata['patronymic']. '</div>
            </div>
            <div id="adminData" class="col s4 m4 l4">
                <div class="studentData input-field col l12 m12 s12">
                    <p>Your Role: '.$userdata['role'].'</p>
                    <p>Email: '.$userdata['email'].'</p>
                    <p>Password: '.$userdata['password'].'</p>
                </div>
                <button id="getEditInput" class="waves-effect waves-light btn center">Edit</button>
            </div>
            
            <div id="progress_check">
                <div class="box col s8 m8 l8">' .getYoursStudentProgress($userdata['id']). '
                </div>
            </div>
            <div id="adminDataEdit" class="col l12 m12 s12" hidden="true">
                <form class="col l12 m12 s12" action="dashboard_student.php?task=update_profil" method="post">
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

// function loadUserData($id){
//     global $conn;
//     $rez=0;
//     $id=(int)$id;
//     $sql="SELECT * FROM `users` WHERE id='".$id."' LIMIT 0,1";
//     $result = $conn->query($sql);
//     if($result->num_rows>0) { 
//         $rez= $result->fetch_assoc();
//     }
//     return $rez;
// }

    function loadYours(){
        global $conn;
    $start='
    <table class="table table-hover">
      <thead class="centered">
        <tr>
          <th>№ п/п</th>
          <th>Theme</th>
          <th>Type</th>
          <th>Groups</th>
          <th>Edit</th>
          <th>Delete</th>
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
                $item.='<td><a class="btn btn-info btn-sm" href="?task=editTheme&id='.$row['id'].'">Edit</a></td>';
                $item.='<td><a class="btn btn-warning btn-sm" href="?task=deleteTheme&id='.$row['id'].'">Delete</a></td>';
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
                $str="Course project";
                break;
            }
            case "course_prj":{
                $str="Course project";
                break;
            }
            case "diploma":{
                $str="Diploma project";
                break;
            }
            case "diploma_prj":{
                $str="Diploma project";
                break;
            }
        }
        return $str;
    }
    
    function loadRequests(){
        global $conn;
    $start='
    <table class="table table-hover">
      <thead class="centered">
        <tr>
          <th>№ п/п</th>
          <th>Theme</th>
          <th>Student</th>
          <th>Group</th>
          <th>Edit</th>
          <th>Delete</th>
        </tr>
        </thead>
        <tbody class="">
    
    ';
    $end="
        </tbody>
    </table>
    ";
            $sql = '
            SELECT `requests`.`id`,`projects`.`status`,`projects`.`theme`, `users`.`firstname`, `users`.`lastname`,  `users`.`patronymic`, `groups`.`group_name` FROM `requests`
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
                if($row['status'] != 'close'){
                $item.='<td><a class="btn btn-success btn-sm" href="?task=aceptRequest&id='.$row['id'].'">Accepted</a></td>';
                $item.='<td><a class="btn btn-warning btn-sm" href="?task=deleteRequest&id='.$row['id'].'">Decline</a></td>';
                }else{
                $item.='<td><a class="btn-flat disabled">Accepted</a></td>';
                $item.='<td><a class="btn-flat disabled">Decline</a></td>';
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
    <table class="table table-hover tblcompact">
      <thead>
        <tr>
          <th>№ п/п</th>
          <th>Curator</th>
          <th>Theme</th>
          <th>Status</th>
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
    $pre=file_get_contents("tmpl/nav_edit_block.php");
    $fields=loadThemeData($id);
    if(is_array($fields)){
        
        $tmpl='
<div class="container">
    <div class="row">
        <form id="edit_project" name="edit_project" class="col l12 s12" action="?task=updateTheme" method="post">
            <div class="row" align="center">
                <div class="editUser">
                    <div class="input-field col l12 s10">
                        <input id="themeName" name="theme" type="text" class="validate" value="'.$fields['theme'].'">
                        <label for="themeName">Theme project</label>
                    </div>
                    <div class="input-field col l12 s10">
                        <select id="type" name="type">
                            <option disabled selected>Select type project</option>
                            <option value="course_prj" '.($fields['type']=="course"?" selected ":"").'>Course project</option>
                            <option value="diploma_prj" '.($fields['type']=="diploma"?" selected ":"").'>Diploma project</option>
                        </select>
                        <label for="type">Type</label>
                    </div>
                    <div class="input-field col l12 s10">
                            <select id="groupSelect" multiple name="group[]">
                            '.getGroupOption($fields['allowedForGroup']).'
                            </select>
                        </div>
                    </div>
                    <div class="input-field col l12 s10">
                            <input type="hidden" name="theme_id" value="'.$fields['id'].'">
                            <button class="waves-effect waves-light btn" type="submit" id="edit_project" name="edit_project">UppDate</button>
                        </div>
                    </div>
                </div>
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
    
    function loadThemeData($id){
        global $conn;
        $rez=0;
        $id=(int)$id;
        $sql="SELECT * FROM `projects` WHERE id='".$id."' LIMIT 0,1";
        $result = $conn->query($sql);
        if($result->num_rows>0) { $rez= $result->fetch_assoc();}
        return $rez;
    }

    function updateProfilData($id){
        global $conn;
    
        $rez=0;
        $userdata=loadUserData($id);
        if (!empty($_POST['email']) || !empty($_POST['password'])) {
    
            $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
            $id=(int)$id;
            $firstname = $userdata['firstname'];
            $lastname = $userdata['lastname'];
            $patronymic = $userdata['patronymic'];
            $email = $_POST['email'];
            $role = $userdata['role'];
            if(isset($_POST['email']) || strlen($_POST['password'])>=4){
                $password = $_POST['password'];
                $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `patronymic`='$patronymic', `email`='$email', `role`='$role', `password`='$password' WHERE `id`='$id'";
            }else{
                $sql = "UPDATE `users` SET `firstname`='$firstname', `lastname`='$lastname', `patronymic`='$patronymic', `email`='$email', `role`='$role', `password`='$password' WHERE `id`='$id'";
            }
            $result = $conn->query($sql);
            if($conn->affected_rows>0) $MSG="Record updated<br>";
            else{$MSG="Record not found or no changes<br>".$conn->error;}
        }else{
            $ERR.=$err_msg;
        }
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
    
        $sql = 'DELETE FROM `requests` WHERE `student_id` ='. $rez['student_id'].' AND `project_id`!='.$rez['project_id'];
    
        if ($conn->query($sql) === TRUE) {
          
            $MSG= 'success!';
          } else {
            $ERR='trouble!';
          }
          
    }

function getgetYoursCheckBox ($id){
    global $conn;
    $tasks=getAllTasks();
    $sql = 
    'SELECT `projects`.`theme`, `users`.`id`, `users`.`firstname`, `users`.`lastname`,  `users`.`patronymic` FROM `requests`
    LEFT JOIN `projects` ON `projects`.`id`=`requests`.`project_id`
    LEFT JOIN `users` ON `users`.`id`=`requests`.`student_id`
    WHERE `projects`.`status`="close" AND `projects`.`curator`='.$id;
    
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        $entry = get_tasks($userdata['id']);
        $entry_tasks = explode(';', $entry['tasks']);
        foreach ($tasks as $task_slug => $task_name) {
            $checked = in_array($task_slug, $entry_tasks) ? 'checked' : '';
            $content .= '
                <div>
                    <input 
                        type="checkbox" 
                        id="' . $task_slug . '" 
                        name="tasks[]" 
                        value="' . $task_slug . '"
                        ' . $checked . ' />
                    <label for="' . $task_slug . '">' . $task_name . '</label>
                </div>
            ';
        }
    }

}

    function getYoursStudentProgress($id){
        global $conn;
    
    $sql = 
    'SELECT `projects`.`theme`, `users`.`id`, `users`.`firstname`, `users`.`lastname`,  `users`.`patronymic` FROM `requests`
    LEFT JOIN `projects` ON `projects`.`id`=`requests`.`project_id`
    LEFT JOIN `users` ON `users`.`id`=`requests`.`student_id`
    WHERE `projects`.`status`="close" AND `projects`.`curator`='.$id;
    
    $i=1; $rez="";
    $result = $conn->query($sql);
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $entry = get_tasks($row['id']);     
            $entry_tasks = explode(';', $entry['tasks']);
            $pr_bar = getProgress((int)count($entry_tasks));

            $item = '<div class="studentData">'.$i.".";
            $item.= $row['lastname']." ".$row['firstname']." ".$row['patronymic'].'</div>';
            $item.= '<p>Тема роботи: "'.$row['theme'].'" Виконано на: '.$pr_bar.'%</p>';
            $item.= '
                    <div class="progress">
                    <div class="determinate" style="width: '.$pr_bar.'%" title="'.$pr_bar.'%"></div>
                    </div>
                    ';
            $rez.= $item."\n";
            $i++;
        }
    }
   
    return $rez;
    }
 
    function getProgress($ready_tasks)
    {
        $all_tasks = count(getAllTasks());
        $progress = ($ready_tasks * 100)/$all_tasks;
        return $progress;
    }
    
    function get_tasks ($student_ID) {
        global $conn;
    
        $sql = "SELECT `tasks` FROM `tasks` WHERE `student_ID` = '" . $student_ID . "'";
    
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) { 
            return $result->fetch_assoc();
        }
    }
    
// function add_tasks ($student_ID, $tasks) {
//     global $conn;

//     $sql = "INSERT INTO `tasks` (`student_ID`, `tasks`) VALUES ('".$student_ID."', '".$tasks."')";

//     if ($conn->query($sql) === TRUE) {
//         return true;
//     } else {
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }

// function get_tasks ($student_ID) {
//     global $conn;

//     $sql = "SELECT * FROM `tasks` WHERE `student_ID` = '" . $student_ID . "'";

//     $result = $conn->query($sql);

//     if ($result->num_rows > 0) { 
//         return $result->fetch_assoc();
//     }
// }

// function update_tasks ($ID, $student_ID, $tasks) {
//     global $conn;

//     $sql = "UPDATE `tasks` SET `student_ID`= '$student_ID', `tasks` = '$tasks' WHERE `id`='$ID'";
    
//     $result = $conn->query($sql);
   
//     if ($conn->affected_rows > 0) {
        
//         return header('Location:dashboard_student.php');
//     } else {
//         return header('Location:dashboard_student.php');
//         echo "Error: " . $sql . "<br>" . $conn->error;
//     }
// }