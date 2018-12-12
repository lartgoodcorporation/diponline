<?php
require_once 'auth.php';
require 'db_connection.php';


//Output requests
$sql = 'SELECT * FROM requests';
$result = $conn->query($sql);
$requests = false;

if ($result->num_rows > 0) {
  while ($row4 = $result->fetch_assoc()) {
    $requests[] = $row4;
  }
} 

//Output users
$sql = 'SELECT * FROM users';
$result = $conn->query($sql);
$users = false;

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }
} else {
  echo "<!-- Error: " . $sql . " " . $conn->error." -->";
}


//Output users
$sql = 'SELECT * FROM student_groups';
$result = $conn->query($sql);
$students = false;

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $students[] = $row;
  }
} else {
  echo "<!-- Error: " . $sql . " " . $conn->error." -->";
}

//Output curators
$curators = array_filter($users, function($user) {
  return $user['role'] === 'curator';
});

//Output projects
$sql = "SELECT * FROM projects";
$result = $conn->query($sql);
$projects = false;

if ($result->num_rows > 0) {
  while ($row2 = $result->fetch_assoc()) {
    $projects[] = $row2;
  }

} else {
  echo "<!-- Error: " . $sql . "<br>" . $conn->error." -->";
}

//Output groups
$sql = "SELECT * FROM groups";
$result = $conn->query($sql);
$groups = false;

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
  }

} else {
  echo "<!-- Error: " . $sql . "<br>" . $conn->error." -->";
}

//Output active projects
$active_projects = array_filter($projects, function($ap) {
  return $ap['status'] === 'open';
});

//Output only my projects
$_projects = array_filter($projects, function($mp) {
  return $mp['curator'] === $_SESSION['user']['id'];
});

?>
