<?php

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественного числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
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

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);

    if (!is_array($headers)) {
        return FALSE;
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return TRUE;
    }

    return TRUE;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * Сокращает текст до нужного количества символов
 * @param string $text Сокращаемый текст
 * @param integer $num_char Число символов, до которого нужно сократить
 * текст. Ограничение: только целые числа.
 *
 * @return string Сокращённый текст
*/
function short_text($text, $num_char = 300)
{
    if (mb_strlen($text, 'UTF-8') > $num_char) {
        $text_array = explode(' ', $text);
        $result = array_shift($text_array);
        foreach ($text_array as $word) {
            $result .= ' ' . $word;
            $num = mb_strlen($result, 'UTF-8');
            if ($num >= $num_char) {
                return $result . '...';
            }
        }
    }
    return $text;
}

/**
 * Приводит дату к формату “дд.мм.гггг чч:мм” или "чч:мм"
 * @param string $datetime Данные, получаемые из типа данных MySQL
 * TIMESTAMP "гггг-мм-дд чч:мм:сс"
 * @param string $format Если равен "date", то данные переводятся в
 * вид "дд.мм.гггг чч:мм", если другое значение, то в вид "чч:мм"
 *
 * @return string дата или время
*/
function datetime_format($datetime, $format = 'date')
{
    $datetime = ($format === 'date') ? date('d.m.Y H:i', strtotime($datetime)) : date('H:i', strtotime($datetime));
    return $datetime;
}

/**
 * Показывает прошедшее время между датой и настоящим моментом
 * Если прошло меньше года, то показывает месяцы, если меньше месяца, то
 * недели, если меньше недели, то дни, если меньше суток, то часы, если
 * меньше часа, то минуты, если меньше минуты, то "менее 1 минуты"
 *
 * @param string $datetime Данные, получаемые из типа данных MySQL
 * TIMESTAMP "гггг-мм-дд чч:мм:сс"
 *
 * @param string Число с размерными единицами
*/
function datetime_relative($datetime)
{
    $date_now = date_create('now');
    $date_earlier = date_create($datetime);
    $date_diff = date_diff($date_now, $date_earlier);

    $years = date_interval_format($date_diff, '%y');
    if ($years > 0) {
        return $years . ' ' . get_noun_plural_form($years, 'год', 'года', 'лет');
    }
    $months = date_interval_format($date_diff, '%m');
    if ($months > 0) {
        return $months . ' ' . get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев');
    }
    $weeks = ceil(date_interval_format($date_diff, '%d') / 7);
    if ($weeks > 0) {
        return $weeks . ' ' . get_noun_plural_form($weeks, 'неделя', 'недели', 'недель');
    }
    $days = date_interval_format($date_diff, '%d');
    if ($days > 0) {
        return $days . ' ' . get_noun_plural_form($days, 'день', 'дня', 'дней');
    }
    $hours = date_interval_format($date_diff, '%h');
    if ($hours > 0) {
        return $hours . ' ' . get_noun_plural_form($hours, 'час', 'часа', 'часов');
    }
    $minutes = date_interval_format($date_diff, '%i');
    if ($minutes > 0) {
        return $minutes . ' ' . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут');
    }
    return 'менее 1 минуты';
}

/**
 * Выводит текст ошибки, забирая его из массива
 * @param array $errors Массив с ошибками
 * @param string $type Имя поля, для которого выводится ошибка
 * @param string $value Столбец массива, в котором содержится текст
 *
 * @return string Текст ошибки
*/
function errors_content($errors, $type, $value)
{
    foreach($errors as $key => $error_content) {
        if($key === $type) {
            $error_content = (isset($err_content[$value])) ? $err_content[$value] : false;
            return $error_content;
        }
    }
}

/**
 * Запоминает содержимое одного поля при отправке данных через POST
 * @param string $name Имя (атрибут name) поля, полученного через POST
 * @return string Содержимое поля, полученного через POST
*/
function getPostVal($name)
{
    $post_name = filter_input(INPUT_POST, $name, FILTER_SANITIZE_SPECIAL_CHARS) ?? false;
    return $post_name;
}

/**
 * Очищает содержимое поля, полученного через POST
 * от специальных символов HTML
 * @param string $input Имя (атрибут name) поля, полученного через POST
 * @return string Содержимое поля с заменёнными HTML-символами
*/
function clear_input($input)
{
    if (isset($_POST[$input])) {
        return filter_input(INPUT_POST, $input, FILTER_SANITIZE_SPECIAL_CHARS);
    }
}

/**
 * Получает массив с данными из SQL-запроса или редиректит на страницу
 * @param object $con Положительный результат функции mysqli_connect
 * @param string $sql MySQL-запрос SELECT
 * @param string $type Тип данных, который планируется получить:
 * 'num' -- число строк, 'assoc' -- одномерный массив (строка таблицы),
 * 'all' -- двумерный массив (таблица)
 *
 * @retutn integer|array В зависимости от $type
*/
function con_sql($con, $sql, $type = 'assoc')
{
    if ($sql === '') {
        return false;
    }
    $sql_request = mysqli_query($con, $sql);
    if ($sql_request) {
        if ($type === 'num') {
            return mysqli_num_rows($sql_request);
        } elseif ($type === 'assoc') {
            return mysqli_fetch_assoc($sql_request);
        } elseif ($type === 'all') {
            return mysqli_fetch_all($sql_request, MYSQLI_ASSOC);
        }
    } else {
        include('includes/1goto_404.php');
    }
}

// функция убирает дубли в многомерных массивах по названию столбца
/**
 * Убирает дубли в многомерных массивах по названию столбца (ключа)
 * @param array $array Массив
 * @param string $key Ключ, по которому ищутся и удаляются дубли
 *
 * @return array Массив без дублей по данному ключу
*/
function array_unique_key($array, $key)
{
    $tmp = $key_array = array();
    $i = 0;

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $tmp[$i] = $val;
        }
        $i++;
    }
    return $tmp;
}
