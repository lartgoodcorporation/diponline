<link type="text/css" rel="stylesheet" href="css/style.css">
<?php
  require_once 'header.php';

 

function getUserSalutation(){
    if(isset($_SESSION['user'])){
        return "".$_SESSION['user']['lastname']." ".$_SESSION['user']['firstname']." ".$_SESSION['user']['patronymic']."";
    }else{    
        return "Вітаємо Вас";
    }
}

//функція для захисту sql запитів від sql-ін'єкцій, для шифрування sql запитів
function protectSQL_vals($params){
    global $conn;
    if(is_array($params)){
        foreach($params as $key=>$val){
            if(is_array($val)){
                $val=protectSQL_vals($val);
                $rez[$key]=$val;
            }else{
                $rez[$key]=mysqli_real_escape_string($conn,$val);
            }

        }
    }else{
        $rez=mysqli_real_escape_string($conn,$params);
    }
    return $rez;
}

function loadUserData($id){
    global $conn;
    $rez=0;
    $id=(int)$id;
    $sql="SELECT * FROM `users` WHERE `id`='".$id."' LIMIT 0,1";
    $result = $conn->query($sql);
    if($result->num_rows>0) { 
        $rez= $result->fetch_assoc();
    }
    return $rez;
}

//розпарсений список груп в селект
function getGroupOption($selected=0){

    $arr=getGroupListArray(); $rez="";$was=0;
    if($selected!=0){
        $sel=explode("|",$selected);
    }else{
        $sel[]=-1;
    }
    foreach($arr as $row){
        foreach($sel as $i){
            if($row['id']==$i){
                $rez.='<option selected="selected" value="' . $row['id'] . '">' . $row['group_name'] . "</option>\n";
                $was=1;
                break;
            }
        }
        if($was==0){
             $rez.='<option value="' . $row['id'] . '">' . $row['group_name'] . "</option>\n";
        }
        $was=0;
    }
    return $rez;
}

//функція отримання списку груп в масивом
function getGroupListArray(){
    global $conn;
    $sql = 'SELECT * FROM `groups`';
    $result = $conn->query($sql);
    $rez =array();
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $rez[] = $row;
      }
    }
    return $rez;
}

function getNews(){
    $post = isset($_COOKIE['post']) ? $_COOKIE['post'] : 0;
    $post++;
    setcookie("post", $post);
    global $conn;
    $sql = 'SELECT * FROM `news`';
    $result = $conn->query($sql); 
    $i=1; 
    $item=""; 
    $rez="";
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item= '<div class="container center"><div class="news_title" align="center">'.$row['news_title']."</div>";
            $item.='<div class="news_content">'.$row['news_content']."</div>";
            $item.='<div class="post"><i>Views: '.$post.'</i></div></div>';
            $rez.='<div id="post" class="newBox">'.$item.'</div>';
            $i++;
        }
    }
    return $rez;
}

function getNewsAdmin(){
    global $conn;
    $sql = 'SELECT * FROM `news`';
    $result = $conn->query($sql); 
    $i=1; 
    $item=""; 
    $rez="";
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $item= '<div class="container"><div class="new_block"><div class="news_title" align="center">'.$row['news_title']."</div>";
            $item.='<div class="news_content">'.$row['news_content']."</div>";
            $item.='<a class="waves-effect waves-light btn" href="?task=editNews&edit_news='.$row['ID'].'">Edit</a>';
            $item.='<a class="waves-effect waves-light btn" href="?task=deleteNews&id='.$row['ID'].'">Delete</a></div></div>';
            $rez.='<div class="newBox">'.$item.'</div>';
            $i++;
        }
    }
    return $rez;
}

function getNewsEditForm($id){
    global $MSG, $ERR;
    $pre=file_get_contents("tmpl/nav_edit_block.php");
    $fields=loadNewsData($id);
    if(is_array($fields)){
        
        $tmpl='
        <div class="container" id="add_news">
            <div class="row">
                <div class="newBox col xl8 l8 m8 s8 offset-xl2">
                    <form id="newEditForm" class="col xl12 l12 m12 s12" action="?task=updateNews" method="post">
                        <div align="center" class="news_title">Edit News</div>
                        <div class="row">
                            <div class="news_content input-field col xl12 l12 m12 s12">
                                <input id="news_title" name="news_title" type="text" data-length="50" value="'.$fields['news_title'].'">
                                <label for="news_title">News Title</label>
                            </div>
                            <div class="news_content input-field col xl12 l12 m12 s12">
                                <textarea id="news_content" name="news_content" class="materialize-textarea" data-length="200">'.$fields['news_content'].'</textarea>
                                <label for="news_content">News content...</label>
                            </div>
                            <input type="hidden" name="news" value="'.$fields['ID'].'">
                            <button id="add_news" name="add_news" class="waves-effect waves-light btn" type="submit">Updete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            ';
        }else{
            $ERR="record not found<br>";
            $tmpl= loadAllUsers();
    
        }
        return $pre.$tmpl;
    }

    function loadNewsData($id){
        global $conn;
        $rez=0;
        $id=(int)$id;
        $sql="SELECT * FROM `news` WHERE `ID` = '".$id."' LIMIT 0,1";
        $result = $conn->query($sql);
        if($result->num_rows>0) { $rez= $result->fetch_assoc();}
        return $rez;
    }
    
function updateNewsData($id){
    global $conn;

    $rez=0;

    if (!empty($_POST['news_title']) || !empty($_POST['news_content'])) {

        $_POST=protectSQL_vals($_POST); //in file funcs_basic.php
        $id=(int)$id;
        $news_title = $_POST['news_title'];
        $news_content = $_POST['news_content'];
        if(isset($_POST['news_title']) && strlen($_POST['news_content'])>=4){
            $sql = "UPDATE `news` SET `news_title`='$news_title', `news_content`='$news_content' WHERE `ID`='$id'";
        }else{
            $sql = "UPDATE `news` SET `news_title`='$news_title', `news_content`='$news_content' WHERE `ID`='$id'";
        }
        $result = $conn->query($sql);
        if($conn->affected_rows>0) $MSG="Record updated<br>";
        else{$MSG="Record not found or no changes<br>".$conn->error;}
    }else{
        echo "Error: " . $sql . "<br>" . $conn->error;
        return header('Location:dashboard_admin.php');
    }
}
