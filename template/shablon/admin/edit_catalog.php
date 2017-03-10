<div id="content">
	<? if($option == 'view') :?>
		<h2>
			Редактирование каталога
		</h2>

		<? if($category) :?>
			<div class="button-catalog-adm">
				<a href="<?=SITE_URL;?>editcatalog/option/add/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/add_produkt.jpg" alt="Добавить продукт в категорию" /></a>
				<a href="<?=SITE_URL;?>editcategory/option/edit/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/change_cat.jpg" alt="Изменить категорию" /></a>
				<a href="<?=SITE_URL;?>editcategory/option/delete/id/<?=$category;?>"><img src="<?=SITE_URL.VIEW;?>admin/images/del_cat.jpg" alt="Удалить категорию" /></a>
			</div>
		<? endif;?>
	<p><?=$mes;?></p>
		<? if($goods) :?>
			<?
			$i = 1;
			?>
			<? foreach($goods as $item) :?>
				<div class="product-cat-main">
					<? if($i == 3) :?>
					<div class="col col_14 product_gallery no_margin_right">
						<? $i = 0;?>
						<? else :?>
						<div class="col col_14 product_gallery">
							<? endif;?>
						<p><?=$item['title']?></p>
						<img src="<?=SITE_URL.UPLOAD_DIR.$item['img'];?>" alt="<?=$ietm['title']?>" />
						<p>
							<a href="<?=SITE_URL;?>editcatalog/option/edit/tovar/<?=$item['tovar_id']?>">
								Изменить
							</a>  |
							<a href="<?=SITE_URL;?>editcatalog/option/delete/tovar/<?=$item['tovar_id']?><?=$previous;?>">
								Удалить
							</a>
						</p>
					</div>

				</div>
				<? $i++;?>
			<? endforeach;?>

			<? if($navigation) :?>
				<ul class="pager">
					<? if($navigation['first']) :?>
						<li class="first">
							<a href="<?=SITE_URL;?>editcatalog/page/1<?=$previous;?>">Первая</a>
						</li>
					<? endif; ?>

					<? if($navigation['last_page']) :?>
						<li>
							<a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['last_page']?><?=$previous;?>">&lt;</a>
						</li>
					<? endif; ?>

					<? if($navigation['previous']) :?>
						<? foreach($navigation['previous'] as $val) :?>
							<li>
								<a href="<?=SITE_URL;?>editcatalog/page/<?=$val;?><?=$previous;?>"><?=$val;?></a>
							</li>
						<? endforeach; ?>
					<? endif; ?>

					<? if($navigation['current']) :?>
						<li>
							<span><?=$navigation['current'];?></span>
						</li>
					<? endif; ?>

					<? if($navigation['next']) :?>
						<? foreach($navigation['next'] as $v) :?>
							<li>
								<a href="<?=SITE_URL;?>editcatalog/page/<?=$v;?><?=$previous;?>"><?=$v;?></a>
							</li>
						<? endforeach; ?>
					<? endif; ?>
					<? if($navigation['next_pages']) :?>
						<li>
							<a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['next_pages']?><?=$previous;?>">&gt;</a>
						</li>
					<? endif; ?>

					<? if($navigation['end']) :?>
						<li class="last">
							<a href="<?=SITE_URL;?>editcatalog/page/<?=$navigation['end']?><?=$previous;?>">Последняя</a>
						</li>
					<? endif; ?>
				</ul>
			<? endif;?>


		<? elseif($category && !$goods) :?>
			<p>Товаров нет</p>
		<? else :?>
			<p>Выберите категорию товара</p>
		<? endif;?>

	<? elseif($option == 'add') :?>
		<h1>
			Добавление нового товара
		</h1>
		<p><?=$mes;?></p>

		<!--FORM ADD-->
		<form enctype="multipart/form-data" action="<?=SITE_URL;?>editcatalog/option/add/id/<?=$category?>" method="POST">
			<p><span>Название: &nbsp;
			</span><input class="txt-zag" type="text" name="title"></p><br/>
			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>картинка анонса:
			</span><input class="txt-zag" type="file" value="" name="img"><br/><br/>
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15"></textarea><br /><br />

			<p><span>Полное описание:</span></p>
			<textarea name="text" cols="60" rows="15"></textarea><br /><br />

			<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords"></p><br/>
			<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" name="description"></p><br/>

			<p>Публиковать товар:<br />
				<input type="radio" name="publish" value="1" checked>Да
				<input type="radio" name="publish" value="0">Нет</p>
			<br/>
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" name="price"></p>
			<br/>
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="submit_add_cat">

		</form>
		<!--FORM ADD-->
	<? elseif($option == 'edit') :?>

		<h1>
			Изменение товара - <?=$tovar['title'];?>
		</h1>
		<p><?=$mes;?></p>

		<!--FORM ADD-->
		<form enctype="multipart/form-data" action="<?=SITE_URL;?>editcatalog/option/edit" method="POST">
			<p><span>Название: &nbsp;
					<input type="hidden" name="id" value="<?=$tovar['tovar_id']?>">
			</span><input class="txt-zag" type="text" name="title" value="<?=$tovar['title'];?>"></p><br/>

			<input type="hidden" name="MAX_FILE_SIZE" value="2097152">
			<p><span>картинка анонса:
			</span><input class="txt-zag" type="file" value="" name="img"><br/><br/>
			<p><span>Краткое описание:</span></p>
			<textarea name="anons" cols="60" rows="15"><?=$tovar['anons']?></textarea><br /><br />

			<p><span>Полное описание:</span></p>
			<textarea name="text" cols="60" rows="15"><?=$tovar['text']?></textarea><br /><br />

			<p><span>Ключевые слова: &nbsp;
						</span><input class="txt-zag" type="text" name="keywords" value="<?=$tovar['keywords']?>"></p><br/>
			<p><span>Описание: &nbsp;
						</span><input class="txt-zag" type="text" value="<?=$tovar['description']?>" name="description"></p><br/>
			<p><span>Выберите категорию:</span></p>
			<? if($brands) :?>
				<select name="category">
					<? if($tovar['brand_id'] == 0) :?>
						<option selected value="0">Без категории</option>
					<? endif;?>

					<? foreach($brands as $key => $item) :?>
						<? if($key == $tovar['brand_id']) :?>
							<option selected value="<?=$key;?>"><?=$item[0];?></option>
						<? else :?>
							<option value="<?=$key;?>"><?=$item[0];?></option>
						<? endif;?>

						<? if($item['next_lvl']) :?>
							<? foreach($item['next_lvl'] as $k => $val) :?>
								<? if($k == $tovar['brand_id']) :?>
									<option selected value="<?=$k;?>">--<?=$val;?></option>
								<? else :?>
									<option  value="<?=$k;?>">--<?=$val;?></option>
								<? endif;?>
							<? endforeach;?>
						<? endif?>

					<? endforeach;?>
				</select>
			<? else :?>
				<p>Категорий нет</p>
			<? endif;?><br/><br/>
			<p>Публиковать товар:<br />
				<? if($tovar['publish'] === '1') :?>
				<input type="radio" name="publish" value="1" checked>Да
				<input type="radio" name="publish" value="0">Нет</p>
		<? else :?>
			<input type="radio" name="publish" value="1">Да
			<input type="radio" name="publish" value="0" checked>Нет</p>
		<? endif;?>

			<br/>
			<p><span>Цена: &nbsp;
			</span><input class="txt-zag" type="text" value="<?=$tovar['price']?>" name="price"></p>
			<br/>

			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_add_cat">

		</form>
		<!--FORM ADD-->
	<? endif;?>
</div>

