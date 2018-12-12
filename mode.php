<div class="container">
    <div class="row">
        <div id="tabs_office" align="center">
            <ul class="tabs">
                <li class="tab col l3">
                    <a href="#yours">Yours Themes</a>
                </li>
                <li class="tab col l3">
                    <a href="#request">Request</a>
                </li>
                <li class="tab col l3">
                    <a href="#allThemes">All Themes</a>
                </li>
                <li class="tab col l3">
                    <a href="#addNewTheme">+ New Theme</a>
                </li>
            </ul>


            <div id="yours" role="tabpanel active" class="col l12">
                '.loadYours().'
            </div>
            <div id="request" role="tabpanel" class="col l12">
                '.loadRequests().'
            </div>
            <div id="allThemes" role="tabpanel" class="col l12">
                '.loadAllThemes().'
            </div>
            <div id="addNewTheme" role="tabpanel" class="col l12">

                <form class="col s12" action="?task=add" method="post">
                    <div class="row" align="center">
                        <div class="addUser">
                            <div class="input-field col s6">
                                <input name="themeName" type="text" class="validate">
                                <label for="themeName">Theme Name</label>
                            </div>
                            <div class="input-field col s12">
                                <select name="themeType">
                                    <option disabled selected>Оберіть тип проекту</option>
                                    <option value="course">Курсова робота</option>
                                    <option value="course_prj">Курсовий проект</option>
                                    <option value="diploma">Дипломна робота</option>
                                    <option value="diploma_prj">Дипломний проект</option>
                                </select>
                                <label for="role">Select your group</label>
                            </div>
                            <div class="row">
                                <div class="input-field col l12">
                                    <label for="groupSelect">Для яких груп</label>
                                    <select id="groupSelect" multiple name="group[]">
                                        '.getGroupOption().'
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col l12">
                                    <button class="waves-effect waves-light btn" type="submit" id="add" name="add">Add User</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

            <div id="profil_form" hidden="true"></div>

        </div>
    </div>
</div>