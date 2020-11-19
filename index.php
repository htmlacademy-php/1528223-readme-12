<?php

// функция-шаблонизатор
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

// функция для сокращения текста в карточках главной страницы
function short_text($text, $num_char = 300) {
	if (mb_strlen($text, 'UTF-8') > $num_char) {
		$text_array = explode(' ', $text);
		$result = array_shift($text_array);
		foreach ($text_array as $key => $val) {
			$result .= ' ' . $val;
			$num = mb_strlen($result, 'UTF-8');
			if ($num >= $num_char) {
				return $result  . '... <a class="post-text__more-link" href="#">Читать далее</a>';
			}
		}
	}
	return $text;	
}

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
			"content"	=> "<script>alert('Evil')</script> Примерная структура маркетингового исследования разнородно переворачивает эксклюзивный рекламный клаттер, учитывая современные тенденции. Правда, специалисты отмечают, что точечное воздействие уравновешивает медиабизнес. Организация практического взаимодействия программирует конструктивный мониторинг активности. PR усиливает диктат потребителя. Бизнес-стратегия изящно масштабирует сублимированный креатив, используя опыт предыдущих кампаний. Как предсказывают футурологи ценовая стратегия интуитивно синхронизирует конвергентный product placement.",
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
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное']);

print($layout_content);

?>
