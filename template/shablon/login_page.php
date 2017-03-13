<div class="content autoriz">
	<h3>
		Авторизация для администратора
	</h3>
	<p>
	<? if($error):?>
		<?=$error;?>
	<? endif;?>
	</p>
	<form action='<?=SITE_URL?>login' method='post'>
		<span>Логин:</span><br/>
		<input type='text' name = 'name'><br/>
		<span>Пароль:</span><br/>
		<input type='password' name ='password'><br/><br/>
		
		<input class="submit_login" type='submit' name='submit' value ='Войти'><br/>
	</form>
	
</div>