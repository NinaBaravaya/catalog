# catalog
Каталог бытовой техники

Инсталляция:
1.Создать в phpMyAdmin бд с именем catalog, collation utf8_general_ci, импортировать содержимое catalog.sql.
2.Распаковать папку catalog.7z и скопировать ее в корневую директорию локального сервера.

Авторизация для администратора
Логин: admin
Пароль: 12345

Принцип работы с каталогом (пользоват.часть)
На главной странице сайта выводятся все товары, содержащиеся в бд
При наведении на аккордеон в левой части страницы можно выбрать товары родительской категории(все типы), либо дочерней.
При клике на товар, открывается страница с его изображением и описанием.

Принцип работы с каталогом (админ панель):
Для перехода к странице авторизации администратора необходимо кликнуть по ссылке вход для администратора в шапке сайта.
Ввести в поле логин: admin, в поле пароль: 12345, нажать «вход».
Примечание:
Если три раза (в течении 24 часов) неверно ввести логин или пароль ip адрес пользователя будет заблокирован на сутки.
Если администратор не совершает какой-либо активности на сайте в течение 10 минут, он перебрасывается на страницу авторизации.

РЕДАКТИРОВАНИЕ КАТАЛОГА
Создание новой категории
Чтобы создать новую категорию нажмите на «новая категория», в шапке либо левой части страницы.
Добавьте название категории в соответствующее поле и выберите уровень данной категории (родительская либо дочерняя к существующей родительской).
Нажмите сохранить.
Теперь созданная категория появилась в списке категорий в левой части сайта.

Редактирование категории
Выберите категорию для редактирования из списка в левой части сайта, нажмите «изменить категорию», далее внесите изменения в название категории и ее уровень (родительская либо дочерняя к существующей родительской). Нажмите «обновить».
Удаление категории
Выберите категорию для редактирования из списка в левой части сайта, нажмите «удалить категорию». 
Примечание: все товары, принадлежавшие удаленной категории, теперь находятся в категории «Без категории».

Добавление нового товара
Выберите категорию для нового товара из списка в левой части сайта, нажмите «добавить продукт категорию», далее заполните поля. 
Примечание: поле «название», «краткое описание», «полное описание», «цена» являются обязательными для заполнения. Картинка для товара может быть формата .png либо .jpeg. Нажмите «сохранить».

Редактирование товара
Выберите категорию из списка в левой части сайта, выберите товар и нажмите, далее внесите необходимые изменения в поля. 
Примечание: поле «название», «краткое описание», «полное описание», «цена» являются обязательными для заполнения. Картинка для товара может быть формата .png либо .jpeg. Нажмите «обновить».

Удаление товара
Выберите категорию из списка в левой части сайта, выберите товар и нажмите «удалить».
