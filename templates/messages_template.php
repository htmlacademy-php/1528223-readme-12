<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <ul class="messages__contacts-list tabs__list">

<?php foreach ($users as $user) : ?>
    <?php $user_db_id = isset($user['id']) ? $user['id'] : null; ?>
    <?php $user_name = isset($user['name']) ? $user['name'] : null; ?>
    <?php $user_avatar = isset($user['avatar']) ? $user['avatar'] : null; ?>
    <?php $user_message = isset($user['message']) ? $user['message'] : null; ?>
    <?php $user_dt_add = isset($user['dt_add']) ? $user['dt_add'] : null; ?>
    <?php if ($user_db_id !== $user_id) : ?>
                        <li class="messages__contacts-item">
                            <a class="messages__contacts-tab
        <?php if ($get_id === $user_db_id) : ?>
                                    messages__contacts-tab--active tabs__item tabs__item--active
        <?php endif; ?>"
                                    href="messages.php?id=<?= $user_db_id ?>">
                                <div class="messages__avatar-wrapper">
                                    <img class="messages__avatar"
                                            src="img/userpic-<?= $user_avatar ?>"
                                            alt="Аватар пользователя">
                                </div>
                                <div class="messages__info">
                                    <span class="messages__contact-name">
                                        <?= $user_name ?>
                                    </span>
                                    <div class="messages__preview">
        <?php if (!empty($user_message)) : ?>
                                            <p class="messages__preview-text">
                                                <?= short_text($user_message, 20) ?>
                                            </p>
        <?php endif; ?>
        <?php if (!empty($user_dt_add)) : ?>
                                            <time class="messages__preview-time"
                                                  datetime="<?= datetime_format($user_dt_add) ?>">
                                                <?= datetime_format($user_dt_add, 'time') ?>
                                            </time>
        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </li>
    <?php endif; ?>
<?php endforeach; ?>
            </ul>
        </div>

<?php if ($get_id !== '') : ?>
        <div class="messages__chat">
            <div class="messages__chat-wrapper">
                <ul class="messages__list tabs__content tabs__content--active">

    <?php foreach ($messages as $message) : ?>
        <?php $message_sender_id = isset($message['sender_id']) ? $message['sender_id'] : null; ?>
        <?php $message_avatar = isset($message['avatar']) ? $message['avatar'] : null; ?>
        <?php $message_username = isset($message['username']) ? $message['username'] : null; ?>
        <?php $message_message = isset($message['message']) ? $message['message'] : null; ?>
        <?php $message_dt_add = isset($message['dt_add']) ? $message['dt_add'] : null; ?>
                    <li class="messages__item
        <?php if ($message_sender_id === $user_id) : ?>
                                messages__item--my
        <?php endif; ?>">
                        <div class="messages__info-wrapper">
                            <div class="messages__item-avatar">
                                <a class="messages__author-link" href="#">
                                    <img class="messages__avatar" src="img/userpic-<?= $message_avatar ?>"
                                         alt="Аватар пользователя">
                                </a>
                            </div>
                            <div class="messages__item-info">
                                <a class="messages__author" href="profile.php?id=<?= $message_sender_id ?>">
                                    <?= $message_username ?>
                                </a>
                                <time class="messages__time" datetime="2019-05-01T14:40">
                                    <?= datetime_relative($message_dt_add) ?> назад
                                </time>
                            </div>
                        </div>
                        <p class="messages__text">
                            <?= $message_message ?>
                        </p>
                    </li>
    <?php endforeach; ?>
                </ul>
            </div>
    <?php if ($get_id !== $user_id) : ?>
                <div class="comments">
                    <form class="comments__form form" action="messages.php?id=<?= $get_id ?>" method="post">
                        <div class="comments__my-avatar">
        <?php $session_avatar = (isset($_SESSION['avatar'])) ? filter_var($_SESSION['avatar'], FILTER_SANITIZE_SPECIAL_CHARS) : false; ?>
                            <img class="comments__picture" src="img/userpic-<?= $session_avatar ?>" alt="Аватар пользователя">
                        </div>
                        <div class="form__input-section
        <?php if ($errors === 1) : ?>
                                form__input-section--error
        <?php endif; ?>">
                            <input type="hidden" name="id" value="<?= $get_id ?>">
                            <textarea class="comments__textarea form__textarea form__input" placeholder="Ваше сообщение"
                                      name="message"></textarea>
                            <label class="visually-hidden">Ваше сообщение</label>
        <?php if ($errors === 1) : ?>
                                <button class="form__error-button button" type="button">!</button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Ошибка валидации</h3>
                                    <p class="form__error-desc">Это поле обязательно к заполнению</p>
                                </div>
        <?php endif; ?>
                        </div>
                        <button class="comments__submit button button--green" type="submit" name="submit">Отправить
                        </button>
                    </form>
                </div>
    <?php endif; ?>
        </div>
<?php endif; ?>
    </section>
</main>
