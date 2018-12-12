<?php
$counter = isset($_COOKIE['counter']) ? $_COOKIE['counter'] : 0;
$counter++;
setcookie("counter", $counter);
?>

      <!DOCTYPE html>
    <html>

    <head>
        
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fjalla+One|Shadows+Into+Light" rel="stylesheet">
        <link type="text/css" rel="stylesheet" href="css/materialize.min.css" media="screen,projection" />
        <!-- <link type="text/css" rel="stylesheet" href="materialize_100/css/materialize.min.css" media="screen,projection" />
        <link type="text/css" rel="stylesheet" href="materialize_100/css/materialize.min.css" media="screen,projection" /> -->
        <link type="text/css" rel="stylesheet" href="css/style.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title><?= $HEADER_TITLE ? $HEADER_TITLE : 'Deponline' ?></title>
    </head>

    <body>
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <!-- <script type="text/javascript" src="materialize_100/js/materialize.min.js"></script> -->
        <script type="text/javascript" src="js/btn_operations.js"></script>
        <script>
            $(document).ready(function () {
            $('select').material_select();
            });
        </script>