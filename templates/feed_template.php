<main class="page__main page__main--feed">
    <div class="container">
        <h1 class="page__title page__title--feed">Моя лента</h1>
    </div>
    <div class="page__main-wrapper container">
        <section class="feed">
            <h2 class="visually-hidden">Лента</h2>
            <div class="feed__main-wrapper">
                <div class="feed__wrapper">

<?php foreach ($posts as $post) : ?>
    <?php $post_id = (isset($post['post_id']) ? $post['post_id'] : null) ?>
    <?php $post_user_id = (isset($post['user_id']) ? $post['user_id'] : null) ?>
    <?php $post_username = (isset($post['username']) ? $post['username'] : null) ?>
    <?php $post_avatar = (isset($post['avatar']) ? $post['avatar'] : null) ?>
    <?php $post_dt_add = (isset($post['dt_add']) ? $post['dt_add'] : null) ?>
    <?php $post_header = (isset($post['header']) ? $post['header'] : null) ?>
    <?php $post_text = (isset($post['text']) ? $post['text'] : null) ?>
    <?php $post_author = (isset($post['author']) ? $post['author'] : null) ?>
    <?php $post_image_url = (isset($post['image_url']) ? $post['image_url'] : null) ?>
    <?php $post_site_url = (isset($post['site_url']) ? $post['site_url'] : null) ?>
    <?php $post_repost = (isset($post['repost']) ? $post['repost'] : null) ?>
    <?php $post_original_author = (isset($post['original_author']) ? $post['original_author'] : null) ?>
    <?php $post_type = (isset($post['type']) ? $post['type'] : null) ?>
    <?php $post_count_likes = (isset($post['count_likes']) ? $post['count_likes'] : null) ?>
    <?php $post_count_comments = (isset($post['count_comments']) ? $post['count_comments'] : null) ?>
    <?php $post_count_reposts = (isset($post['count_reposts']) ? $post['count_reposts'] : null) ?>
    <?php $post_author_name = (isset($post['author_name']) ? $post['author_name'] : null) ?>
    <?php $post_author_avatar = (isset($post['author_avatar']) ? $post['author_avatar'] : null) ?>
                        <article class="feed__post post post-<?= $post_type ?>">
                            <header class="post__header post__author">
                                <a class="post__author-link"
                                        href="profile.php?id=
    <?php if ($post_repost !== null) : ?>
                            <?= $post_original_author ?>
    <?php else : ?>
                            <?= $post_user_id ?>
    <?php endif; ?>
                                        "
                                        title="Автор">
                                    <div class="post__avatar-wrapper
    <?php if ($post_repost !== null) : ?>
                                        post__avatar-wrapper--repost
    <?php endif; ?>
                                    ">
    <?php if ($post_repost !== null) : ?>
                                        <img class="post__author-avatar"
                                                src="img/userpic-<?= $post_author_avatar ?>"
                                                alt="Аватар пользователя" width="60" height="60">
    <?php else : ?>
                                        <img class="post__author-avatar" src="img/userpic-<?= $post_avatar ?>"
                                                alt="Аватар пользователя" width="60" height="60">
    <?php endif; ?>
                                    </div>
                                    <div class="post__info">
                                        <b class="post__author-name">
    <?php if ($post_repost !== null) : ?>
                                            <a href="profile.php?id=<?= $post_original_author ?>">
                                                Репост: <?= $post_author_name ?>
                                            </a>
    <?php else : ?>
                                            <a href="profile.php?id=<?= $post_user_id ?>">
                                                <?= $post_username ?>
                                            </a>
    <?php endif; ?>
                                        </b>
                                        <span class="post__time"><?= datetime_relative($post_dt_add) ?> назад</span>
                                    </div>
                                </a>

                            </header>

                            <div class="post__main">
                                <h2><a href="post.php?id=<?= $post_id ?>"><?= $post_header ?></a></h2>
    <?php if ($post_type === 'text') : ?>
                                    <p>
                                        <?= short_text($post_text) ?>
                                    </p>
                                    <a class="post-text__more-link" href="post.php?id=<?= $post_id ?>">Читать
                                        далее</a>
    <?php elseif ($post_type === 'quote') : ?>
                                    <blockquote>
                                        <p>
                                            <?= $post_text ?>
                                        </p>
                                        <cite><?= $post_author ?></cite>
                                    </blockquote>
    <?php elseif ($post_type === 'photo') : ?>
                                    <div class="post-photo__image-wrapper">
                                        <img src="img/<?= $post_image_url ?>" alt="Фото от пользователя" width="760"
                                             height="396">
                                    </div>
    <?php elseif ($post_type === 'video') : ?>
                                    <div class="post-video__block">
                                        <div class="post-video__preview">
                                            <img src="img/coast.jpg" alt="Превью к видео" width="760" height="396">
                                        </div>
                                        <div class="post-video__control">
                                            <button class="post-video__play post-video__play--paused
                                                    button button--video"
                                                    type="button"><span class="visually-hidden">Запустить видео</span>
                                            </button>
                                            <div class="post-video__scale-wrapper">
                                                <div class="post-video__scale">
                                                    <div class="post-video__bar">
                                                        <div class="post-video__toggle"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="post-video__fullscreen post-video__fullscreen--inactive
                                                    button button--video"
                                                    type="button"><span
                                                        class="visually-hidden">Полноэкранный режим</span></button>
                                        </div>
                                        <button class="post-video__play-big button" type="button">
                                            <svg class="post-video__play-big-icon" width="27" height="28">
                                                <use xlink:href="#icon-video-play-big"></use>
                                            </svg>
                                            <span class="visually-hidden">Запустить проигрыватель</span>
                                        </button>
                                    </div>

    <?php elseif ($post_type === 'link') : ?>
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
    <?php endif; ?>

                            </div>

                            <footer class="post__footer post__indicators">
                                <div class="post__buttons">
                                    <a class="post__indicator post__indicator--likes button"
                                       href="feed.php?likepost=<?= $post_id ?>" title="Лайк">
                                        <svg class="post__indicator-icon" width="20" height="17">
                                            <use xlink:href="#icon-heart"></use>
                                        </svg>
                                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20"
                                             height="17">
                                            <use xlink:href="#icon-heart-active"></use>
                                        </svg>
                                        <span><?= $post_count_likes ?></span>
                                        <span class="visually-hidden">количество лайков</span>
                                    </a>
                                    <a class="post__indicator post__indicator--comments button"
                                       href="post.php?id=<?= $post_id ?>" title="Комментарии">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-comment"></use>
                                        </svg>
                                        <span><?= $post_count_comments ?></span>
                                        <span class="visually-hidden">количество комментариев</span>
                                    </a>
                                    <a class="post__indicator post__indicator--repost button"
    <?php $session_user = (isset($_SESSION['user'])) ? filter_var($_SESSION['user'], FILTER_SANITIZE_SPECIAL_CHARS) : false; ?>
                                            href="profile.php?id=<?= $session_user ?>&repost=<?= $post_id ?>"
                                            title="Репост">
                                        <svg class="post__indicator-icon" width="19" height="17">
                                            <use xlink:href="#icon-repost"></use>
                                        </svg>
                                        <span><?= $post_count_reposts ?></span>
                                        <span class="visually-hidden">количество репостов</span>
                                    </a>
                                </div>
                            </footer>
    <?php if (isset($hashtags[$post_id])) : ?>
                                <ul class="post__tags">
        <?php foreach ($hashtags[$post_id] as $hashtag) : ?>
                                        <li><a href="search.php?s=%23<?= $hashtag ?>">#<?= $hashtag ?></a></li>
        <?php endforeach; ?>
                                </ul>
    <?php endif; ?>
                        </article>
