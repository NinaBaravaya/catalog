<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$title;?></title>
    <meta name="description" content="<?=$description;?>" />
    <meta name="keywords" content="<?=$keywords;?>" />

    <? if($styles) :?>
        <? foreach($styles as $style) :?>
            <link rel="stylesheet" type="text/css" href="<?=$style;?>" />
        <? endforeach;?>
    <? endif; ?>

    <? if($scripts) :?>
        <? foreach($scripts as $script) :?>
            <script type="text/javascript" src="<?=$script?>"></script>
        <? endforeach;?>
    <? endif; ?>

</head>


<body id="home">

<div id="templatemo_wrapper">
    <div id="templatemo_header">
        <div id="site_title"><h1><a href="<?=SITE_URL;?>">бытовой техники</a></h1></div>

        <div id="header_right">
            <p>Вход для администратора</p><br/>
            <p>
                <? if($error):?>
                    <?=$error;?>
                <? endif;?>
            </p>
            <form action='<?=SITE_URL?>login' method='post'>
                <span>Логин:</span>
                <input type='text' name = 'name'>
                <span>Пароль:</span>
                <input type='password' name ='password'>
                <input class="submit_login" type='submit' name='submit' value ='Войти'><br/>
            </form>
        </div> <!-- END -->
    </div> <!-- END of header -->
