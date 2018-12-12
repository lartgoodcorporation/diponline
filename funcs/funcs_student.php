<link type="text/css" rel="stylesheet" href="css/style.css">
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
                    <li class="tab col xl4 l4 m4 s4">
                        <a href="#yours">Yours Themes</a>
                    </li>
                    <li class="tab col xl4 l4 m4 s4">
                        <a href="#request">Request</a>
                    </li>
                    <li class="tab col xl4 l4 m4 s4">
                        <a href="#allThemes">All Themes</a>
                    </li>
                </ul>
                <div id="yours" role="tabpanel active" class="col xl12 l12 m12 s12">
                    '.loadActiveThemes().'
                </div>
                <div id="request" role="tabpanel" class="col xl12 l12 m12 s12">
                    '.loadRequests().'
                </div>
                <div id="allThemes" role="tabpanel" class="col xl12 l12 m12 s12">
                    '.loadAllThemes().'
                </div>
            </div>

            <div id="profil_form" class="profil_form col xl12 l12 m12 s12" hidden="true">
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
}

function loadProfil(){
    $userdata = loadUserData($_SESSION['user']['id']);
    $tasks=getAllTasks();
    if (is_array($userdata)) {
        $content ='
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
                            </div>
                            <button  id="getEditInput" class="waves-effect waves-light btn center">Edit</button>
                        </div>
                        
                    <div id="progress_check">
                        <div class="studBox col s7 m7 l7 xl7">
                            <form class="task_form col xl12 l12 m12 s12" action="dashboard_student.php?task=update_tasks" method="post">
            ';

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
                                
            $content .= '
                <input type="hidden" name="user_id" value="'.$userdata['id'].'">
                                <button type="submit" id="add_progress" name="add_progress" class="waves-effect waves-light btn">Save</button>
                            </form>
                        </div>
                    </div>    
                    <div id="adminDataEdit" class="col xl12 l12 m12 s12" hidden="true">
                        <form class="col xl12 l12 m12 s12" action="dashboard_student.php?task=update_profil" method="post">
                            <div class="studentData">'.getUserSalutation().'</div>
                            <div class="input-field col xl12 l12 m12 s12">
                                <input title="The password must be at least 4 characters long" name="password" id="password" type="text" value="'.$userdata['password'].'">
                                <label for="password">Your password:</label>
                            </div>
                            <div class="input-field col xl12 l12 m12 s12">
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

function loadActiveThemes(){
    global $conn;
$start='
<table class="striped">
  <thead class="centered">
    <tr>
      <th>№</th>
      <th>Theme</th>
      <th>Type</th>
      <th>Groups</th>
      <th>Add request</th>
    </tr>
    </thead>
    <tbody>

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
            $item.='<td><a class="waves-effect waves-light btn" href="?task=addRequest&id='.$row['id'].'">Add</a></td>';
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
            $str="Course project";
            break;
        }
        case "diploma":{
            $str="Diploma project";
            break;
        }
    }
    return $str;
}

function loadRequests(){
    global $conn;
$start='
<table class="striped">
  <thead class="centered">
    <tr>
      <th>№</th>
      <th>Theme</th>
      <th>Curator</th>
      <th>Група</th>
      <th>Status</th>
      <th>Decline</th>
    </tr>
    </thead>
    <tbody>

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
                $item.='<td style="color: green;">Theme accepted!</td>';
            }else{
                $item.='<td style="color: rgba(34, 34, 36, 0.863);">Pending...</td>';
            }
            if ($row['status'] === 'pending'){
            $item.='<td><a  id="delete_req" class="waves-effect waves-light btn red" href="?task=deleteRequest&id='.$row['id'].'">Decline</a></td>';
           
            }else{
                $item.='<td><a class="waves-effect waves-light btn red" disabled>Decline</a></td>';
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
  <thead class="centered">
    <tr>
      <th>№</th>
      <th>Curator</th>
      <th>Theme</th>
      <th>Status</th>
    </tr>
    </thead>
    <tbody>

';
//WHERE `id`<>".$_SESSION['user']['id']." ORDER BY `id` ASC
$end="
    </tbody>
</table>
";  


$sql="
SELECT `users`.`firstname`, `users`.`lastname`, `users`.`patronymic`, `projects`.`theme`, `projects`.`status` FROM `projects`
LEFT JOIN `users` ON `users`.`id`=`projects`.`curator`";
    $i=1; $rez="";
    $result = $conn->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item="\n<td>".$i."</td>\n";
            $item.="\n<td>".$row['lastname']." ".$row['firstname']." ".$row['patronymic']."</td>\n";
            $item.="\n<td>".$row['theme']."</td>\n";
            $item.="\n<td>".$row['status']."</td>\n";
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
        
        // header('Location: ./dashboard_student.php');
        // exit();
      } else {
        return false;
      }
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



function loadReadyOptions($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `progress_projects` WHERE `student_id`='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { 
        $rez= $result->fetch_assoc();
    }
    return $rez['option_id'];
}


function add_tasks ($student_ID, $tasks) {
    global $conn;

    $sql = "INSERT INTO `tasks` (`student_ID`, `tasks`) VALUES ('".$student_ID."', '".$tasks."')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function get_tasks ($student_ID) {
    global $conn;

    $sql = "SELECT * FROM `tasks` WHERE `student_ID` = '" . $student_ID . "'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) { 
        return $result->fetch_assoc();
    }
}

function update_tasks ($ID, $student_ID, $tasks) {
    global $conn;

    $sql = "UPDATE `tasks` SET `student_ID`= '$student_ID', `tasks` = '$tasks' WHERE `id`='$ID'";
    
    $result = $conn->query($sql);
   
    if ($conn->affected_rows > 0) {
        
        return header('Location:dashboard_student.php');
    } else {
        return header('Location:dashboard_student.php');
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}