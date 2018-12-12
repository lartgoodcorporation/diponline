<?
if (!empty($_SESSION['user'])) {
    header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
    exit();
}

?>

    <!DOCTYPE html>
    <html>

    <head>
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>
                <?= $HEADER_TITLE ? $HEADER_TITLE : 'Кафедра Online' ?>
            </title>
    </head>

    <body>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script>
            $(document).ready(function () {
                $('select').material_select();
            });
        </script>


        <div class="wrapper">
            <div class="side">
                <div id="info_block" class="content">
                    <h1>Diploma</h1>
                    <p class="intro">
                        Вітаємо Вас на сайті Природничо-гуманітарного коледжу ДВНЗ «Ужгородський національний університет», а саме – «Кафедра Online».
                        <br/>Саме тут можна отримати інформацію про курсове та дипломне проектування. Студенти мають можливість
                        ознайомитися зі списком тем для курсових робіт, курсових та дипломних проектів. Кожен студент має
                        можливість зареєструватися і подати заявку он-лайн на ту чи іншу тему. А вже керівники вирішують
                        за яким студентом яку тему закріпити. Даний сервіс має на меті полегшити процес пошуку та вибору
                        тем для студентів.
                    </p>

                    <a id="get_form" class="waves-effect waves-light btn">Увійти</a>
                </div>
                <h2 class="signIn">
                    <a href="#" id="btn_in">Sign In</a> |
                    <a href="#" id="btn_up">Sign Up</a>
                </h2>

                <div id="reg_block" hidden="true" class="login">
                    <div class="row">
                        <form class="col s12">
                            <div class="row">
                                <div class="input-field col s6">
                                    <input id="first_name" type="text" class="validate">
                                    <label for="first_name">First Name</label>
                                </div>
                                <div class="input-field col s6">
                                    <input id="last_name" type="text" class="validate">
                                    <label for="last_name">Last Name</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="patronymic" type="text" class="validate">
                                    <label for="patronymic">Patronymic</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="email" type="email" class="validate">
                                    <label for="email">Email</label>
                                </div>
                                <div class="input-field col s12">
                                    <input id="password" type="password" class="validate">
                                    <label for="password">Password</label>
                                </div>
                                <div class="input-field col s12">

                                    <select name="group">
                                        <option value="" disabled selected>Оберіть групу</option>
                                        <?php
                                    require_once 'output.php';
                                    foreach ($groups as $group) {
                                        echo '<option value="' . $group['id'] . '">' . $group['group_name'] . '</option>';
                                    }
                                    ?>
                                    </select>
                                    <label for="group">Select your group</label>
                                </div>
                                <button class="waves-effect waves-light btn" id="btn_login" name="btn_login" type="submit">Enter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="login_block" class="login" hidden="true">
                    <div class="row">

                        <form name="loginForm" class="col s12" action="functions.php" method="post">
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="email" name="sign_in_email" type="email" class="validate" required>
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="password" name="sign_in_password" type="password" class="validate" required>
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <button class="waves-effect waves-light btn" id="btn_login" name="btn_login" type="submit">Enter</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="side">
                <div class="fade"></div>
            </div>


            <script>
                $('#get_form').click(function () {
                    $('#info_block').hide()
                    $('#login_block').show()
                })
                $('#btn_up').click(function () {
                    $('#login_block').hide()
                    $('#reg_block').show()
                })
                $('#btn_in').click(function () {
                    $('#reg_block').hide()
                    $('#login_block').show()

                })
            </script>

        </div>
        <?
require_once 'footer.php';