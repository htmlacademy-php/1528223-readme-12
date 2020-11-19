<?php

$is_auth = rand(0, 1);

$user_name = 'Максим'; // укажите здесь ваше имя

include ('helpers.php');

// создаём двумерный массив для списка постов
$popular_posts = [
		[	"header" 	=> "Цитата",
			"type"		=> "post-quote",
			"content"	=> "Мы в жизни любим только раз, а после ищем лишь похожих",
			"username"	=> "Лариса",
			"avatar"	=> "userpic-larisa-small.jpg"
		],
		[	"header" 	=> "Игра престолов",
			"type"		=> "post-text",
			"content"	=> "Примерная структура маркетингового исследования разнородно переворачивает эксклюзивный рекламный клаттер, учитывая современные тенденции. Правда, специалисты отмечают, что точечное воздействие уравновешивает медиабизнес. Организация практического взаимодействия программирует конструктивный мониторинг активности. PR усиливает диктат потребителя. Бизнес-стратегия изящно масштабирует сублимированный креатив, используя опыт предыдущих кампаний. Как предсказывают футурологи ценовая стратегия интуитивно синхронизирует конвергентный product placement.",
			"username"	=> "Владик",
			"avatar"	=> "userpic.jpg"
		],
		[	"header" 	=> "Наконец, обработал фотки!",
			"type"		=> "post-photo",
			"content"	=> "rock-medium.jpg",
			"username"	=> "Виктор",
			"avatar"	=> "userpic-mark.jpg"
		],
		[	"header" 	=> "Моя мечта",
			"type"		=> "post-photo",
			"content"	=> "coast-medium.jpg",
			"username"	=> "Лариса",
			"avatar"	=> "userpic-larisa-small.jpg"
		],
		[	"header" 	=> "Лучшие курсы",
			"type"		=> "post-link",
			"content"	=> "www.htmlacademy.ru",
			"username"	=> "Владик",
			"avatar"	=> "userpic.jpg"
		]
	];
	
// защита от XSS-атак
foreach ($popular_posts as $array_key => $array_value) {
	foreach ($array_value as $key => $value) {
		$popular_posts[$array_key][$key] = htmlspecialchars($value);
	}
}

// HTML-код главной страницы
$page_content = include_template('main.php', ['popular_posts' => $popular_posts]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout_content);

?>
