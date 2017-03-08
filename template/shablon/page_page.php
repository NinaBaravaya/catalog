<div id="content">
	<? if($page) :?>
	<h2>
		<?=$page['title']?>
	</h2>
		<p><?=$page['text']?></p>
	<ul class="tmo_list">
		<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
		<li>Pellentesque quis nulla id orci malesuada porta posuere quis massa.</li>
		<li>Nunc vitae purus non augue scelerisque ultricies vitae et velit quis.</li>
		<li>Aliquam fermentum cursus risus aliquam erat volutpat.</li>
		<li>Morbi a augue eget orci sodales blandit morbiet mi in mi eleifend adipiscing.</li>
	</ul>
	<div class="cleaner h20"></div>
	<h3>Praesent pede massa, feugiat auctor</h3>
	<p>Nam nec leo. Curabitur quis eros a arcu feugiat egestas. Nunc sagittis, dui non porttitor tincidunt, mi tortor tincidunt sem, et aliquet mi tortor eu turpis. Ut nisi ligula, viverra ac, placerat sed, ultricies vitae, neque. Morbi feugiat neque non odio eleifend pulvinar.</p>
	<div class="cleaner"></div>
	<blockquote>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque quis nulla id orci malesuada porta posuere quis massa. Nunc vitae purus non augue scelerisque ultricies vitae et velit quis nulla id orci malesua.
	</blockquote>
	<? else: ?>
		<p>Данных с такими параметрами не существует</p>
	<? endif; ?>
</div>