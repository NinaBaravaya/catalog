<div id="sidebar">
	<h1>
		Категории
	</h1>

	<p><a href="<?=SITE_URL;?>editcategory"><strong>Новая категория</strong></a></p>
	<br />
	<p><a href="<?=SITE_URL;?>editcatalog/brand/0"><strong>Без категории</strong></a></p>
	<br />

	<? if($brands) :?>
		<ul class="sidebar_menu">
			<!--<li><a href="<?/*=SITE_URL;*/?>editcatalog/brand/0">Без категории</a></li>-->
			<? foreach($brands as $key=>$item) :?>
				<? if($item['next_lvl']) :?>
					<p>
						<a href="<?=SITE_URL;?>editcatalog/parent/<?=$key;?>">
							<?=$item[0];?>
						</a>
					</p>
							<? foreach($item['next_lvl'] as $k=>$val) :?>
								<li>
									<a href="<?=SITE_URL;?>editcatalog/brand/<?=$k?>">
										<?=$val;?>
									</a>
								</li>
							<? endforeach;?>


				<? else :?>
					<li>
						<a href="<?=SITE_URL;?>editcatalog/brand/<?=$key;?>">
							<?=$item[0];?>
						</a>
					</li>
				<? endif;?>
			<? endforeach;?>

		</ul>
	<? else :?>
		<p>Категорий нет</p>
	<? endif;?>

</div>