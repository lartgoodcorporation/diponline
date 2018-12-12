<?php

function add_user($firstname, $lastname, $patronymic, $email, $pass, $group_ID, $role)
{
  require 'db_connection.php';

  $role = $role ? $role : 'student';
  // $id = md5($firstname . $lastname . $patronymic . $email);

  $sql = 'SELECT * FROM users WHERE email = "' . $email . '"';
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();

  if (!$user) {
    $sql = "INSERT INTO users (firstname, lastname, patronymic, email, password, role) VALUES ('$firstname', '$lastname', '$patronymic', '$email', '$pass', '$role')";

    if ($conn->query($sql) === TRUE) {

      $id = mysqli_insert_id($conn);
      $sql = "INSERT INTO student_groups (user_id, group_id) VALUES ('$id', '$group_ID')";
  
      if ($conn->query($sql) === TRUE) {
        header('Location: login.php');
        exit();
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}

function add_student ($student_ID, $group_ID){
  require 'db_connection.php';
  if ($user) {
    $sql = 'SELECT * FROM student_groups WHERE user_id = "' . $student_ID . '"';
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
  
    if (!$student) {
      $sql = "INSERT INTO student_groups (user_id, group_id) VALUES ('" . $student_ID . "', '$group_ID')";
  
      if ($conn->query($sql) === TRUE) {
        header('Location: login.php');
        exit();
      } else {
        return false;
      }

  } else {
    return false;
  }
    }
}

function add_request($project_ID, $student_ID, $status) {
  require 'db_connection.php';

  // $id = md5($project_ID . $student_ID);

  $sql = "INSERT INTO requests (project_id, student_id, status) VALUES ('$project_ID', '$student_ID', '$status')";

  if ($conn->query($sql) === TRUE) {
    header('Location: index.php');
    exit();
  } else {
    return false;
  }
}

function get_request($request_ID) {
  require 'db_connection.php';

  $sql = 'SELECT * FROM requests WHERE id = "' . $request_ID . '"';
  $result = $conn->query($sql);
  $request = $result->fetch_assoc();

  if ($request) {
    return $request;
  } else {
    return false;
  }

  exit();
}

function delete_request($request_ID) {
  require 'db_connection.php';

  $sql = "DELETE FROM requests WHERE id='" . $request_ID . "'";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}

function decline_project($request_ID) {
  require 'db_connection.php';

  $sql = "DELETE FROM requests WHERE project_id='" . $request_ID . "'";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}

function decline_request($request_ID) {
  require 'db_connection.php';

  $sql = 'UPDATE projects SET status="open" WHERE id="' . $request_ID . '"';

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}

function set_request_status($request_ID) {
  require 'db_connection.php';

  $sql = 'UPDATE requests SET status="accepted" WHERE id="' . $request_ID . '"';

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}

function set_status($project_ID) {
  require 'db_connection.php';

  $sql = 'UPDATE projects SET status="close" WHERE id="' . $project_ID . '"';

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}

function join_project_user($project_ID, $user_ID) {
  require 'db_connection.php';

  $sql = "INSERT INTO join_projects_users (project_id, user_id) VALUES ('$project_ID', '$user_ID')";

  if ($conn->query($sql) === TRUE) {
    return true;
  } else {
    return false;
  }

  exit();
}


function get_projects($user_ID, $project_status) {
  require 'db_connection.php';

  $final_sql = false;

  if (!$user_ID && !$project_status) {
    $final_sql = 'SELECT * FROM projects';
  } else if (!$user_ID && $project_status) {
    $final_sql = 'SELECT * FROM projects WHERE status = "' . $project_status . '"';
  } else if ($user_ID) {
    $sql = 'SELECT * FROM join_projects_users WHERE user_id = "' . $user_ID . '"';
    $result = $conn->query($sql);

    $joins_IDs = "";
  
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $joins_IDs .= "'" . $row['project_id'] . "', ";
      }
      $joins_IDs = rtrim($joins_IDs,", ");
      
      $final_sql = "SELECT * FROM projects WHERE id IN (" . $joins_IDs . ")";

      if ($project_status) {
        $final_sql .= ' AND status = "' . $project_status . '"';
      }
    }
  }

  $result = $conn->query($final_sql);

  $projects = false;

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $projects[] = $row;
    }

    return $projects;
  } else {
    return false;
  }
}