<?php endforeach; ?>
                </div>
            </div>
            <ul class="feed__filters filters">

                <li class="feed__filters-item filters__item">
                    <a class="filters__button
<?php if ($get_type === 0) : ?>
                            filters__button--active
<?php endif; ?>
                            " href="feed.php?id=0">
                        <span>Все</span>
                    </a>
                </li>

<?php foreach ($content_types as $type) : ?>
    <?php $type_id = (isset($type['id']) ? $type['id'] : null) ?>
    <?php $type_name = (isset($type['name']) ? $type['name'] : null) ?>
    <?php $type_class = (isset($type['class']) ? $type['class'] : null) ?>
                    <li class="feed__filters-item filters__item">
                        <a class="filters__button filters__button--<?= $type_class ?> button
    <?php if ($get_type === $type_id) : ?>
                                filters__button--active
    <?php endif; ?>"
                                href="feed.php?type=<?= $type_id ?>">
                            <span class="visually-hidden"><?= $type_name ?></span>
                            <svg class="filters__icon" width="22" height="18">
                                <use xlink:href="#icon-filter-<?= $type_class ?>"></use>
                            </svg>
                        </a>
                    </li>
<?php endforeach; ?>

            </ul>
        </section>
        <aside class="promo">
            <article class="promo__block promo__block--barbershop">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Все еще сидишь на окладе в офисе? Открой свой барбершоп по нашей франшизе!
                </p>
                <a class="promo__link" href="#">
                    Подробнее
                </a>
            </article>
            <article class="promo__block promo__block--technomart">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Товары будущего уже сегодня в онлайн-сторе Техномарт!
                </p>
                <a class="promo__link" href="#">
                    Перейти в магазин
                </a>
            </article>
            <article class="promo__block">
                <h2 class="visually-hidden">Рекламный блок</h2>
                <p class="promo__text">
                    Здесь<br> могла быть<br> ваша реклама
                </p>
                <a class="promo__link" href="#">
                    Разместить
                </a>
            </article>
        </aside>
    </div>
</main>
