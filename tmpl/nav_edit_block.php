
<!-- <div class="navbar-fixed"> -->
    <nav>
        <div class="nav-wrapper">
            <div class="row">
                <div class="col l12 m12 s12">
                    <ul class="right hide-on-med-and-down">
                        <li>
                            <a href="logout.php" id="logout">LogOut</a>
                        </li>
                    </ul>
                    <a href="#" class="brand-logo center">CursachMaker</a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse">
                        <i class="material-icons">menu</i>
                    </a>
                    <ul class="left hide-on-med-and-down">
                        <li>
                            <a href="#" id="news">Home Page</a>
                        </li>    
                        <li>
                            <a href="dashboard_admin.php" id="profile">Back</a>
                        </li>
                    </ul>
                    
                    <ul class="side-nav" id="mobile-demo">
                        <li>
                            <a href="#" id="m_news">Home Page</a>
                        </li>    
                        <li>
                            <a href="<?='dashboard_' . $_SESSION['user']['role'] . '.php'?>" id="profile">Back</a>
                        </li>
                        <li>
                            <a href="logout.php" id="logout">LogOut</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <script>
        
        $('#m_office').click(function () {
            $('#tabs_office').show()
            $('#profil_form').hide()
            $('#edit_form').hide()
            $('#news_form').hide()
        })

        $('#m_profil').click(function () {
            $('#profil_form').show()
            $('#tabs_office').hide()
            $('#edit_form').hide()
            $('#news_form').hide()
        })

        $('#m_news').click(function () {
            $('#news_form').show()
            $('#tabs_office').hide()
            $('#edit_form').hide()
            $('#profil_form').hide()
        })
    </script>
<!-- </div> -->