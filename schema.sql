CREATE DATABASE readme
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;
USE readme;

CREATE TABLE users(
	id INT PRIMARY KEY AUTO_INCREMENT,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email VARCHAR(128) NOT NULL UNIQUE,
	password VARCHAR (128),
	avatar VARCHAR (128)
)
COMMENT='Таблица пользователей';

CREATE UNIQUE INDEX users.emails ON users(email);

CREATE TABLE hashtag(
	id INT PRIMARY KEY AUTO_INCREMENT,
	hashtag VARCHAR(64) NOT NULL UNIQUE
)
COMMENT='Таблица хештегов';

CREATE TABLE content_type(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(16) NOT NULL UNIQUE,
	class VARCHAR(16) NOT NULL UNIQUE
}
COMMENT='Таблица типов контента';

CREATE TABLE messages(
	id INT PRIMARY KEY AUTO_INCREMENT,
	message TEXT,
	sender_id INT,
	recepient_id INT,
	FOREIGN KEY (sender_id)
		REFERENCES users(id)
		ON UPDATE CASCADE,
	FOREIGN KEY (recepient_id)
		REFERENCES users(id)
		ON UPDATE CASCADE,
	PRIMARY KEY (sender_id, recepient_id)
)
COMMENT='Таблица личных сообщений';

CREATE UNIQUE INDEX communication ON messages(sender_id, recepient_id);

CREATE TABLE post(
	id INT PRIMARY KEY AUTO_INCREMENT,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	text TEXT;
	author VARCHAR(128),
	image_url VARCHAR(128),
	video_url VARCHAR(32),
	site_url VARCHAR(128),
	num_views INT,
	user_id INT,
	content_type_id INT,
	FOREIGN KEY (user_id)
		REFERENCES users(id)
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	FOREIGN KEY (content_type_id)
		REFERENCES content_type(id)
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
)
COMMENT='Таблица постов -- связь с хештегами вынесена в отдельную таблицу ниже';

CREATE INDEX posts_author ON post(user_id);

CREATE TABLE post_hashtag(
	post_id INT,
	hashtag_id INT,
	FOREIGN KEY (post_id)
		REFERENCES post(id)
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	FOREIGN KEY (hashtag_id)
		REFERENCES hashtag(id)
		ON UPDATE CASCADE 
		ON DELETE CASCADE,
	PRIMARY KEY (post_id, hashtag_id)
)
COMMENT='Дополнительная таблица связей постов и хештегов';
	
CREATE TABLE comments(
	id INT PRIMARY KEY AUTO_INCREMENT,
	dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	user_id INT,
	post_id INT,
	FOREIGN KEY (user_id)
		REFERENCES user(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	FOREIGN KEY (post_id)
		REFERENCES post(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE
)
COMMENT='Таблица комментариев';

CREATE INDEX posts_comments ON comments(post_id);

CREATE TABLE subscribes(
	sourcer_id INT,
	subscriber_id INT,
	FOREIGN KEY (sourcer_id)
		REFERENCES user(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	FOREIGN KEY (subscriber_id)
		REFERENCES user(id)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	PRIMARY KEY (sourcer_id, subscriber_id)
)
COMMENT='Таблица подписчиков';

CREATE INDEX users_subscribers ON subscribes(sourcer_id);

CREATE TABLE likes(
	user_id INT,
	post_id INT,
	FOREIGN KEY (user_id)
		REFERENCES user(id)
		ON UPDATE CASCADE,
	FOREIGN KEY (post_id)
		REFERENCES post(id)
		ON UPDATE CASCADE,
	PRIMARY KEY (user_id, post_id)
)
COMMENT='Таблица лайков';

CREATE INDEX posts_likes ON likes(post_id);
