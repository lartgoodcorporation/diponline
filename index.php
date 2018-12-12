<?php

if (!empty($_SESSION['user']) && $_SESSION['user']['role'] !== null) {
    header('Location: dashboard_' . $_SESSION['user']['role'] . '.php');
    exit();
}

require_once 'header.php';

?>

    <div class="row" align="center">
        <div class="col xl12 m12 l12 s12">

            <div id="info_block" class="content">
                <h1>MyProject</h1>
                <p class="intro">
                let's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text herelet's say the text here
                </p>
                <button class="waves-effect waves-light btn" id="get_form">Enter</button>
            </div>

            <div id="logForm" class="signFrm" hidden="true" align="center">
                <div class="col xl12 m12 l12 s12">
                    <h3 class="sign">
                        <a href="#" id="btn_in">Sign In</a> |
                        <a href="#" id="btn_up">Sign Up</a>
                    </h3>
                </div>
                <div id="reg_block" hidden="true">

                    <div class="row">
                        <form class="col xl12 s10 l12 s10" action="func_regist.php" method="post">
                            <div class="row">
                                <div class="input-field col xl6 m6 l6 s5">
                                    <input name="firstname" type="text" class="validate">
                                    <label for="firstname">First Name</label>
                                </div>
                                <div class="input-field col xl6 m6 l6 s5">
                                    <input name="lastname" type="text" class="validate">
                                    <label for="lastname">Last Name</label>
                                </div>
                                <div class="input-field col xl12 m10 l12 s10">
                                    <input name="patronymic" type="text" class="validate">
                                    <label for="patronymic">Patronymic</label>
                                </div>
                                <div class="input-field col xl12 m10 l12 s10">
                                    <input name="sign_up_email" type="email" class="validate">
                                    <label for="sign_up_email">Email</label>
                                </div>
                                <div class="input-field col xl12 m10 l12 s10">
                                    <input name="sign_up_password" type="password" class="validate">
                                    <label for="sign_up_password">Password</label>
                                </div>
                                <div class="input-field col xl12 m10 l12 s10">
                                    <select name="group">
                                        <option value="" disabled selected>Select group</option>
                                        <?php
                                        require_once 'output.php';
                                        foreach ($groups as $group) {
                                            echo '<option value="' . $group['id'] . '">' . $group['group_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <label for="group">Select your group</label>
                                </div>
                                <button class="waves-effect waves-light btn" id="btn_reg" name="btn_reg" type="submit">Enter</button>
                            </div>
                        </form>
                    </div>

                </div>
                <div id="login_block" hidden="true">
                    <div class="row">

                        <form name="loginForm" class="col xl12 s10 l12 s12" action="func_login.php" method="post">
                            <div class="row">
                                <div class="input-field col xl12 s10 l12 s10">
                                    <input id="sign_in_email" name="sign_in_email" type="email" class="validate" required>
                                    <label for="sign_in_email">Email</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col xl12 s10 l12 s10">
                                    <i class="material-icons prefix" id="show" onclick="myFunction()" hidden="true">visibility</i>
                                    <i class="material-icons prefix" id="not_show" onclick="myFunction()">visibility_off</i>
                                    <input id="sign_in_password" name="sign_in_password" type="password" class="validate" required>
                                    <label for="sign_in_password">Password</label>
                                </div>
                                <div>
                                    <input type="checkbox" id="remember" />
                                    <label for="remember">Remember me</label>
                                </div>
                            </div>
                            <button class="waves-effect waves-light btn" id="btn_login" name="btn_login" type="submit">Enter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
require_once 'page_footer.php';
require_once 'footer.php';