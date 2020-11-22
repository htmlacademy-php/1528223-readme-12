<?php

$is_auth = rand(0, 1);

$user_name = 'Максим'; // укажите здесь ваше имя

// подключаем файл с функциями
include ('helpers.php');

// создаём двумерный массив для списка постов
$popular_posts = [
		[	'header' 	=> 'Цитата',
			'type'		=> 'post-quote',
			'content'	=> 'Мы в жизни любим только раз, а после ищем лишь похожих',
			'username'	=> 'Лариса',
			'avatar'	=> 'userpic-larisa-small.jpg'
		],
		[	'header' 	=> 'Игра престолов',
			'type'		=> 'post-text',
			'content'	=> 'Примерная структура маркетингового исследования разнородно переворачивает эксклюзивный рекламный клаттер, учитывая современные тенденции. Правда, специалисты отмечают, что точечное воздействие уравновешивает медиабизнес. Организация практического взаимодействия программирует конструктивный мониторинг активности. PR усиливает диктат потребителя. Бизнес-стратегия изящно масштабирует сублимированный креатив, используя опыт предыдущих кампаний. Как предсказывают футурологи ценовая стратегия интуитивно синхронизирует конвергентный product placement.',
			'username'	=> 'Владик',
			'avatar'	=> 'userpic.jpg'
		],
		[	'header' 	=> 'Наконец, обработал фотки!',
			'type'		=> 'post-photo',
			'content'	=> 'rock-medium.jpg',
			'username'	=> 'Виктор',
			'avatar'	=> 'userpic-mark.jpg'
		],
		[	'header' 	=> 'Моя мечта',
			'type'		=> 'post-photo',
			'content'	=> 'coast-medium.jpg',
			'username'	=> 'Лариса',
			'avatar'	=> 'userpic-larisa-small.jpg'
		],
		[	'header' 	=> 'Лучшие курсы',
			'type'		=> 'post-link',
			'content'	=> 'www.htmlacademy.ru',
			'username'	=> 'Владик',
			'avatar'	=> 'userpic.jpg'
		]
	];


// защита от XSS-атак
foreach ($popular_posts as $array_key => $array_value) {
	foreach ($array_value as $key => $value) {
		$popular_posts[$array_key][$key] = htmlspecialchars($value);
	}
}

// установка временной зоны по умолчанию
date_default_timezone_set('Europe/Moscow');

// добавляем случайные даты в двумерный массив списка постов с помощью функции generate_random_date
$index = 0;
foreach ($popular_posts as $array_key => $array_value) {
	$popular_posts[$array_key]['datetime'] = generate_random_date($index);
	$index += 1;
}

// обрабатываем даты в списке постов, чтобы получить дату в формате “дд.мм.гггг чч:мм” и относительную дату, затем добавляем их в массив
foreach ($popular_posts as $array_key => $array_value) {
	foreach ($array_value as $key) {
		// приводим даты к формату “дд.мм.гггг чч:мм”
		$popular_posts[$array_key]['datetime_format'] = date('d.m.Y H:i', strtotime($popular_posts[$array_key]['datetime']));
		// приводим даты к относительному виду
		$date_diff = strtotime(date('d.m.Y H:i')) - strtotime($popular_posts[$array_key]['datetime']);
		if (ceil($date_diff / 60) < 60) {
			$num = ceil($date_diff / 60);
			$popular_posts[$array_key]['datetime_relative'] = $num . ' ' . get_noun_plural_form ($num, 'минута', 'минуты', 'минут') . ' назад';
		} elseif (ceil($date_diff / 60) >= 60 && ceil($date_diff / 60) < 60*24) {
			$num = ceil($date_diff / (60*60));
			$popular_posts[$array_key]['datetime_relative'] = $num . ' ' . get_noun_plural_form ($num, 'час', 'часа', 'часов') . ' назад';
		} elseif (ceil($date_diff / 60/60) >= 24 && ceil($date_diff / 60/60) < 24*7) {
			$num = ceil($date_diff / (60*60*24));
			$popular_posts[$array_key]['datetime_relative'] = $num . ' ' . get_noun_plural_form ($num, 'день', 'дня', 'дней') . ' назад';
		} elseif (ceil($date_diff / 60/60/24) >= 7 && ceil($date_diff / 60/60/24) < 7*5) {
			$num = ceil($date_diff / (60*60*24*7));
			$popular_posts[$array_key]['datetime_relative'] = $num . ' ' . get_noun_plural_form ($num, 'неделя', 'недели', 'недель') . ' назад';
		} else {
			$num = ceil($date_diff / (60*60*24*7*5));
			$popular_posts[$array_key]['datetime_relative'] = $num . ' ' . get_noun_plural_form ($num, 'месяц', 'месяца', 'месяцев') . ' назад';
		}
	}
}

// HTML-код главной страницы
$page_content = include_template('main.php', ['popular_posts' => $popular_posts]);

// окончательный HTML-код
$layout_content = include_template('layout.php', ['content' => $page_content, 'title' => 'readme: популярное', 'is_auth' => $is_auth, 'user_name' => $user_name]);

print($layout_content);

?>
