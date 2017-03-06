<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
        <title><?php echo SITE_NAME ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet">
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet" href="http://getbootstrap.com/examples/starter-template/starter-template.css"/>
    <link rel="stylesheet" href="https://bootswatch.com/united/bootstrap.css"/>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
<div class="container">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/"><?php echo SITE_NAME ?></a>
        <?php if (isset($_SESSION['isLogged'])) { if ($_SESSION['isLogged'] == 1) { if($data['in_game'][0]['in_game'] == 1){ ?>
            <a class="navbar-brand" href="/game/outputroom/">Выйти из комнаты</a>
        <?php }}} ?>
    </div>
    <?php  if (isset($_SESSION['isLogged'])) { if ($_SESSION['isLogged'] == 1) { if($data['in_game'][0]['in_game'] != 1){ ?>
        <form class="navbar-form navbar-left" method="post" action="/game/createroom/">
            <div class="form-group">
                <input name="room" type="text" class="form-control" placeholder="Название Комнаты">
            </div>
            <button type="submit" class="btn btn-default">Создать комнату</button>
        </form>
    <?php }}} ?>

    <div id="navbar" class="collapse navbar-collapse">

        <ul class="nav navbar-nav navbar-right">
            <?php if (isset($_SESSION['isLogged'])) { if ($_SESSION['isLogged'] == 1) { ?>
                <li><a class="navbar-brand" href="/user/profile/?id=<?php echo $_SESSION['userId'];?>">Профиль</a></li>
            <?php }} ?>
            <?php if (!isset($_SESSION['isLogged'])) { ?>
                <li><a class="navbar-brand" href="/login/login/">Вход</a></li>
            <?php } ?>
            <?php if (!isset($_SESSION['isLogged'])) { ?>
                <li><a class="navbar-brand" href="/login/signup/">Регистрация</a></li>
            <?php } ?>
            <?php if (isset($_SESSION['isLogged'])) { if ($_SESSION['isLogged'] == 1) { ?>
                <li><a class="navbar-brand" href="/login/logout/">Выход</a></li>
            <?php }} ?>
        </>
    </div>
</div>
</nav>
<br>
<br>
<br>
<div align="justify">
    <div id="module" class="col-sm-2">
    Рейтинги:
    </div>
    <div id="module" class="col-sm-8">
       <?php echo $content; ?>
    </div>
    <div id="module" class="col-sm-2">
        Игроки на сайте:<br>
        <?php foreach ($data['online'] as $value){ ?>
            <a href="/user/profile/?id=<?php echo $value['id'];?>"><?php echo $value['login'];?></a><br>
        <?php } ?><br>
        <?php if (isset($_SESSION['isLogged'])) { if ($_SESSION['isLogged'] == 1) { ?>
    Войти в комнату:<br>
    <?php foreach ($data['open_room'] as $value){ ?>
        <a href="/game/enterroom/?room=<?php echo $value['room_name'];?>"><?php echo $value['room_name'];?></a><br>
    <?php }}} ?>
    </div>
</div>
</body>
</html>