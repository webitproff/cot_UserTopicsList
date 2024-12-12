# cot_UserTopicsList
In the user's profile and on a separate page, displays all topics on the forum that were created by this user.

В профиле пользователя и на отдельной странице, выводит все темы(топики) на форуме, которые были созданы этим пользователем.

За основу был взят этот плагин (https://github.com/Alex300/userlatestposts), который выводит последние посты в темах, а не просто темы.

Скачать с github (https://github.com/webitproff/cot_UserTopicsList/) плагин "UserTopicsList".

Установка:

Залить в папку с плагинами, установить в админке.

в свой шаблон users.details.tpl в нужном месте добавить тег
```
			<!-- IF {PHP.cot_plugins_active.usertopicslist} -->
				{USERS_DETAILS_USERTOPICSLIST}
			<!-- ENDIF -->
```
в header.tpl или в другом нужном месте ссылку на самостоятельную страницу со списком
```
<!-- IF {PHP.cot_plugins_active.usertopicslist} -->
	<a href="{PHP|cot_url('usertopicslist')}">{PHP.L.usertopicslist_meta_title}</a>
<!-- ENDIF -->
```
Бета-версия.
