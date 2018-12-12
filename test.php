.task_form > div{ width: 50%; margin-bottom: 16px; } .box1{ width: 26rem; /* Ширина блока */ height: 20rem; padding: 10px;
/* Поля */ border-width: 8px; border: #fbf5ff double 5px; } .box2{ width: 26rem; /* Ширина блока */ height: 20rem; padding:
10px; /* Поля */ border-width: 8px; border: #fbf5ff double; }


<div class="col l6 m6 s6">
    <label for="newForm">Додати новину:</label>
    <form id="newForm">
        <div class="row">
            <div class="input-field col l12 m12 s12">
                <input id="input_text" type="text" data-length="10">
                <label for="input_text">Заголовок</label>
            </div>
        </div>
        <div class="row">
            <div class="input-field col l12 m12 s12">
                <textarea id="textarea1" class="materialize-textarea" data-length="120"></textarea>
                <label for="textarea1">Зміст новини...</label>
            </div>
        </div>
    </form>
</div>

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
                        <option value="admin" '.($userdata['role ']=="admin" ? " selected ": "").'>Administrator</option>
                        <option value="curator" '.($userdata['role ']=="curator" ? " selected ": "").'>Project curator</option>
                        <option value="student" '.($userdata['role ']=="student" ? " selected ": "").'>Student</option>
                    </select>
                    <label for="role">Select your group</label>
                </div>
                <input type="hidden" name="user" value="'.$userdata['id'].'">
                <button class="btn btn-warning btn-sm" href="dashboard_admin.php" type="submit" id="add" name="add">Save</button>
            </div>
        </form>
    </div>
</div>

?task=edit&edit_user='.$row['id'].'

<div class="container">
    <div class="row">
        <div id="tabs_office" align="center">
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
                <div class="dataBox">' .loadProfil(). '
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div id="add_news">
            <div class="box col s7 m7 l7">
                <form id="newForm" class="col l12 m12 s12" action="?task=add_news" method="post">
                    <div align="center">Add News</div>
                    <div class="row">
                        <div class="input-field col l12 m12 s12">
                            <input id="news_title" name="news_title" type="text" data-length="10">
                            <label for="news_title">News Title</label>
                        </div>
                        <div class="input-field col l12 m12 s12">
                            <textarea id="news_content" name="news_content" class="materialize-textarea" data-length="200"></textarea>
                            <label for="news_content">News content...</label>
                        </div>
                        <button id="add_news" name="add_news" class="waves-effect waves-light btn center" type="submit">Add</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>



<div class="container" id="tabs_office">
    <div class="row">
        <div align="center">
            <div class="container" id="tabs_office">
                <div class="row">
                    <div align="center">
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
                    <div class="col xl6 l6 m6 s6 offset-xl3">' .getNews(). '
                    </div>
                </div>
            </div>



            <div class="container" id="tabs_office">
                <div class="row">
                    <div align="center">
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
                    <div class="col xl6 l6 m6 s6 offset-xl3">' .getNews(). '
                    </div>
                </div>
            </div>

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
                        <div class="studBox col s7 m7 l7 xl7">
                            <form class="task_form col xl12 l12 m12 s12" action="dashboard_student.php?task=update_tasks" method="post">

                                <input type="hidden" name="user_id" value="'.$userdata['id'].'">
                                <button type="submit" id="add_progress" name="add_progress" class="waves-effect waves-light btn">Save</button>
                            </form>
                        </div>
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