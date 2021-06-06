<?php $profile_id = isset($profile['id']) ? $profile['id'] : null; ?>
<?php $profile_avatar = isset($profile['avatar']) ? $profile['avatar'] : null; ?>
<?php $profile_name = isset($profile['name']) ? $profile['name'] : null; ?>
<?php $profile_datetime = isset($profile['datetime']) ? $profile['datetime'] : null; ?>
<?php $profile_posts_count = isset($profile['posts_count']) ? $profile['posts_count'] : null; ?>
<?php $profile_subscribers_count = isset($profile['subscribers_count']) ? $profile['subscribers_count'] : null; ?>

<main class="page__main page__main--profile">
    <h1 class="visually-hidden">Профиль</h1>
    <div class="profile profile--default">
        <div class="profile__user-wrapper">
            <div class="profile__user user container">
                <div class="profile__user-info user__info">
                    <div class="profile__avatar user__avatar">
                        <img class="profile__picture user__picture" src="img/userpic-<?= $profile_avatar ?>"
                             alt="Аватар пользователя">
                    </div>
                    <div class="profile__name-wrapper user__name-wrapper">
                        <span class="profile__name user__name"><?= $profile_name ?></span>
                        <time class="profile__user-time user__time"
                              datetime="2014-03-20"><?= datetime_relative($profile_datetime) ?> на сайте
                        </time>
                    </div>
                </div>
                <div class="profile__rating user__rating">
                    <p class="profile__rating-item user__rating-item user__rating-item--publications">
                        <span class="user__rating-amount"><?= $profile_posts_count ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form($profile_posts_count, 'публикация', 'публикации', 'публикаций') ?>
                        </span>
                    </p>
                    <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
                        <span class="user__rating-amount"><?= $profile_subscribers_count ?></span>
                        <span class="profile__rating-text user__rating-text">
                            <?= get_noun_plural_form($profile_subscribers_count, 'подписчик', 'подписчика', 'подписчиков') ?>
                        </span>
                    </p>
                </div>
                <form action="profile.php?id=<?= $get_id ?>" method="post">
                    <div class="profile__user-buttons user__buttons">
<?php if ($subscribe === 0) : ?>
                            <input class="profile__user-button user__button
                                    user__button--subscription button button--main"
                                    type="submit" name="subscribe" value="Подписаться">
<?php else : ?>
                            <input class="profile__user-button user__button
                                    user__button--subscription button button--quartz"
                                    type="submit" name="unsubscribe" value="Отписаться">
                            <a class="profile__user-button user__button user__button--writing button button--green"
                                    href="http://localhost/messages.php?id=<?= $profile_id ?>">Сообщение</a>
<?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <div class="profile__tabs-wrapper tabs">
            <div class="container">
                <div class="profile__tabs filters">
                    <b class="profile__tabs-caption filters__caption">Показать:</b>
                    <ul class="profile__tabs-list filters__list tabs__list">
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button
                                    filters__button--active tabs__item tabs__item--active button">Посты</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button" href="#">Лайки</a>
                        </li>
                        <li class="profile__tabs-item filters__item">
                            <a class="profile__tabs-link filters__button tabs__item button" href="#">Подписки</a>
                        </li>
                    </ul>
                </div>
                <div class="profile__tab-content">
                    <section class="profile__posts tabs__content tabs__content--active">
                        <h2 class="visually-hidden">Публикации</h2>
