<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

<title><?=$title;?></title>
</head>

<body id="home">
<div id="templatemo_wrapper">
	<div id="templatemo_header">

		<div id="site_title"><h1><a href="<?=SITE_URL;?>">бытовой техники</a></h1></div>
		<ul class="adm_menu">
			<li><a href="<?=SITE_URL;?>editcategory">Новая категория</a></li>
			<li><a href="<?=SITE_URL;?>editcatalog">Редактирование каталога</a></li>
			<li><a href="<?=SITE_URL?>login/logout/1">Выйти из админ.панели</a></li>
		</ul>

		</div><!-- END of header -->

