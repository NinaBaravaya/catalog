<div id="content">

	<? if($option == 'add') :?>
		<h2>
			Добавление категории
		</h2>
		<p><?=$mes;?></p>
		<form action="<?=SITE_URL;?>editcategory/option/add" method="POST">
			<p><span>Название категории:</span>
				<input class="txt_zag" type="text" name="title">
			</p>
			<br/>
			<p><span>Родительская категория:</span>
				<select name="parent">
					<option value="0">Родительская</option>
					<? if($parents_cat) :?>
						<? foreach($parents_cat as $item) :?>
							<option value="<?=$item['brand_id']?>"><?=$item['brand_name']?></option>
						<? endforeach;?>
					<? endif;?>
				</select>
			</p>
			<br/>
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/save_btn.jpg" name="submit_add_cat">
		</form>

	<? elseif($option == 'edit' && $category) :?>
		<h2>
			Редактирование категории - <?=$category['brand_name']?>
		</h2>
		<p><?=$mes;?></p>
		<form action="<?=SITE_URL;?>editcategory/option/edit" method="POST">
			<p><span>Название категории:</span>
				<input class="txt_zag" type="text" name="title" value="<?=$category['brand_name'];?>">
			</p>
			<input type="hidden" name="id" value="<?=$category['brand_id']?>">
<br/>
			<p><span>Родительская категория:</span>
				<select name="parent">
					<option value="0">Родительская</option>
					<? if($parents_cat) :?>
						<? foreach($parents_cat as $item) :?>
							<? if($item['brand_id'] == $category['parent_id']):?>
								<option selected value="<?=$item['brand_id']?>"><?=$item['brand_name']?></option>
							<? else :?>
								<option value="<?=$item['brand_id']?>"><?=$item['brand_name']?></option>
							<? endif;?>

						<? endforeach;?>
					<? endif;?>
				</select>
			</p>
			<br/>
			<input type="image" src="<?=SITE_URL.VIEW;?>admin/images/update_btn.jpg" name="submit_edit_cat">
		</form>

	<? endif;?>

</div>