<?php foreach ($posts as $post) : ?>
    <?php $post_id = isset($post['post_id']) ? $post['post_id'] : null; ?>
    <?php $post_type = isset($post['type']) ? $post['type'] : null; ?>
    <?php $post_header = isset($post['header']) ? $post['header'] : null; ?>
    <?php $post_text = isset($post['text']) ? $post['text'] : null; ?>
    <?php $post_author = isset($post['author']) ? $post['author'] : null; ?>
    <?php $post_image_url = isset($post['image_url']) ? $post['image_url'] : null; ?>
    <?php $post_site_url = isset($post['site_url']) ? $post['site_url'] : null; ?>
    <?php $post_likes_count = isset($post['likes_count']) ? $post['likes_count'] : null; ?>
    <?php $post_reposts_count = isset($post['reposts_count']) ? $post['reposts_count'] : null; ?>
    <?php $post_dt_add = isset($post['dt_add']) ? $post['dt_add'] : null; ?>
                            <article class="profile__post post post-<?= $post_type ?>">
                                <header class="post__header">
                                    <h2><a href="post.php?id=<?= $post_id ?>"><?= $post_header ?></a></h2>
                                </header>
    <?php if ($post_type === 'text') : ?>
                                    <div class="post__main">
                                        <p>
                                            <?= short_text($post_text) ?>
                                        </p>
                                        <a class="post-text__more-link" href="post.php?id=<?= $post_id ?>">
                                            Читать далее
                                        </a>
                                    </div>
    <?php elseif ($post_type === 'quote') : ?>
                                    <div class="post__main">
                                        <blockquote>
                                            <p>
                                                <?= $post['text'] ?>
                                            </p>
                                            <cite><?= $post['author'] ?></cite>
                                        </blockquote>
                                    </div>
    <?php elseif ($post_type === 'photo') : ?>
                                    <div class="post__main">
                                        <div class="post-photo__image-wrapper">
                                            <img src="img/<?= $post_image_url ?>" alt="Фото от пользователя"
                                                 width="760" height="396">
                                        </div>
                                    </div>
    <?php elseif ($post_type === 'video') : ?>
                                    <div class="post__main">
                                        <div class="post-video__block">
                                            <div class="post-video__preview">
                                                <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                                            </div>
                                            <div class="post-video__control">
                                                <button class="post-video__play
                                                        post-video__play--paused button button--video"
                                                        type="button">
                                                    <span class="visually-hidden">Запустить видео</span>
                                                </button>
                                                <div class="post-video__scale-wrapper">
                                                    <div class="post-video__scale">
                                                        <div class="post-video__bar">
                                                            <div class="post-video__toggle"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="post-video__fullscreen
                                                        post-video__fullscreen--inactive button button--video"
                                                        type="button">
                                                    <span class="visually-hidden">Полноэкранный режим</span>
                                                </button>
                                            </div>
                                            <button class="post-video__play-big button" type="button">
                                                <svg class="post-video__play-big-icon" width="27" height="28">
                                                    <use xlink:href="#icon-video-play-big"></use>
                                                </svg>
                                                <span class="visually-hidden">Запустить проигрыватель</span>
                                            </button>
                                        </div>
                                    </div>

    <?php elseif ($post_type === 'link') : ?>
                                    <div class="post__main">
                                        <div class="post-link__wrapper">
                                            <a class="post-link__external" href="http://www.vitadental.ru"
                                               title="Перейти по ссылке">
                                                <div class="post-link__icon-wrapper">
                                                    <img src="img/logo-vita.jpg" alt="Иконка">
                                                </div>
                                                <div class="post-link__info">
                                                    <h3><?= $post_header ?></h3>
                                                    <p><?= $post_text ?></p>
                                                    <span><?= $post_site_url ?></span>
                                                </div>
                                                <svg class="post-link__arrow" width="11" height="16">
                                                    <use xlink:href="#icon-arrow-right-ad"></use>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
    <?php endif; ?>

                                <footer class="post__footer">
                                    <div class="post__indicators">
                                        <div class="post__buttons">
                                            <a class="post__indicator post__indicator--likes button"
                                               href="profile.php?id=<?= $get_id ?>&likepost=<?= $post_id ?>"
                                               title="Лайк">
                                                <svg class="post__indicator-icon" width="20" height="17">
                                                    <use xlink:href="#icon-heart"></use>
                                                </svg>
                                                <svg class="post__indicator-icon post__indicator-icon--like-active"
                                                     width="20" height="17">
                                                    <use xlink:href="#icon-heart-active"></use>
                                                </svg>
                                                <span><?= $post_likes_count ?></span>
                                                <span class="visually-hidden">количество лайков</span>
                                            </a>
                                            <a class="post__indicator post__indicator--repost button" href="#"
                                               title="Репост">
                                                <svg class="post__indicator-icon" width="19" height="17">
                                                    <use xlink:href="#icon-repost"></use>
                                                </svg>
                                                <span><?= $post_reposts_count ?></span>
                                                <span class="visually-hidden">количество репостов</span>
                                            </a>
                                        </div>
                                        <time class="post__time" datetime="2019-01-30T23:41">
                                            <?= datetime_relative($post_dt_add) ?> назад
                                        </time>
                                    </div>
                                    <ul class="post__tags">
    <?php if (isset($hashtags[$post_id]) and count($hashtags[$post_id]) > 1) : ?>
        <?php foreach ($hashtags[$post_id] as $hashtag) : ?>
                                        <li><a href="search.php?s=%23<?= $hashtag ?>">#<?= $hashtag ?></a></li>
        <?php endforeach; ?>
    <?php elseif (isset($hashtags[$post_id]) and count($hashtags[$post_id]) === 1) : ?>
                                            <li>
                                                <a href="search.php?s=%23<?= $hashtags[$post_id][0] ?>">
                                                    #<?= $hashtags[$post_id][0] ?>
                                                </a>
                                            </li>
    <?php endif; ?>
                                    </ul>
                                </footer>
                                <div class="comments">
                                    <a class="comments__button button" href="#">Показать комментарии</a>
                                </div>
                            </article>
