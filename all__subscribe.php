<?php

// если нажата кнопка "Подписаться", то подписываем пользователя $user_id на пользователя $get_id, внося соответствующую запись в БД
if (isset($_POST['subscribe']) !== FALSE) {
	$sql = '
		INSERT INTO subscribes SET
		sourcer_id = "' . $sourcer_id . '",
		subscriber_id = "' . $user_id . '"
	';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con);
		print("Ошибка MySQL: " . $error);
	}
	
	// отправляем уведомление о подписчике
	$subscriber_id = $_SESSION['user'];										// id текущего пользователя (подписчика)
	$subscriber_name = $_SESSION['username'];								// имя текущего пользователя (подписчика)

	require 'vendor/autoload.php';										// подключаем библиотеку

	/* закомментил, чтобы страница не падала, тк SMTP-сервер не работает
	$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))			// конфигурация траспорта
	  ->setUsername('keks@phpdemo.ru')
	  ->setPassword('htmlacademy')
	*/

	$message = new Swift_Message('У вас новый подписчик');				// формируем сообщение для отправки уведомления
	$message->setTo([$sourcer_email => $sourcer_name]);
	$message->setBody('Здравствуйте, ' . $sourcer_name . '. На вас подписался новый пользователь ' . $subscriber_name . '. Вот ссылка на его профиль: <a href="profile.php?id=' . $subscriber_id . '">ссылка на профиль</a>');
	$message->setFrom('keks@phpdemo.ru', 'Кекс');

	/* закомментил, чтобы страница не падала, тк SMTP-сервер не работает
	$mailer = new Swift_Mailer($transport);								// отправляем уведомление
	$mailer->send($message);
	*/
}

// если нажата кнопка "Отписаться", то отписываем пользователя $user_id на пользователя $get_id, удаляя соответствующую запись БД
if (isset($_POST['unsubscribe']) !== FALSE) {
	$sql = '
		DELETE
		FROM subscribes
		WHERE sourcer_id = "' . $sourcer_id . '" AND subscriber_id = "' . $user_id . '"
	';
	$result = mysqli_query($con, $sql);
	if (!$result) { 
		$error = mysqli_error($con); 
		print("Ошибка MySQL: " . $error);
	}
}

// проверяем подписан ли пользователь $user_id на пользователя $get_id, чтобы показать кнопку "подписаться"/"отписаться"
$sql = '
	SELECT sourcer_id, subscriber_id
	FROM subscribes
	WHERE sourcer_id = "' . $sourcer_id . '" AND subscriber_id = "' . $user_id . '"
';
$sql_check = mysqli_query($con, $sql);
$check = mysqli_num_rows($sql_check);

$subscribe = 0;
if ($check > 0) {
	$subscribe = 1;
}

?>
