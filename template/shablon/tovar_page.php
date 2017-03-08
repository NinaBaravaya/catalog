<div id="content">
	<? if($tovar) :?>

	<h2><?=$tovar['title']?></h2>


		<div class="kat_map">
			<? if(count($krohi) == 1) :?>
				<a href="<?=SITE_URL?>">Главная</a> /
				<span><?=$krohi[0]['tovar_name'];?></span>
			<? endif;?>
		</div>

		<div class="col col_13">
		<a title="Lady Shoes" href="<?=SITE_URL.VIEW?>images/product/7_1.jpg" rel="lightbox[portfolio]">
			<img alt="Image 10" src="<?=SITE_URL.UPLOAD_DIR.$tovar['img'];?>">
		</a>
	</div>

	<div class="cleaner h30"></div>

	<h5><strong>Описание продукта</strong></h5>
		<p><?=$tovar['text']?></p>
	<? else: ?>
		<p>Данных с такими параметрами не существует</p>
	<? endif; ?>

	<div class="cleaner h50"></div>

	<h4>Other Products</h4>
	<div class="col col_14 product_gallery">
		<a href="productdetail.html"><img alt="Product 01" src="images/product/01.jpg"></a>
		<h3>Ut eu feugiat</h3>
		<p class="product_price">$ 100</p>
		<a class="add_to_cart" href="shoppingcart.html">Add to Cart</a>
	</div>
	<div class="col col_14 product_gallery">
		<a href="productdetail.html"><img alt="Product 02" src="images/product/02.jpg"></a>
		<h3>Curabitur et turpis</h3>
		<p class="product_price">$ 200</p>
		<a class="add_to_cart" href="shoppingcart.html">Add to Cart</a>
	</div>
	<div class="col col_14 product_gallery no_margin_right">
		<a href="productdetail.html"><img alt="Product 03" src="images/product/03.jpg"></a>
		<h3>Mauris consectetur</h3>
		<p class="product_price">$ 120</p>
		<a class="add_to_cart" href="shoppingcart.html">Add to Cart</a>
	</div>
	<a class="more float_r" href="#">View all</a>

	<div class="cleaner"></div>
</div>