<?php endforeach; ?>

                    </section>
                    <section class="profile__likes tabs__content">
                        <h2 class="visually-hidden">Лайки</h2>
                        <ul class="profile__likes-list">

<?php foreach ($likes as $like) : ?>
    <?php $like_id = isset($like['id']) ? $like['id'] : null; ?>
    <?php $like_type = isset($like['type']) ? $like['type'] : null; ?>
    <?php $like_name = isset($like['name']) ? $like['name'] : null; ?>
    <?php $like_avatar = isset($like['avatar']) ? $like['avatar'] : null; ?>
    <?php $like_post_id = isset($like['post_id']) ? $like['post_id'] : null; ?>
                            <li class="post-mini post-mini--<?= $like_type ?> post user">
                                <div class="post-mini__user-info user__info">
                                    <div class="post-mini__avatar user__avatar">
                                        <a class="user__avatar-link" href="profile.php?id=<?= $like_id ?>">
                                            <img class="post-mini__picture user__picture"
                                                 src="img/userpic-<?= $like_avatar ?>" alt="Аватар пользователя">
                                        </a>
                                    </div>
                                    <div class="post-mini__name-wrapper user__name-wrapper">
                                        <a class="post-mini__name user__name"
                                           href="profile.php?id=<?= $like_id ?>">
                                            <span><?= $like_name ?></span>
                                        </a>
                                        <div class="post-mini__action">
                                            <span class="post-mini__activity user__additional">
                                                Лайкнул вашу публикацию
                                            </span>
                                            <time class="post-mini__time user__additional"
                                                  datetime="2014-03-20T20:20">5 минут назад
                                            </time>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-mini__preview">
                                    <a class="post-mini__link" href="post.php?id=<?= $like_post_id ?>"
                                            title="Перейти на публикацию">

    <?php if ($like_type === 'photo') : ?>
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image" src="img/rock-small.png" width="109"
                                                 height="109" alt="Превью публикации">
                                        </div>
                                        <span class="visually-hidden">Фото</span>
    <?php elseif ($like_type === 'text') : ?>
                                        <span class="visually-hidden">Текст</span>
                                        <svg class="post-mini__preview-icon" width="20" height="21">
                                            <use xlink:href="#icon-filter-text"></use>
                                        </svg>
    <?php elseif ($like_type === 'video') : ?>
                                        <div class="post-mini__image-wrapper">
                                            <img class="post-mini__image" src="img/coast-small.png" width="109"
                                                 height="109" alt="Превью публикации">
                                            <span class="post-mini__play-big">
                                            <svg class="post-mini__play-big-icon" width="12" height="13">
                                              <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                          </span>
                                        </div>
                                        <span class="visually-hidden">Видео</span>
    <?php elseif ($like_type === 'quote') : ?>
                                        <span class="visually-hidden">Цитата</span>
                                        <svg class="post-mini__preview-icon" width="21" height="20">
                                            <use xlink:href="#icon-filter-quote"></use>
                                        </svg>
    <?php elseif ($like_type === 'link') : ?>
                                        <span class="visually-hidden">Ссылка</span>
                                        <svg class="post-mini__preview-icon" width="21" height="18">
                                            <use xlink:href="#icon-filter-link"></use>
                                        </svg>
    <?php endif; ?>
                                    </a>
                                </div>
                            </li>
