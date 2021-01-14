-- вставить список типов контента для поста
INSERT INTO content_type (name, class) VALUES
	('Текст', 'text'),
	('Цитата', 'quote'),
	('Картинка', 'photo'),
	('Видео', 'video'),
	('Ссылка', 'link');

-- вставить пользователей
INSERT INTO users (email, avatar, username) VALUES
	('a@mail.ru', 'elvira.jpg', 'Эльвира Хайпулинова'),
	('b@mail.ru', 'tanya.jpg', 'Таня Фирсова'),
	('c@mail.ru', 'petro.jpg', 'Петр Демин'),
	('d@mail.ru', 'mark.jpg', 'Марк Смолов'),
	('e@mail.ru', 'larisa.jpg', 'Лариса Роговая');

-- вставить существующий список постов
INSERT INTO posts (user_id, content_type_id, header, text, author, image_url, video_url, site_url) VALUES
	(1, 3, 'Наконец, обработала фотки!', '', '', '/img/rock.jpg', '', ''),
	(2, 1, 'Полезный пост про Байкал', 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.', '', '', '', ''),
	(3, 4, '', '', '', '', 'https://youtu.be/LqB2pZXEfO4', ''),
	(4, 2, '', 'Тысячи людей живут без любви, но никто — без воды.', 'Xью Оден', '', '', ''),
	(5, 5, 'Стоматология «Вита»', 'Семейная стоматология в Адлере', '', '', '', 'www.vitadental.ru');

-- вставить два комментария к разным постам
INSERT INTO comments (user_id, post_id, content) VALUES
	(2, 1, 'Красота!'),
	(1, 2, 'Была там этим летом!');

-- получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT
	posts.num_views as views,
	posts.id as post_id,
	users.username as username,
	content_type.name as content_type
FROM posts
LEFT JOIN users
	ON posts.user_id = users.id
LEFT JOIN content_type
	ON posts.content_type_id = content_type.id
ORDER BY num_views DESC;

-- получить список постов для конкретного пользователя
SELECT id
FROM posts
WHERE user_id = 1;

-- получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT
	comments.id as comments_id,
	users.username as username
FROM comments
LEFT JOIN users
	ON comments.user_id = users.id
WHERE comments.post_id = 1;

-- добавить лайк к посту
INSERT INTO likes (user_id, post_id)
	VALUES (1, 2);

-- подписаться на пользователя
INSERT INTO subscribes (sourcer_id, subscriber_id)
	VALUES (1, 2);
