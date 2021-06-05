<?php

// названия всех полей кроме content и file
$fields_names = [
    'header' => [
        'input_name' => 'Заголовок',
        'placeholder' => 'Введите заголовок'
    ],
    'tags' => [
        'input_name' => 'Теги',
        'placeholder' => 'Введите заголовок'
    ],
    'author' => [
        'input_name' => 'Автор',
        'placeholder' => 'Введите заголовок'
    ]
];
$fields_header = isset($fields_names['header']['input_name']) ? $fields_names['header']['input_name'] : null;
$fields_tags = isset($fields_names['tags']['input_name']) ? $fields_names['tags']['input_name'] : null;
$fields_author = isset($fields_names['author']['input_name']) ? $fields_names['author']['input_name'] : null;

$content_names = [
    'text' => [
        'input_name' => 'Текст поста',
        'placeholder' => 'Введите текст публикации',
        'required' => true
    ],
    'photo' => [
        'input_name' => 'Ссылка из интернета',
        'placeholder' => 'Введите ссылку',
        'required' => false
    ],
    'video' => [
        'input_name' => 'Ссылка YouTube',
        'placeholder' => 'Введите ссылку',
        'required' => true
    ],
    'quote' => [
        'input_name' => 'Текст цитаты',
        'placeholder' => 'Текст цитаты',
        'required' => true
    ],
    'link' => [
        'input_name' => 'Ссылка',
        'placeholder' => 'Введите ссылку',
        'required' => true
    ]
];
$name_text = isset($content_names['text']['input_name']) ? $content_names['text']['input_name'] : null;
$name_photo = isset($content_names['photo']['input_name']) ? $content_names['photo']['input_name'] : null;
$name_video = isset($content_names['video']['input_name']) ? $content_names['video']['input_name'] : null;
$name_quote = isset($content_names['quote']['input_name']) ? $content_names['quote']['input_name'] : null;
$name_link = isset($content_names['link']['input_name']) ? $content_names['link']['input_name'] : null;

// сообщения при пустых полях
$empties = [
    'head' => 'Пустое поле', // заголовок всплывающего поля
    'message' => 'Заполните поле', // текст под заголовком
    'details' => 'Поле не заполнено' // сообщение в блоке справа
];
$empties_head = isset($empties['head']) ? $empties['head'] : null;
$empties_message = isset($empties['message']) ? $empties['message'] : null;
$empties_details = isset($empties['details']) ? $empties['details'] : null;

// тексты всех ошибок
$errors_texts = [
    'header_empty' => [ // ошибка заголовка, пустое поле
        'head' => $empties_head,
        'message' => $empties_message,
        'subhead' => $fields_header,
        'details' => $empties_details
    ],
    'tags_empty' => [ // ошибка тегов, пустое поле
        'head' => $empties_head,
        'message' => $empties_message,
        'subhead' => $fields_tags,
        'details' => $empties_details
    ],
    'author_empty' => [ // ошибка автора, пустое поле
        'head' => $empties_head,
        'message' => $empties_message,
        'subhead' => $fields_author,
        'details' => $empties_details // сделать в цикле
    ],
    'content_empty' => [ // ошибка контента, пустое поле
        'head' => $empties_head,
        'message' => $empties_message,
        'details' => $empties_details
    ],
    'content_link' => [ // ошибка контента, неверная ссылка
        'head' => 'Неверный адрес',
        'message' => 'Ссылка имеет неверный формат',
        'details' => 'Неверный формат адреса'
    ],
    'tags_no_one' => [ // ошибка тегов, не одно слово
        'head' => 'Неверный формат',
        'message' => 'Теги должны разделяться пробелами',
        'subhead' => $fields_tags,
        'details' => 'Неверный формат записи'
    ],
    'photo_file' => [ // ошибка файла фотографии
        'head' => 'Файл на загружен',
        'message' => 'По указанному адресу файла не обнаружено',
        'subhead' => $name_photo,
        'details' => 'Не удалось загрузить файл'
    ],
    'file_file' => [ // ошибка файла файла
        'head' => 'Неверный тип',
        'message' => 'Тип файла должен быть JPG, GIF или PNG',
        'subhead' => $name_photo,
        'details' => 'Неверный тип файла'
    ],
    'video_file' => [ // ошибка видео yotube
        'head' => 'Видео не найдено',
        'message' => 'Видео по такой ссылке не найдено. Проверьте ссылку на видео',
        'subhead' => $name_video,
        'details' => 'Видео не найдено'
    ]
];
$errors_header = isset($errors_texts['header_empty']) ? $errors_texts['header_empty'] : null;
$errors_tags = isset($errors_texts['tags_empty']) ? $errors_texts['tags_empty'] : null;
$errors_author = isset($errors_texts['author_empty']) ? $errors_texts['author_empty'] : null;
$errors_content = isset($errors_texts['content_empty']) ? $errors_texts['content_empty'] : null;
$errors_content_link = isset($errors_texts['content_link']) ? $errors_texts['content_link'] : null;
$errors_photo_file = isset($errors_texts['photo_file']) ? $errors_texts['photo_file'] : null;
$errors_video_file = isset($errors_texts['video_file']) ? $errors_texts['video_file'] : null;
$errors_tags_noone = isset($errors_texts['tags_no_one']) ? $errors_texts['tags_no_one'] : null;
$errors_file_file = isset($errors_texts['file_file']) ? $errors_texts['file_file'] : null;