<?php endforeach; ?>
                        </ul>
                    </section>
                    <section class="profile__subscriptions tabs__content">
                        <h2 class="visually-hidden">Подписки</h2>
                        <ul class="profile__subscriptions-list">

<?php foreach ($subscribes as $subscriber) : ?>
    <?php $subscriber_avatar = isset($subscriber['avatar']) ? $subscriber['avatar'] : null; ?>
    <?php $subscriber_id = isset($subscriber['subscriber_id']) ? $subscriber['subscriber_id'] : null; ?>
    <?php $subscriber_username = isset($subscriber['username']) ? $subscriber['username'] : null; ?>
    <?php $subscriber_dt_add = isset($subscriber['dt_add']) ? $subscriber['dt_add'] : null; ?>
    <?php $subscriber_count_posts = isset($subscriber['count_posts']) ? $subscriber['count_posts'] : null; ?>
    <?php $subscriber_count_subscribes = isset($subscriber['count_subscribes']) ? $subscriber['count_subscribes'] : null; ?>
    <?php $subscriber_subscribers = isset($subscriber['subscribers']) ? $subscriber['subscribers'] : null; ?>
                                <li class="post-mini post-mini--photo post user">
                                    <div class="post-mini__user-info user__info">
                                        <div class="post-mini__avatar user__avatar">
                                            <a class="user__avatar-link" href="#">
                                                <img class="post-mini__picture user__picture"
                                                     src="img/userpic-<?= $subscriber_avatar ?>"
                                                     alt="Аватар пользователя">
                                            </a>
                                        </div>
                                        <div class="post-mini__name-wrapper user__name-wrapper">
                                            <a class="post-mini__name user__name"
                                               href="profile.php?id=<?= $subscriber_id ?>">
                                                <span><?= $subscriber_username ?></span>
                                            </a>
                                            <time class="post-mini__time user__additional"
                                                  datetime="<?= $subscriber_dt_add ?>">
                                                <?= datetime_relative($subscriber_dt_add) ?> на сайте
                                            </time>
                                        </div>
                                    </div>
                                    <div class="post-mini__rating user__rating">
                                        <p class="post-mini__rating-item user__rating-item
                                                user__rating-item--publications">
                                            <span class="post-mini__rating-amount user__rating-amount">
                                                <?= $subscriber_count_posts ?>
                                            </span>
                                            <span class="post-mini__rating-text user__rating-text">
                                                <?= get_noun_plural_form($subscriber_count_posts, 'публикация', 'публикации', 'публикаций') ?>
                                            </span>
                                        </p>
                                        <p class="post-mini__rating-item user__rating-item
                                                user__rating-item--subscribers">
                                            <span class="post-mini__rating-amount user__rating-amount">
                                                <?= $subscriber_count_subscribes ?>
                                            </span>
                                            <span class="post-mini__rating-text user__rating-text">
                                                <?= get_noun_plural_form($subscriber_count_subscribes, 'подписчик', 'подписчика', 'подписчиков') ?>
                                            </span>
                                        </p>
                                    </div>

                                    <form action="profile.php?id=<?= $get_id ?>" method="post">
                                        <div class="post-mini__user-buttons user__buttons">
    <?php if (array_search($user_id, explode(',', $subscriber_subscribers)) === false) : ?>
                                            <button class="post-mini__user-button user__button
                                                    user__button--subscription button button--main"
                                                    type="submit" value="subscribe">Подписаться
                                            </button>
    <?php else : ?>
                                            <button class="post-mini__user-button user__button
                                                    user__button--subscription button button--quartz"
                                                    type="submit" value="unsubscribe">Отписаться
                                            </button>
    <?php endif; ?>
                                        </div>
                                    </form>
                                </li>
<?php endforeach; ?>

                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
</main>
