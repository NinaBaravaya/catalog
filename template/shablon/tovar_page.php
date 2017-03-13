<div id="content">
	<? if($tovar) :?>
	<h2><?=$tovar['title']?></h2>
		<div class="kat_map">
			<? if(count($krohi) == 1) :?>
				<a href="<?=SITE_URL?>">Главная</a> /
				<span><?=$krohi[0]['tovar_name'];?></span>
			<? endif;?>
		</div>

			<img alt="Image 10" src="<?=SITE_URL.UPLOAD_DIR.$tovar['img'];?>">
	<div class="cleaner h30"></div>

	<h5><strong>Описание продукта</strong></h5>
		<p><?=$tovar['text']?></p>
	<? else: ?>
		<p>Данных с такими параметрами не существует</p>
	<? endif; ?>
</div>
